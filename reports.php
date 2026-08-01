<?php

include "config/auth.php";
include "config/database.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/*
|--------------------------------------------------------------------------
| Read and validate filters
|--------------------------------------------------------------------------
*/

$start_date = trim($_GET["start_date"] ?? "");
$end_date = trim($_GET["end_date"] ?? "");

$product_id = filter_input(
    INPUT_GET,
    "product_id",
    FILTER_VALIDATE_INT
);

$category_id = filter_input(
    INPUT_GET,
    "category_id",
    FILTER_VALIDATE_INT
);

if ($product_id === false) {
    $product_id = null;
}

if ($category_id === false) {
    $category_id = null;
}

/*
|--------------------------------------------------------------------------
| Validate date format
|--------------------------------------------------------------------------
*/

function isValidDate(string $date): bool
{
    if ($date === "") {
        return true;
    }

    $date_object = DateTime::createFromFormat("Y-m-d", $date);

    return $date_object &&
           $date_object->format("Y-m-d") === $date;
}

if (!isValidDate($start_date)) {
    $start_date = "";
}

if (!isValidDate($end_date)) {
    $end_date = "";
}

$filter_error = "";

if (
    $start_date !== "" &&
    $end_date !== "" &&
    $start_date > $end_date
) {
    $filter_error =
        "The start date cannot be later than the end date.";
}

/*
|--------------------------------------------------------------------------
| Build Sales query
|--------------------------------------------------------------------------
*/

$sales_sql = "
    SELECT COALESCE(SUM(s.total_amount), 0) AS total
    FROM sales s
    WHERE 1 = 1
";

$sales_types = "";
$sales_parameters = [];

if ($start_date !== "") {
    $sales_sql .= " AND s.date >= ?";
    $sales_types .= "s";
    $sales_parameters[] = $start_date;
}

if ($end_date !== "") {
    $sales_sql .= " AND s.date <= ?";
    $sales_types .= "s";
    $sales_parameters[] = $end_date;
}

if ($product_id) {
    $sales_sql .= "
        AND EXISTS (
            SELECT 1
            FROM sale_details sd
            WHERE sd.sale_id = s.sale_id
            AND sd.product_id = ?
        )
    ";

    $sales_types .= "i";
    $sales_parameters[] = $product_id;
}

if ($category_id) {
    $sales_sql .= "
        AND EXISTS (
            SELECT 1
            FROM sale_details sd
            INNER JOIN products p
                ON sd.product_id = p.product_id
            WHERE sd.sale_id = s.sale_id
            AND p.category_id = ?
        )
    ";

    $sales_types .= "i";
    $sales_parameters[] = $category_id;
}

$sales_statement = mysqli_prepare($conn, $sales_sql);

if ($sales_parameters) {
    mysqli_stmt_bind_param(
        $sales_statement,
        $sales_types,
        ...$sales_parameters
    );
}

mysqli_stmt_execute($sales_statement);

$sales_result =
    mysqli_stmt_get_result($sales_statement);

$sales_row =
    mysqli_fetch_assoc($sales_result);

$total_sales =
    (float) ($sales_row["total"] ?? 0);

mysqli_stmt_close($sales_statement);

/*
|--------------------------------------------------------------------------
| Build Purchases query
|--------------------------------------------------------------------------
*/

$purchases_sql = "
    SELECT COALESCE(SUM(pu.total_cost), 0) AS total
    FROM purchases pu
    WHERE 1 = 1
";

$purchases_types = "";
$purchases_parameters = [];

if ($start_date !== "") {
    $purchases_sql .= " AND pu.date >= ?";
    $purchases_types .= "s";
    $purchases_parameters[] = $start_date;
}

if ($end_date !== "") {
    $purchases_sql .= " AND pu.date <= ?";
    $purchases_types .= "s";
    $purchases_parameters[] = $end_date;
}

if ($product_id) {
    $purchases_sql .= "
        AND EXISTS (
            SELECT 1
            FROM purchase_details pd
            WHERE pd.purchase_id = pu.purchase_id
            AND pd.product_id = ?
        )
    ";

    $purchases_types .= "i";
    $purchases_parameters[] = $product_id;
}

if ($category_id) {
    $purchases_sql .= "
        AND EXISTS (
            SELECT 1
            FROM purchase_details pd
            INNER JOIN products p
                ON pd.product_id = p.product_id
            WHERE pd.purchase_id = pu.purchase_id
            AND p.category_id = ?
        )
    ";

    $purchases_types .= "i";
    $purchases_parameters[] = $category_id;
}

$purchases_statement =
    mysqli_prepare($conn, $purchases_sql);

if ($purchases_parameters) {
    mysqli_stmt_bind_param(
        $purchases_statement,
        $purchases_types,
        ...$purchases_parameters
    );
}

mysqli_stmt_execute($purchases_statement);

$purchases_result =
    mysqli_stmt_get_result($purchases_statement);

$purchases_row =
    mysqli_fetch_assoc($purchases_result);

$total_purchases =
    (float) ($purchases_row["total"] ?? 0);

mysqli_stmt_close($purchases_statement);

/*
|--------------------------------------------------------------------------
| Temporary estimated profit
|--------------------------------------------------------------------------
|
| We will replace this in Reports Part 3 with a proper gross-profit
| calculation based on the cost of inventory actually sold.
|
*/

$estimated_profit =
    $total_sales - $total_purchases;

/*
|--------------------------------------------------------------------------
| Load product and category filter options
|--------------------------------------------------------------------------
*/

$products_result = mysqli_query(
    $conn,
    "SELECT product_id, product_name
     FROM products
     ORDER BY product_name ASC"
);

$categories_result = mysqli_query(
    $conn,
    "SELECT category_id, category_name
     FROM categories
     ORDER BY category_name ASC"
);

/*
|--------------------------------------------------------------------------
| Low-stock list
|--------------------------------------------------------------------------
*/

$low_stock_statement = mysqli_prepare(
    $conn,
    "SELECT product_name, stock_quantity
     FROM products
     WHERE stock_quantity < ?
     ORDER BY stock_quantity ASC, product_name ASC"
);

$low_stock_limit = 5;

mysqli_stmt_bind_param(
    $low_stock_statement,
    "i",
    $low_stock_limit
);

mysqli_stmt_execute($low_stock_statement);

$low_stock_result =
    mysqli_stmt_get_result($low_stock_statement);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">

  <title>Reports - Inventory System</title>

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

      <div id="sidebar" class="col-md-2"></div>

      <main class="col-md-10 p-4">

        <h1 class="mb-2">Reports</h1>

        <p class="text-muted mb-4">
          Filter sales and purchase summaries by date, product, or category.
        </p>

        <?php if ($filter_error): ?>
          <div class="alert alert-danger">
            <?php echo htmlspecialchars($filter_error); ?>
          </div>
        <?php endif; ?>

        <!-- Report Filters -->
        <div class="card shadow-sm mb-4">
          <div class="card-body">

            <h5 class="card-title mb-3">
              Report Filters
            </h5>

            <form method="GET" action="reports.php">

              <div class="row g-3">

                <div class="col-md-2">
                  <label
                    for="startDate"
                    class="form-label">

                    Start Date
                  </label>

                  <input
                    type="date"
                    id="startDate"
                    name="start_date"
                    class="form-control"
                    value="<?php echo htmlspecialchars($start_date); ?>">
                </div>

                <div class="col-md-2">
                  <label
                    for="endDate"
                    class="form-label">

                    End Date
                  </label>

                  <input
                    type="date"
                    id="endDate"
                    name="end_date"
                    class="form-control"
                    value="<?php echo htmlspecialchars($end_date); ?>">
                </div>

                <div class="col-md-3">
                  <label
                    for="productFilter"
                    class="form-label">

                    Product
                  </label>

                  <select
                    id="productFilter"
                    name="product_id"
                    class="form-select">

                    <option value="">
                      All Products
                    </option>

                    <?php
                      while (
                          $product =
                          mysqli_fetch_assoc($products_result)
                      ) {
                    ?>
                        <option
                          value="<?php echo $product["product_id"]; ?>"
                          <?php
                            echo (
                                (int) $product_id ===
                                (int) $product["product_id"]
                            ) ? "selected" : "";
                          ?>>

                          <?php
                            echo htmlspecialchars(
                                $product["product_name"]
                            );
                          ?>
                        </option>
                    <?php
                      }
                    ?>
                  </select>
                </div>

                <div class="col-md-3">
                  <label
                    for="categoryFilter"
                    class="form-label">

                    Category
                  </label>

                  <select
                    id="categoryFilter"
                    name="category_id"
                    class="form-select">

                    <option value="">
                      All Categories
                    </option>

                    <?php
                      while (
                          $category =
                          mysqli_fetch_assoc($categories_result)
                      ) {
                    ?>
                        <option
                          value="<?php echo $category["category_id"]; ?>"
                          <?php
                            echo (
                                (int) $category_id ===
                                (int) $category["category_id"]
                            ) ? "selected" : "";
                          ?>>

                          <?php
                            echo htmlspecialchars(
                                $category["category_name"]
                            );
                          ?>
                        </option>
                    <?php
                      }
                    ?>
                  </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                  <div class="d-grid gap-2 w-100">

                    <button
                      type="submit"
                      class="btn btn-success">

                      Apply Filters
                    </button>

                    <a
                      href="reports.php"
                      class="btn btn-outline-secondary">

                      Reset Filters
                    </a>

                  </div>
                </div>

              </div>
            </form>

          </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">

          <div class="col-md-4">
            <div class="card text-center shadow-sm h-100">
              <div class="card-body">

                <h5 class="card-title">
                  Filtered Sales
                </h5>

                <h3>
                  $<?php echo number_format($total_sales, 2); ?>
                </h3>

              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card text-center shadow-sm h-100">
              <div class="card-body">

                <h5 class="card-title">
                  Filtered Purchases
                </h5>

                <h3>
                  $<?php echo number_format($total_purchases, 2); ?>
                </h3>

              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card text-center shadow-sm h-100">
              <div class="card-body">

                <h5 class="card-title">
                  Estimated Profit
                </h5>

                <h3>
                  $<?php echo number_format($estimated_profit, 2); ?>
                </h3>

                <small class="text-muted">
                  Sales minus purchases
                </small>

              </div>
            </div>
          </div>

        </div>

        <!-- Low Stock Alerts -->
        <div class="card shadow-sm">
          <div class="card-body">

            <h3 class="mb-3">
              Low Stock Alerts
            </h3>

            <?php if (mysqli_num_rows($low_stock_result) > 0): ?>

              <ul class="list-group">

                <?php
                  while (
                      $product =
                      mysqli_fetch_assoc($low_stock_result)
                  ) {
                ?>
                    <li
                      class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">

                      <span>
                        <?php
                          echo htmlspecialchars(
                              $product["product_name"]
                          );
                        ?>
                      </span>

                      <span class="badge bg-warning text-dark">
                        <?php
                          echo $product["stock_quantity"];
                        ?>
                        remaining
                      </span>

                    </li>
                <?php
                  }
                ?>

              </ul>

            <?php else: ?>

              <p class="text-muted mb-0">
                No low-stock products currently.
              </p>

            <?php endif; ?>

          </div>
        </div>

      </main>
    </div>
  </div>

  <div id="footer"></div>

  <script src="assets/js/main.js"></script>

</body>
</html>

<?php
mysqli_stmt_close($low_stock_statement);
?>