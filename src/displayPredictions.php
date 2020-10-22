<?php
    include("includes/header.php");
    include("includes/nav.php");
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
      PREDICT
    </h1>
    <div class="btn-group mr-2">
      <input type="submit" class="btn btn-success" value="PREDICT" name="PREDICT" id="predict">
    </div>
  </div>

  
  <form method="GET" action="displayPredictions.php">
    <div class="row">
        <div class="col-sm">
          <label for="recorddatestart">Start Date </label><input class="form-control mr-sm-2" type="date" id="recorddatestart" name="recorddatestart" value="2020-09-04" required>
          <input type="radio" id="week" name="PERIOD" value="WEEK" checked="checked"><label for="week"> Week</label>
          <input type="radio" id="month" name="PERIOD" value="MONTH"><label for="month"> Month</label>
        </div>
        <div class="col">
            <input type="radio" class="form-check-input" id="category" name="WHICHDATA" value="CATEGORY"><label for="category">Catergory ID</label><input class="form-control mr-sm-2" type="text" id="CATEGORYID" placeholder="Category Name"></p>
        </div>
        <div class="col">
            <input type="radio" class="form-check-input" id="item" name="WHICHDATA" value="ITEM" checked="checked"><label for="item">Item ID </label><input class="form-control mr-sm-2" type="text" id="ITEMID" placeholder="Item Name">
        </div>
    </div>
    
    <div>
      <h4 id="reportTitle">Past sales</h2>
    </div>

    <div class="row table-responsive">
      <table id="pastSales" class="col-md-12 table table-striped table-sm col-md-12">
          <tbody>
              <tr><th>Date</th><th>Items sold</th><th>Total sales</th></tr>
          </tbody>
      </table>
    </div>

    <div>
      <h4 id="reportTitle">Predicted sales</h2>
    </div>

    <div class="row table-responsive">
      <table id="futureSales" class="col-md-12 table table-striped table-sm col-md-12">
          <tbody>
              <tr><th>Date</th><th>Total sales</th></tr>
          </tbody>
      </table>
    </div>

    <div id="chart">
      <canvas id="canvas"></canvas>
    </div>
</form> 
<!-- 
    <fieldset><legend>Customer Data</legend> -->
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
        echo "Example Date: " ;
        foreach ($test[2] as $a)
        {
          echo $a['SalesDate'];
          echo "<br>";
        }
    }
    ?>

</main>
<script src="js/predict-chart.js"></script>
<script src="js/predict.js"></script>
<?php
    include("includes/footer.php");
?>