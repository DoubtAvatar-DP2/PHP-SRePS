<?php
    include("includes/header.php");
    include("includes/nav.php");
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
<div
          class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="btn-group mr-2">
        <a href="backend_api/export-file.php?file=records" class="btn btn-success">Export Records</a>
    </div>
    <div class="btn-group mr-2">
        <a href="backend_api/export-file.php?file=salesReport" class="btn btn-success">Export Sales Report</a>
    </div>
    <div class="btn-group mr-2">
        <a href="backend_api/export-file.php?file=summary" class="btn btn-success">Export Summary</a>
    </div>
    </div>
</main>

<?php
    include("includes/footer.php");
?>