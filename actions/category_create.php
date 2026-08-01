<?php

include "../config/database.php";

$category_name = trim($_POST["category_name"]);

// Check if category already exists
$check_sql = "SELECT * FROM categories
              WHERE category_name = '$category_name'";

$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    header("Location: ../categories.php?error=A+category+with+this+name+already+exists.");
    exit();
}

// Insert category
$sql = "INSERT INTO categories (category_name)
        VALUES ('$category_name')";

if (mysqli_query($conn, $sql)) {

    header("Location: ../categories.php?success=category_created");
    exit();

} else {

    header("Location: ../categories.php?error=" . urlencode(mysqli_error($conn)));
    exit();

}

?>