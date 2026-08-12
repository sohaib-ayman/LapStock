<?php
include("../shared/db.php");

$successMessage = "";
$errorMessage = "";

if (isset($_GET["edit"])) {
    $id = $_GET["edit"];
    $query = "SELECT * from brands where id = $id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        $errorMessage = "Brand Fetch Failed: " . mysqli_error($conn);
    } else {
        $row = mysqli_num_rows($result);

        if ($row > 0) {
            $brand = mysqli_fetch_assoc($result);
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
            $errorMessage = "Brand Name should be at least 3 characters";
        } else if (strlen($name) > 20) {
            $errorMessage = "Brand Name should be at most 20 characters";
        } else {
            $checkQuery = "SELECT id FROM brands WHERE name = '$name' AND id != $id";
            $checkResult = mysqli_query($conn, $checkQuery);
            if (!$checkResult) {
                $errorMessage = "Brand Check Failed: " . mysqli_error($conn);
            } else {
                if (mysqli_num_rows($checkResult) > 0) {
                    $errorMessage = "Brand already exists";
                } else {
                    if ($image) {
                        $query = "UPDATE brands SET name = '$name', description = '$description', image = '$image' WHERE id = $id";
                    } else {
                        $query = "UPDATE brands SET name = '$name', description = '$description' WHERE id = $id";
                    }

                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        if ($image) {
                            $uploadResult = move_uploaded_file(
                                $_FILES["image"]["tmp_name"],
                                "../uploads/brands/" . $image
                            );
                            if (!$uploadResult) {
                                $errorMessage = "Brand Image Upload Failed";
                            } else {
                                if (!empty($brand['image'])) {
                                    unlink("../uploads/brands/" . $brand['image']);
                                }
                                header("location:index.php");
                                exit;
                            }
                        } else {
                            header("location:index.php");
                            exit;
                        }
                    } else {
                        $errorMessage = "Brand Update Failed: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Edit <?php echo $brand['name'] ?></title>
</head>

<body>
    <div class="container mt-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Inventory</li>
                <li class="breadcrumb-item"><a class="text-decoration-none" href="./index.php">Brands</a></li>
                <li class="breadcrumb-item active" aria-current="page"><span>Edit</span></li>
            </ol>
        </nav>
        <div class="row justify-content-start">
            <?php include('../shared/alert.php') ?>
            <div class="headerContent w-100 justify-content-between d-flex mb-3">
                <h1 class="title">Edit: <?php echo $brand['name'] ?></h1>
            </div>
            <div class="col-lg-5 col-md-8 col-12 py-3">
                <div class="card bg-light border-0 shadow-sm rounded-3">
                    <div class="card-header py-3">
                        Brand Details
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $id ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Brand Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="e.g. Lenovo"
                                    required value="<?php echo $brand['name'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="Brief description of this brand..."><?php echo $brand['description'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Brand Logo</label>
                                <input type="file" name="image" class="form-control" id="image">
                            </div>
                            <div class="d-flex justify-content-start gap-3 mt-4">
                                <button name="btn"
                                    class="btn px-3 py-2 d-flex justify-content-center align-items-center gap-2"
                                    type="submit"><i
                                        class="bi bi-check-lg d-flex justify-content-center align-items-center"></i>
                                    Update Brand</button>
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