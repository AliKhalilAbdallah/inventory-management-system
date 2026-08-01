<?php

include "../config/database.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$product_name = trim($_POST["product_name"] ?? "");
$category_id = filter_input(INPUT_POST, "category_id", FILTER_VALIDATE_INT);
$stock_quantity = filter_input(INPUT_POST, "stock_quantity", FILTER_VALIDATE_INT);
$price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);

try {

    if ($product_name === "") {
        throw new Exception("Product name cannot be empty.");
    }

    if (!$category_id) {
        throw new Exception("Please select a valid category.");
    }

    if ($stock_quantity === false || $stock_quantity < 0) {
        throw new Exception("Stock quantity must be zero or greater.");
    }

    if ($price === false || $price <= 0) {
        throw new Exception("Price must be greater than zero.");
    }

    $check = mysqli_prepare(
        $conn,
        "SELECT product_id
         FROM products
         WHERE product_name = ?"
    );

    mysqli_stmt_bind_param($check, "s", $product_name);
    mysqli_stmt_execute($check);

    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {
        throw new Exception("A product with this name already exists.");
    }

    mysqli_stmt_close($check);

    $statement = mysqli_prepare(
        $conn,
        "INSERT INTO products
        (product_name, price, stock_quantity, category_id)
        VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $statement,
        "sdii",
        $product_name,
        $price,
        $stock_quantity,
        $category_id
    );

    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);

    header("Location: ../products.php?success=product_created");
    exit;

} catch (Exception $error) {

    header(
        "Location: ../products.php?error=" .
        urlencode($error->getMessage())
    );

    exit;
}