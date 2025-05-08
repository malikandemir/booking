<?php

namespace App\Http\Controllers;


use App\Models\Role;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $users = User::get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $companies = Company::all();
        $roles = Role::all();
        return view('users.create', compact('companies', 'roles'));
    }

    public function store(Request $request)
    {
        $this->validateUser($request);

        User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'company_id' => $request->company_id,
                'password' => Hash::make($request->password),
                'is_admin' => $this->determineUserRole($request),
            ]
        );

        return redirect()->route('users.index')
            ->with('success', __('User created successfully.'));
    }

    public function edit(User $user)
    {
        $companies = Company::all();
        $roles = Role::get();
        return view('users.edit', compact('user', 'companies','roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->validateUser($request, $user->id);

        $userData = [
            'name' => $request->name,
            'email' => $request->email
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Set company_id based on role and current user
        if (!Auth::user()->isSuperAdmin()) {
            $userData['company_id'] = Auth::user()->company_id;
        } else {
            $userData['company_id'] = $request->company_id;
        }

        // Validate roles input
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update($userData);

        // Sync roles
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('users.index')
            ->with('success', __('User updated successfully.'));
    }

    protected function validateUser(Request $request, $userId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email' . ($userId ? ",$userId" : ''),
            'is_admin' => 'nullable|integer|in:0,1',
        ];

        if (!$userId || $request->filled('password')) {
            $rules['password'] = 'required|string|min:8';
        }

        if (Auth::user()->isSuperAdmin()) {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        return $request->validate($rules);
    }

    protected function determineUserRole(Request $request)
    {
        // Super admin can create admins, regular admins can only create users
        if (Auth::user()->isSuperAdmin() && $request->is_admin) {
            return User::ROLE_ADMIN;
        }
        return User::ROLE_USER;
    }
}
