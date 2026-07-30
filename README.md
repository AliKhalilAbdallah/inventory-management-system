# Inventory Management System

A full-stack inventory management web application built with PHP, MySQL, JavaScript, Bootstrap, HTML, and CSS.

The system helps users manage products, categories, purchases, sales, stock quantities, and basic business reports through a simple browser-based interface.

## Features

- User authentication and session-based access
- Dashboard with inventory and transaction summaries
- Category creation, editing, and deletion
- Product creation, editing, and deletion
- Purchase recording with automatic stock increases
- Multi-item sales workflow
- Automatic stock updates after sales
- Sales history
- Purchase history
- Low-stock alerts
- Summary of total sales, purchases, and estimated net profit
- Responsive interface built with Bootstrap

## Technologies Used

- PHP
- MySQL
- JavaScript
- Bootstrap
- HTML5
- CSS3
- XAMPP
- phpMyAdmin

## Project Structure

```text
inventory-management-system/
├── actions/
├── assets/
│   ├── css/
│   ├── img/
│   └── js/
├── components/
├── config/
├── database/
│   └── inventory_db.sql
├── categories.php
├── category_edit.php
├── dashboard.php
├── index.php
├── product_edit.php
├── products.php
├── purchases.php
├── register.php
├── reports.php
└── sales.php
```

## Installation

### Requirements

Install the following software:

- XAMPP
- PHP 8 or later
- MySQL or MariaDB
- A modern web browser

### Setup Instructions

1. Clone the repository:

```bash
git clone https://github.com/AliKhalilAbdallah/inventory-management-system.git
```

2. Copy the project folder into the XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\InventoryManagementSystem
```

3. Start Apache and MySQL from the XAMPP Control Panel.

4. Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

5. Create a database named:

```text
inventory_db
```

6. Import the SQL file located at:

```text
database/inventory_db.sql
```

7. Verify the database settings inside:

```text
config/database.php
```

Default local XAMPP configuration:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "inventory_db";
```

8. Open the application:

```text
http://localhost/InventoryManagementSystem/
```

## Main Modules

### Dashboard

Displays totals for products, categories, sales, and purchases.

### Categories

Allows users to create, edit, and delete product categories.

### Products

Manages product names, categories, prices, and available quantities.

### Sales

Allows users to add products to a sale, calculate totals, save transactions, and automatically reduce stock.

### Purchases

Records supplier purchases and automatically increases product stock.

### Reports

Displays total sales, total purchases, estimated net profit, and low-stock alerts.

## Current Limitations

This repository represents the first portfolio release of the project. Some areas are planned for further improvement:

- Product stock can currently become negative in certain cases
- Registration requires repair
- Some database errors need friendlier user-facing messages
- The login-page logo requires correction
- Delete operations need confirmation dialogs
- Reports need richer filtering and product-level analytics
- Search, sorting, and pagination are not yet available
- Demo data needs additional cleanup

## Planned Improvements

- Prevent sales that exceed available stock
- Add date, category, product, and supplier filters
- Display best-selling and highest-revenue products
- Improve profit calculations
- Add search, sorting, and pagination
- Add success, validation, and error alerts
- Improve deletion behavior for referenced database records
- Add charts and report export options
- Improve the visual design and demo dataset

## Author

**Ali Khalil Al Abdallah**

- GitHub: [AliKhalilAbdallah](https://github.com/AliKhalilAbdallah)
- Email: ali.abdallah.cs@gmail.com

## Project Status

The core application is functional and will continue to receive improvements as part of my software-development portfolio.