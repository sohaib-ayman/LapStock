<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$loggedInUser = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin User';
$currentPath = $_SERVER['PHP_SELF'];
?>

<aside class="sidebar" id="sidebar">

  <div class="sidebar-logo">

    <div class="logo-icon">
      <i class="bi bi-laptop"></i>
    </div>

    <div>
      <div class="logo-title">Laptop</div>
      <div class="logo-subtitle">Inventory System</div>
    </div>

  </div>

  <ul class="sidebar-nav">

    <li>
      <a href="/LapStock/dashboard/home.php" class="nav-link <?php echo strpos($currentPath, '/dashboard/home.php') !== false ? 'active' : ''; ?>">
        <i class="bi bi-grid me-2"></i>
        Dashboard
      </a>
    </li>

    <li class="section-label">
      INVENTORY
    </li>

    <li>
      <a href="/LapStock/dashboard/products/index.php" class="nav-link <?php echo strpos($currentPath, '/products/') !== false ? 'active' : ''; ?>">
        <i class="bi bi-laptop me-2"></i>
        Products
      </a>
    </li>

    <li>
      <a href="/LapStock/dashboard/categories/index.php" class="nav-link <?php echo strpos($currentPath, '/categories/') !== false ? 'active' : ''; ?>">
        <i class="bi bi-tag me-2"></i>
        Categories
      </a>
    </li>

    <li>
      <a href="/LapStock/dashboard/brands/index.php" class="nav-link <?php echo strpos($currentPath, '/brands/') !== false ? 'active' : ''; ?>">
        <i class="bi bi-building me-2"></i>
        Brands
      </a>
    </li>

    <li>
      <a href="/LapStock/dashboard/suppliers/index.php" class="nav-link <?php echo strpos($currentPath, '/suppliers/') !== false ? 'active' : ''; ?>">
        <i class="bi bi-people me-2"></i>
        Suppliers
      </a>
    </li>

  </ul>

  <div class="sidebar-user">

    <div class="avatar">
      <?php echo strtoupper(substr($loggedInUser, 0, 1)); ?>
    </div>

    <div>
      <div class="user-name">
        <?php echo htmlspecialchars($loggedInUser); ?>
      </div>

      <div class="user-email">
        <?php echo htmlspecialchars($loggedInUser); ?>
      </div>
    </div>

  </div>

</aside>