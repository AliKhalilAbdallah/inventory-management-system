<?php

include "../config/database.php";

$product_id = $_POST["product_id"];
$product_name = $_POST["product_name"];
$category_id = $_POST["category_id"];
$stock_quantity = $_POST["stock_quantity"];
$price = $_POST["price"];

$sql = "UPDATE products
        SET product_name = '$product_name',
            category_id = '$category_id',
            stock_quantity = '$stock_quantity',
            price = '$price'
        WHERE product_id = $product_id";

if (mysqli_query($conn, $sql)) {
    header("Location: ../products.php");
} else {
    echo "Error: " . mysqli_error($conn);
}

?>