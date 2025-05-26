<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Branch Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .details {
            margin: 20px 0;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .details td:first-child {
            font-weight: bold;
            width: 30%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Branch Information</h1>
    </div>

    <div class="qr-code">
        {!! $model->generateQrCode() !!}
    </div>

    <div class="details">
        <table>
            <tr>
                <td>Branch Name</td>
                <td>{{ $model->name }}</td>
            </tr>
            <tr>
                <td>Branch Code</td>
                <td>{{ $model->code }}</td>
            </tr>
            <tr>
                <td>Country</td>
                <td>{{ $model->country->name }}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td>{{ $model->address }}</td>
            </tr>
            <tr>
                <td>Phone</td>
                <td>{{ $model->phone }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $model->email }}</td>
            </tr>
            <tr>
                <td>Manager</td>
                <td>{{ $model->manager->name ?? 'Not Assigned' }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
