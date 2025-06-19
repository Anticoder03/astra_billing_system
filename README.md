# Astra Billing System

## Table of Contents
- [Introduction](#introduction)
- [Software Tools Used](#software-tools-used)
- [Objectives](#objectives)
- [Features](#features)
- [System Requirements](#system-requirements)
- [Diagram](#diagram)
- [Installation](#installation)
- [Usage](#usage)
- [Security](#security)
- [License](#license)
- [Credits](#credits)
- [Contact](#contact)
- [Output](#output)
- [Conclusion](#conclusion)
- [References](#references)

---

## Introduction
Astra Billing System is a  billing and invoicing solution designed for small businesses and shops. It allows users to generate bills, manage products, and provides an admin dashboard for analytics and reporting.

## Software Tools Used
- PHP (Core backend logic)
- MySQL (Database)
- Bootstrap 5 (Frontend framework)
- Chart.js (Data visualization)
- jQuery (AJAX and DOM manipulation)
- FontAwesome (Icons)
- XAMPP (Recommended local server)

## Objectives
- Simplify billing and invoice generation for small businesses
- Provide a user-friendly interface for both users and admins
- Enable data-driven business analysis and reporting
- Ensure data security and restricted access to sensitive features

## Features
- User-side bill generation (with customer and product details)
- Admin login and dashboard
- View all bills (admin only)
- Monthly billing chart (bar, line, pie)
- Business analysis (total bills, revenue, top customer/product)
- Bill amount distribution histogram
- AJAX-powered admin panel (no full page reloads)
- Responsive and modern UI
- Secure session-based authentication for admin

## System Requirements
- PHP 7.4+
- MySQL 5.7+
- Web browser (Chrome, Firefox, Edge, etc.)
- XAMPP, WAMP, or LAMP stack recommended for local development

## Diagram
```
+-------------------+
|   User Interface  |
+-------------------+
         |
         v
+-------------------+
|   PHP Backend     |
+-------------------+
         |
         v
+-------------------+
|   MySQL Database  |
+-------------------+
         |
         v
+-------------------+
|   Admin Dashboard |
+-------------------+
```

## Screenshorts

## User Side:


## Admin Side: 



## Installation
1. Clone or download this repository.
2. Place the `HMS` folder in your web server's root directory (e.g., `htdocs` for XAMPP).
3. Import the `bill_details.sql` file into your MySQL server.
4. Update database credentials in `config.php` and admin scripts if needed.
5. Start your web server and navigate to `http://localhost/HMS/index.php` for user or `http://localhost/HMS/admin/login.php` for admin.

## Usage
- **User:** Fill in customer and product details to generate a bill. Print or save the bill as needed.
- **Admin:** Log in with username `admin` and password `admin123` to access the dashboard, view all bills, see charts, and analyze business data.

## Security
- Admin features are protected by session-based authentication.
- All sensitive actions (viewing all bills, analytics) are restricted to admin users.
- Data is validated and sanitized before database insertion.
- Do not share your admin credentials.

## License
**This software is proprietary. No one is allowed to use, copy, modify, or distribute any part of this project without explicit written permission from the developer.**

To request permission, contact the developer at: [ap5381545@gail.com](mailto:ap5381545@gail.com)

## Credits
- Developed by: Anticoder03
- Chart.js (https://www.chartjs.org/)
- tailwindcss (https://https://tailwindcss.com/)
- FontAwesome (https://fontawesome.com/)

## Contact
- Email: [ap5381545@gail.com](mailto:ap5381545@gail.com)

## Output
- Bills are generated as printable HTML pages.
- Admin dashboard provides interactive charts and business analytics.
- All data is stored in the MySQL database for future reference.

## Conclusion
Astra Billing System is a robust, user-friendly, and secure solution for small business billing and analytics. For any queries, feature requests, or permission to use, please contact the developer.

## References
- [Chart.js Documentation](https://www.chartjs.org/docs/)
- [Bootstrap Documentation](https://getbootstrap.com/docs/5.0/getting-started/introduction/)
- [PHP Manual](https://www.php.net/manual/en/)
- [MySQL Documentation](https://dev.mysql.com/doc/) 