<?php

include "../config/database.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$category_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$category_id) {
    header("Location: ../categories.php?error=invalid_category");
    exit;
}

try {

    $statement = mysqli_prepare(
        $conn,
        "DELETE FROM categories WHERE category_id = ?"
    );

    mysqli_stmt_bind_param(
        $statement,
        "i",
        $category_id
    );

    mysqli_stmt_execute($statement);

    if (mysqli_stmt_affected_rows($statement) === 0) {

        mysqli_stmt_close($statement);

        header("Location: ../categories.php?error=category_not_found");
        exit;
    }

    mysqli_stmt_close($statement);

    header("Location: ../categories.php?success=category_deleted");
    exit;

}
catch (mysqli_sql_exception $error) {

    /*
     * Error 1451 = Foreign key constraint
     * (Products are still using this category.)
     */
    if ((int)$error->getCode() === 1451) {

        header("Location: ../categories.php?error=category_in_use");
        exit;
    }

    header("Location: ../categories.php?error=delete_failed");
    exit;
}