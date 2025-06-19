<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Astra Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: #fff;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #495057;
            color: #fff;
        }
        .sidebar .nav-link i {
            margin-right: 8px;
        }
        .content {
            padding: 2rem;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar py-4">
            <div class="text-center mb-4">
                <h4>Astra Store</h4>
                <hr class="bg-secondary">
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link active" href="#" id="viewBillsMenu">
                        <i class="fas fa-file-invoice"></i> View All Bills
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link" href="#" id="showChartMenu">
                        <i class="fas fa-chart-bar"></i> Show Chart
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link" href="#" id="analysisMenu">
                        <i class="fas fa-chart-pie"></i> Analysis
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-danger" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>
        <main class="col-md-10 ms-sm-auto content" id="mainContent">
            <!-- Content will be loaded here via AJAX -->
        </main>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function loadSection(url, menuId) {
    $("#mainContent").html('<div class="text-center my-5"><div class="spinner-border text-primary" role="status"></div></div>');
    $.get(url, function(data) {
        $("#mainContent").html(data);
        $(".nav-link").removeClass("active");
        $(menuId).addClass("active");
    });
}

// Chart.js chart logic for AJAX loads
window.initChartJsChart = function() {
    if (!window.chartLabels || !window.chartData) return;
    const chartColors = [
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 99, 132, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)',
        'rgba(255, 159, 64, 0.7)',
        'rgba(199, 199, 199, 0.7)'
    ];
    function getDataset(type) {
        if (type === 'pie') {
            return [{
                label: 'Total Billed (₹)',
                data: window.chartData,
                backgroundColor: chartColors,
                borderColor: chartColors.map(c => c.replace('0.7', '1')),
                borderWidth: 1
            }];
        } else {
            return [{
                label: 'Total Billed (₹)',
                data: window.chartData,
                backgroundColor: chartColors[0],
                borderColor: chartColors[0].replace('0.7', '1'),
                borderWidth: 1,
                fill: false
            }];
        }
    }
    function getOptions(type) {
        let options = {
            responsive: true,
            plugins: {
                legend: { display: type === 'pie' },
                title: {
                    display: true,
                    text: 'Total Billing Amount Per Month (Last 12 Months)'
                }
            }
        };
        if (type !== 'pie') {
            options.scales = {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value;
                        }
                    }
                }
            };
        }
        return options;
    }
    // Destroy previous chart instance if exists
    if (window.billsChartInstance) {
        window.billsChartInstance.destroy();
    }
    let chartType = 'bar';
    const ctx = document.getElementById('billsChart').getContext('2d');
    window.billsChartInstance = new Chart(ctx, {
        type: chartType,
        data: {
            labels: window.chartLabels,
            datasets: getDataset(chartType)
        },
        options: getOptions(chartType)
    });
    // Chart type switcher
    document.getElementById('chartType').addEventListener('change', function() {
        chartType = this.value;
        window.billsChartInstance.destroy();
        window.billsChartInstance = new Chart(ctx, {
            type: chartType,
            data: {
                labels: window.chartLabels,
                datasets: getDataset(chartType)
            },
            options: getOptions(chartType)
        });
    });
};

// Histogram chart logic for AJAX loads
window.initHistogramChart = function() {
    if (!window.histoLabels || !window.histoData) return;
    // Destroy previous histogram chart instance if exists
    if (window.histogramChartInstance) {
        window.histogramChartInstance.destroy();
    }
    const ctxHisto = document.getElementById('histogramChart').getContext('2d');
    window.histogramChartInstance = new Chart(ctxHisto, {
        type: 'bar',
        data: {
            labels: window.histoLabels,
            datasets: [{
                label: 'Number of Bills',
                data: window.histoData,
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Histogram: Distribution of Bill Amounts'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Bills'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bill Amount Range'
                    }
                }
            }
        }
    });
};

$(function() {
    // Default: load bills table
    loadSection('partials/view_bills_content.php', '#viewBillsMenu');

    $("#viewBillsMenu").click(function(e) {
        e.preventDefault();
        loadSection('partials/view_bills_content.php', '#viewBillsMenu');
    });
    $("#showChartMenu").click(function(e) {
        e.preventDefault();
        loadSection('partials/show_chart_content.php', '#showChartMenu');
    });
    $("#analysisMenu").click(function(e) {
        e.preventDefault();
        loadSection('partials/analysis_content.php', '#analysisMenu');
    });
});
</script>
</body>
</html> 