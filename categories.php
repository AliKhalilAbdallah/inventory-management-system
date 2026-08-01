<?php

include "config/auth.php";
include "config/database.php";

$success_message = "";
$error_message = "";

if (isset($_GET["success"])) {
    switch ($_GET["success"]) {
        case "category_created":
            $success_message = "Category created successfully.";
            break;

        case "category_updated":
            $success_message = "Category updated successfully.";
            break;

        case "category_deleted":
            $success_message = "Category deleted successfully.";
            break;
    }
}

if (isset($_GET["error"])) {
    switch ($_GET["error"]) {
        case "duplicate_category":
            $error_message = "A category with this name already exists.";
            break;

        case "empty_category":
            $error_message = "Category name cannot be empty.";
            break;

        case "invalid_category":
            $error_message = "Invalid category selection.";
            break;

        case "category_not_found":
            $error_message = "The selected category could not be found.";
            break;

        case "category_in_use":
            $error_message = "This category cannot be deleted because products are assigned to it.";
            break;

        case "delete_failed":
            $error_message = "The category could not be deleted.";
            break;

        default:
            /*
             * category_create.php may send a readable validation message.
             */
            $error_message = urldecode($_GET["error"]);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">

  <title>Categories - Inventory System</title>

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <link
    rel="stylesheet"
    href="assets/css/style.css">
</head>

<body>

<div id="navbar"></div>

<div class="container-fluid">
  <div class="row">

    <div id="sidebar" class="col-md-2"></div>

    <main class="col-md-10 p-4">

      <h2 class="mb-2">Categories</h2>

      <p class="text-muted">
        Manage product categories used in the inventory system.
      </p>

      <?php if ($success_message): ?>
        <div
          class="alert alert-success alert-dismissible fade show"
          role="alert">

          <?php echo htmlspecialchars($success_message); ?>

          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
          </button>
        </div>
      <?php endif; ?>

      <?php if ($error_message): ?>
        <div
          class="alert alert-danger alert-dismissible fade show"
          role="alert">

          <?php echo htmlspecialchars($error_message); ?>

          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
          </button>
        </div>
      <?php endif; ?>

      <div class="card shadow-sm mb-4">
        <div class="card-body">

          <form
            action="actions/category_create.php"
            method="POST"
            class="row g-3">

            <div class="col-md-9">
              <input
                type="text"
                name="category_name"
                class="form-control"
                placeholder="Category Name"
                maxlength="100"
                required>
            </div>

            <div class="col-md-3">
              <button
                class="btn btn-success w-100"
                type="submit">

                Add Category
              </button>
            </div>

          </form>

        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">

          <table
            class="table table-hover"
            id="categoriesTable">

            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>

              <?php
                $sql =
                    "SELECT category_id, category_name
                     FROM categories
                     ORDER BY category_id DESC";

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) === 0) {
              ?>
                  <tr>
                    <td
                      colspan="3"
                      class="text-center text-muted py-4">

                      No categories are available.
                    </td>
                  </tr>
              <?php
                }

                while ($row = mysqli_fetch_assoc($result)) {
              ?>
                  <tr>
                    <td>
                      <?php echo $row["category_id"]; ?>
                    </td>

                    <td>
                      <?php
                        echo htmlspecialchars(
                            $row["category_name"]
                        );
                      ?>
                    </td>

                    <td>
                      <a
                        href="category_edit.php?id=<?php echo $row["category_id"]; ?>"
                        class="btn btn-primary btn-sm">

                        Edit
                      </a>

                      <a
                        href="actions/category_delete.php?id=<?php echo $row["category_id"]; ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this category?');">

                        Delete
                      </a>
                    </td>
                  </tr>
              <?php
                }
              ?>

            </tbody>
          </table>

        </div>
      </div>

    </main>

  </div>
</div>

<div id="footer"></div>

<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>

<script src="assets/js/main.js"></script>

</body>
</html>