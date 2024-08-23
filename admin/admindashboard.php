<?php
    // session_start(); // Start the session (important for checking session variables)
    include '../admin.php';
    $admin = new Admin();
    // $admin->handleRedirect(); // Call handleRedirect to check login status and redirect

    // Removing this causes website to proceed to index.php despite isLoggedIn from admin.php returning false
    if(!$admin->isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
    if($admin->isLoggedIn() && $admin->session_role == 'user') {
        header("Location: ../index.php");
        exit();
    }
    
    $data = $admin->getTenantCountByApartment();
    // print_r($data);

    // echo "<br><br>";
    
    $incomedata = $admin->getMonthlyIncome();
    // print_r($incomedata);

    // echo "<br><br>";

    $userRolesData = $admin->getUserRolePercentage();
    // print_r($userRolesData);

    // echo "<br><br>";

    $incomeexpenses = $admin->getIncomeExpensesData();
    // print_r($incomeexpenses);

    // echo "<br><br>";

    $yearlyIncomeData = $admin->getYearlyIncomeData(); // Fetch the yearly income data for the current year
    // print_r($yearlyIncomeData);

    // echo "<br><br>";
    $expenseperhouseData = $admin->getExpensesPerApartmentData();
    // print_r($expenseperhouseData);


    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
    $page = "admindashboard";
?>

    <div class="container-fluid">
        <div class="row">
        <?php include 'includes/header.php'; ?>
            <div class="col main content">
                <div class="card-body bg-transparent">
                    <div class="row">
                        <div class="col-xl-6 py-md-2">
                            <div class="card h-100" style="width: 100%;">
                                <div class="card-header p-3" style="background-color: #F28543;">
                                    <p class="fs-4 fw-bolder text-center text-uppercase mb-0">Tenants Per House</p>
                                </div>
                                <div class="card-body mt-2 position-relative">
                                    <canvas id="myChart" style="min-height: 250px; max-height: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 py-md-2">
                            <div class="card h-100" style="width: 100%;">
                                <div class="card-header p-3" style="background-color: #F28543;">
                                    <p class="fs-4 fw-bolder text-center text-uppercase mb-0">Income Per Month</p>
                                </div>
                                <div class="card-body mt-2">
                                    <canvas id="incomeChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 py-md-2">
                            <div class="card h-100" style="width: 100%;">
                                <div class="card-header p-3" style="background-color: #F28543;">
                                    <p class="fs-4 fw-bolder text-center text-uppercase mb-0">Admin to User Ratio</p>
                                </div>
                                <div class="card-body mt-2 position-relative">
                                    <canvas id="roleChart" style="max-height: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 py-md-2">
                            <div class="card h-100" style="width: 100%;">
                                <div class="card-header p-3" style="background-color: #F28543;">
                                    <p class="fs-4 fw-bolder text-center text-uppercase mb-0">Profit and Losses</p>
                                </div>
                                <div class="card-body mt-2 position-relative">
                                    <!-- Dropdown to switch views -->
                                    <div class="mb-3">
                                        <select id="chartViewSelect" class="form-select w-auto mw-100">
                                            <option value="incomeExpenses">Show Income and Expenses</option>
                                            <option value="expensesPerApartment">Show Expenses per Apartment</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <select id="houseSelect" class="form-select w-auto mw-100" style="display: none;">
                                            <option value="">All Houses</option>
                                            <!-- Options will be dynamically populated by JavaScript -->
                                        </select>
                                    </div>
                                    <canvas id="incomeExpenseChart" style="max-height: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 py-md-2">
                            <div class="card h-100" style="width: 100%;">
                                <div class="card-header p-3" style="background-color: #F28543;">
                                    <p class="fs-4 fw-bolder text-center text-uppercase mb-0">Annual Revenue</p>
                                </div>
                                <div class="card-body mt-2">
                                    <p class="card-text">Yearly Income Chart</p>
                                    <canvas id="yearlyIncomeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
                             
                <p>Home</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var chartData = <?php echo $data; ?>;

        var labels = chartData.map(function(e) {
            return e.house_name;
        });
        var data = chartData.map(function(e) {
            return e.tenant_count;
        });

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tenant Count',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

    <script>
        var ctx = document.getElementById('incomeChart').getContext('2d');
        var chartData = <?php echo $incomedata; ?>;

        // Filter the data to include only approved payments
        var filteredData = chartData.filter(function(e) {
            return e.approval === 'true';
        });
        var labels = filteredData.map(function(e) {
            return e.month;
        });
        var data = filteredData.map(function(e) {
            return e.total_income;
        });

        var incomeChart = new Chart(ctx, {
            type: 'line', // You can change this to 'bar' if you prefer bar charts
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Monthly Income',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        var ctx = document.getElementById('roleChart').getContext('2d');
        var chartData = <?php echo $userRolesData; ?>;

        var labels = chartData.map(function(e) {
            return e.role === 'admin' ? 'Admin' : 'User';
        });
        var data = chartData.map(function(e) {
            return e.count;
        });
        var percentages = chartData.map(function(e) {
            return e.percentage + '%';
        });

        var roleChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Role Distribution',
                        data: data,
                        backgroundColor: [
                            'rgb(54, 162, 235)', // Blue for Admin
                            'rgb(255, 205, 86)'  // Red for User
                        ],
                        borderColor: [
                            'rgba(255, 255, 255)',
                            'rgba(255, 255, 255)'
                        ],
                        hoverOffset: 40,
                        borderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    datalabels: {
                        color: '#000000',
                        formatter: function(value, context) {
                            return context.chart.data.labels[context.dataIndex] + '\n' + percentages[context.dataIndex];
                        },
                        font: {
                            weight: 'bold',
                            size: 16
                        }
                    },
                    legend: {
                        position: 'top',
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>

    <script>
        // Initialize the chart with default data
        var ctx = document.getElementById('incomeExpenseChart').getContext('2d');

        var initialData = <?php echo ($incomeexpenses); ?>;
        var expensePerHouseData = <?php echo ($expenseperhouseData); ?>;

        // Filter out entries with null house_name
        expensePerHouseData = expensePerHouseData.filter(function(e) {
            return e.house_name !== null;
        });

        // Populate house names in the second dropdown
        var houseSelect = document.getElementById('houseSelect');
        var uniqueHouseNames = [...new Set(expensePerHouseData.map(e => e.house_name))];
        uniqueHouseNames.forEach(function(house) {
            var option = document.createElement('option');
            option.value = house;
            option.textContent = house;
            houseSelect.appendChild(option);
        });

        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: initialData.map(e => e.month),
                datasets: [
                    {
                        label: 'Income',
                        data: initialData.map(e => e.total_income),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: initialData.map(e => e.total_expenses),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        document.getElementById('chartViewSelect').addEventListener('change', function() {
            var selectedView = this.value;

            if (selectedView === 'expensesPerApartment') {
                // // Show the house select dropdown
                // houseSelect.style.display = 'block';

                // // Default view to show all houses
                // chart.data.labels = expensePerHouseData.map(e => e.house_name);
                // chart.data.datasets = [{
                //     label: 'Expenses per Apartment',
                //     data: expensePerHouseData.map(e => e.total_expenses),
                //     backgroundColor: 'rgba(255, 99, 132, 0.2)',
                //     borderColor: 'rgba(255, 99, 132, 1)',
                //     borderWidth: 1
                // }];


                // Show the house select dropdown
                houseSelect.style.display = 'block';

                // Get the selected house
                var selectedHouse = houseSelect.value;

                if (selectedHouse === '') {
                    // Show all houses if no specific house is selected
                    chart.data.labels = expensePerHouseData.map(e => e.house_name);
                    chart.data.datasets = [{
                        label: 'Expenses per Apartment',
                        data: expensePerHouseData.map(e => e.total_expenses),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }];
                } else {
                    // Filter data to show only the selected house
                    var filteredData = expensePerHouseData.filter(function(e) {
                        return e.house_name === selectedHouse;
                    });

                    chart.data.labels = filteredData.map(e => e.house_name);
                    chart.data.datasets = [{
                        label: 'Expenses per Apartment',
                        data: filteredData.map(e => e.total_expenses),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }];
                }
            } else {
                // Hide the house select dropdown
                houseSelect.style.display = 'none';

                // Switch back to income and expenses view
                chart.data.labels = initialData.map(e => e.month);
                chart.data.datasets = [
                    {
                        label: 'Income',
                        data: initialData.map(e => e.total_income),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: initialData.map(e => e.total_expenses),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ];
            }

            chart.update(); // Refresh the chart with new data
        });

        // Event listener for house-specific filtering
        houseSelect.addEventListener('change', function() {
            var selectedHouse = this.value;

            if (selectedHouse === '') {
                // Show all houses if no specific house is selected
                chart.data.labels = expensePerHouseData.map(e => e.house_name);
                chart.data.datasets[0].data = expensePerHouseData.map(e => e.total_expenses);
            } else {
                // Filter data to show only the selected house
                var filteredData = expensePerHouseData.filter(function(e) {
                    return e.house_name === selectedHouse;
                });

                chart.data.labels = filteredData.map(e => e.house_name);
                chart.data.datasets[0].data = filteredData.map(e => e.total_expenses);
            }

            chart.update(); // Refresh the chart with filtered data
        });

        // var ctx = document.getElementById('incomeExpenseChart').getContext('2d');
        // var chartData = <?php echo $incomeexpenses; ?>;

        // var labels = chartData.map(function(e) {
        //     return e.month;
        // });
        // var incomeData = chartData.map(function(e) {
        //     return e.total_income;
        // });
        // var expensesData = chartData.map(function(e) {
        //     return e.total_expenses;
        // });

        // var incomeExpenseChart = new Chart(ctx, {
        //     type: 'bar',
        //     data: {
        //         labels: labels,
        //         datasets: [
        //             {
        //                 label: 'Income',
        //                 data: incomeData,
        //                 backgroundColor: 'rgba(75, 192, 192, 0.2)',
        //                 borderColor: 'rgba(75, 192, 192, 1)',
        //                 borderWidth: 1
        //             },
        //             {
        //                 label: 'Expenses',
        //                 data: expensesData,
        //                 backgroundColor: 'rgba(255, 99, 132, 0.2)',
        //                 borderColor: 'rgba(255, 99, 132, 1)',
        //                 borderWidth: 1
        //             }
        //         ]
        //     },
        //     options: {
        //         scales: {
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        // });
    </script>
    <script>
        var ctx = document.getElementById('yearlyIncomeChart').getContext('2d');
        var chartData = <?php echo $yearlyIncomeData; ?>;

        var labels = chartData.map(function(e) {
            return e.year;
        });

        var data = chartData.map(function(e) {
            return e.total_income;
        });

        var yearlyIncomeChart = new Chart(ctx, {
            type: 'line', // Changed from 'bar' to 'line'
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Yearly Income',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2, // Made the border width a bit thicker for visibility
                        fill: false // Fill the area under the line
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1000 // Adjust the step size as needed
                        }
                    }
                }
            }
        });
    </script>




    <?php include 'includes/footer.php'; ?>
