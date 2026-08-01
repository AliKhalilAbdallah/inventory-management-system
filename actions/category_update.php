<?php

include "../config/database.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$category_id = filter_input(INPUT_POST, "category_id", FILTER_VALIDATE_INT);
$category_name = trim($_POST["category_name"] ?? "");

try {

    if (!$category_id) {
        throw new Exception("invalid_category");
    }

    if ($category_name === "") {
        throw new Exception("empty_category");
    }

    /*
     * Prevent duplicate category names
     */
    $check = mysqli_prepare(
        $conn,
        "SELECT category_id
         FROM categories
         WHERE category_name = ?
         AND category_id <> ?"
    );

    mysqli_stmt_bind_param(
        $check,
        "si",
        $category_name,
        $category_id
    );

    mysqli_stmt_execute($check);

    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {

        mysqli_stmt_close($check);

        throw new Exception("duplicate_category");
    }

    mysqli_stmt_close($check);

    /*
     * Update category
     */
    $statement = mysqli_prepare(
        $conn,
        "UPDATE categories
         SET category_name = ?
         WHERE category_id = ?"
    );

    mysqli_stmt_bind_param(
        $statement,
        "si",
        $category_name,
        $category_id
    );

    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    header("Location: ../categories.php?success=category_updated");
    exit;

}
catch (Exception $error) {

    header(
        "Location: ../categories.php?error=" .
        urlencode($error->getMessage())
    );

    exit;
}