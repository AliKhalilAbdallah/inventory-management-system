<?php

session_start();

header("Content-Type: application/json");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "../config/database.php";

try {
    if (!isset($_SESSION["user_id"])) {
        throw new Exception("You must be logged in to create a sale.");
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if (
        !is_array($data) ||
        empty($data["date"]) ||
        empty($data["items"]) ||
        !is_array($data["items"])
    ) {
        throw new Exception("Invalid sale data.");
    }

    $date = $data["date"];
    $items = $data["items"];
    $user_id = (int) $_SESSION["user_id"];

    mysqli_begin_transaction($conn);

    /*
     * Validate all products and lock their rows before creating the sale.
     * This prevents another sale from changing the same stock simultaneously.
     */
    $validated_items = [];
    $calculated_total = 0;

    $product_statement = mysqli_prepare(
        $conn,
        "SELECT product_name, price, stock_quantity
         FROM products
         WHERE product_id = ?
         FOR UPDATE"
    );

    foreach ($items as $item) {
        $product_id = (int) ($item["product_id"] ?? 0);
        $quantity = (int) ($item["quantity"] ?? 0);

        if ($product_id <= 0 || $quantity <= 0) {
            throw new Exception("Every sale item must have a valid product and quantity.");
        }

        mysqli_stmt_bind_param($product_statement, "i", $product_id);
        mysqli_stmt_execute($product_statement);

        $result = mysqli_stmt_get_result($product_statement);
        $product = mysqli_fetch_assoc($result);

        if (!$product) {
            throw new Exception("One of the selected products no longer exists.");
        }

        $available_stock = (int) $product["stock_quantity"];

        if ($quantity > $available_stock) {
            throw new Exception(
                "Not enough stock for {$product["product_name"]}. " .
                "Available: {$available_stock}, requested: {$quantity}."
            );
        }

        /*
         * Use the price stored in the database instead of trusting
         * a price sent by the browser.
         */
        $price = (float) $product["price"];
        $calculated_total += $price * $quantity;

        $validated_items[] = [
            "product_id" => $product_id,
            "quantity" => $quantity,
            "price" => $price
        ];
    }

    mysqli_stmt_close($product_statement);

    $sale_statement = mysqli_prepare(
        $conn,
        "INSERT INTO sales (date, total_amount, user_id)
         VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $sale_statement,
        "sdi",
        $date,
        $calculated_total,
        $user_id
    );

    mysqli_stmt_execute($sale_statement);
    $sale_id = mysqli_insert_id($conn);
    mysqli_stmt_close($sale_statement);

    $detail_statement = mysqli_prepare(
        $conn,
        "INSERT INTO sale_details
         (quantity, price, sale_id, product_id)
         VALUES (?, ?, ?, ?)"
    );

    $stock_statement = mysqli_prepare(
        $conn,
        "UPDATE products
         SET stock_quantity = stock_quantity - ?
         WHERE product_id = ?"
    );

    foreach ($validated_items as $item) {
        mysqli_stmt_bind_param(
            $detail_statement,
            "idii",
            $item["quantity"],
            $item["price"],
            $sale_id,
            $item["product_id"]
        );

        mysqli_stmt_execute($detail_statement);

        mysqli_stmt_bind_param(
            $stock_statement,
            "ii",
            $item["quantity"],
            $item["product_id"]
        );

        mysqli_stmt_execute($stock_statement);
    }

    mysqli_stmt_close($detail_statement);
    mysqli_stmt_close($stock_statement);

    mysqli_commit($conn);

    echo json_encode([
        "success" => true,
        "message" => "Sale completed successfully.",
        "total" => $calculated_total
    ]);

} catch (Throwable $error) {
    if (isset($conn)) {
        mysqli_rollback($conn);
    }

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => $error->getMessage()
    ]);
}