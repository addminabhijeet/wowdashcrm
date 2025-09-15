@extends('layout.layout')

@php
    $title='Dashboard';
    $subTitle = 'AI';
    $script= '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2><i class="fas fa-user-shield"></i> Admin Dashboard</h2>
             
                <a href="{{ route('logins') }}" 
                    class="upload-btn" 
                    style="background:none;border:none;color:#ffffff;cursor:pointer;text-decoration:none;">
                        Logins History
                </a>
            
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="upload-btn" style="background:none;border:none;color:#ffffff;cursor:pointer;">
                        Logout
                    </button>
                </form>

            </div>
        
        <div class="dashboard-content">
            <h3 class="section-title">
                <i class="fas fa-users"></i> All Users
            </h3>
            
            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-title">TOTAL USERS</div>
                    <div class="stat-value">{{ $users->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">JUNIOR USERS</div>
                    <div class="stat-value">{{ $users->where('role', 'junior')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">SENIOR USERS</div>
                    <div class="stat-value">{{ $users->where('role', 'senior')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">CUSTOMER USERS</div>
                    <div class="stat-value">{{ $users->where('role', 'customer')->count() }}</div>
                </div>
            </div>
                    @if($users && count($users) > 0)
                    <div class="users-table-container">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="role-badge role-{{ $user->role }}">{{ $user->role }}</span>
                                    </td>
                                    <td class="date-cell">{{ $user->created_at->format('M j, Y g:i A') }}</td>
                                    <td class="date-cell">{{ $user->updated_at->format('M j, Y g:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-user-times"></i>
                        <p>No users found.</p>
                    </div>
                    @endif
                </div>
            
        </h3>

        

    </div>
@endsection