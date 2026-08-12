<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$loggedInUser = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin User';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-2 dashboard-navbar fixed-top">
    <div class="container-fluid p-0">
        <button class="sidebar-toggle me-3" id="sidebarToggle" type="button"><i class="bi bi-list"></i></button>
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/LapStock/dashboard/home.php">
            <span>LapStock</span>
        </a>
        <div class="d-flex align-items-center ms-auto gap-3">
            <div class="dropdown">
                <button class="btn btn-light bg-transparent border-0 dropdown-toggle d-flex align-items-center gap-2 fw-medium text-dark user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">
                        <?php echo strtoupper(substr($loggedInUser, 0, 1)); ?>
                    </div>
                    <span class="d-none d-sm-inline"><?php echo htmlspecialchars($loggedInUser); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2 rounded-3 mt-2" style="min-width: 200px;">
                    <li class="px-3 py-2 border-bottom mb-1">
                        <div class="fw-bold text-dark small"><?php echo htmlspecialchars($loggedInUser); ?></div>
                        <small class="text-muted" style="font-size: 0.75rem;"><?php echo htmlspecialchars($loggedInUser); ?></small>
                    </li>
                    <li>
                        <a class="dropdown-item text-danger d-flex align-items-center gap-2 py-2 rounded-2 mt-1" href="/LapStock/shared/logout.php">
                            <i class="bi bi-box-arrow-right fs-6"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>