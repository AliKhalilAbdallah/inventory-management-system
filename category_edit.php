<?php include "config/auth.php"; ?>
<?php include "config/database.php"; ?>

<?php
$id = $_GET["id"];

$sql = "SELECT * FROM categories WHERE category_id = $id";
$result = mysqli_query($conn, $sql);
$category = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Category - Inventory System</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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

          <form action="actions/category_update.php" method="POST">

            <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">

            <div class="mb-3">
              <label class="form-label">Category Name</label>
              <input type="text"
                     name="category_name"
                     class="form-control"
                     value="<?php echo $category['category_name']; ?>"
                     required>
            </div>

            <button type="submit" class="btn btn-success">Update Category</button>
            <a href="categories.php" class="btn btn-secondary">Cancel</a>

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