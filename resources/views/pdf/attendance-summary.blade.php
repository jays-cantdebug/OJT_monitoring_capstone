<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Summary Report</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #000000; margin: 30px; }
        h1 { font-size: 18px; color: #14213D; margin-bottom: 4px; }
        .meta { font-size: 12px; color: #555; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>NORMI OJT &mdash; Attendance Summary Report</h1>
    <p class="meta">
        {{ $filterLabel }}<br>
        Generated {{ now()->format('M j, Y g:i A') }}
    </p>

    <x-reports.attendance-summary-table :entries="$entries" :total-hours="$totalHours" :total-minutes="$totalMinutes" />
</body>
</html>
