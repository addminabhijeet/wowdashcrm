<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Junior Dashboard</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #334155;
        }

        .dashboard-container {
            max-width: 1300px;
            margin: 20px auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .dashboard-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(90deg, #6366f1, #4f46e5);
            color: #fff;
            padding: 15px 20px;
        }

        .dashboard-header h2 {
            font-size: 22px;
            margin: 0;
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .dashboard-header h2 i {
            margin-right: 10px;
        }

        .user-info {
            font-size: 0.9rem;
            margin-right: 15px;
        }

        .user-info span {
            margin: 0 5px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-btn {
            padding: 6px 12px;
            border-radius: 6px;
            background: #4f46e5;
            color: #fff;
            text-decoration: none;
            font-size: 0.85rem;
            transition: background 0.3s;
        }

        .header-btn:hover {
            background: #4338ca;
        }

        .logout-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin: 30px 0 15px;
            color: #1e293b;
        }

        table th {
            background: #f1f5f9;
            font-weight: 600;
        }

    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h2><i class="fas fa-user-tie"></i> Junior Dashboard</h2>

        <div class="user-info">
            <span>{{ Auth::user()->name }}</span> |
            <span>{{ Auth::user()->email }}</span>
        </div>

        <div class="header-actions">
            <div>
                <h6 class="mb-1">Countdown: <span id="countdown">08:00:00</span></h6>
                <h6 class="mb-1">Elapsed: <span id="elapsed">00:00:00</span></h6>
                <select id="pauseSelect" class="form-select form-select-sm">
                    <option value="resume" selected>Resume</option>
                    <option value="lunch">Lunch</option>
                    <option value="break">Break</option>
                    <option value="tea">Tea Break</option>
                </select>
            </div>

            <a href="{{ route('calendar.index') }}" class="header-btn"><i class="fas fa-calendar-alt"></i> Calendar</a>
            <a href="{{ route('google.sheet.index') }}" class="header-btn"><i class="fas fa-database"></i> Database</a>
            <a href="{{ route('call.reports') }}" class="header-btn"><i class="fas fa-phone-alt"></i> Call Tracker</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="p-4">
        <h2 class="mb-4">📊 Call Reports</h2>



        <!-- Duplicates -->
        <div class="row">
            <div class="col-md-6">
                <h5>Duplicate Names</h5>
                <table class="table table-bordered table-sm">
                    <thead><tr><th>Name</th><th>Count</th></tr></thead>
                    <tbody>
                        @foreach($dupByName as $row)
                        <tr><td>{{ $row->candidate_name }}</td><td>{{ $row->cnt }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h5>Duplicate Emails</h5>
                <table class="table table-bordered table-sm">
                    <thead><tr><th>Email</th><th>Count</th></tr></thead>
                    <tbody>
                        @foreach($dupByEmail as $row)
                        <tr><td>{{ $row->email }}</td><td>{{ $row->cnt }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>



        <!-- Full Call Log -->
        <div class="mt-5">
            <h4>📞 Full Call Log</h4>
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Date</th><th>Name</th><th>Email</th><th>Phone</th>
                        <th>Location</th><th>Course</th><th>Remarks</th>
                        <th>Follow Up</th><th>Rating</th><th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($calls as $c)
                    <tr>
                        <td>{{ $c->call_date }}</td>
                        <td>{{ $c->candidate_name }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->phone }}</td>
                        <td>{{ $c->location }}</td>
                        <td>{{ $c->course }}</td>
                        <td>{{ $c->exe_remarks }}</td>
                        <td>{{ $c->followup_remarks }}</td>
                        <td>{{ $c->rating }}</td>
                        <td>{{ $c->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const callsPerHourChart = new Chart(document.getElementById('callsPerHourChart'), {
        type: 'bar',
        data: {
            labels: JSON.parse('{!! json_encode($callsPerHour->pluck("call_hour") ?? []) !!}'),
            datasets: [{
                label: 'Calls',
                data: JSON.parse('{!! json_encode($callsPerHour->pluck("total_calls") ?? []) !!}'),
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        }
    });

    // Location distribution
    const locationChart = new Chart(document.getElementById('locationChart'), {
        type: 'pie',
        data: {
            labels: JSON.parse('{!! json_encode($locationDist->pluck("location") ?? []) !!}'),
            datasets: [{
                data: JSON.parse('{!! json_encode($locationDist->pluck("cnt") ?? []) !!}'),
                backgroundColor: ['#f39c12','#27ae60','#2980b9','#8e44ad','#e74c3c','#16a085']
            }]
        }
    });

    // Ratings
    const ratingsChart = new Chart(document.getElementById('ratingsChart'), {
        type: 'doughnut',
        data: {
            labels: JSON.parse('{!! json_encode($ratings->pluck("rating") ?? []) !!}'),
            datasets: [{
                data: JSON.parse('{!! json_encode($ratings->pluck("cnt") ?? []) !!}'),
                backgroundColor: ['#2ecc71','#f1c40f','#e67e22','#e74c3c','#95a5a6']
            }]
        }
    });
</script>


</body>
</html>
