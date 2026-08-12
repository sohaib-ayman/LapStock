<?php

include "shared/db.php";


/* Counts */

$productsCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM products")
)['total'];

$categoriesCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories")
)['total'];

$brandsCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM brands")
)['total'];

$suppliersCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM suppliers")
)['total'];


/* Low stock (3 or fewer units) */

$lowStockCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM products WHERE quantity <= 3")
)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Home — Laptop Inventory</title>

    <link rel="stylesheet" href="shared/style.css">

    <style>
        * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    color: #222;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 40px auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

h1 {
    font-size: 30px;
}

.btn {
    display: inline-block;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 15px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-success {
    background: #198754;
    color: white;
}

.btn-warning {
    background: #ffc107;
    color: #000;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.search-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.search-box input {
    width: 70%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.search-box button {
    padding: 12px 20px;
    border: none;
    background: #007bff;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}

.table-container {
    background: white;
    padding: 20px;
    border-radius: 10px;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th,
table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}

table th {
    background: #343a40;
    color: white;
}

.product-image {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
}

.actions {
    display: flex;
    justify-content: center;
    gap: 5px;
    flex-wrap: wrap;
}

.form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    max-width: 700px;
    margin: auto;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 11px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.form-group textarea {
    height: 120px;
    resize: vertical;
}

.error {
    background: #f8d7da;
    color: #842029;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.success {
    background: #d1e7dd;
    color: #0f5132;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.details {
    background: white;
    padding: 30px;
    border-radius: 10px;
}

.details-row {
    padding: 12px 0;
    border-bottom: 1px solid #ddd;
}

.details-row strong {
    display: inline-block;
    width: 150px;
}

.big-image {
    width: 250px;
    height: 250px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 20px;
}

.no-image {
    width: 70px;
    height: 70px;
    background: #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-size: 12px;
}

.navbar {
    background: white;
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

.navbar-brand {
    font-size: 20px;
    font-weight: bold;
    color: #007bff;
}

.navbar-links a {
    margin-left: 25px;
    text-decoration: none;
    color: #444;
    font-size: 15px;
}

.navbar-links a:hover {
    color: #007bff;
}

.breadcrumb {
    color: #777;
    margin-bottom: 5px;
    font-size: 14px;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.card-label {
    color: #666;
    margin-bottom: 8px;
}

.card-number {
    font-size: 34px;
    font-weight: bold;
}

.alert-warning {
    background: #fff3cd;
    color: #664d03;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.quick-links {
    background: white;
    padding: 25px;
    border-radius: 10px;
}

.quick-links h2 {
    font-size: 18px;
    margin-bottom: 18px;
}

.quick-links-buttons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
    </style>
</head>

<body>

<?php include "shared/navbar.php"; ?>


<div class="container">

    <div class="breadcrumb">
        Home
    </div>

    <h1>Laptop Inventory Dashboard</h1>


    <div class="dashboard-cards">

        <div class="card">
            <div class="card-label">Products</div>
            <div class="card-number"><?php echo $productsCount; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Categories</div>
            <div class="card-number"><?php echo $categoriesCount; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Brands</div>
            <div class="card-number"><?php echo $brandsCount; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Suppliers</div>
            <div class="card-number"><?php echo $suppliersCount; ?></div>
        </div>

    </div>


    <?php if ($lowStockCount > 0) { ?>

        <div class="alert-warning">
            ⚠ <?php echo $lowStockCount; ?> product(s) are low on stock (3 or fewer units).
        </div>

    <?php } ?>


    <div class="quick-links">

        <h2>Quick Links</h2>

        <div class="quick-links-buttons">

            <a href="/products/product.php" class="btn btn-primary">
                Manage Products
            </a>

            <a href="/categories/categories.php" class="btn btn-secondary">
                Categories
            </a>

            <a href="/brands/brands.php" class="btn btn-secondary">
                Brands
            </a>

            <a href="/suppliers/suppliers.php" class="btn btn-secondary">
                Suppliers
            </a>

        </div>

    </div>

</div>

</body>

</html>
