<?php
include("../../shared/db.php");
$categoriesQuery = "SELECT * FROM categories";
$categories = mysqli_query($conn, $categoriesQuery);
$suppliersQuery = "SELECT * FROM suppliers";
$suppliers = mysqli_query($conn, $suppliersQuery);
$brandsQuery = "SELECT * FROM brands";
$brands = mysqli_query($conn, $brandsQuery);

$successMessage = "";
$errorMessage = "";
if (isset($_POST["btn"])) {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = trim($_POST["price"]);
    $quantity = trim($_POST["quantity"]);
    $category_id = trim($_POST["category_id"]);
    $brand_id = trim($_POST["brand_id"]);
    $supplier_id = trim($_POST["supplier_id"]);
    $image = null;

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowedExtensions = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($extension, $allowedExtensions)) {
            $errorMessage = "Invalid image format";
        } else {
            $image = time() . "." . $extension;
        }
    }

    if ($errorMessage == "") {
        if (strlen($name) < 3) {
            $errorMessage = "Product Name should be at least 3 characters";
        } else if (strlen($name) > 20) {
            $errorMessage = "Product Name should be at most 20 characters";
        } else {
            $checkQuery = "SELECT id FROM products WHERE name = '$name'";
            $checkResult = mysqli_query($conn, $checkQuery);
            if (!$checkResult) {
                $errorMessage = "Product Check Failed: " . mysqli_error($conn);
            } else {
                if (mysqli_num_rows($checkResult) > 0) {
                    $errorMessage = "Product already exists";
                } else {
                    $query = "INSERT INTO products (name, description, price, quantity, category_id, brand_id, supplier_id, image) VALUES('$name', '$description', '$price', '$quantity', '$category_id', '$brand_id', '$supplier_id', '$image')";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        if ($image) {
                            $uploadResult = move_uploaded_file(
                                $_FILES["image"]["tmp_name"],
                                "../../uploads/products/" . $image
                            );
                            if (!$uploadResult) {
                                $errorMessage = "Product Image Upload Failed";
                            } else {
                                $successMessage = "Product Added Successfully";
                            }
                        } else {
                            $successMessage = "Product Added Successfully";
                        }
                    } else {
                        $errorMessage = "Product Insertion Failed: " . mysqli_error($conn);
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Product</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body>
    <?php include('../../shared/nav.php') ?>
        <?php include '../../shared/sidepar.php'; ?>
    <div class="main-content">
        <div class="container mt-4">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="../home.php">Home</a></li>
                    <li class="breadcrumb-item active">Inventory</li>
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="./index.php">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>Add New</span></li>
                </ol>
            </nav>
            <div class="row justify-content-start">
                <?php include('../../shared/alert.php') ?>
                <div class="headerContent w-100 justify-content-between d-flex mb-3">
                    <h1 class="title">Add New Product</h1>
                </div>
                <div class="col-lg-5 col-md-8 col-12 py-3">
                    <div class="card bg-light border-0 shadow-sm rounded-3">
                        <div class="card-header py-3">
                            Product Details
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="inputsGroup mb-3 d-flex justify-content-between">
                                    <div>
                                        <label for="name" class="form-label">Product Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="e.g. Samsung Galaxy Book4" required>
                                    </div>
                                    <div>
                                        <label for="price" class="form-label">Price <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="price" class="form-control" id="price"
                                            placeholder="42000" required>
                                    </div>
                                </div>
                                <div class="inputsGroup mb-3 d-flex justify-content-between">
                                    <div>
                                        <label for="quantity" class="form-label">Quantity <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="quantity" class="form-control" id="quantity"
                                            placeholder="e.g. 10" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        placeholder="Brief description of this product..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image Photo</label>
                                    <input type="file" name="image" class="form-control" id="image">
                                </div>
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select name="category_id" class="form-control" id="category_id">
                                        <option value="-1" selected>Select Category</option>
                                        <?php foreach ($categories as $category) { ?>
                                            <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="supplier_id" class="form-label">Supplier</label>
                                    <select name="supplier_id" class="form-control" id="supplier_id">
                                        <option value="-1" selected>Select Supplier</option>
                                        <?php foreach ($suppliers as $supplier) { ?>
                                            <option value="<?php echo $supplier['id'] ?>"><?php echo $supplier['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select name="brand_id" class="form-control" id="brand_id">
                                        <option value="-1" selected>Select Brand</option>
                                        <?php foreach ($brands as $brand) { ?>
                                            <option value="<?php echo $brand['id'] ?>"><?php echo $brand['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-start gap-3 mt-4">
                                    <button name="btn"
                                        class="btn px-3 py-2 d-flex justify-content-center align-items-center gap-2"
                                        type="submit"><i
                                            class="bi bi-check-lg d-flex justify-content-center align-items-center"></i>
                                        Save Product</button>
                                    <a class="btn px-3 py-2" href="./index.php">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth <= 767.98) {
                document.body.classList.toggle('sidebar-open');
            } else {
                document.body.classList.toggle('sidebar-closed');
            }
        });
        document.addEventListener('click', function (event) {
            if (window.innerWidth > 767.98) {
                return;
            }
            if (!document.body.classList.contains('sidebar-open')) {
                return;
            }
            const sidebar = document.getElementById('sidebar');
            if (
                !sidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target)
            ) {
                document.body.classList.remove('sidebar-open');
            }
        });
        window.addEventListener('resize', function () {

            if (window.innerWidth > 767.98) {
                document.body.classList.remove('sidebar-open');
            }

        });
    </script>
</body>

</html>