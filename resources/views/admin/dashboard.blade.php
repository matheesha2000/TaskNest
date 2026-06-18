@extends('layouts.admin')
@section('title', 'Dashboard')
@section('subtitle', 'Overview of your platform')

@section('content')
<div class="space-y-6">

    {{-- ── Top Stats Row ────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Users --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full font-medium">
                    +{{ $stats['new_users_week'] }} this week
                </span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($stats['total_users']) }}</p>
            <p class="text-sm text-slate-400 mt-0.5">Total Users</p>
            <div class="mt-3 flex gap-3 text-xs">
                <span class="text-brand-600 font-medium">{{ $stats['pro_users'] }} Pro</span>
                <span class="text-slate-400">{{ $stats['free_users'] }} Free</span>
            </div>
        </div>

        {{-- Total Tasks --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                @if($stats['overdue_tasks'] > 0)
                    <span class="text-xs text-red-600 bg-red-50 px-2 py-0.5 rounded-full font-medium">
                        {{ $stats['overdue_tasks'] }} overdue
                    </span>
                @endif
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($stats['total_tasks']) }}</p>
            <p class="text-sm text-slate-400 mt-0.5">Total Tasks</p>
            <div class="mt-3 flex gap-3 text-xs">
                <span class="text-green-600 font-medium">{{ $stats['completed_tasks'] }} done</span>
                <span class="text-blue-500">{{ $stats['inprogress_tasks'] }} active</span>
                <span class="text-slate-400">{{ $stats['pending_tasks'] }} pending</span>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full font-medium">
                    +${{ number_format($stats['revenue_week'], 2) }} this week
                </span>
            </div>
            <p class="text-3xl font-bold text-slate-800">${{ number_format($stats['total_revenue'], 2) }}</p>
            <p class="text-sm text-slate-400 mt-0.5">Total Revenue</p>
            <div class="mt-3 text-xs text-slate-400">
                {{ $stats['total_payments'] }} successful payments
            </div>
        </div>

        {{-- Reviews --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['avg_rating'] }}<span class="text-lg text-slate-400">/5</span></p>
            <p class="text-sm text-slate-400 mt-0.5">Avg Rating</p>
            <div class="mt-3 text-xs text-slate-400">
                from {{ $stats['total_reviews'] }} reviews
            </div>
        </div>
    </div>

    {{-- ── Task Status Bar ──────────────────────────────────────────────── --}}
    @php
        $total = max($stats['total_tasks'], 1);
        $doneW    = round($stats['completed_tasks']  / $total * 100);
        $activeW  = round($stats['inprogress_tasks'] / $total * 100);
        $pendingW = 100 - $doneW - $activeW;
    @endphp
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-slate-700">Task Status Breakdown</h3>
            <a href="{{ route('admin.tasks') }}" class="text-xs text-brand-600 hover:underline">View all tasks →</a>
        </div>
        <div class="flex h-3 rounded-full overflow-hidden gap-0.5">
            @if($doneW)   <div class="bg-green-400 transition-all" style="width:{{ $doneW }}%"></div>   @endif
            @if($activeW) <div class="bg-blue-400 transition-all"  style="width:{{ $activeW }}%"></div> @endif
            @if($pendingW)<div class="bg-slate-200 transition-all" style="width:{{ $pendingW }}%"></div>@endif
        </div>
        <div class="flex items-center gap-6 mt-3 text-xs text-slate-500">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>Completed ({{ $stats['completed_tasks'] }})</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>In Progress ({{ $stats['inprogress_tasks'] }})</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-200"></span>Pending ({{ $stats['pending_tasks'] }})</span>
        </div>
    </div>

    {{-- ── Revenue Chart + Recent Payments ─────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Mini bar chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-5">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">Revenue — Last 6 Months</h3>
            @php $maxRev = $revenueChart->max('revenue') ?: 1; @endphp
            <div class="flex items-end gap-2 h-28">
                @foreach($revenueChart as $month)
                    @php $pct = round($month['revenue'] / $maxRev * 100); @endphp
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <span class="text-xs text-slate-400">${{ number_format($month['revenue'], 0) }}</span>
                        <div class="w-full rounded-t-md bg-brand-500 transition-all"
                             style="height: {{ max($pct, 4) }}%;  min-height:4px"></div>
                        <span class="text-xs text-slate-400">{{ $month['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent payments --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-700">Recent Payments</h3>
                <a href="{{ route('admin.payments') }}" class="text-xs text-brand-600 hover:underline">View all →</a>
            </div>
            <div class="space-y-2">
                @forelse($recentPayments as $payment)
                    <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-0">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($payment->user->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700 truncate">{{ $payment->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-slate-400">{{ $payment->subscription->name ?? '—' }} · {{ $payment->paid_at?->format('M d, Y') }}</p>
                        </div>
                        <span class="text-sm font-semibold text-green-600 flex-shrink-0">+${{ number_format($payment->amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 py-4 text-center">No payments yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Recent Users ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-700">Newest Users</h3>
            <a href="{{ route('admin.users') }}" class="text-xs text-brand-600 hover:underline">Manage all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-2 text-left text-xs font-semibold text-slate-400">User</th>
                        <th class="pb-2 text-left text-xs font-semibold text-slate-400">Plan</th>
                        <th class="pb-2 text-left text-xs font-semibold text-slate-400">Role</th>
                        <th class="pb-2 text-left text-xs font-semibold text-slate-400">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($recentUsers as $user)
                        <tr>
                            <td class="py-2.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-700">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->isPro() ? 'bg-brand-100 text-brand-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $user->isPro() ? '⭐ Pro' : 'Free' }}
                                </span>
                            </td>
                            <td class="py-2.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-2.5 text-slate-400 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection