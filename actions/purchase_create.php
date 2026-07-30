<?php

session_start();
include "../config/database.php";

$product_id = $_POST["product_id"];
$quantity = $_POST["quantity"];
$cost = $_POST["cost"];
$supplier_name = $_POST["supplier_name"];
$date = $_POST["date"];
$user_id = $_SESSION["user_id"];

$total_cost = $quantity * $cost;

mysqli_begin_transaction($conn);

try {
    $purchase_sql = "INSERT INTO purchases (date, supplier_name, total_cost, user_id)
                     VALUES ('$date', '$supplier_name', '$total_cost', '$user_id')";

    mysqli_query($conn, $purchase_sql);

    $purchase_id = mysqli_insert_id($conn);

    $detail_sql = "INSERT INTO purchase_details (quantity, cost, purchase_id, product_id)
                   VALUES ('$quantity', '$cost', '$purchase_id', '$product_id')";

    mysqli_query($conn, $detail_sql);

    $stock_sql = "UPDATE products
                  SET stock_quantity = stock_quantity + $quantity
                  WHERE product_id = $product_id";

    mysqli_query($conn, $stock_sql);

    mysqli_commit($conn);

    header("Location: ../purchases.php");

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Error: " . $e->getMessage();
}

?>