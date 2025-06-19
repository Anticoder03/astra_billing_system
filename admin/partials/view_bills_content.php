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

try {
    $stmt = $pdo->query("SELECT id, invoice_number, invoice_date, company_name, customer_name, payment_method, grand_total FROM bill_details ORDER BY invoice_date DESC, id DESC");
    $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching bills: " . $e->getMessage());
}
?>
<h2>All Bills</h2>
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