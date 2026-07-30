<?php

include "../config/database.php";

$username = $_POST["username"];
$password = $_POST["password"];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, role)
VALUES ('$username', '$hashed_password', 'admin')";

if (mysqli_query($conn, $sql)) {
    header("Location: ../index.php");
} else {
    echo "Error: " . mysqli_error($conn);
}

?>