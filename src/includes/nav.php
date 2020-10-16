<?php
  /**
   * TODO:
   *  Uncomment and update remaining nav files names when created
   *  Consider solution for highlighting "Records" for Display, Add, Edit, Delete, etc.
   */

  $currentPage = $_SERVER["SCRIPT_NAME"];

?>
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
        <div class="sidebar-sticky">
          <a class="navbar-brand" href="index.php"> PHP-SRePS </a>
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link <?php echo strpos($currentPage, "index.php") ? "active" : "" ?>" href="#"> Records </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo strpos($currentPage, "sales-predictor.php") ? "active" : "" ?>" href="#"> Prediction </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php /* echo strpos($currentPage, "") ? "active" : "" */ ?>" href="#"> Sales Report </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo strpos($currentPage, "") ? "active" : "export.php" ?>" href="#"> Export </a>
            </li>
          </ul>
          <hr />
        </div>
      </nav>
