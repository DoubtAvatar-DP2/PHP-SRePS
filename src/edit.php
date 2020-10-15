<?php
    include("includes/header.php");
    include("includes/nav.php");
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
      Edit Sales Record
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group mr-2">
        <button type="button" id="delete" class="btn btn-danger">
          DELETE
        </button>
      </div>
    </div>
  </div>

  
  <form id="addrecordform" autocomplete="off">
    <div class="form-group mb-2">
      <label for="recorddate">Record Date:</label>
      <input type="date" id="recorddate" name="recorddate" placeholder="Please enter the record date" required>
    </div>
    <div class="form-group">
      <table id="productEntries">
          <tbody>
              <tr><th>#</th><th>Product Name</th><th>Quantity</th><th>Quoted Price</th><th>Total Price</th></tr>
          </tbody>
      </table>
      <b id=total>Total $0</b><br>
    </div>
    <div class="form-group col-md-12 col-lg-4">
      <label for="note">Note</label><br>
      <input type="text" id="note" name="note" placeholder="Enter any comment"><br>
    </div>
    <div class="form-group col-md-12 col-lg-4 d-flex justify-content-between">
      <a href="index.php" class="btn btn-secondary">
        HOMEPAGE
      </a>
      <input type="submit" id="update" class="btn btn-success" value="UPDATE">
    </div>
</form> 




</main>

<!-- Custom script to add/remove new entries to the form -->
<script src=" js/recordForm.js"></script>
<script src=" js/edit.js"></script>
<?php
    include("includes/footer.php");
?>