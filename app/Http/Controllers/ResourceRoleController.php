<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceRoleController extends Controller
{
    public function index(Resource $resource)
    {
        $this->authorize('manage', $resource);

        $users = User::query()
            ->when(auth()->user()->isAdmin(), function ($query) {
                $query->where('company_id', auth()->user()->company_id);
            })
            ->with(['resourceRoles' => function ($query) use ($resource) {
                $query->where('resource_id', $resource->id);
            }])
            ->get();

        $roles = Role::all();

        return view('resources.roles', compact('resource', 'users', 'roles'));
    }

    public function update(Request $request, Resource $resource)
    {
        $this->authorize('manage', $resource);

        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'array',
            'roles.*.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            // Remove all existing roles for this resource
            DB::table('user_resource_roles')->where('resource_id', $resource->id)->delete();

            // Add new roles
            foreach ($validated['roles'] as $userId => $roleIds) {
                foreach ($roleIds as $roleId) {
                    DB::table('user_resource_roles')->insert([
                        'user_id' => $userId,
                        'resource_id' => $resource->id,
                        'role_id' => $roleId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', __('Failed to update resource roles. Error: ') . $e->getMessage());
        }

        return redirect()->route('resources.roles', $resource)
            ->with('success', __('Resource roles updated successfully'));
    }
}
