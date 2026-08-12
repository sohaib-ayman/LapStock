<?php

include "db.php";

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search != "") {

    $searchValue = "%" . $search . "%";

    $stmt = mysqli_prepare($con, "
        SELECT 
            products.*,
            categories.name AS category_name,
            brands.name AS brand_name,
            suppliers.name AS supplier_name
        FROM products
        LEFT JOIN categories 
            ON products.category_id = categories.id
        LEFT JOIN brands 
            ON products.brand_id = brands.id
        LEFT JOIN suppliers 
            ON products.supplier_id = suppliers.id
        WHERE products.name LIKE ?
        OR products.description LIKE ?
        ORDER BY products.id DESC
    ");

    mysqli_stmt_bind_param($stmt, "ss", $searchValue, $searchValue);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

} else {

    $sql = "
        SELECT 
            products.*,
            categories.name AS category_name,
            brands.name AS brand_name,
            suppliers.name AS supplier_name
        FROM products
        LEFT JOIN categories 
            ON products.category_id = categories.id
        LEFT JOIN brands 
            ON products.brand_id = brands.id
        LEFT JOIN suppliers 
            ON products.supplier_id = suppliers.id
        ORDER BY products.id DESC
    ";

    $result = mysqli_query($con, $sql);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="header">

        <h1>Products</h1>

        <a href="create_product.php" class="btn btn-primary">
            + Add Product
        </a>

    </div>


    <div class="search-box">

        <form method="GET">

            <input
                type="text"
                name="search"
                placeholder="Search product..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit">
                Search
            </button>

            <a href="products.php" class="btn btn-secondary">
                Reset
            </a>

        </form>

    </div>


    <div class="table-container">

        <table>

            <tr>

                <th>ID</th>

                <th>Image</th>

                <th>Name</th>

                <th>Price</th>

                <th>Quantity</th>

                <th>Category</th>

                <th>Brand</th>

                <th>Supplier</th>

                <th>Actions</th>

            </tr>


            <?php if (mysqli_num_rows($result) > 0) { ?>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>

                        <td>
                            <?php echo $row['id']; ?>
                        </td>


                        <td>

                            <?php if (!empty($row['image']) && file_exists("uploads/" . $row['image'])) { ?>

                                <img
                                    src="uploads/<?php echo htmlspecialchars($row['image']); ?>"
                                    class="product-image"
                                >

                            <?php } else { ?>

                                <div class="no-image">
                                    No Image
                                </div>

                            <?php } ?>

                        </td>


                        <td>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>


                        <td>
                            <?php echo $row['price']; ?>
                        </td>


                        <td>
                            <?php echo $row['quantity']; ?>
                        </td>


                        <td>
                            <?php echo htmlspecialchars($row['category_name'] ?? ''); ?>
                        </td>


                        <td>
                            <?php echo htmlspecialchars($row['brand_name'] ?? ''); ?>
                        </td>


                        <td>
                            <?php echo htmlspecialchars($row['supplier_name'] ?? ''); ?>
                        </td>


                        <td>

                            <div class="actions">

                                <a
                                    href="show_product.php?id=<?php echo $row['id']; ?>"
                                    class="btn btn-success"
                                >
                                    Show
                                </a>


                                <a
                                    href="edit_product.php?id=<?php echo $row['id']; ?>"
                                    class="btn btn-warning"
                                >
                                    Edit
                                </a>


                                <a
                                    href="delete_product.php?id=<?php echo $row['id']; ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this product?');"
                                >
                                    Delete
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>

                    <td colspan="9">
                        No Products Found
                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>