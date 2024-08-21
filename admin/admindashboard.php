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
    print_r($data);

    echo "<br><br>";
    
    $incomedata = $admin->getMonthlyIncome();
    print_r($incomedata);

    echo "<br><br>";

    $userRolesData = $admin->getUserRolePercentage();
    print_r($userRolesData);

    echo "<br><br>";

    $incomeexpenses = $admin->getIncomeExpensesData();
    print_r($incomeexpenses);

    echo "<br><br>";

    $yearlyIncomeData = $admin->getYearlyIncomeData(); // Fetch the yearly income data for the current year
    print_r($yearlyIncomeData);

    echo "<br><br>";
    $expenseperhouseData = $admin->getExpensesPerApartmentData();
    print_r($expenseperhouseData);


    // Set the title for this page
    $pageTitle = "RentTrackPro"; // Change this according to the current page
    $page = "admindashboard";
?>

    <div class="container-fluid">
        <div class="row">
        <?php include 'includes/header.php'; ?>
            <div class="col main content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 py-md-2">
                            <div class="card" style="width: 100%;">
                                <div class="card-body">
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <canvas id="myChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 py-md-2">
                            <div class="card" style="width: 100%;">
                                <div class="card-body">
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <canvas id="incomeChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 py-md-2">
                            <div class="card" style="width: 100%;">
                                <div class="card-body">
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <canvas id="roleChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 py-md-2">
                            <div class="card" style="width: 100%;">
                                <div class="card-body">
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <button id="toggleView" class="btn btn-primary">Show Expenses per Apartment</button>
                                    <canvas id="incomeExpenseChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 py-md-2">
                            <div class="card" style="width: 100%;">
                                <div class="card-body">
                                    <p class="card-text">Yearly Income Chart</p>
                                    <canvas id="yearlyIncomeChart" width="400" height="200"></canvas>
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

        var labels = chartData.map(function(e) {
            return e.month;
        });
        var data = chartData.map(function(e) {
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
                            'rgba(54, 162, 235, 0.2)', // Blue for Admin
                            'rgba(255, 99, 132, 0.2)'  // Red for User
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
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

        document.getElementById('toggleView').addEventListener('click', function() {
            var button = this;
            var isExpensesView = button.textContent.includes('Expenses per Apartment');
            
            if (isExpensesView) {
                // Toggle to expenses per apartment view
                chart.data.labels = expensePerHouseData.map(e => e.house_name);
                chart.data.datasets = [{
                    label: 'Expenses per Apartment',
                    data: expensePerHouseData.map(e => e.total_expenses),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }];
                chart.update(); // Refresh the chart
                button.textContent = 'Show Income and Expenses'; // Update button text
            } else {
                // Toggle back to income and expenses view
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
                chart.update(); // Refresh the chart
                button.textContent = 'Show Expenses per Apartment'; // Update button text
            }
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
