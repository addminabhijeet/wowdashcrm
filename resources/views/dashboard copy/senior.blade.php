@extends('layout.layout')

@php
    $title='Dashboard';
    $subTitle = 'AI';
    $script= '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>
                <i class="fas fa-user-tie"></i> Senior Dashboard
            </h2>

            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name }}</span> |
                <span class="user-email">{{ Auth::user()->email }}</span>
            </div>

            <!-- Calendar Button -->
            <a href="{{ route('calendar.index') }}" 
            class="calendar-btn"
            style="margin-right: 15px; padding: 6px 12px; background-color: #4f46e5; color: #fff; border-radius: 6px; text-decoration: none; font-size: 0.9em;">
                <i class="fas fa-calendar-alt"></i> Calendar
            </a>

            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:none; border:none; color:#ffffff; cursor:pointer; font-size:inherit;">
                    Logout
                </button>
            </form>
        </div>

            
            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-title">TOTAL ASSIGNED</div>
                    <div class="stat-value">{{ $resumes->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">PENDING REVIEW</div>
                    <div class="stat-value">{{ $resumes->where('status', 'pending')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">IN REVIEW</div>
                    <div class="stat-value">{{ $resumes->where('status', 'in_review')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">COMPLETED</div>
                    <div class="stat-value">{{ $resumes->where('status', 'completed')->count() }}</div>
                </div>
            </div>
            
            @if($resumes && count($resumes) > 0)
            <div class="resumes-table-container">
                <table class="resumes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Candidate</th>
                            <th>Status</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resumes as $resume)
                        <tr>
                            <td>{{ $resume->id }}</td>
                            <td>{{ $resume->candidate_name }}</td>
                            <td>
                                <form action="{{ route('resumes.updateStatus', $resume->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                            class="status-badge {{ $resume->status }}">
                                        @php
                                            $statuses = [
                                                
                                                'forwarded_to_senior' => 'Forwarded to Senior',
                                                'pending_review' => 'Pending Review',
                                                'rejected_by_senior' => 'Rejected by Senior',
                                                'customer_confirmation' => 'Customer Confirmation'
                                            ];
                                        @endphp
                                        @foreach($statuses as $key => $label)
                                            <option value="{{ $key }}" {{ $resume->status === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <div class="file-actions">
                                    <!-- Download button -->
                                        <a href="{{ asset('storage/resumes/' . $resume->resume_file) }}" 
                                        class="download-link" 
                                        download="{{ $resume->candidate_name }}_resume.pdf" 
                                        rel="noopener noreferrer">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    <!-- View button -->
                                    <a href="{{ asset('storage/resumes/' . $resume->resume_file) }}" 
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
                <i class="fas fa-file-alt"></i>
                <p>No resumes assigned for review yet.</p>
            </div>
            @endif
        </div>
    </div>
@endsection