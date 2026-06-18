<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('subscription')
            ->withCount('tasks');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Filter by plan
        if ($plan = $request->input('plan')) {
            if ($plan === 'pro') {
                $query->whereHas('subscription', fn ($q) => $q->where('price', '>', 0));
            } elseif ($plan === 'free') {
                $query->where(function ($q) {
                    $q->whereHas('subscription', fn ($q2) => $q2->where('price', 0))
                      ->orWhereNull('subscription_id');
                });
            }
        }

        $users         = $query->latest()->paginate(15)->withQueryString();
        $subscriptions = Subscription::all();

        return view('admin.users', compact('users', 'subscriptions'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:user,admin']);

        // Prevent self-demotion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "Role updated to {$request->role} for {$user->name}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete an admin account.');
        }

        $user->delete();

        return back()->with('success', "User {$user->name} deleted.");
    }
}