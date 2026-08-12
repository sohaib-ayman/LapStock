<?php
include("../shared/db.php");

$successMessage = "";
$errorMessage = "";

if (isset($_GET["edit"])) {
    $id = $_GET["edit"];
    $query = "SELECT * from categories where id = $id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        $errorMessage = "Category Fetch Failed: " . mysqli_error($conn);
    } else {
        $row = mysqli_num_rows($result);

        if ($row > 0) {
            $category = mysqli_fetch_assoc($result);
        } else {
            header("location:index.php");
            exit;
        }
    }
}

if (isset($_POST["btn"])) {
    $id = $_POST["id"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    if (strlen($name) < 3) {
        $errorMessage = "Category Name should be at least 3 characters";
    } else if (strlen($name) > 20) {
        $errorMessage = "Category Name should be at most 20 characters";
    } else {
        $checkQuery = "SELECT id FROM categories WHERE name = '$name' AND id != $id";
        $checkResult = mysqli_query($conn, $checkQuery);
        if (!$checkResult) {
            $errorMessage = "Category Check Failed: " . mysqli_error($conn);
        } else {
            if (mysqli_num_rows($checkResult) > 0) {
                $errorMessage = "Category already exists";
            } else {
                $query = "UPDATE categories set name = '$name', description = '$description' where id = $id";
                $result = mysqli_query($conn, $query);
                if ($result) {
                    header("location:index.php");
                    exit;
                } else {
                    $errorMessage = "Category Update Failed: ";
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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Edit <?php echo $category['name'] ?></title>
</head>

<body>
    <div class="container mt-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Inventory</li>
                <li class="breadcrumb-item"><a class="text-decoration-none" href="./index.php">Categories</a></li>
                <li class="breadcrumb-item active" aria-current="page"><span>Edit</span></li>
            </ol>
        </nav>
        <div class="row justify-content-start">
            <?php include('../shared/alert.php') ?>
            <div class="headerContent w-100 justify-content-between d-flex mb-3">
                <h1 class="title">Edit: <?php echo $category['name'] ?></h1>
            </div>
            <div class="col-lg-5 col-md-8 col-12 py-3">
                <div class="card bg-light border-0 shadow-sm rounded-3">
                    <div class="card-header py-3">
                        Category Details
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $id ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="e.g. Gaming"
                                    required value="<?php echo $category['name'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="Brief description of this category..."><?php echo $category['description'] ?></textarea>
                            </div>
                            <div class="d-flex justify-content-start gap-3 mt-4">
                                <button name="btn"
                                    class="btn px-3 py-2 d-flex justify-content-center align-items-center gap-2"
                                    type="submit"><i
                                        class="bi bi-check-lg d-flex justify-content-center align-items-center"></i>
                                    Update Category</button>
                                <a class="btn px-3 py-2" href="./index.php">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>