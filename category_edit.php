<?php

include "config/auth.php";
include "config/database.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$category_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$category_id) {
    header("Location: categories.php?error=invalid_category");
    exit;
}

$statement = mysqli_prepare(
    $conn,
    "SELECT category_id, category_name
     FROM categories
     WHERE category_id = ?"
);

mysqli_stmt_bind_param(
    $statement,
    "i",
    $category_id
);

mysqli_stmt_execute($statement);

$result = mysqli_stmt_get_result($statement);
$category = mysqli_fetch_assoc($result);

mysqli_stmt_close($statement);

if (!$category) {
    header("Location: categories.php?error=category_not_found");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Category - Inventory System</title>

  <link
    rel="stylesheet"
    href="assets/css/style.css">

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>

<div id="navbar"></div>

<div class="container-fluid">
  <div class="row">

    <div id="sidebar" class="col-md-2 bg-light"></div>

    <main class="col-md-10 p-4">

      <h1 class="mb-4">Edit Category</h1>

      <div class="card shadow-sm">
        <div class="card-body">

          <form
            action="actions/category_update.php"
            method="POST">

            <input
              type="hidden"
              name="category_id"
              value="<?php echo $category["category_id"]; ?>">

            <div class="mb-3">
              <label class="form-label">
                Category Name
              </label>

              <input
                type="text"
                name="category_name"
                class="form-control"
                value="<?php echo htmlspecialchars($category["category_name"]); ?>"
                maxlength="100"
                required>
            </div>

            <button
              type="submit"
              class="btn btn-success">

              Update Category
            </button>

            <a
              href="categories.php"
              class="btn btn-secondary">

              Cancel
            </a>

          </form>

        </div>
      </div>

    </main>
  </div>
</div>

<div id="footer"></div>

<script src="assets/js/main.js"></script>

</body>
</html>