<?php

include "db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid Product ID");
}

$stmt = mysqli_prepare($con, "
    SELECT 
        products.*,
        categories.name AS category_name,
        brands.name AS brand_name,
        partners.name AS partner_name
    FROM products
    LEFT JOIN categories 
        ON products.category_id = categories.id
    LEFT JOIN brands 
        ON products.brand_id = brands.id
    LEFT JOIN partners 
        ON products.partner_id = partners.id
    WHERE products.id = ?
");

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product Not Found");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Show Product</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="header">

        <h1>
            Product Details
        </h1>

        <a href="products.php" class="btn btn-secondary">
            Back
        </a>

    </div>

    <div class="details">

        <?php if (!empty($product['image']) && file_exists("uploads/" . $product['image'])) { ?>

            <img
                src="uploads/<?php echo htmlspecialchars($product['image']); ?>"
                class="big-image"
            >

        <?php } ?>

        <div class="details-row">

            <strong>ID:</strong>

            <?php echo $product['id']; ?>

        </div>

        <div class="details-row">

            <strong>Name:</strong>

            <?php echo htmlspecialchars($product['name']); ?>

        </div>

        <div class="details-row">

            <strong>Description:</strong>

            <?php echo htmlspecialchars($product['description']); ?>

        </div>

        <div class="details-row">

            <strong>Price:</strong>

            <?php echo $product['price']; ?>

        </div>

        <div class="details-row">

            <strong>Quantity:</strong>

            <?php echo $product['quantity']; ?>

        </div>

        <div class="details-row">

            <strong>Category:</strong>

            <?php echo htmlspecialchars($product['category_name'] ?? ''); ?>

        </div>

        <div class="details-row">

            <strong>Brand:</strong>

            <?php echo htmlspecialchars($product['brand_name'] ?? ''); ?>

        </div>

        <div class="details-row">

            <strong>Partner:</strong>

            <?php echo htmlspecialchars($product['partner_name'] ?? ''); ?>

        </div>

        <br>

        <a
            href="edit_product.php?id=<?php echo $product['id']; ?>"
            class="btn btn-warning"
        >
            Edit
        </a>

    </div>

</div>

</body>

</html>