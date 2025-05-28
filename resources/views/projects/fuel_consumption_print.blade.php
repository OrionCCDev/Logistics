<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Fuel Consumption Summary - {{ $project->name }}</title>
    <link rel="stylesheet" href="{{ asset('dashAssets/vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <style>
        body {
            margin: 20px;
            font-family: Arial, sans-serif;
            background-color: white;
        }
        .print-logo {
            display: block !important;
            max-width: 150px !important;
            margin: 0 auto 20px auto !important;
        }
        .print-title {
            text-align: center !important;
            margin-bottom: 20px !important;
            font-size: 18px !important;
            font-weight: bold !important;
        }
        .print-total {
            text-align: center !important;
            margin-bottom: 20px !important;
            font-size: 16px !important;
            padding: 10px !important;
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
        }
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 20px !important;
            font-size: 12px !important;
        }
        th, td {
            border: 1px solid #666 !important;
            padding: 8px !important;
            text-align: left !important;
        }
        th {
            background-color: #f0f0f0 !important;
            font-weight: bold !important;
        }
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div id="print-section-content">
        <img width="150" height="75" src="{{ asset('dashAssets/dist/img/logo-light.png') }}" alt="Company Logo" class="print-logo">
        <h5 class="print-title">Fuel Consumption Summary - {{ $project->name }} (This Month)</h5>
        <div class="print-total">
            <strong>Total Fuel Consumption: {{ $totalFuelConsumption }} Liters</strong><br>
            <strong>Total Working Hours: {{ $totalWorkingHours }} Hours</strong>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Fuel Consumed</th>
                        <th>Total Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($timesheets as $timesheet)
                    <tr>
                        <td>{{ $timesheet->date->format('Y-m-d') }}</td>
                        <td>{{ $timesheet->vehicle ? $timesheet->vehicle->name : 'N/A' }} ({{ $timesheet->vehicle ? $timesheet->vehicle->plate_number : 'N/A' }})</td>
                        <td>{{ $timesheet->fuel_consumption }}</td>
                        <td>{{ $timesheet->working_hours }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No fuel consumption records found for this project this month.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            // Optional: Close the window after printing.
            // This might not work in all browsers due to security restrictions.
            // window.onafterprint = function() {
            //     window.close();
            // };
        };
    </script>
</body>
</html>
