<?php include "config/auth.php"; ?>
<?php include "config/database.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Categories - Inventory System</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div id="navbar"></div>

<div class="container-fluid">
  <div class="row">

    <div id="sidebar" class="col-md-2 "></div>

    <main class="col-md-10 p-4">

      <h2 class="mb-2">Categories</h2>
      <p class="text-muted">Manage product categories used in the inventory system.</p>

      <div class="card shadow-sm mb-4">
        <div class="card-body">

          <form action="actions/category_create.php" method="POST" class="row g-3">

            <div class="col-md-9">
              <input type="text"
                     name="category_name"
                     class="form-control"
                     placeholder="Category Name"
                     required>
            </div>

            <div class="col-md-3">
              <button class="btn btn-success w-100" type="submit">
                Add Category
              </button>
            </div>

          </form>

        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">

          <table class="table table-hover" id="categoriesTable">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <?php
                $sql = "SELECT * FROM categories ORDER BY category_id DESC";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
              ?>
                  <tr>
                    <td><?php echo $row["category_id"]; ?></td>
                    <td><?php echo $row["category_name"]; ?></td>
                    <td>
                      <a href="category_edit.php?id=<?php echo $row["category_id"]; ?>"
                         class="btn btn-primary btn-sm">
                        Edit
                      </a>

                      <a href="actions/category_delete.php?id=<?php echo $row["category_id"]; ?>"
                         class="btn btn-danger btn-sm">
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

<script src="assets/js/main.js"></script>

</body>
</html>