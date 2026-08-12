<?php

include "db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid Product ID");
}


/* Get Product */

$stmt = mysqli_prepare(
    $con,
    "SELECT * FROM products WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$product = mysqli_fetch_assoc($result);


if (!$product) {
    die("Product Not Found");
}


/* Default Values */

$name = $product['name'];
$description = $product['description'];
$price = $product['price'];
$quantity = $product['quantity'];
$category_id = $product['category_id'];
$brand_id = $product['brand_id'];
$partner_id = $product['partner_id'];
$oldImage = $product['image'];

$errors = [];


/* Get Categories */

$categories = mysqli_query(
    $con,
    "SELECT * FROM categories ORDER BY name"
);


/* Get Brands */

$brands = mysqli_query(
    $con,
    "SELECT * FROM brands ORDER BY name"
);


/* Get Partners */

$partners = mysqli_query(
    $con,
    "SELECT * FROM partners ORDER BY name"
);


/* Update */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name'] ?? "");
    $description = trim($_POST['description'] ?? "");
    $price = trim($_POST['price'] ?? "");
    $quantity = trim($_POST['quantity'] ?? "");
    $category_id = trim($_POST['category_id'] ?? "");
    $brand_id = trim($_POST['brand_id'] ?? "");
    $partner_id = trim($_POST['partner_id'] ?? "");


    /* Validation */

    if ($name == "") {
        $errors[] = "Product name is required.";
    }

    if ($description == "") {
        $errors[] = "Description is required.";
    }

    if ($price == "" || !is_numeric($price) || $price < 0) {
        $errors[] = "Please enter a valid price.";
    }

    if ($quantity == "" || !is_numeric($quantity) || $quantity < 0) {
        $errors[] = "Please enter a valid quantity.";
    }

    if ($category_id == "") {
        $errors[] = "Please select a category.";
    }

    if ($brand_id == "") {
        $errors[] = "Please select a brand.";
    }

    if ($partner_id == "") {
        $errors[] = "Please select a partner.";
    }


    $newImage = $oldImage;


    /* New Image */

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['image']['error'] != UPLOAD_ERR_OK) {

            $errors[] = "Image upload failed.";

        } else {

            $allowedTypes = [
                "image/jpeg",
                "image/png",
                "image/webp"
            ];

            $fileType = mime_content_type(
                $_FILES['image']['tmp_name']
            );


            if (!in_array($fileType, $allowedTypes)) {

                $errors[] = "Only JPG, PNG and WEBP images are allowed.";

            }


            if ($_FILES['image']['size'] > 2 * 1024 * 1024) {

                $errors[] = "Image size must be less than 2MB.";

            }


            if (empty($errors)) {

                $extension = strtolower(
                    pathinfo(
                        $_FILES['image']['name'],
                        PATHINFO_EXTENSION
                    )
                );


                $newImage = uniqid(
                    "product_",
                    true
                ) . "." . $extension;


                $uploadPath = "uploads/" . $newImage;


                if (!move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $uploadPath
                )) {

                    $errors[] = "Failed to save new image.";

                    $newImage = $oldImage;
                }
            }
        }
    }


    /* Update Database */

    if (empty($errors)) {

        $stmt = mysqli_prepare($con, "
            UPDATE products
            SET
                name = ?,
                description = ?,
                price = ?,
                quantity = ?,
                category_id = ?,
                brand_id = ?,
                partner_id = ?,
                image = ?
            WHERE id = ?
        ");


        mysqli_stmt_bind_param(
            $stmt,
            "ssiiiiisi",
            $name,
            $description,
            $price,
            $quantity,
            $category_id,
            $brand_id,
            $partner_id,
            $newImage,
            $id
        );


        if (mysqli_stmt_execute($stmt)) {

            if (
                $newImage != $oldImage &&
                $oldImage != "" &&
                file_exists("uploads/" . $oldImage)
            ) {

                unlink("uploads/" . $oldImage);
            }


            header(
                "Location: show_product.php?id=" . $id
            );

            exit;

        } else {

            if (
                $newImage != $oldImage &&
                file_exists("uploads/" . $newImage)
            ) {

                unlink("uploads/" . $newImage);
            }

            $errors[] = "Failed to update product.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Product</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="header">

        <h1>
            Edit Product
        </h1>

        <a href="products.php" class="btn btn-secondary">
            Back
        </a>

    </div>


    <div class="form-container">


        <?php if (!empty($errors)) { ?>

            <div class="error">

                <?php foreach ($errors as $error) { ?>

                    <div>
                        <?php echo htmlspecialchars($error); ?>
                    </div>

                <?php } ?>

            </div>

        <?php } ?>


        <form
            method="POST"
            enctype="multipart/form-data"
        >


            <div class="form-group">

                <label>
                    Product Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="<?php echo htmlspecialchars($name); ?>"
                >

            </div>


            <div class="form-group">

                <label>
                    Description
                </label>

                <textarea name="description"><?php echo htmlspecialchars($description); ?></textarea>

            </div>


            <div class="form-group">

                <label>
                    Price
                </label>

                <input
                    type="number"
                    name="price"
                    min="0"
                    value="<?php echo htmlspecialchars($price); ?>"
                >

            </div>


            <div class="form-group">

                <label>
                    Quantity
                </label>

                <input
                    type="number"
                    name="quantity"
                    min="0"
                    value="<?php echo htmlspecialchars($quantity); ?>"
                >

            </div>


            <div class="form-group">

                <label>
                    Category
                </label>

                <select name="category_id">

                    <?php while ($category = mysqli_fetch_assoc($categories)) { ?>

                        <option
                            value="<?php echo $category['id']; ?>"
                            <?php
                            if ($category_id == $category['id']) {
                                echo "selected";
                            }
                            ?>
                        >

                            <?php echo htmlspecialchars($category['name']); ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Brand
                </label>

                <select name="brand_id">

                    <?php while ($brand = mysqli_fetch_assoc($brands)) { ?>

                        <option
                            value="<?php echo $brand['id']; ?>"
                            <?php
                            if ($brand_id == $brand['id']) {
                                echo "selected";
                            }
                            ?>
                        >

                            <?php echo htmlspecialchars($brand['name']); ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Partner
                </label>

                <select name="partner_id">

                    <?php while ($partner = mysqli_fetch_assoc($partners)) { ?>

                        <option
                            value="<?php echo $partner['id']; ?>"
                            <?php
                            if ($partner_id == $partner['id']) {
                                echo "selected";
                            }
                            ?>
                        >

                            <?php echo htmlspecialchars($partner['name']); ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Current Image
                </label>

                <?php if (!empty($oldImage) && file_exists("uploads/" . $oldImage)) { ?>

                    <br>

                    <img
                        src="uploads/<?php echo htmlspecialchars($oldImage); ?>"
                        class="product-image"
                    >

                <?php } ?>

            </div>


            <div class="form-group">

                <label>
                    New Image
                </label>

                <input
                    type="file"
                    name="image"
                    accept="image/jpeg,image/png,image/webp"
                >

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Product
            </button>


        </form>

    </div>

</div>

</body>

</html>