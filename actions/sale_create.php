<?php

session_start();
include "../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

$date = $data["date"];
$total = $data["total"];
$items = $data["items"];
$user_id = $_SESSION["user_id"];

mysqli_begin_transaction($conn);

try {
    $sale_sql = "INSERT INTO sales (date, total_amount, user_id)
                 VALUES ('$date', '$total', '$user_id')";

    mysqli_query($conn, $sale_sql);

    $sale_id = mysqli_insert_id($conn);

    foreach ($items as $item) {
        $product_id = $item["product_id"];
        $quantity = $item["quantity"];
        $price = $item["price"];

        $detail_sql = "INSERT INTO sale_details (quantity, price, sale_id, product_id)
                       VALUES ('$quantity', '$price', '$sale_id', '$product_id')";

        mysqli_query($conn, $detail_sql);

        $stock_sql = "UPDATE products
                      SET stock_quantity = stock_quantity - $quantity
                      WHERE product_id = $product_id";

        mysqli_query($conn, $stock_sql);
    }

    mysqli_commit($conn);

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    mysqli_rollback($conn);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}

?>