<?php

include "../config/database.php";

$id = $_GET["id"];

$sql = "DELETE FROM products WHERE product_id = $id";

if (mysqli_query($conn, $sql)) {

    header("Location: ../products.php");

} else {

    echo "Error: " . mysqli_error($conn);

}

?>