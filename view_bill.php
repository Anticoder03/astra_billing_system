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
    <title>View Bill - <?php echo htmlspecialchars($bill['invoice_number']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Bill Details</h2>
        <div>
            <a href="print_bill.php?id=<?php echo $bill['id']; ?>" class="btn btn-success" target="_blank"><i class="fas fa-print"></i> Print</a>
        
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header"><strong>Invoice Info</strong></div>
        <div class="card-body row">
            <div class="col-md-6">
                <p><strong>Invoice Number:</strong> <?php echo htmlspecialchars($bill['invoice_number']); ?></p>
                <p><strong>Invoice Date:</strong> <?php echo htmlspecialchars($bill['invoice_date']); ?></p>
                <p><strong>Due Date:</strong> <?php echo htmlspecialchars($bill['due_date']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($bill['payment_method']); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($bill['created_at']); ?></p>
                <p><strong>Updated At:</strong> <?php echo htmlspecialchars($bill['updated_at']); ?></p>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Company Details</strong></div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($bill['company_name']); ?></p>
                    <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($bill['company_address'])); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($bill['company_phone']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($bill['company_email']); ?></p>
                    <p><strong>GSTIN:</strong> <?php echo htmlspecialchars($bill['company_gstin']); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Customer Details</strong></div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($bill['customer_name']); ?></p>
                    <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($bill['customer_address'])); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($bill['customer_phone']); ?></p>
                    <p><strong>GSTIN:</strong> <?php echo htmlspecialchars($bill['customer_gstin']); ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header"><strong>Products</strong></div>
        <div class="card-body">
            <div class="table-responsive">
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
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header"><strong>Totals</strong></div>
        <div class="card-body">
            <p><strong>Sub Total:</strong> ₹<?php echo number_format($bill['sub_total'], 2); ?></p>
            <p><strong>Total GST:</strong> ₹<?php echo number_format($bill['total_gst'], 2); ?></p>
            <p><strong>Grand Total:</strong> <span class="fw-bold">₹<?php echo number_format($bill['grand_total'], 2); ?></span></p>
        </div>
    </div>
</div>
</body>
</html> 