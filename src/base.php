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
    <h1 class="h2">
      <!-- Page name goes here -->
      Page title
    </h1>
  </div>

  <!-- Optional sub-heading with toolbar -->
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h2 class="h3">
      <!-- Sub heading goes here -->
      Page subheading
    </h2>
    <!-- <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group mr-2">
        <form class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" />
          <button class="btn btn-primary my-2 my-sm-0" type="submit">
            Search
          </button>
        </form>
      </div>
      <div class="btn-group mr-2">
        <button type="button" class="btn btn-sm btn-success">
          Add
        </button>
      </div>
    </div> -->
  </div>

  <!-- Page content goes here -->


</main>

<!--     
      If a page-specific file is needed, uncomment and update file
      <script src="js/customScript.js" type="text/javascript"></script>
-->

<?php
    include("includes/footer.php");
?>