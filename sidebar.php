<!-- sidebar.php -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

<!-- ===== Desktop Sidebar ===== -->
<aside class="sidebar d-none d-lg-block">
  <h2>PGSRS</h2>
  <ul>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'userdash.php' ? 'active' : ''; ?>">
      <a href="userdash.php">Dashboard</a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'submit_report.php' ? 'active' : ''; ?>">
      <a href="submit_report.php">Submit Report</a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'view_reports.php' ? 'active' : ''; ?>">
      <a href="view_reports.php">My Reports</a>
    </li>
    <li class="logout-btn">
      <a href="logout.php">Logout</a>
    </li>
  </ul>
</aside>

<!-- ===== Mobile Navbar ===== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top d-lg-none">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">PGSRS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mobileMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'userdash.php' ? 'active' : ''; ?>" href="userdash.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'submit_report.php' ? 'active' : ''; ?>" href="submit_report.php">Submit Report</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_reports.php' ? 'active' : ''; ?>" href="view_reports.php">My Reports</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
