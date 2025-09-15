@extends('layout.layout')

@php
    $title='Dashboard';
    $subTitle = 'AI';
    $script= '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2><i class="fas fa-user-tie"></i> Customer Dashboard</h2>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="upload-btn" style="background:none;border:none;color:#ffffff;cursor:pointer;">
                    Logout
                </button>
            </form>
        </div>

        <div class="dashboard-content">

            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-title">TOTAL PAYMENTS</div>
                    <div class="stat-value">${{ number_format($payments->sum('amount'), 2) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">RESUMES PROCESSED</div>
                    <div class="stat-value">{{ $payments->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">IN TRAINING</div>
                    <div class="stat-value">{{ $payments->where('resume.status', 'in_training')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">COMPLETED</div>
                    <div class="stat-value">{{ $payments->where('resume.status', 'completed')->count() }}</div>
                </div>
            </div>

            @if($payments->count() > 0)
            <div class="payments-table-container">
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Resume</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Training</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            @php
                                $statusClass = match($payment->resume->status) {
                                    'completed' => 'status-completed',
                                    'in_training' => 'status-in_training',
                                    'rejected' => 'status-rejected',
                                    default => 'status-pending',
                                };

                                $paymentStatusClass = match($payment->status) {
                                    'completed' => 'payment-completed',
                                    'failed' => 'payment-failed',
                                    default => 'payment-pending',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <span class="candidate-name">{{ $payment->resume->candidate_name }}</span>                        
                                </td>
                                <td>
                                    <div class="file-actions">
                                    <!-- View button -->
                                    <a href="{{ asset('storage/resumes/' . $payment->resume->resume_file) }}" 
                                    class="view-link" 
                                    target="_blank" 
                                    rel="noopener noreferrer">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $statusClass }}">{{ $payment->resume->status }}</span>
                                </td>
                                <td>
                                    <span class="payment-status {{ $paymentStatusClass }}">{{ $payment->status }}</span>
                                    <span class="amount">${{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td>
                                    @if($payment->resume->status == 'in_training')
                                        <div class="training-info">
                                            <i class="fas fa-calendar-alt"></i>
                                            {{ $payment->resume->trainings?->first()?->batch_name ?? 'Scheduled' }}
                                        </div>
                                    @else
                                        <span class="training-na">N/A</span>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-file-invoice"></i>
                <p>No payment record found.</p>
            </div>
            @endif

        </div>
    </div>
@endsection