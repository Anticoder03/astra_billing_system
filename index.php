<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Software</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .product-row {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">Astra Store</h2>
                <div style="font-size: 1rem;">
                    <div><strong>Phone:</strong> 1254789652</div>
                    <div><strong>Address:</strong> 12/3 Gujarat, India</div>
                    <div><strong>Email:</strong> astra123@gmail.com</div>
                    <div><strong>GST No:</strong> 24BLSPP34ED</div>
                </div>
            </div>
          
        </div>
        <form action="generate_bill.php" method="POST" id="billingForm">
            <!-- Customer Details Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Customer Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerName" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customerName" name="customerName" required>
                            </div>
                            <div class="mb-3">
                                <label for="customerAddress" class="form-label">Billing Address</label>
                                <textarea class="form-control" id="customerAddress" name="customerAddress" rows="2" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerPhone" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="customerPhone" name="customerPhone" required>
                            </div>
                            <div class="mb-3">
                                <label for="customerGSTIN" class="form-label">Customer GSTIN (if applicable)</label>
                                <input type="text" class="form-control" id="customerGSTIN" name="customerGSTIN">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Details Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Invoice Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="invoiceNumber" class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" id="invoiceNumber" name="invoiceNumber" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="invoiceDate" class="form-label">Invoice Date</label>
                                <input type="date" class="form-control" id="invoiceDate" name="invoiceDate" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="dueDate" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="dueDate" name="dueDate">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select class="form-control" id="paymentMethod" name="paymentMethod" required>
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Products</h4>
                </div>
                <div class="card-body">
                    <div id="productsContainer">
                        <div class="product-row">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">Product ID</label>
                                        <input type="text" class="form-control product-id" name="products[0][id]" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" class="form-control product-name" name="products[0][name]" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control product-price" name="products[0][price]" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">Units</label>
                                        <input type="number" class="form-control product-units" name="products[0][units]" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">GST %</label>
                                        <input type="number" class="form-control product-gst" name="products[0][gst]" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="addProduct">
                        <i class="fas fa-plus"></i> Add More Products
                    </button>
                </div>
            </div>

            <div class="text-center mb-4">
                <button type="submit" class="btn btn-success btn-lg">Generate Bill</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let productCount = 1;

            $('#addProduct').click(function() {
                const newProduct = `
                    <div class="product-row">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Product ID</label>
                                    <input type="text" class="form-control product-id" name="products[${productCount}][id]" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control product-name" name="products[${productCount}][name]" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control product-price" name="products[${productCount}][price]" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Units</label>
                                    <input type="number" class="form-control product-units" name="products[${productCount}][units]" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">GST %</label>
                                    <input type="number" class="form-control product-gst" name="products[${productCount}][gst]" value="0">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger remove-product">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#productsContainer').append(newProduct);
                productCount++;
            });

            $(document).on('click', '.remove-product', function() {
                $(this).closest('.product-row').remove();
            });
        });
    </script>
</body>
</html> 