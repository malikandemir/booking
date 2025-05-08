<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
        ]);
        Role::create($validated);
        return redirect()->route('roles.index')->with('success', __('Role created successfully.'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role','permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
        ]);
        $role->update($validated);
        // Sync permissions
        $role->permissions()->sync($request->input('permissions', []));
        return redirect()->route('roles.index')->with('success', __('Role updated successfully.'));

    }
    

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', __('Role deleted successfully.'));
    }
}
