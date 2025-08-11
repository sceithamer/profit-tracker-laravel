<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users with permission summary.
     */
    public function index()
    {
        $users = User::withCount('sales')
                    ->orderBy('is_admin', 'desc')
                    ->orderBy('name')
                    ->paginate(15);
                    
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $availablePermissions = User::getAvailablePermissions();
        
        return view('users.create', compact('availablePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['boolean'],
            'permissions' => ['array'],
            'permissions.*' => ['string', function ($attribute, $value, $fail) {
                $availablePermissions = collect(User::getAvailablePermissions())
                    ->flatten(1)
                    ->keys()
                    ->toArray();
                
                if (!in_array($value, $availablePermissions)) {
                    $fail('The selected permission is invalid.');
                }
            }],
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->boolean('is_admin', false),
            'permissions' => $request->input('permissions', []),
            'email_verified_at' => now(), // Auto-verify admin-created accounts
        ]);

        return redirect()->route('users.show', $user)
                        ->with('success', "User '{$user->name}' created successfully.");
    }

    /**
     * Display the specified user with their permissions and activity.
     */
    public function show(User $user)
    {
        $user->load('sales.platform', 'sales.storageUnit');
        
        // Calculate user statistics
        $totalSales = $user->sales->count();
        $grossRevenue = $user->sales->sum('sale_price');
        $totalExpenses = $user->sales->sum('fees') + $user->sales->sum('shipping_cost');
        $netRevenue = $grossRevenue - $totalExpenses;
        
        // Get recent sales (last 10)
        $recentSales = $user->sales()
                          ->with(['platform', 'storageUnit', 'category'])
                          ->orderBy('sale_date', 'desc')
                          ->limit(10)
                          ->get();
                          
        return view('users.show', compact(
            'user', 'totalSales', 'grossRevenue', 'totalExpenses', 
            'netRevenue', 'recentSales'
        ));
    }

    /**
     * Show the form for editing the specified user's permissions.
     */
    public function edit(User $user)
    {
        $availablePermissions = User::getAvailablePermissions();
        $userPermissions = $user->getGroupedPermissions();
        
        return view('users.edit', compact('user', 'availablePermissions', 'userPermissions'));
    }

    /**
     * Update the specified user's permissions and details.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'is_admin' => ['boolean'],
            'permissions' => ['array'],
            'permissions.*' => ['string', function ($attribute, $value, $fail) {
                // Validate that each permission exists in our available permissions list
                $availablePermissions = collect(User::getAvailablePermissions())
                    ->flatten(1)
                    ->keys()
                    ->toArray();
                
                if (!in_array($value, $availablePermissions)) {
                    $fail('The selected permission is invalid.');
                }
            }],
        ]);

        // Update basic user information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->boolean('is_admin', false),
        ]);

        // Sync permissions (admins don't need explicit permissions)
        $permissions = $request->input('permissions', []);
        $user->syncPermissions($permissions);

        return redirect()->route('users.show', $user)
            ->with('success', 'User permissions updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent users from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                           ->with('error', 'You cannot delete your own account.');
        }

        // Prevent deletion if user has sales (protect data integrity)
        if ($user->sales()->count() > 0) {
            return redirect()->route('users.index')
                           ->with('error', "Cannot delete user '{$user->name}' because they have associated sales records. Transfer or reassign their sales first.");
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
                        ->with('success', "User '{$userName}' has been deleted successfully.");
    }
}
