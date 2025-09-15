<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            padding: 20px;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .dashboard-header {
            background: linear-gradient(to right, #6366f1, #4f46e5);
            color: white;
            padding: 25px 30px;
        }
        
        .dashboard-header h2 {
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .dashboard-header h2 i {
            margin-right: 12px;
        }
        
        .dashboard-content {
            padding: 30px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1e293b;
            display: flex;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #6366f1;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #6366f1;
        }
        
        .stat-title {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #6366f1;
        }
        
        .resumes-table-container {
            overflow-x: auto;
            border-radius: 8px;
            box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
        }
        
        .resumes-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        
        .resumes-table th {
            background-color: #f1f5f9;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .resumes-table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .resumes-table tr:last-child td {
            border-bottom: none;
        }
        
        .resumes-table tr {
            transition: background-color 0.2s ease;
        }
        
        .resumes-table tr:hover {
            background-color: #f8fafc;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }
        
        .status-in_review {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        
        .status-completed {
            background-color: #dcfce7;
            color: #16a34a;
        }
        
        .status-rejected {
            background-color: #fee2e2;
            color: #dc2626;
        }
        
        .review-btn {
            display: inline-flex;
            align-items: center;
            background: #6366f1;
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .review-btn:hover {
            background: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(99, 102, 241, 0.2);
        }
        
        .review-btn i {
            margin-right: 6px;
            font-size: 12px;
        }
        
        .candidate-id {
            color: #64748b;
            font-size: 14px;
        }
        
        .candidate-name {
            font-weight: 600;
            color: #1e293b;
            display: block;
            margin-top: 3px;
        }
        
        .uploader-name {
            color: #475569;
            font-weight: 500;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #cbd5e1;
        }
        
        .empty-state p {
            font-size: 16px;
        }

        .download-link {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            transition: color 0.2s ease;
        }
        
        .download-link:hover {
            color: #7c3aed;
        }
        
        .download-link i {
            margin-right: 6px;
            font-size: 14px;
        }

        .download-link, .view-link {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s, transform 0.3s;
        }

        .download-link:hover, .view-link:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

                .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: #fff;
        }

        .user-info {
            margin-right: 15px;
            font-size: 0.9em;
        }

        .user-info span {
            margin: 0 5px;
        }
        
        @media (max-width: 1024px) {
            .resumes-table-container {
                overflow-x: auto;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 20px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-header {
                padding: 20px;
            }
            
            .dashboard-header h2 {
                font-size: 20px;
            }
            
            .section-title {
                font-size: 18px;
            }
            
            .resumes-table th,
            .resumes-table td {
                padding: 12px 10px;
                font-size: 14px;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-value {
                font-size: 20px;
            }
            
            .review-btn {
                padding: 6px 10px;
                font-size: 13px;
            }

        .download-link, .view-link {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s, transform 0.3s;
        }

        .download-link:hover, .view-link:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
            
            .file-actions {
            display: flex;
            gap: 10px;
        }
        }
    </style>
</head>
<body>
    
<div class="dashboard-container">
    <div class="dashboard-header">
        <h2>
            <i class="fas fa-calendar-alt"></i> Attendance Calendar
        </h2>
        <a href="{{ route('dashboard') }}" 
            class="calendar-btn"
            style="margin-right: 15px; padding: 6px 12px; background-color: #4f46e5; color: #fff; border-radius: 6px; text-decoration: none; font-size: 0.9em;">
                <i class="fas fa-calendar-alt"></i> Dashboard
        </a>
    </div>

    <!-- Month Navigation -->
    <div class="calendar-controls" style="padding:20px; display:flex; justify-content:space-between; align-items:center;">
        <a href="{{ route('calendar.index', [($month-1) ?: 12, $month == 1 ? $year-1 : $year]) }}" class="px-4 py-2 bg-gray-200 rounded">Prev</a>
        <h3 class="text-xl font-semibold">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</h3>
        <a href="{{ route('calendar.index', [($month % 12) +1 , $month == 12 ? $year+1 : $year]) }}" class="px-4 py-2 bg-gray-200 rounded">Next</a>
    </div>

    <!-- Calendar Grid -->
    <div class="calendar-grid" style="display:grid; grid-template-columns: repeat(7, 1fr); gap:1px; background:#e2e8f0;">
        @php
            $weekdays = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
            foreach($weekdays as $day) {
                echo "<div class='bg-gray-200 font-semibold text-center p-2'>$day</div>";
            }

            // fill empty days at start
            $startDay = $dates[0]->dayOfWeek;
            for($i=0;$i<$startDay;$i++){
                echo "<div class='bg-white p-4'></div>";
            }
        @endphp

        @foreach($dates as $date)
            @php
                $formatted = $date->format('Y-m-d');
                $status = $attendances[$formatted]->status ?? 'working';
                $statusClass = 'status-' . $status; // e.g., status-present
            @endphp
            <div class="calendar-day cursor-pointer p-4 {{ $statusClass }}" 
                 data-date="{{ $formatted }}" 
                 title="{{ ucfirst($status) }}">
                <div class="day-number font-bold">{{ $date->day }}</div>
                <div class="status-text text-sm mt-1">{{ ucfirst($status) }}</div>
            </div>
        @endforeach
    </div>
</div>

<!-- Styles for calendar status -->
<style>
    .calendar-day {
        text-align: center;
        border-radius: 6px;
        transition: transform 0.2s;
    }
    .calendar-day:hover {
        transform: scale(1.05);
    }

    /* Status Colors */
    .status-working { background-color: #facc1533; }     /* yellow */
    .status-holiday { background-color: #ef444433; }     /* red */
    .status-present { background-color: #22c55e33; }     /* green */
    .status-absent { background-color: #f8717133; }      /* pink/red */
</style>

<!-- JS to handle click and update -->
<script>
    const colors = {
        'working': '#facc1533',
        'holiday': '#ef444433',
        'present': '#22c55e33',
        'absent': '#f8717133'
    };

    document.querySelectorAll('.calendar-day').forEach(day => {
        day.addEventListener('click', function() {
            let date = this.dataset.date;
            let currentStatus = this.querySelector('.status-text').innerText.toLowerCase();
            let options = ['working','holiday','present','absent'];
            let nextStatus = options[(options.indexOf(currentStatus)+1)%options.length];

            fetch("{{ route('calendar.updateStatus') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({date: date, status: nextStatus})
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.querySelector('.status-text').innerText = nextStatus.charAt(0).toUpperCase() + nextStatus.slice(1);
                    this.className = 'calendar-day cursor-pointer p-4 status-' + nextStatus;
                }
            });
        });
    });
</script>

</body>
</html>


