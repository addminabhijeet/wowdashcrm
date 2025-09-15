@extends('layout.layout')

@php
    $title='Dashboard';
    $subTitle = 'AI';
    $script= '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2><i class="fas fa-chalkboard-teacher"></i> Trainer Dashboard</h2>

        
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
                    <div class="stat-title">TOTAL BATCHES</div>
                    <div class="stat-value">{{ $trainings->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">IN PROGRESS</div>
                    <div class="stat-value">{{ $trainings->where('status', 'in_progress')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">COMPLETED</div>
                    <div class="stat-value">{{ $trainings->where('status', 'completed')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">UPCOMING</div>
                    <div class="stat-value">{{ $trainings->where('status', 'scheduled')->count() }}</div>
                </div>
            </div>
            
            @if($trainings && count($trainings) > 0)
            <div class="trainings-table-container">
                <table class="trainings-table">
                    <thead>
                        <tr>
                            <th>Batch</th>
                            <th>Customer</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trainings as $training)
                        <tr>
                            <td>
                                <span class="batch-name">{{ $training->batch_name }}</span>
                            </td>
                            <td>
                                <span class="customer-name">{{ $training->customer->name }}</span>
                            </td>
                            <td class="date-cell">
                                <i class="fas fa-calendar-alt"></i> 
                                {{ \Carbon\Carbon::parse($training->start_date)->format('M j, Y') }}
                            </td>
                            <td class="date-cell">
                                <i class="fas fa-calendar-alt"></i> 
                                {{ \Carbon\Carbon::parse($training->end_date)->format('M j, Y') }}
                            </td>
                            <td>
                                <form action="{{ route('training.updateStatus', $training->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                            class="status-badge {{ $training->status }}">
                                        @php
                                            $statuses = [
                                                'paid' => 'Paid',
                                                'in_training' => 'In Training'
                                            ];
                                        @endphp
                                        @foreach($statuses as $key => $label)
                                            <option value="{{ $key }}" {{ $training->status === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <div class="file-actions">
                                    <!-- View button -->
                                    <a href="{{ asset('storage/invoice/' . $training->invoice_file) }}" 
                                    class="view-link" 
                                    target="_blank" 
                                    rel="noopener noreferrer">
                                        <i class="fas fa-eye"></i> View
                                    </a>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-chalkboard"></i>
                <p>No training batches assigned yet.</p>
            </div>
            @endif
        </div>
    </div>
@endsection