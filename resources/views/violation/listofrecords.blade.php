<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-5.15.4-web/css/all.min.css') }}" rel="stylesheet">
    <title>List of Violations</title>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; 
            font-family: 'Merriweather', serif;
            background-color: #f4f4f4;
        }

        .control-panel {
            width: 280px;
            background-color: green;
            border-right: 1px solid green;
            position: fixed;
            top: 40px;
            left: 0;
            height: calc(100% - 40px);
            padding: 20px;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        .control-panel .btn {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            margin-bottom: 10px;
            background-color: white;
            color: green;
            border: 1px solid green;
        }

        .control-panel .btn i {
            margin-right: 10px;
            color: green;
        }

        .thin-container {
            background-color: black;
            height: 40px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            z-index: 1001;
        }
        .table-container {
            margin-left: 270px;
            padding: 20px;
            height: calc(100vh - 80px);
            box-sizing: border-box;
            padding-top: 60px; 
            overflow: auto; 
        }
        .table-wrapper {
            position: relative;
            max-height: 100%; 
            overflow: auto; 
        }
        .table-scroll {
            max-height: 100%; 
            overflow-y: auto;
            border: 1px solid #ccc; 
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1); 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            box-sizing: border-box;
        }
        .table-header th {
            background-color: green;
            color: white;
            position: sticky;
            top: 0; 
            z-index: 1; 
            box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.1); 
        }
        .btn-primary {
            background-color: black;
            border-color: black;
            color: white;
        }

        .btn-primary:hover {
            background-color: #333;
            border-color: #333;
        }

        h2, h5 {
            color: white;
        }
    </style>
</head>
<body>

    <div class="thin-container">
        <h5 class="container-text">Municipal Agriculture Office-Aparri</h5>
    </div>

    <div class="control-panel">
        <div class="control-panel-header text-center">
            <img src="{{ asset('img/maologo.jpg') }}" alt="Logo" class="rounded-circle" style="width: 100px; height: 100px;">
            <h2 class="control-panel-title" style="font-size: 1.25rem;">List of Violations</h2>
        </div>
        <a href="{{ route('dashboard') }}" class="btn mb-2">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('admin.report') }}" class="btn mb-2">
            <i class="fas fa-file-alt"></i> Maritime Reports
        </a>
        <a href="{{ route('admin.referrals') }}" class="btn mb-2">
            <i class="fas fa-share-square"></i> View Referrals
        </a>
        <a href="{{ route('violation.record') }}" class="btn mb-2">
            <i class="fas fa-plus-square"></i> Record a Violation
        </a>
        <a href="{{ route('violation.list') }}" class="btn mb-2">
            <i class="fas fa-list"></i> List of Records
        </a>
    </div>

    <div class="table-container">
        <!-- Display success message -->
        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <!-- Filter by Barangay Dropdown -->
        <form method="GET" action="{{ route('violation.list') }}" class="mb-4">
            <label for="barangay">Filter by Barangay:</label>
            <select name="barangay" id="barangay" class="form-control" style="width: 200px; display: inline-block;">
                <option value="">All Barangays</option>
                @foreach($barangays as $barangayOption)
                    <option value="{{ $barangayOption }}" {{ $barangay == $barangayOption ? 'selected' : '' }}>
                        {{ $barangayOption }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <!-- Table displaying violations -->
        <div class="table-wrapper">
            <div class="table-scroll">
                <table>
                    <thead class="table-header">
                        <tr>
                            <th>Violation</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Extension Name</th>
                            <th>Sex</th>
                            <th>Barangay</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($violations as $violation)
                        <tr>
                            <td>{{ $violation->violation }}</td>
                            <td>{{ $violation->location }}</td>
                            <td>{{ $violation->date_of_violation }}</td>
                            <td>{{ $violation->time_of_violation }}</td>
                            <td>{{ $violation->first_name }}</td>
                            <td>{{ $violation->middle_name }}</td>
                            <td>{{ $violation->last_name }}</td>
                            <td>{{ $violation->extension_name }}</td>
                            <td>{{ $violation->sex }}</td>
                            <td>{{ $violation->address }}</td>
                            <td>
                                <a href="{{ route('violation.edit', $violation->id) }}" class="btn btn-primary">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</body>
</html>
