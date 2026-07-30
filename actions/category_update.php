<?php

include "../config/database.php";

$category_id = $_POST["category_id"];
$category_name = $_POST["category_name"];

$sql = "UPDATE categories
        SET category_name = '$category_name'
        WHERE category_id = $category_id";

if (mysqli_query($conn, $sql)) {
    header("Location: ../categories.php");
} else {
    echo "Error: " . mysqli_error($conn);
}

?>