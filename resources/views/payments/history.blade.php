@extends('layouts.app')
@section('title', 'Payment History')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Payment History</h2>
    </div>

    @if($payments->isEmpty())
        <div class="glass-card rounded-2xl border border-slate-200/60 p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-700">No payments yet</h3>
            <p class="text-slate-500 mt-1 max-w-sm mx-auto text-sm">Upgrade to TaskNest Pro to unlock smart filters, priority lists, and view your payment history here.</p>
            <a href="{{ route('subscription.index') }}"
               class="inline-block mt-5 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 hover:shadow-md transition-all hover:scale-[1.02] active:scale-100">
                View Plans
            </a>
        </div>
    @else
        <div class="glass-card rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left glass-table">
                    <thead>
                        <tr class="border-b border-slate-200/60 bg-slate-50">
                            <th class="px-6 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Method</th>
                            <th class="px-6 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Transaction</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-slate-50/70 transition-all duration-150">
                                <td class="px-6 py-4.5 font-bold text-slate-800">
                                    {{ $payment->subscription->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4.5 font-semibold text-slate-900">
                                    ${{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4.5 text-slate-550 capitalize">
                                    {{ $payment->payment_method }}
                                </td>
                                <td class="px-6 py-4.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide border
                                        {{ in_array($payment->payment_status, ['completed', 'paid']) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-red-50 text-red-700 border border-red-200/60' }}">
                                        {{ $payment->payment_status === 'completed' ? 'COMPLETED' : strtoupper($payment->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4.5 text-slate-500">
                                    {{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4.5 text-slate-400 font-mono text-xs truncate max-w-32" title="{{ $payment->transaction_id }}">
                                    {{ $payment->transaction_id ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="px-6 py-4 border-t border-slate-200/60 bg-slate-50">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection