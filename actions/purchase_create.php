<?php

session_start();

include "../config/database.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (!isset($_SESSION["user_id"])) {
        header("Location: ../index.php");
        exit;
    }

    $product_id = filter_input(
        INPUT_POST,
        "product_id",
        FILTER_VALIDATE_INT
    );

    $quantity = filter_input(
        INPUT_POST,
        "quantity",
        FILTER_VALIDATE_INT
    );

    $cost = filter_input(
        INPUT_POST,
        "cost",
        FILTER_VALIDATE_FLOAT
    );

    $supplier_name = trim($_POST["supplier_name"] ?? "");
    $date = trim($_POST["date"] ?? "");
    $user_id = (int) $_SESSION["user_id"];

    if (!$product_id) {
        throw new Exception("invalid_product");
    }

    if ($quantity === false || $quantity <= 0) {
        throw new Exception("invalid_quantity");
    }

    if ($cost === false || $cost <= 0) {
        throw new Exception("invalid_cost");
    }

    if ($supplier_name === "") {
        throw new Exception("invalid_supplier");
    }

    if (strlen($supplier_name) > 100) {
        throw new Exception("supplier_too_long");
    }

    $date_object = DateTime::createFromFormat("Y-m-d", $date);

    if (
        !$date_object ||
        $date_object->format("Y-m-d") !== $date
    ) {
        throw new Exception("invalid_date");
    }

    /*
     * Confirm that the selected product exists.
     */
    $product_statement = mysqli_prepare(
        $conn,
        "SELECT product_id
         FROM products
         WHERE product_id = ?"
    );

    mysqli_stmt_bind_param(
        $product_statement,
        "i",
        $product_id
    );

    mysqli_stmt_execute($product_statement);

    $product_result = mysqli_stmt_get_result($product_statement);

    if (mysqli_num_rows($product_result) === 0) {
        mysqli_stmt_close($product_statement);
        throw new Exception("product_not_found");
    }

    mysqli_stmt_close($product_statement);

    $total_cost = $quantity * $cost;

    mysqli_begin_transaction($conn);

    /*
     * Create the main purchase record.
     */
    $purchase_statement = mysqli_prepare(
        $conn,
        "INSERT INTO purchases
        (date, supplier_name, total_cost, user_id)
        VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $purchase_statement,
        "ssdi",
        $date,
        $supplier_name,
        $total_cost,
        $user_id
    );

    mysqli_stmt_execute($purchase_statement);

    $purchase_id = mysqli_insert_id($conn);

    mysqli_stmt_close($purchase_statement);

    /*
     * Create the purchase detail record.
     */
    $detail_statement = mysqli_prepare(
        $conn,
        "INSERT INTO purchase_details
        (quantity, cost, purchase_id, product_id)
        VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $detail_statement,
        "idii",
        $quantity,
        $cost,
        $purchase_id,
        $product_id
    );

    mysqli_stmt_execute($detail_statement);
    mysqli_stmt_close($detail_statement);

    /*
     * Increase the product's available stock.
     */
    $stock_statement = mysqli_prepare(
        $conn,
        "UPDATE products
         SET stock_quantity = stock_quantity + ?
         WHERE product_id = ?"
    );

    mysqli_stmt_bind_param(
        $stock_statement,
        "ii",
        $quantity,
        $product_id
    );

    mysqli_stmt_execute($stock_statement);
    mysqli_stmt_close($stock_statement);

    mysqli_commit($conn);

    header("Location: ../purchases.php?success=purchase_created");
    exit;

} catch (Throwable $error) {
    if (isset($conn)) {
        try {
            mysqli_rollback($conn);
        } catch (Throwable $ignored) {
        }
    }

    $allowed_errors = [
        "invalid_product",
        "invalid_quantity",
        "invalid_cost",
        "invalid_supplier",
        "supplier_too_long",
        "invalid_date",
        "product_not_found"
    ];

    $error_code = $error->getMessage();

    if (!in_array($error_code, $allowed_errors, true)) {
        $error_code = "purchase_failed";
    }

    header(
        "Location: ../purchases.php?error=" .
        urlencode($error_code)
    );

    exit;
}