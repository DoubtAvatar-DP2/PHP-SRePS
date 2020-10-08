<?php
    include("includes/header.php");
    include("includes/nav.php");

    /**
     * TODO:
     *  Query DB for all sales records
     *  Implement pagination with query (e.g. ?page=5 should skip the first 5 pages of results) - Consider limiting pages to 25 records per page?
     */
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
                <?php
                  /**
                   * TODO:
                   *  Implement search
                   */
                ?>
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" />
                <button class="btn btn-primary my-2 my-sm-0" type="submit">
                  Search
                </button>
              </form>
            </div>
            <div class="btn-group mr-2">
              <a href="add.php" class="btn btn-success">Add</a>
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

    
      <script src="js/list.js" type="text/javascript"></script>
      <link rel="stylesheet" href="css/list.css">

<?php
    include("includes/footer.php");
?>