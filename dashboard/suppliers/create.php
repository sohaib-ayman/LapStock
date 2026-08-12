<?php
include("../../shared/db.php");

$successMessage = "";
$errorMessage = "";
if (isset($_POST["btn"])) {
    $name = trim($_POST["name"]);
    $company = trim($_POST["company"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $notes = trim($_POST["notes"]);
    $status = trim($_POST["status"]);
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
            $errorMessage = "Supplier Name should be at least 3 characters";
        } else if (strlen($name) > 20) {
            $errorMessage = "Supplier Name should be at most 20 characters";
        } else {
            if ($status != "0" && $status != "1") {
                $errorMessage = "Invalid supplier status";
            } else {
                $checkQuery = "SELECT id FROM suppliers WHERE name = '$name'";
                $checkResult = mysqli_query($conn, $checkQuery);
                if (!$checkResult) {
                    $errorMessage = "Supplier Check Failed: " . mysqli_error($conn);
                } else {
                    if (mysqli_num_rows($checkResult) > 0) {
                        $errorMessage = "Supplier already exists";
                    } else {
                        $query = "INSERT INTO suppliers (name, company, email, phone, address, notes, image, status) VALUES('$name', '$company', '$email', '$phone', '$address', '$notes', '$image', '$status')";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            if ($image) {
                                $uploadResult = move_uploaded_file(
                                    $_FILES["image"]["tmp_name"],
                                    "../../uploads/suppliers/" . $image
                                );
                                if (!$uploadResult) {
                                    $errorMessage = "Supplier Image Upload Failed";
                                } else {
                                    $successMessage = "Supplier Added Successfully";
                                }
                            } else {
                                $successMessage = "Supplier Added Successfully";
                            }
                        } else {
                            $errorMessage = "Supplier Insertion Failed: " . mysqli_error($conn);
                        }
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
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Add Supplier</title>
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
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="./index.php">Suppliers</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>Add New</span></li>
                </ol>
            </nav>
            <div class="row justify-content-start">
                <?php include('../../shared/alert.php') ?>
                <div class="headerContent w-100 justify-content-between d-flex mb-3">
                    <h1 class="title">Add New Supplier</h1>
                </div>
                <div class="col-lg-5 col-md-8 col-12 py-3">
                    <div class="card bg-light border-0 shadow-sm rounded-3">
                        <div class="card-header py-3">
                            Supplier Details
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="inputsGroup mb-3 d-flex justify-content-between">
                                    <div>
                                        <label for="name" class="form-label">Supplier Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="e.g. Ahmed Al-Rashid" required>
                                    </div>
                                    <div>
                                        <label for="company" class="form-label">Company Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="company" class="form-control" id="company"
                                            placeholder="e.g. TechWorld Trading" required>
                                    </div>
                                </div>
                                <div class="inputsGroup mb-3 d-flex justify-content-between">
                                    <div>
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            placeholder="e.g. contact@company.com" required>
                                    </div>
                                    <div>
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" id="phone"
                                            placeholder="e.g. 01234567890">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" id="address"
                                        placeholder="Full Address...">
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"
                                        placeholder="Additional notes this supplier..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Supplier Avatar</label>
                                    <input type="file" name="image" class="form-control" id="image">
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="1" selected>Active </option>
                                        <option value="0">Inactive </option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-start gap-3 mt-4">
                                    <button name="btn"
                                        class="btn px-3 py-2 d-flex justify-content-center align-items-center gap-2"
                                        type="submit"><i
                                            class="bi bi-check-lg d-flex justify-content-center align-items-center"></i>
                                        Save Supplier</button>
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