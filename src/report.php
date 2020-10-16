<?php
    include("includes/header.php");
    include("includes/nav.php");
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
      Report
    </h1>
    <div class="btn-group mr-2">
    <input type="submit" class="btn btn-success" value="GENERATE" name="GENERATE" id="report">
    </div>
  </div>

  <div id="error"></div>

  <form method="GET" action="report.php">
    <div class="form-group mb-2">
        <div class="row">
            <div class="col-sm">
                <label for="recorddatestart">Start Date </label><input class="form-control mr-sm-2" type="date" id="recorddatestart" name="recorddatestart" value="2020-09-04" required>
            </div>

            <div class="col">
            <label for="recorddateend">End Date </label><input class="form-control mr-sm-2" type="date" id="recorddateend" name="recorddateend" value="2020-10-16" required>
            </div>
            <div class="col">
                <input type="radio" class="form-check-input" id="category" name="WHICHDATA" value="CATEGORY"><label for="category">Catergory </label><input class="form-control mr-sm-2" type="text" id="CATEGORYID" placeholder="Category Name"></p>
            </div>

        <div class="col">
            <input type="radio" class="form-check-input" id="item" name="WHICHDATA" value="ITEM" checked="checked"><label for="item">Item </label><input class="form-control mr-sm-2" type="text" id="ITEMID" placeholder="Item Name">
        </div>
    </div>
</form> 
<div>
  <h4 id="reportTitle"></h2>
</div>
<div class="row table-responsive">
      <table id="reportTable" class="table table-striped table-sm col-md-12" >
          <tbody>
              <tr><th>Date</th><th>Items sold</th><th>Total sales</th></tr>
          </tbody>
      </table>
    </div>


    <div class="row">
      <p id="note"></p>
    </div>

    <div id="chart">
      <canvas id="canvas"></canvas>
    </div>

</main>

<script src="js/report-chart.js"></script>
<script src="js/report.js"></script>


<link rel="stylesheet" href="css/list.css">
<?php
    include("includes/footer.php");
?>