<x-app-layout>
<head></head>
    
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


        <!-- Display the search results in a table format -->
        <div class="table-wrapper">
            <div class="table-scroll">
                <table>
                    <thead class="table-header">
                        <tr>
                            <th>Violator</th>
                            <th>Sex</th>
                            <th>Address</th>
                            <th>Violation</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
    @if(empty($violators) || $violators->isEmpty())
        <tr>
            <td colspan="10" class="text-center">No violators found for your search.</td>
        </tr>
    @else
        @foreach($violators as $violator)
            <tr>
                <td>{{ $violator->violator }}</td>
                <td>{{ $violator->sex }}</td>
                <td>{{ $violator->address }}</td>
                <td>{{ $violator->recordViolation->violation ?? 'N/A' }}</td>
                <td>{{ $violator->recordViolation->location ?? 'N/A' }}</td>
                <td>{{ $violator->recordViolation->date_of_violation ?? 'N/A' }}</td>
                <td>{{ $violator->recordViolation->time_of_violation ?? 'N/A' }}</td>
            </tr>
        @endforeach
    @endif
</tbody>

                </table>
            </div>
        </div>
    </div>

    <script src="/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</x-app-layout>
