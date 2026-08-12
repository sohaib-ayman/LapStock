<?php
include("../shared/db.php");
$query = "SELECT * FROM brands";
$brands = mysqli_query($conn, $query);

$successMessage = "";
$errorMessage = "";
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    try {
        $deleteQuery = "DELETE FROM brands where id = $id";
        $deleteResult = mysqli_query($conn, $deleteQuery);
        if ($deleteResult) {
            header("location:index.php");
        } else {
            $errorMessage = "Brand Can't be Deleted";
        }
    } catch (Exception $e) {
        $errorMessage = "Brand Can't be Deleted" . $e->getMessage();
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
    <title>Brands</title>
</head>

<body>
    <?php include('../shared/nav.php') ?>
    <div class="container mt-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="../dashboard/home.php">Home</a></li>
                <li class="breadcrumb-item active">Inventory</li>
                <li class="breadcrumb-item active" aria-current="page"><span>Brands</span></li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <?php include('../shared/alert.php') ?>
            <div class="headerContent w-100 justify-content-between d-flex mb-3">
                <h1 class="title">Brands</h1>
                <a class="btn d-flex justify-content-center align-items-center gap-2" href="./create.php"><i
                        class="bi bi-plus"></i> Add Brand</a>
            </div>
            <div class="col-12 py-3">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body px-0 pb-0">
                        <div class="search-container ps-4">
                            <i class="bi bi-search"></i>
                            <input type="text" id="tableSearchInput" placeholder="Search brands...">
                        </div>

                        <div class="table-responsive">
                            <table class="filterable-table table-responsive" id="myFilterableTable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="ps-4">BRAND</th>
                                        <th scope="col">DESCRIPTION</th>
                                        <th scope="col">LAPTOPS</th>
                                        <th scope="col">CREATED</th>
                                        <th class="text-center" scope="col">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($brands as $brand) { ?>
                                        <?php
                                        $brandId = $brand['id'];
                                        $productQuery = "SELECT COUNT(*) AS total FROM products WHERE brand_id = $brandId";
                                        $productsResult = mysqli_query($conn, $productQuery);
                                        $products = mysqli_fetch_assoc($productsResult);
                                        $totalProducts = $products['total'];
                                        ?>
                                        <tr>
                                            <td class="name ps-4">
                                                <div class="d-flex justify-content-start align-items-center gap-2">
                                                    <div class="image <?php echo $brand['image'] ? 'image' : 'brandLogo' ?>">
                                                        <?php if ($brand['image']){ ?>
                                                            <img class="w-100" src="../uploads/brands/<?php echo $brand['image']; ?>"
                                                                alt="">
                                                        <?php }else{ ?>
                                                            <i class="bi bi-shop-window"></i>
                                                        <?php } ?>
                                                    </div>
                                                    <?php echo $brand['name'] ?>
                                                </div>
                                            </td>
                                            <td><?php echo $brand['description'] ?></td>
                                            <td>
                                                <div>
                                                    <span
                                                        class="<?php echo $totalProducts ? 'qty' : 'qtyZero' ?>"><?php echo $totalProducts; ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo $brand['created_at'] ?></td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="./edit.php?edit=<?php echo $brand['id'] ?>"
                                                        class="fs-6 me-3"><i class="bi bi-pencil"></i></a>
                                                    <a href="./index.php?delete=<?php echo $brand['id'] ?>" class="fs-6"><i
                                                            class="bi bi-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function enableTableFilter(inputEl, tableEl) {
            inputEl.addEventListener('keyup', () => {
                const query = inputEl.value.toLowerCase();
                const rows = tableEl.getElementsByTagName('tr');

                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const rowText = row.textContent || row.innerText;

                    if (rowText.toLowerCase().indexOf(query) > -1) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        }

        const searchInput = document.getElementById('tableSearchInput');
        const dataTable = document.getElementById('myFilterableTable');
        if (searchInput && dataTable) {
            enableTableFilter(searchInput, dataTable);
        }

    </script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>