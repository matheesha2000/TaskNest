@extends('layouts.admin')
@section('title', 'Payment Monitoring')
@section('subtitle', 'All transactions and revenue tracking')

@section('content')
<div class="space-y-5">

    {{-- ── Summary Cards ────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">${{ number_format($summary['total'], 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Paid Transactions</p>
            <p class="text-2xl font-bold text-slate-800">{{ $summary['count'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Pending</p>
            <p class="text-2xl font-bold text-amber-500">{{ $summary['pending'] }}</p>
        </div>
    </div>

    {{-- ── Filter Bar ───────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <form method="GET" action="{{ route('admin.payments') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-48">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search user or transaction ID…"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <select name="status" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">All Statuses</option>
                <option value="paid"    {{ request('status') === 'paid'    ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed"  {{ request('status') === 'failed'  ? 'selected' : '' }}>Failed</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-xl text-sm font-medium hover:bg-brand-700 transition-colors">
                Filter
            </button>

            @if(request()->hasAny(['search','status']))
                <a href="{{ route('admin.payments') }}" class="text-sm text-slate-400 hover:text-slate-600">Clear</a>
            @endif

            <span class="ml-auto text-xs text-slate-400">{{ $payments->total() }} records</span>
        </form>
    </div>

    {{-- ── Payments Table ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">User</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Plan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Amount</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Method</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Date</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Transaction ID</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50 transition-colors">

                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($payment->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-700">{{ $payment->user->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ $payment->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 text-slate-600">
                            {{ $payment->subscription->name ?? '—' }}
                        </td>

                        <td class="px-5 py-4 font-semibold text-green-600">
                            ${{ number_format($payment->amount, 2) }}
                        </td>

                        <td class="px-5 py-4 text-slate-500 capitalize">
                            {{ $payment->payment_method }}
                        </td>

                        <td class="px-5 py-4">
                            @php
                                $badge = match($payment->payment_status) {
                                    'paid'    => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    default   => 'bg-red-100 text-red-700',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-slate-400 text-xs">
                            {{ $payment->paid_at?->format('M d, Y · g:i A') ?? '—' }}
                        </td>

                        <td class="px-5 py-4">
                            @if($payment->transaction_id)
                                <span class="font-mono text-xs text-slate-400 bg-slate-50 px-2 py-0.5 rounded">
                                    {{ Str::limit($payment->transaction_id, 24) }}
                                </span>
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                            No payment records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($payments->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection