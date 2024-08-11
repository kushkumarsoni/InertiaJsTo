<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users Report</title>
</head>
<style>
    .table-bordered {
        border:1px solid black;
        width:100%;
        border-collapse:collapse;
    }
    th, td {
        font-size: 12px !important;
        border: 1px solid rgba(0,0,0,0.1);
        font-weight: 400;
        color: #4d4d4d;
        text-align: center
    }
</style>
<body>
    <div style="margin-bottom:25px;"><strong>Total Users : {{ $users?->count() }} </strong></div>
    <table>
        <thead>
            <tr>
                <th style="border-bottom:2pt solid black;">
                    <strong>SN</strong>
                </th>
                <th style="border-bottom:2pt solid black;">
                    <strong>Name</strong>
                </th>
                <th style="border-bottom:2pt solid black;">
                    <strong>Email</strong>
                </th>
                <th style="border-bottom:2pt solid black;">
                    <strong>Mobile</strong>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user?->id }}</td>
                    <td>{{ $user?->name }}</td>
                    <td>{{ $user?->email }}</td>
                    <td>{{ $user?->mobile }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</body>
</html>
