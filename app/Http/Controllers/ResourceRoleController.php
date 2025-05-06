<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceRoleController extends Controller
{
    public function index(Item $item)
    {
        $this->authorize('manage', $item);

        $users = User::query()
            ->when(auth()->user()->isAdmin(), function ($query) {
                $query->where('company_id', auth()->user()->company_id);
            })
            ->with(['itemRoles' => function ($query) use ($item) {
                $query->where('item_id', $item->id);
            }])
            ->get();

        $roles = Role::all();

        return view('items.roles', compact('item', 'users', 'roles'));
    }

    public function update(Request $request, Item $item)
    {
        $this->authorize('manage', $item);

        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'array',
            'roles.*.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            // Remove all existing roles for this item
            DB::table('user_item_roles')->where('item_id', $item->id)->delete();

            // Add new roles
            foreach ($validated['roles'] as $userId => $roleIds) {
                foreach ($roleIds as $roleId) {
                    DB::table('user_item_roles')->insert([
                        'user_id' => $userId,
                        'item_id' => $item->id,
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

        return redirect()->route('items.roles', $item)
            ->with('success', __('Resource roles updated successfully'));
    }
}
