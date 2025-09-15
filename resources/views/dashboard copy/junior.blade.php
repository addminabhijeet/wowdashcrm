@extends('layout.layout')

@php
    $title='Dashboard';
    $subTitle = 'AI';
    $script= '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>
                <i class="fas fa-user-tie"></i> Junior Dashboard
            </h2>

            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name }}</span> |
                <span class="user-email">{{ Auth::user()->email }}</span>
            </div>

            <div>
                <h4>
                    Countdown: <span id="countdown">08:00:00</span><br>
                    Elapsed: <span id="elapsed">00:00:00</span>
                </h4>

                <select id="pauseSelect">
                    <option value="resume" selected>Resume</option>
                    <option value="lunch">Lunch</option>
                    <option value="break">Break</option>
                    <option value="tea">Tea Break</option>
                </select>
            </div>

            <!-- Calendar Button -->
            <a href="{{ route('calendar.index') }}" 
            class="calendar-btn"
            style="margin-right: 15px; padding: 6px 12px; background-color: #4f46e5; color: #fff; border-radius: 6px; text-decoration: none; font-size: 0.9em;">
                <i class="fas fa-calendar-alt"></i> Calendar
            </a>

            <!-- Database Button -->
            <a href="{{ route('google.sheet.index') }}" 
            class="calendar-btn"
            style="margin-right: 15px; padding: 6px 12px; background-color: #4f46e5; color: #fff; border-radius: 6px; text-decoration: none; font-size: 0.9em;">
                <i class="fas fa-database"></i> Database
            </a>
            
            <!-- Database Button -->
            <a href="{{ route('call.reports') }}" 
            class="calendar-btn"
            style="margin-right: 15px; padding: 6px 12px; background-color: #4f46e5; color: #fff; border-radius: 6px; text-decoration: none; font-size: 0.9em;">
                <i class="fas fa-phone-alt"></i> Call Tracker
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
                                            'pending_review' => 'Pending Review',
                                            'forwarded_to_senior' => 'Forwarded to Senior',
                                            'customer_confirmation' => 'Customer Confirmation',
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
            @else
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>You haven't uploaded any resumes yet.</p>
            </div>
            @endif
        </div>
    </div>
@endsection
<script>
    let timerInterval, backendSyncInterval;
    let remainingSeconds = Number("{{ $remaining_seconds ?? 0 }}");
    let elapsedSeconds   = Number("{{ $elapsed_seconds ?? 0 }}");

    function formatTime(sec){
        let h = Math.floor(sec/3600);
        let m = Math.floor((sec%3600)/60);
        let s = sec%60;
        return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
    }

    function updateUI(){
        document.getElementById('countdown').innerText = formatTime(remainingSeconds);
        document.getElementById('elapsed').innerText = formatTime(elapsedSeconds);
    }

    function forceLogout(){
        fetch("{{ route('logout') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        }).then(()=>{
            window.location.href = "/login"; // redirect after logout
        });
    }

    function startTimer(){
        clearInterval(timerInterval);
        clearInterval(backendSyncInterval);

        // UI update every second
        timerInterval = setInterval(()=>{
            if(remainingSeconds > 0){
                remainingSeconds--;
                elapsedSeconds++;
                updateUI();
            } else {
                clearInterval(timerInterval);
                clearInterval(backendSyncInterval);
                alert("Your 8-hour work session has ended.");
                forceLogout();
            }
        }, 1000);

        // Backend sync every minute
        backendSyncInterval = setInterval(()=>{
            fetch("{{ route('timer.update') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'tick' })
            })
            .then(res => res.json())
            .then(data=>{
                remainingSeconds = data.remaining_seconds;
                elapsedSeconds = (8*60*60) - remainingSeconds;
                updateUI();

                if (data.status === 'completed') {
                    clearInterval(timerInterval);
                    clearInterval(backendSyncInterval);
                    alert("Your 8-hour work session has ended.");
                    forceLogout();
                }
            })
        }, 60000); // 1 min
    }

    // Pause / Resume handler
    document.getElementById('pauseSelect').addEventListener('change', function(){
        let type = this.value;

        if(type === "resume"){
            fetch("{{ route('timer.update') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'resume' })
            })
            .then(res=>res.json())
            .then(()=>{
                startTimer();
            })
        } 
        else if(type){ // Pause
            fetch("{{ route('timer.update') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'pause', pause_type: type })
            })
            .then(res=>res.json())
            .then(()=>{
                clearInterval(timerInterval);
                clearInterval(backendSyncInterval);
            })
        }
    });

    startTimer();
</script>


