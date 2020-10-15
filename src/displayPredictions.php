<?php
    include("includes/header.php");
    //include("includes/nav.php");
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
      Edit Sales Record
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group mr-2">
        <button type="button" class="btn btn-danger">
          DELETE
        </button>
      </div>
    </div>
  </div>
  
  <form method="GET" action="displayPredictions.php">
    <div class="form-group mb-2">
      <label for="recorddatestart">Record Start Date:</label>
      <input type="date" id="recorddatestart" name="recorddatestart" value="2020-09-04"> <!--This date is just temp, we can maybe as JS to se the current date to be the deffault-->
    </div>
    <div>
      <p>What do you want to display?</p>
      <input type="radio" id="item" name="WHICHDATA" value="ITEM" checked="checked">
      <label for="item">Item</label><br>
      <input type="radio" id="category" name="WHICHDATA" value="CATEGORY">
      <label for="category">Catergory</label><br>

      <p>What period do you want to display?</p>
      <input type="radio" id="week" name="PERIOD" value="WEEK" checked="checked">
      <label for="week">Week</label><br>
      <input type="radio" id="month" name="PERIOD" value="MONTH">
      <label for="month">Month</label><br>
    </div>
    <div class="form-group col-md-12 col-lg-4 d-flex justify-content-between">
      <a href="index.php" class="btn btn-secondary">
        HOMEPAGE
      </a>

      <input type="submit" class="btn btn-success" value="DISPLAY" name="DISPLAY">
    </div>  
</form> 

    <fieldset><legend>Customer Data</legend>
    <?php
    // if data has been submitted
    if (!empty($_GET["DISPLAY"]))
    {
        $test = include("backend_api/sales-predictor.php");
        echo "<br>";
        echo "slope: " . $test[0];
        echo "<br>";
        echo "intercept: " . $test[1];
        echo "<br>";
        echo "Example Date: " . $test[2][3]['SalesDate'];
    }

    ?>

</main>

<?php
    include("includes/footer.php");
?>