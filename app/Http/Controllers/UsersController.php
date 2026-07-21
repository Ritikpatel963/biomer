<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function usersList()
    {
        $users = User::paginate(10);
        return view('users/usersList', compact('users'));
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function addUser()
    {
        $roles = Role::where('status', 'active')->get();
        return view('users/addUser', compact('roles'));
    }

    /**
     * Store a newly created admin user in database.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => [
                'required',
                Rule::exists('roles', 'name')->where('status', 'active'),
            ],
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Assign Spatie Role
        $user->assignRole($request->input('role'));

        // Sync legacy column for backward compatibility
        $user->update(['role' => $request->input('role')]);

        return redirect()->route('usersList')->with('success', 'Admin user created successfully.');
    }

    /**
     * Remove the specified admin user.
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('usersList')->with('error', 'You cannot delete your own account.');
        }

        // Super-admin accounts must be demoted explicitly before removal.
        if ($user->hasRole('super-admin')) {
            return redirect()->route('usersList')->with('error', 'This system administrator account cannot be deleted.');
        }

        $user->delete();

        return redirect()->route('usersList')->with('success', 'Admin user deleted successfully.');
    }

    public function usersGrid()
    {
        $users = User::paginate(12);
        return view('users/usersGrid', compact('users'));
    }
    
    public function viewProfile()
    {
        return view('users/viewProfile');
    }
}
