<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/chart.umd.js') }}"></script>
    <style>
        body {
            font-family: 'Merriweather', serif;
            font-weight: bold;
            background-color: #f4f4f4;
        }

        .thin-container {
            background-color: black;
            height: 40px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container-text {
            color: white;
            font-size: 1.2rem;
            margin: 0;
            text-align: center;
        }

        .control-panel {
            width: 300px;
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

        .main-content {
            margin-left: 300px;
            padding: 20px;
        }

        .btn {
            display: flex;
            align-items: center;
            background-color: white;
            color: green;
            border: 1px solid green;
            padding: 10px;
            text-align: left;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .btn i {
            margin-right: 10px;
        }

        .btn:hover {
            background-color: #f0f0f0;
        }

        .box {
            border-radius: 15px;
            background: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .box-header {
            margin-bottom: 10px;
        }

        .box-header i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .box-header h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }

        .box-content {
            font-size: 2rem;
            font-weight: 700;
            color: white;
        }

        .bg-primary {
            background: linear-gradient(135deg, #007bff, #00d4ff);
        }

        .bg-success {
            background: linear-gradient(135deg, #28a745, #7fffd4);
        }

        .bg-info {
            background: linear-gradient(135deg, #17a2b8, #00ced1);
        }
    </style>




<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <a href="{{ route('violation.barangays') }}" class="text-decoration-none">
            <div class="box bg-success d-flex flex-column h-100">
                <div class="box-header">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4>Barangay with Violators</h4>
                </div>
                <div class="box-content mt-auto">
                    <h2 class="card-text">
                        {{ \App\Models\Violator::distinct('address')->count('address') }}
                    </h2>
                </div>
            </div>
        </a>
    </div>


        <div class="col-lg-4 col-md-6 mb-4">
            <div class="box bg-primary d-flex flex-column h-100">
                <div class="box-header">
                    <i class="fas fa-exclamation-circle"></i>
                    <h4>Total # of Violations</h4>
                </div>
                <div class="box-content mt-auto">
                    <h2 class="card-text">
                        {{ \App\Models\RecordViolation::distinct('violation')->count('violation') }}
                    </h2>
                </div>
            </div>
        </div>


        <!-- Monthly Violations Graph -->
        <!-- Charts Section -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <h3>Monthly Violations</h3>
                <canvas id="monthlyViolationsChart" width="500" height="300"></canvas>
            </div>
            <div class="col-lg-6">
                <h3>Yearly Violations</h3>
                <canvas id="yearlyViolationsChart" width="500" height="300"></canvas>


            </div>
        </div>

        <script>
            // Monthly Violations Chart
            const monthlyData = @json($monthlyViolationsData); // Ensure this contains day numbers and counts
            const monthlyLabels = Array.from({
                length: 31
            }, (_, i) => (i + 1).toString()); // Array for day 1 to 31

            // Create an array to hold counts for each day
            const monthlyCounts = Array(31).fill(0); // Initialize an array with 31 zeros

            // Fill the monthlyCounts based on the provided monthlyData
            monthlyData.forEach(data => {
                const dayIndex = data.day - 1; // Day index (0-30) for the array
                monthlyCounts[dayIndex] = data.count; // Set the count for the corresponding day
            });

            // Get the context for the monthly chart
            const ctxMonthly = document.getElementById('monthlyViolationsChart').getContext('2d');
            const monthlyViolationsChart = new Chart(ctxMonthly, {
                type: 'line', // Change to 'bar' if you prefer a bar chart
                data: {
                    labels: monthlyLabels, // Day labels from 1 to 31
                    datasets: [{
                        label: 'Monthly Violations',
                        data: monthlyCounts, // Use the filled counts array
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: true, // Fill the area under the line
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                display: false // Show y-axis labels
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Days' // Title for the x-axis indicating it shows days
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top' // Adjusts the legend position
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const day = context.label; // Day number
                                    const count = context.raw; // Corresponding count
                                    return `Day ${day}: ${count} violations`; // Tooltip showing day and count
                                }
                            }
                        }
                    }
                }
            });


            // Yearly Violations Chart
            const yearlyData = @json($yearlyViolationsData); // Ensure this contains month names and counts
            const yearlyLabels = [
                'January', 'February', 'March', 'April', 'May',
                'June', 'July', 'August', 'September', 'October',
                'November', 'December'
            ]; // Array of all month names

            // Create an array to hold counts for each month
            const yearlyCounts = Array(12).fill(0); // Initialize an array with 12 zeros

            // Fill the yearlyCounts based on the provided yearlyData
            yearlyData.forEach(data => {
                const monthIndex = new Date(data.month + " 1").getMonth(); // Get the index (0-11) for the month
                yearlyCounts[monthIndex] = data.count; // Set the count for the corresponding month
            });

            const ctxYearly = document.getElementById('yearlyViolationsChart').getContext('2d');
            const yearlyViolationsChart = new Chart(ctxYearly, {
                type: 'bar',
                data: {
                    labels: yearlyLabels, // Month names displayed on the x-axis
                    datasets: [{
                        label: 'Yearly Violations',
                        data: yearlyCounts, // Use the filled counts array
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1,
                        barThickness: 30,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                display: false // Hide y-axis labels
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Months' // Title for the x-axis indicating it shows months
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top' // Adjusts the legend position
                        },
                        tooltip: { // This entire section is removed
                            enabled: true // Disable tooltips
                        }
                    }
                }
            });
        </script>
</x-app-layout>