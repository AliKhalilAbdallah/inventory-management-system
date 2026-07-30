<?php

include "../config/database.php";

$product_name = $_POST["product_name"];
$category_id = $_POST["category_id"];
$stock_quantity = $_POST["stock_quantity"];
$price = $_POST["price"];

$sql = "INSERT INTO products
(product_name, price, stock_quantity, category_id)

VALUES

('$product_name', '$price', '$stock_quantity', '$category_id')";

if (mysqli_query($conn, $sql)) {

    header("Location: ../products.php");

} else {

    echo "Error: " . mysqli_error($conn);

}

?>