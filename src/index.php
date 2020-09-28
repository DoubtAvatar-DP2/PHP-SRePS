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
                <th>Record #</th>
                <th>Date</th>
                <th>Total Price</th>
              </tr>
            </thead>
            <tbody>
              <?php
              /**
               * TODO:
               *  Loop through all sales record results
               */
              ?>
              <!-- <tr>
                <td>1001</td>
                <td>18-09-2020</td>
                <td>$55.60</td>
              </tr>
              <tr>
                <td>1002</td>
                <td>18-09-2020</td>
                <td>$132.00</td>
              </tr>
              <tr>
                <td>1003</td>
                <td>19-09-2020</td>
                <td>$99.05</td>
              </tr> -->
            </tbody>
          </table>
        </div>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <li class="page-item">
              <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <?php
              /**
               * TODO:
               *  Implement current page highlighting (page-item active)
               *  Implement accurate page numbering
               *  Implement pagination links
               *  Extension: Implement custom page sizes
               */
            ?>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
      </main>

    
      <script src="js/list.js" type="text/javascript"></script>

<?php
    include("includes/footer.php");
?>