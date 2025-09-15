@extends('layout.layout')

@php
    $title='Dashboard';
    $subTitle = 'AI';
    $script= '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2><i class="fas fa-calculator"></i> Accountant Dashboard</h2>

        
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="upload-btn" style="background:none;border:none;color:#ffffff;cursor:pointer;">
                    Logout
                </button>
            </form>
        </div>
            
            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-title">TOTAL PAYMENTS</div>
                    <div class="stat-value">${{ number_format($payments->sum('amount'), 2) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">COMPLETED PAYMENTS</div>
                    <div class="stat-value">{{ $payments->where('status', 'completed')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">PENDING PAYMENTS</div>
                    <div class="stat-value">{{ $payments->where('status', 'pending')->count() }}</div>
                </div>
            </div>
            
            @if($payments && count($payments) > 0)
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Transaction ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->customer->name }}</td>
                        <td class="amount">${{ number_format($payment->amount, 2) }}</td>
                        <td>
                            <form action="{{ route('payment.updateStatus', $payment->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" 
                                        class="status-badge {{ $payment->status }}">
                                    @php
                                        $statuses = [
                                            'customer_confirmation' => 'Customer Confirmation',
                                            'paid' => 'Paid'
                                        ];
                                    @endphp
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ $payment->status === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td>
                            <span class="transaction-id">{{ $payment->transaction_id ?? 'N/A' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                <p>No payment records found.</p>
            </div>
            @endif
        </div>
    </div>
@endsection