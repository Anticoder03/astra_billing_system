<?php
// Database connection
$host = 'localhost';
$dbname = 'astra_billing_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get total bill amounts per month for the last 12 months
$sql = "SELECT DATE_FORMAT(invoice_date, '%Y-%m') AS month, SUM(grand_total) AS total
        FROM bill_details
        WHERE invoice_date >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 11 MONTH), '%Y-%m-01')
        GROUP BY month
        ORDER BY month ASC";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$data = [];
foreach ($rows as $row) {
    $labels[] = $row['month'];
    $data[] = (float)$row['total'];
}
?>
<div class="d-flex justify-content-between align-items-center mb-2">
    <h2 class="text-center flex-grow-1 mb-0">Monthly Billing Chart</h2>
    <button class="btn btn-outline-primary ms-3" id="refreshChartBtn" onclick="refreshChart()" title="Refresh Chart"><i class="fas fa-sync-alt"></i> Refresh</button>
</div>
<div class="d-flex justify-content-center mb-3">
    <label for="chartType" class="form-label me-2 mt-2">Chart Type:</label>
    <select id="chartType" class="form-select w-auto">
        <option value="bar">Bar</option>
        <option value="line">Line</option>
        <option value="pie">Pie</option>
    </select>
</div>
<div class="d-flex justify-content-center">
    <div style="max-width:700px;width:100%;">
        <canvas id="billsChart" height="100"></canvas>
    </div>
</div>
<script>
function refreshChart() {
    if (window.loadSection) {
        window.loadSection('partials/show_chart_content.php', '#showChartMenu');
    } else {
        location.reload();
    }
}

window.chartLabels = <?php echo json_encode($labels); ?>;
window.chartData = <?php echo json_encode($data); ?>;
if (typeof initChartJsChart === 'function') {
    initChartJsChart();
}
</script>
