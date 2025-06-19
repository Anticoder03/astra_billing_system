-- Create database
CREATE DATABASE IF NOT EXISTS astra_billing_system;
USE astra_billing_system;

-- Drop table if exists
DROP TABLE IF EXISTS bill_details;

-- Create bill_details table
CREATE TABLE bill_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    invoice_date DATE NOT NULL,
    due_date DATE,
    
    -- Company Details
    company_name VARCHAR(255) NOT NULL,
    company_address TEXT NOT NULL,
    company_phone VARCHAR(20) NOT NULL,
    company_email VARCHAR(255) NOT NULL,
    company_gstin VARCHAR(50),
    
    -- Customer Details
    customer_name VARCHAR(255) NOT NULL,
    customer_address TEXT NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_gstin VARCHAR(50),
    
    -- Payment Details
    payment_method VARCHAR(50) NOT NULL,
    
    -- Product Details (stored as JSON)
    products JSON NOT NULL,
    
    -- Totals
    sub_total DECIMAL(10,2) NOT NULL,
    total_gst DECIMAL(10,2) NOT NULL,
    grand_total DECIMAL(10,2) NOT NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes
    INDEX idx_invoice_number (invoice_number),
    INDEX idx_invoice_date (invoice_date),
    INDEX idx_company_name (company_name),
    INDEX idx_customer_name (customer_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO bill_details (
    invoice_number,
    invoice_date,
    due_date,
    company_name,
    company_address,
    company_phone,
    company_email,
    company_gstin,
    customer_name,
    customer_address,
    customer_phone,
    customer_gstin,
    payment_method,
    products,
    sub_total,
    total_gst,
    grand_total
) VALUES (
    'INV-2024-001',
    '2024-01-20',
    '2024-02-20',
    'Tech Solutions Ltd.',
    '123 Tech Park, Silicon Valley, CA 94025',
    '1234567890',
    'info@techsolutions.com',
    'GSTIN123456789',
    'John Doe',
    '789 Residential Area, CA 94025',
    '5551234567',
    'CUST123456',
    'Card',
    JSON_ARRAY(
        JSON_OBJECT(
            'product_id', 'PROD001',
            'name', 'Laptop',
            'price', 500.00,
            'units', 1,
            'gst_percentage', 18.00,
            'gst_amount', 90.00,
            'total_amount', 590.00
        ),
        JSON_OBJECT(
            'product_id', 'PROD002',
            'name', 'Mouse',
            'price', 50.00,
            'units', 2,
            'gst_percentage', 18.00,
            'gst_amount', 18.00,
            'total_amount', 118.00
        )
    ),
    550.00,
    108.00,
    658.00
);

INSERT INTO bill_details (
    invoice_number,
    invoice_date,
    due_date,
    company_name,
    company_address,
    company_phone,
    company_email,
    company_gstin,
    customer_name,
    customer_address,
    customer_phone,
    customer_gstin,
    payment_method,
    products,
    sub_total,
    total_gst,
    grand_total
) VALUES
-- Existing record
('INV-2024-001', '2024-01-20', '2024-02-20', 'Tech Solutions Ltd.', '123 Tech Park, Silicon Valley, CA 94025', '1234567890', 'info@techsolutions.com', 'GSTIN123456789', 'John Doe', '789 Residential Area, CA 94025', '5551234567', 'CUST123456', 'Card', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD001', 'name', 'Laptop', 'price', 500.00, 'units', 1, 'gst_percentage', 18.00, 'gst_amount', 90.00, 'total_amount', 590.00),
    JSON_OBJECT('product_id', 'PROD002', 'name', 'Mouse', 'price', 50.00, 'units', 2, 'gst_percentage', 18.00, 'gst_amount', 18.00, 'total_amount', 118.00)
), 550.00, 108.00, 658.00),
-- 10 new records
('INV-2024-002', '2024-02-10', '2024-03-10', 'Astra Store', '12/3 Gujarat, India', '1254789652', 'astra123@gmail.com', '24BLSPP34ED', 'Alice Smith', '22 Main St, Mumbai', '9001234567', 'CUST654321', 'UPI', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD003', 'name', 'Keyboard', 'price', 300.00, 'units', 1, 'gst_percentage', 18.00, 'gst_amount', 54.00, 'total_amount', 354.00)
), 300.00, 54.00, 354.00),
('INV-2024-003', '2024-03-05', '2024-04-05', 'Astra Store', '12/3 Gujarat, India', '1254789652', 'astra123@gmail.com', '24BLSPP34ED', 'Bob Lee', '45 Park Ave, Delhi', '9012345678', 'CUST789012', 'Cash', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD004', 'name', 'Monitor', 'price', 700.00, 'units', 1, 'gst_percentage', 18.00, 'gst_amount', 126.00, 'total_amount', 826.00)
), 700.00, 126.00, 826.00),
('INV-2024-004', '2024-03-15', '2024-04-15', 'Astra Store', '12/3 Gujarat, India', '1254789652', 'astra123@gmail.com', '24BLSPP34ED', 'Charlie Brown', '88 Lake Rd, Pune', '9023456789', 'CUST345678', 'Card', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD005', 'name', 'Printer', 'price', 1200.00, 'units', 1, 'gst_percentage', 18.00, 'gst_amount', 216.00, 'total_amount', 1416.00)
), 1200.00, 216.00, 1416.00),
('INV-2024-005', '2024-04-01', '2024-05-01', 'Astra Store', '12/3 Gujarat, India', '1254789652', 'astra123@gmail.com', '24BLSPP34ED', 'Daisy Ridley', '77 Hill St, Chennai', '9034567890', 'CUST567890', 'Bank Transfer', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD006', 'name', 'Desk Chair', 'price', 1500.00, 'units', 2, 'gst_percentage', 18.00, 'gst_amount', 540.00, 'total_amount', 3540.00)
), 3000.00, 540.00, 3540.00),
('INV-2024-006', '2024-04-20', '2024-05-20', 'Astra Store', '12/3 Gujarat, India', '1254789652', 'astra123@gmail.com', '24BLSPP34ED', 'Eve Adams', '99 River Rd, Kolkata', '9045678901', 'CUST890123', 'UPI', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD007', 'name', 'Webcam', 'price', 800.00, 'units', 1, 'gst_percentage', 18.00, 'gst_amount', 144.00, 'total_amount', 944.00)
), 800.00, 144.00, 944.00),
('INV-2024-007', '2024-05-10', '2024-06-10', 'Astra Store', '12/3 Gujarat, India', '1254789652', 'astra123@gmail.com', '24BLSPP34ED', 'Frank Green', '11 Ocean Dr, Goa', '9056789012', 'CUST234567', 'Cash', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD008', 'name', 'Tablet', 'price', 2000.00, 'units', 1, 'gst_percentage', 18.00, 'gst_amount', 360.00, 'total_amount', 2360.00)
), 2000.00, 360.00, 2360.00),
('INV-2024-008', '2024-05-25', '2024-06-25', 'Astra Store', '12/3 Gujarat, India', '1254789652', 'astra123@gmail.com', '24BLSPP34ED', 'Grace Hopper', '33 Tech Blvd, Bengaluru', '9067890123', 'CUST345679', 'Card', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD009', 'name', 'Smartphone', 'price', 1800.00, 'units', 1, 'gst_percentage', 18.00, 'gst_amount', 324.00, 'total_amount', 2124.00)
), 1800.00, 324.00, 2124.00),
('INV-2024-009', '2024-06-10', '2024-07-10', 'Astra Store', '12/3 Gujarat, India', '1254789652', 'astra123@gmail.com', '24BLSPP34ED', 'Henry Ford', '55 Auto Ln, Surat', '9078901234', 'CUST456789', 'UPI', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD010', 'name', 'Scanner', 'price', 950.00, 'units', 1, 'gst_percentage', 18.00, 'gst_amount', 171.00, 'total_amount', 1121.00)
), 950.00, 171.00, 1121.00),
('INV-2024-010', '2024-06-20', '2024-07-20', 'Astra Store', '12/3 Gujarat, India', '1254789652', 'astra123@gmail.com', '24BLSPP34ED', 'Ivy Lane', '66 Garden St, Jaipur', '9089012345', 'CUST567891', 'Bank Transfer', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD011', 'name', 'Router', 'price', 600.00, 'units', 2, 'gst_percentage', 18.00, 'gst_amount', 216.00, 'total_amount', 1416.00)
), 1200.00, 216.00, 1416.00),
('INV-2024-011', '2024-07-01', '2024-08-01', 'Astra Store', '12/3 Gujarat, India', '1254789652', 'astra123@gmail.com', '24BLSPP34ED', 'Jack Black', '77 Cinema Rd, Hyderabad', '9090123456', 'CUST678912', 'Card', JSON_ARRAY(
    JSON_OBJECT('product_id', 'PROD012', 'name', 'Projector', 'price', 2500.00, 'units', 1, 'gst_percentage', 18.00, 'gst_amount', 450.00, 'total_amount', 2950.00)
), 2500.00, 450.00, 2950.00); 