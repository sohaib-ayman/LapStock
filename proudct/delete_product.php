<?php

include "db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: products.php");
    exit;
}


/* Get image before delete */

$stmt = mysqli_prepare(
    $con,
    "SELECT image FROM products WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$product = mysqli_fetch_assoc($result);


if (!$product) {

    header("Location: products.php");
    exit;
}


/* Delete */

$stmt = mysqli_prepare(
    $con,
    "DELETE FROM products WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);


if (mysqli_stmt_execute($stmt)) {

    if (
        !empty($product['image']) &&
        file_exists("uploads/" . $product['image'])
    ) {

        unlink("uploads/" . $product['image']);
    }
}


header("Location: products.php");

exit;

?>