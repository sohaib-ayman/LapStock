<?php
require_once '../shared/db.php';

$totalProducts = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
if ($res && $row = mysqli_fetch_assoc($res)) { $totalProducts = $row['total']; }

$availableProducts = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE quantity > 3");
if ($res && $row = mysqli_fetch_assoc($res)) { $availableProducts = $row['total']; }

$lowStockProducts = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE quantity > 0 AND quantity <= 3");
if ($res && $row = mysqli_fetch_assoc($res)) { $lowStockProducts = $row['total']; }

$outOfStockProducts = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE quantity = 0");
if ($res && $row = mysqli_fetch_assoc($res)) { $outOfStockProducts = $row['total']; }

$totalBrands = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as total FROM brands");
if ($res && $row = mysqli_fetch_assoc($res)) { $totalBrands = $row['total']; }

$totalSuppliers = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as total FROM suppliers");
if ($res && $row = mysqli_fetch_assoc($res)) { $totalSuppliers = $row['total']; }

$brandLabels = [];
$brandData = [];
$brandQuery = "SELECT brands.name, COUNT(products.id) as total FROM brands LEFT JOIN products ON brands.id = products.brand_id GROUP BY brands.id, brands.name";
$brandRes = mysqli_query($conn, $brandQuery);
if ($brandRes) {
    while ($bRow = mysqli_fetch_assoc($brandRes)) {
        $brandLabels[] = $bRow['name'];
        $brandData[] = (int)$bRow['total'];
    }
}

$categoryLabels = [];
$categoryData = [];
$catQuery = "SELECT categories.name, COUNT(products.id) as total FROM categories LEFT JOIN products ON categories.id = products.category_id GROUP BY categories.id, categories.name";
$catRes = mysqli_query($conn, $catQuery);
if ($catRes) {
    while ($cRow = mysqli_fetch_assoc($catRes)) {
        $categoryLabels[] = $cRow['name'];
        $categoryData[] = (int)$cRow['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Inventory Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="./css/main.css">

    <style>
        body {
            background-color: #f8fafc;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .stat-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            padding: 18px;
        }
        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 12px;
        }
        .badge-in-stock { background-color: #e6f4ea; color: #137333; }
        .badge-low-stock { background-color: #fef7e0; color: #b06000; }
        .badge-out-stock { background-color: #fce8e6; color: #c5221f; }
        .table-custom th {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #64748b;
            background-color: #f8fafc;
        }
        .prod-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
</head>
<body>

    <?php include '../shared/nav.php'; ?>

    <div class="container-fluid px-4 py-4">

        <div class="mb-4">
            <small class="text-muted">Home &gt; <span class="text-dark">Dashboard</span></small>
            <h3 class="fw-bold text-dark mt-1">Laptop Inventory Dashboard</h3>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card shadow-sm">
                    <div class="icon-box bg-primary-subtle text-primary"><i class="bi bi-laptop"></i></div>
                    <h3 class="fw-bold mb-0"><?php echo $totalProducts; ?></h3>
                    <div class="text-secondary small fw-medium">Total Products</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card shadow-sm">
                    <div class="icon-box bg-success-subtle text-success"><i class="bi bi-check-circle"></i></div>
                    <h3 class="fw-bold mb-0"><?php echo $availableProducts; ?></h3>
                    <div class="text-secondary small fw-medium">Available</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card shadow-sm">
                    <div class="icon-box bg-warning-subtle text-warning"><i class="bi bi-exclamation-triangle"></i></div>
                    <h3 class="fw-bold mb-0"><?php echo $lowStockProducts; ?></h3>
                    <div class="text-secondary small fw-medium">Low Stock</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card shadow-sm">
                    <div class="icon-box bg-danger-subtle text-danger"><i class="bi bi-x-circle"></i></div>
                    <h3 class="fw-bold mb-0"><?php echo $outOfStockProducts; ?></h3>
                    <div class="text-secondary small fw-medium">Out of Stock</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card shadow-sm">
                    <div class="icon-box bg-info-subtle text-info"><i class="bi bi-building"></i></div>
                    <h3 class="fw-bold mb-0"><?php echo $totalBrands; ?></h3>
                    <div class="text-secondary small fw-medium">Total Brands</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card shadow-sm">
                    <div class="icon-box bg-secondary-subtle text-secondary"><i class="bi bi-people"></i></div>
                    <h3 class="fw-bold mb-0"><?php echo $totalSuppliers; ?></h3>
                    <div class="text-secondary small fw-medium">Total Suppliers</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            
            <div class="col-lg-8">
                <div class="bg-white rounded-3 p-3 border shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                        <h5 class="fw-bold m-0 text-dark">Recent Products</h5>
                        <a href="./products/product.php" class="text-primary text-decoration-none small fw-semibold">View All &rarr;</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover table-custom">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th>QTY</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "
                                    SELECT products.*, brands.name AS brand_name 
                                    FROM products 
                                    LEFT JOIN brands ON products.brand_id = brands.id 
                                    ORDER BY products.id DESC LIMIT 6
                                ";
                                $result = mysqli_query($conn, $query);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $qty = (int)$row['quantity'];
                                        if ($qty == 0) {
                                            $statusText = 'Out of Stock';
                                            $statusClass = 'badge-out-stock';
                                        } elseif ($qty <= 3) {
                                            $statusText = 'Low Stock';
                                            $statusClass = 'badge-low-stock';
                                        } else {
                                            $statusText = 'In Stock';
                                            $statusClass = 'badge-in-stock';
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if (!empty($row['image']) && file_exists("uploads/" . $row['image'])) { ?>
                                                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="prod-img">
                                                    <?php } else { ?>
                                                        <div class="p-2 bg-light rounded text-secondary">
                                                            <i class="bi bi-laptop fs-5"></i>
                                                        </div>
                                                    <?php } ?>
                                                    <div>
                                                        <div class="fw-bold text-dark mb-0" style="font-size: 14px;"><?php echo htmlspecialchars($row['name']); ?></div>
                                                        <small class="text-muted">ID: #<?php echo $row['id']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-secondary small"><?php echo htmlspecialchars($row['brand_name'] ?? 'N/A'); ?></td>
                                            <td class="text-dark small fw-semibold">$<?php echo number_format($row['price'], 2); ?></td>
                                            <td class="fw-bold text-dark"><?php echo $qty; ?></td>
                                            <td>
                                                <span class="badge <?php echo $statusClass; ?> px-2 py-1">
                                                    ● <?php echo $statusText; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center py-4 text-muted">No products found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="d-flex flex-column gap-3">
                    
                    <div class="bg-white rounded-3 p-3 border shadow-sm">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Low Stock Items</h6>
                        <div class="d-flex flex-column gap-2">
                            <?php
                            $lowSql = "SELECT * FROM products WHERE quantity > 0 AND quantity <= 3 LIMIT 3";
                            $lowRes = mysqli_query($conn, $lowSql);
                            if ($lowRes && mysqli_num_rows($lowRes) > 0) {
                                while ($lRow = mysqli_fetch_assoc($lowRes)) {
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="p-1 bg-light rounded"><i class="bi bi-laptop"></i></div>
                                            <div>
                                                <div class="fw-semibold small"><?php echo htmlspecialchars($lRow['name']); ?></div>
                                                <small class="text-danger">Stock: <?php echo $lRow['quantity']; ?></small>
                                            </div>
                                        </div>
                                        <span class="badge badge-low-stock">Low Stock</span>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<small class="text-muted">No low stock items</small>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="bg-white rounded-3 p-3 border shadow-sm">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-x-circle-fill text-danger me-2"></i>Out of Stock Items</h6>
                        <div class="d-flex flex-column gap-2">
                            <?php
                            $outSql = "SELECT * FROM products WHERE quantity = 0 LIMIT 3";
                            $outRes = mysqli_query($conn, $outSql);
                            if ($outRes && mysqli_num_rows($outRes) > 0) {
                                while ($oRow = mysqli_fetch_assoc($outRes)) {
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="p-1 bg-light rounded"><i class="bi bi-laptop"></i></div>
                                            <div>
                                                <div class="fw-semibold small"><?php echo htmlspecialchars($oRow['name']); ?></div>
                                                <small class="text-danger">0 units</small>
                                            </div>
                                        </div>
                                        <span class="badge badge-out-stock">Out of Stock</span>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<small class="text-muted">No out of stock items</small>';
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="bg-white p-3 rounded-3 border shadow-sm">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Brand Distribution</h6>
                    <div style="height: 250px;">
                        <canvas id="brandChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white p-3 rounded-3 border shadow-sm">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart-fill text-purple me-2"></i>Category Distribution</h6>
                    <div style="height: 250px;" class="d-flex justify-content-center">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        new Chart(document.getElementById('brandChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($brandLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($brandData); ?>,
                    backgroundColor: '#0d6efd',
                    borderRadius: 4
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($categoryLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($categoryData); ?>,
                    backgroundColor: ['#0d6efd', '#198754', '#fd7e14', '#6f42c1', '#0dcaf0', '#ffc107', '#20c997']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
        });
    </script>
</body>
</html>