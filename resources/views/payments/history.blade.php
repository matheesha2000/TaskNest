@extends('layouts.app')
@section('title', 'Payment History')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-slate-800">Payment History</h2>
    </div>

    @if($payments->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-700">No payments yet</h3>
            <p class="text-slate-400 mt-1">Upgrade to Pro to see your payment history here.</p>
            <a href="{{ route('subscription.index') }}"
               class="inline-block mt-4 px-5 py-2.5 bg-brand-600 text-white rounded-lg text-sm font-medium hover:bg-brand-700 transition-colors">
                View Plans
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-slate-500">Plan</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-500">Amount</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-500">Method</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-500">Status</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-500">Date</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-500">Transaction</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($payments as $payment)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $payment->subscription->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                ${{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 capitalize">
                                {{ $payment->payment_method }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ in_array($payment->payment_status, ['completed', 'paid']) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $payment->payment_status === 'completed' ? 'Completed' : ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-slate-400 font-mono text-xs truncate max-w-32">
                                {{ $payment->transaction_id ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($payments->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection