<?php
    include("includes/header.php");
    //include("includes/nav.php");
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
      Prediction
    </h1>
  </div>
  
  <form method="GET" action="displayPredictions.php">
    <div class="form-group mb-2">
      <label for="recorddatestart">Record Start Date:</label>
      <input type="date" id="recorddatestart" name="recorddatestart" placeholder="Please enter the record date">
    </div>

    <div>
      <p>What do you want to display?</p>
      <input type="radio" id="item" name="WHICHDATA" value="ITEM" checked="checked">
      <label for="item">Item-</label><input type="text" id="ItemName" placeholder="Item Name"><br>
      <input type="radio" id="category" name="WHICHDATA" value="CATEGORY">
      <label for="category">Catergory-</label><input type="text" id="CategoryName" placeholder="Category Name"><br>

      <p>What period do you want to display?</p>
      <input type="radio" id="week" name="PERIOD" value="WEEK" checked="checked">
      <label for="week">Week</label><br>
      <input type="radio" id="month" name="PERIOD" value="MONTH">
      <label for="month">Month</label><br>
    </div>
    <div class="form-group col-md-12 col-lg-4 d-flex justify-content-between">
      <input type="submit" class="btn btn-success" value="DISPLAY" name="DISPLAY">
    </div>
    
    <div class="row">
      <table id="productEntries" class="col-md-12">
          <tbody>
              <tr><th>#</th><th>Date</th><th>Items sold</th><th>Total sales</th></tr>
          </tbody>
      </table>
    </div>
    <div class="row">
      <b id="total">Total </b>
    </div>

    <div class="row">
      <p id="note"></p>
    </div>
    <div class="row">
      <a href="index.php" class="btn btn-secondary">
        HOMEPAGE
      </a>
    </div>
</form> 

    <fieldset><legend>Customer Data</legend>
    <?php
    // if data has been submitted
    if (!empty($_GET["DISPLAY"]))
    {
        include("backend_api/sales-predictor.php");
    }

    ?>

</main>

<?php
    include("includes/footer.php");
?>