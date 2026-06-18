<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('user', 'subscription');

        // Search by user name or transaction ID
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('payment_status', $status);
        }

        $payments = $query->latest('paid_at')->paginate(15)->withQueryString();

        $summary = [
            'total'   => Payment::where('payment_status', 'paid')->sum('amount'),
            'count'   => Payment::where('payment_status', 'paid')->count(),
            'pending' => Payment::where('payment_status', 'pending')->count(),
        ];

        return view('admin.payments', compact('payments', 'summary'));
    }
}