@extends('layouts.admin')
@section('title', 'User Management')
@section('subtitle', 'Manage all registered users')

@section('content')
<div class="space-y-5">

    {{-- ── Filter Bar ───────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <form method="GET" action="{{ route('admin.users') }}" class="flex flex-wrap items-center gap-3">

            <div class="relative flex-1 min-w-48">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name or email…"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <select name="role" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">All Roles</option>
                <option value="user"  {{ request('role') === 'user'  ? 'selected' : '' }}>Users</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admins</option>
            </select>

            <select name="plan" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">All Plans</option>
                <option value="pro"  {{ request('plan') === 'pro'  ? 'selected' : '' }}>Pro</option>
                <option value="free" {{ request('plan') === 'free' ? 'selected' : '' }}>Free</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-xl text-sm font-medium hover:bg-brand-700 transition-colors">
                Filter
            </button>

            @if(request()->hasAny(['search','role','plan']))
                <a href="{{ route('admin.users') }}" class="text-sm text-slate-400 hover:text-slate-600">Clear</a>
            @endif

            <span class="ml-auto text-xs text-slate-400">{{ $users->total() }} users found</span>
        </form>
    </div>

    {{-- ── Users Table ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">User</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Plan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tasks</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Role</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Joined</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">

                        {{-- User --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0
                                    {{ $user->role === 'admin' ? 'bg-red-500' : 'bg-brand-500' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800 flex items-center gap-1.5">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span class="text-xs text-slate-400">(you)</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Plan --}}
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $user->isPro() ? 'bg-brand-100 text-brand-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $user->isAdmin() ? '⭐ Admin' : ($user->isPro() ? '⭐ '.($user->subscription->name ?? 'Pro') : 'Free') }}
                            </span>
                        </td>

                        {{-- Task count --}}
                        <td class="px-5 py-4 text-slate-600">
                            {{ $user->tasks_count }}
                        </td>

                        {{-- Role --}}
                        <td class="px-5 py-4">
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                    @csrf @method('PATCH')
                                    <select name="role" onchange="this.form.submit()"
                                            class="text-xs border rounded-lg px-2 py-1 focus:outline-none focus:ring-1 focus:ring-brand-500
                                                   {{ $user->role === 'admin' ? 'border-red-200 bg-red-50 text-red-700' : 'border-slate-200 bg-white text-slate-600' }}">
                                        <option value="user"  {{ $user->role === 'user'  ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    Admin
                                </span>
                            @endif
                        </td>

                        {{-- Joined --}}
                        <td class="px-5 py-4 text-slate-400 text-xs">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4 text-right">
                            @if($user->id !== auth()->id() && $user->role !== 'admin')
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-slate-300">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                            No users found matching your filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection