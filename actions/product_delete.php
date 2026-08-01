<?php

include "../config/database.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$product_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$product_id) {
    header("Location: ../products.php?error=invalid_product");
    exit;
}

try {
    $statement = mysqli_prepare(
        $conn,
        "DELETE FROM products WHERE product_id = ?"
    );

    mysqli_stmt_bind_param($statement, "i", $product_id);
    mysqli_stmt_execute($statement);

    if (mysqli_stmt_affected_rows($statement) === 0) {
        header("Location: ../products.php?error=product_not_found");
        exit;
    }

    mysqli_stmt_close($statement);

    header("Location: ../products.php?success=product_deleted");
    exit;

} catch (mysqli_sql_exception $error) {
    if ((int) $error->getCode() === 1451) {
        header("Location: ../products.php?error=product_in_use");
        exit;
    }

    header("Location: ../products.php?error=delete_failed");
    exit;
}