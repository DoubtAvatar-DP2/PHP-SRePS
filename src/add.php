<?php
    include("includes/header.php");
    include("includes/nav.php");
?>
<main role="main" class="col-md-9 offset-lg-2 offset-md-3 col-lg-10 px-md-4">
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
      New Sales Record
    </h1>
  </div>
  
  <div id="error"></div>
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
      <input type="submit" id="add-record-button" class="btn btn-success" value="CREATE">
    </div>
</form>

</main>

<!-- Custom script to add/remove new entries to the form -->
<script src=" js/recordForm.js"></script>

<?php
    include("includes/footer.php");
?>