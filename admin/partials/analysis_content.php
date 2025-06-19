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

// Total bills
$totalBills = $pdo->query("SELECT COUNT(*) FROM bill_details")->fetchColumn();
// Total revenue
$totalRevenue = $pdo->query("SELECT SUM(grand_total) FROM bill_details")->fetchColumn();
// Top customer by revenue
$topCustomerRow = $pdo->query("SELECT customer_name, SUM(grand_total) as total FROM bill_details GROUP BY customer_name ORDER BY total DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$topCustomer = $topCustomerRow ? $topCustomerRow['customer_name'] : '-';
$topCustomerTotal = $topCustomerRow ? $topCustomerRow['total'] : 0;
// Top product by revenue
$topProduct = '-';
$topProductTotal = 0;
$products = $pdo->query("SELECT products FROM bill_details")->fetchAll(PDO::FETCH_COLUMN);
$productTotals = [];
foreach ($products as $json) {
    $items = json_decode($json, true);
    if (is_array($items)) {
        foreach ($items as $item) {
            $name = $item['name'];
            $total = isset($item['total_amount'])
                ? $item['total_amount']
                : ((isset($item['price']) && isset($item['units']) && isset($item['gst_amount']))
                    ? ($item['price'] * $item['units'] + $item['gst_amount'])
                    : 0);
            if (!isset($productTotals[$name])) {
                $productTotals[$name] = 0;
            }
            $productTotals[$name] += $total;
        }
    }
}
if ($productTotals) {
    arsort($productTotals);
    $topProduct = key($productTotals);
    $topProductTotal = current($productTotals);
}

// Histogram data: get all grand_total values
$allTotals = $pdo->query("SELECT grand_total FROM bill_details WHERE grand_total IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
$allTotals = array_map('floatval', $allTotals);

// Calculate bins for histogram (e.g., 10 bins)
$binCount = 10;
$min = $allTotals ? min($allTotals) : 0;
$max = $allTotals ? max($allTotals) : 0;
$binWidth = ($max > $min) ? ceil(($max - $min) / $binCount) : 1;
$bins = array_fill(0, $binCount, 0);
$labels = [];
for ($i = 0; $i < $binCount; $i++) {
    $start = $min + $i * $binWidth;
    $end = $start + $binWidth - 1;
    $labels[] = '₹' . number_format($start) . ' - ₹' . number_format($end);
}
foreach ($allTotals as $value) {
    if ($binWidth == 0) {
        $bin = 0;
    } else {
        $bin = (int) floor(($value - $min) / $binWidth);
    }
    if ($bin >= $binCount) $bin = $binCount - 1;
    if ($bin < 0) $bin = 0;
    $bins[$bin]++;
}
?>
<h2 class="text-center mb-4">Business Analysis</h2>
<div class="row justify-content-center mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Total Bills</h5>
                <p class="display-6 fw-bold text-primary"><?php echo $totalBills; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <p class="display-6 fw-bold text-success">₹<?php echo number_format($totalRevenue, 2); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Top Customer</h5>
                <p class="fw-bold mb-1"><?php echo htmlspecialchars($topCustomer); ?></p>
                <span class="text-muted">₹<?php echo number_format($topCustomerTotal, 2); ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Top Product</h5>
                <p class="fw-bold mb-1"><?php echo htmlspecialchars($topProduct); ?></p>
                <span class="text-muted">₹<?php echo number_format($topProductTotal, 2); ?></span>
            </div>
        </div>
    </div>
</div>
<h4 class="text-center mt-5">Bill Amount Distribution (Histogram)</h4>
<div class="d-flex justify-content-center">
    <div style="max-width:700px;width:100%;">
        <canvas id="histogramChart" height="100"></canvas>
    </div>
</div>
<script>
window.histoLabels = <?php echo json_encode($labels); ?>;
window.histoData = <?php echo json_encode($bins); ?>;
if (typeof initHistogramChart === 'function') {
    initHistogramChart();
}
</script> 