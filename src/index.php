<?php
    include("includes/header.php");
    include("includes/nav.php");
?>


<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
        <div
          class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Dashboard</h1>
        </div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
          <h2 class="h3">Sales Records</h2>
          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
              <form class="form-inline my-2 my-lg-0">
                <label for="startDate">Start: </label>

                <input class="form-control mr-sm-2" type="date" name="startDate" id="startDate">
                <label for="endDate">End: </label>
                
                <input class="form-control mr-sm-2" type="date" name="endDate" id="endDate">
                <button class="btn btn-primary my-2 my-sm-0" type="submit">
                  Search
                </button>
              </form>
            </div>
            <div class="btn-group mr-2">
              <a href="add.php" class="btn btn-success">Add</a>
            </div>
            <div class="btn-group mr-2">
              <a href="backend_api/export-file.php?file=records" class="btn btn-success">Export</a>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-sm">
            <thead>
              <tr>
                <th><a id="record">Record #</a></th>
                <th><a id="date">Date</a></th>
                <th><a id="price">Total Price</a></th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
          </ul>
        </nav>
      </main>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js" integrity="sha512-Izh34nqeeR7/nwthfeE0SI3c8uhFSnqxV0sI9TvTcXiFJkMd6fB644O64BRq2P/LA/+7eRvCw4GmLsXksyTHBg==" crossorigin="anonymous"></script> 
      <script src="js/list.js" type="text/javascript"></script>
      <link rel="stylesheet" href="css/list.css">

<?php
    include("includes/footer.php");
?>