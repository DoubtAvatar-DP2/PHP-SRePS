<?php
    include("includes/header.php");
    include("includes/nav.php");
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
      Viewing Sales Record
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group mr-2">
        <a class="btn btn-info" id="editLink">
          EDIT
        </a>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="row">
      <p id="recorddate">Record Date: </p>
    </div>
    <div class="row">
      <table id="productEntries" class="col-md-12">
          <tbody>
              <tr><th>#</th><th>Product Name</th><th>Quantity</th><th>Quoted Price</th><th>Total Price</th></tr>
          </tbody>
      </table>
    </div>
    <div class="row">
      <b id="total">Total </b>
    </div>
    <div class="row">
      <p>Note:</p>
    </div>
    <div class="row">
      <p id="note"></p>
    </div>
    <div class="row">
      <a href="index.php" class="btn btn-secondary">
        HOMEPAGE
      </a>
    </div>
  </div>

<script src="js/view.js"></script>

</main>
<?php
    include("includes/footer.php");
?>