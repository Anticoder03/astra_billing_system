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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid bill ID.');
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM bill_details WHERE id = ?");
$stmt->execute([$id]);
$bill = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bill) {
    die('Bill not found.');
}

$products = json_decode($bill['products'], true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Bill - <?php echo htmlspecialchars($bill['invoice_number']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
            body { background: #fff !important; }
            .container { max-width: 100% !important; }
        }
        .bill-header { text-align: center; margin-bottom: 20px; }
        .bill-section { margin-bottom: 20px; }
        .table th { background-color: #f8f9fa; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="bill-header">
        <h2><?php echo htmlspecialchars($bill['company_name']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($bill['company_address'])); ?></p>
        <p>Phone: <?php echo htmlspecialchars($bill['company_phone']); ?> | Email: <?php echo htmlspecialchars($bill['company_email']); ?></p>
        <?php if (!empty($bill['company_gstin'])): ?>
            <p>GSTIN: <?php echo htmlspecialchars($bill['company_gstin']); ?></p>
        <?php endif; ?>
    </div>
    <div class="row bill-section">
        <div class="col-md-6">
            <h5>Bill To:</h5>
            <p>
                <strong><?php echo htmlspecialchars($bill['customer_name']); ?></strong><br>
                <?php echo nl2br(htmlspecialchars($bill['customer_address'])); ?><br>
                Phone: <?php echo htmlspecialchars($bill['customer_phone']); ?><br>
                <?php if (!empty($bill['customer_gstin'])): ?>
                    GSTIN: <?php echo htmlspecialchars($bill['customer_gstin']); ?><br>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-md-6 text-end">
            <h5>Invoice Details:</h5>
            <p>
                Invoice Number: <?php echo htmlspecialchars($bill['invoice_number']); ?><br>
                Date: <?php echo htmlspecialchars($bill['invoice_date']); ?><br>
                <?php if (!empty($bill['due_date'])): ?>
                    Due Date: <?php echo htmlspecialchars($bill['due_date']); ?><br>
                <?php endif; ?>
                Payment Method: <?php echo htmlspecialchars($bill['payment_method']); ?>
            </p>
        </div>
    </div>
    <div class="bill-section">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Units</th>
                    <th>GST %</th>
                    <th>GST Amount</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>₹<?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($product['units']); ?></td>
                    <td><?php echo htmlspecialchars($product['gst_percentage']); ?>%</td>
                    <td>₹<?php echo number_format($product['gst_amount'], 2); ?></td>
                    <td>₹<?php echo number_format($product['total_amount'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="bill-section">
        <div class="row">
            <div class="col-md-8">
                <h5>Terms & Conditions:</h5>
                <p>1. Goods once sold will not be taken back or exchanged.</p>
                <p>2. All disputes are subject to local jurisdiction.</p>
            </div>
            <div class="col-md-4 text-end">
                <h5>For <?php echo htmlspecialchars($bill['company_name']); ?></h5>
                <br><br>
                <p>Authorized Signatory</p>
            </div>
        </div>
    </div>
    <div class="bill-section">
        <table class="table table-bordered w-50 ms-auto">
            <tr>
                <th>Sub Total</th>
                <td>₹<?php echo number_format($bill['sub_total'], 2); ?></td>
            </tr>
            <tr>
                <th>Total GST</th>
                <td>₹<?php echo number_format($bill['total_gst'], 2); ?></td>
            </tr>
            <tr>
                <th>Grand Total</th>
                <td><strong>₹<?php echo number_format($bill['grand_total'], 2); ?></strong></td>
            </tr>
        </table>
    </div>
    <div class="text-center mt-4 mb-4 no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Bill
        </button>
        <a href="view_bill.php?id=<?php echo $bill['id']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Bill
        </a>
    </div>
</div>
</body>
</html> 