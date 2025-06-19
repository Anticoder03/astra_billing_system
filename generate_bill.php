<?php
// Database connection
$host = 'localhost';
$dbname = 'astra_billing_system';
$username = 'root';
$password = '';

// Company constants
$company_name = 'Astra Store';
$company_phone = '1254789652';
$company_address = '12/3 Gujarat, India';
$company_email = 'astra123@gmail.com';
$company_gstin = '24BLSPP34ED';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $invoice_number = $_POST['invoiceNumber'] ?? '';
    $invoice_date = $_POST['invoiceDate'] ?? '';
    $due_date = $_POST['dueDate'] ?? null;
    $customer_name = $_POST['customerName'] ?? '';
    $customer_address = $_POST['customerAddress'] ?? '';
    $customer_phone = $_POST['customerPhone'] ?? '';
    $customer_gstin = $_POST['customerGSTIN'] ?? '';
    $payment_method = $_POST['paymentMethod'] ?? '';
    $products = $_POST['products'] ?? [];

    // Calculate totals
    $sub_total = 0;
    $total_gst = 0;
    $grand_total = 0;
    $products_json = [];
    foreach ($products as $product) {
        $productTotal = $product['price'] * $product['units'];
        $gstAmount = ($productTotal * $product['gst']) / 100;
        $totalAmount = $productTotal + $gstAmount;
        $sub_total += $productTotal;
        $total_gst += $gstAmount;
        $products_json[] = [
            'product_id' => $product['id'],
            'name' => $product['name'],
            'price' => (float)$product['price'],
            'units' => (int)$product['units'],
            'gst_percentage' => (float)$product['gst'],
            'gst_amount' => round($gstAmount, 2),
            'total_amount' => round($totalAmount, 2)
        ];
    }
    $grand_total = $sub_total + $total_gst;

    // Insert into bill_details
    $stmt = $pdo->prepare("INSERT INTO bill_details (
        invoice_number, invoice_date, due_date,
        company_name, company_address, company_phone, company_email, company_gstin,
        customer_name, customer_address, customer_phone, customer_gstin,
        payment_method, products, sub_total, total_gst, grand_total
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $invoice_number, $invoice_date, $due_date,
        $company_name, $company_address, $company_phone, $company_email, $company_gstin,
        $customer_name, $customer_address, $customer_phone, $customer_gstin,
        $payment_method, json_encode($products_json), $sub_total, $total_gst, $grand_total
    ]);
    $bill_id = $pdo->lastInsertId();

    // Redirect to view_bill.php
    header("Location: view_bill.php?id=" . $bill_id);
    exit;
} else {
    die('Invalid request.');
}
