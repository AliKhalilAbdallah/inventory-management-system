<?php

include "../config/database.php";

$id = $_GET["id"];

$sql = "DELETE FROM categories WHERE category_id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: ../categories.php");
} else {
    echo "Error: " . mysqli_error($conn);
}

?>