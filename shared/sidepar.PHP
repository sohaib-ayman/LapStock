
<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laptop Inventory System</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<style>
  body { background:#0b0f1a; }
  .sidebar {
    width: 260px;
    min-height: 100vh;
    background:#0f1526;
    color:#8b93a7;
  }
  .sidebar .nav-link { color:#8b93a7; border-radius:.5rem; }
  .sidebar .nav-link:hover { background:#1a2136; color:#fff; }
  .sidebar .nav-link.active { background:#2563eb; color:#fff; }
  .sidebar .section-label { font-size:.75rem; letter-spacing:.05em; color:#5b6478; }
  .logo-icon { width:40px; height:40px; background:#2563eb; border-radius:.6rem; }
  .avatar { width:36px; height:36px; background:#2563eb; }
</style>
</head>
<body>

<div class="d-flex flex-column sidebar p-3">

  <!-- Logo -->
  <div class="d-flex align-items-center gap-2 pb-3 border-bottom border-secondary-subtle">
    <div class="logo-icon d-flex align-items-center justify-content-center">
      <i class="fa-solid fa-laptop text-white"></i>
    </div>
    <div>
      <div class="fw-bold text-white">Laptop</div>
      <div class="small" style="color:#5b6478;">Inventory System</div>
    </div>
  </div>

  <!-- Nav -->
  <ul class="nav nav-pills flex-column mt-3 gap-1">
    <li class="nav-item">
      <a class="nav-link active d-flex align-items-center justify-content-between" href="#">
        <span><i class="fa-solid fa-table-cells-large me-2"></i>Dashboard</span>
        <span class="badge rounded-circle" style="width:8px;height:8px;background:#fff;"></span>
      </a>
    </li>

    <li class="section-label text-uppercase mt-3 mb-1 px-2">Inventory</li>

    <li class="nav-item">
      <a class="nav-link" href="#"><i class="fa-solid fa-laptop me-2"></i>Laptops</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#"><i class="fa-solid fa-tag me-2"></i>Categories</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#"><i class="fa-solid fa-building me-2"></i>Brands</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#"><i class="fa-solid fa-users me-2"></i>Suppliers</a>
    </li>
  </ul>

  <!-- Push footer down -->
  <div class="mt-auto pt-3 border-top border-secondary-subtle d-flex align-items-center gap-2">
    <div class="avatar rounded-circle d-flex align-items-center justify-content-center text-white fw-bold small">
      AK
    </div>
    <div>
      <div class="text-white small fw-semibold">Admin User</div>
      <div class="small" style="color:#5b6478;">admin@inventory.sa</div>
    </div>
  </div>

</div>

</body>
</html>