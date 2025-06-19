<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

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

try {
    $stmt = $pdo->query("SELECT id, invoice_number, invoice_date, company_name, customer_name, payment_method, grand_total FROM bill_details ORDER BY invoice_date DESC, id DESC");
    $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching bills: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bills - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>All Bills</h2>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Admin Panel
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice Number</th>
                        <th>Date</th>
                        <th>Company</th>
                        <th>Customer</th>
                        <th>Payment Method</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bills)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No bills found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($bills as $bill): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bill['invoice_number']); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($bill['invoice_date'])); ?></td>
                            <td><?php echo htmlspecialchars($bill['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($bill['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($bill['payment_method']); ?></td>
                            <td>₹<?php echo number_format($bill['grand_total'], 2); ?></td>
                            <td>
                                <a href="../view_bill.php?id=<?php echo $bill['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="../print_bill.php?id=<?php echo $bill['id']; ?>" class="btn btn-sm btn-success" target="_blank">
                                    <i class="fas fa-print"></i> Print
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 