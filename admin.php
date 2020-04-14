<!DOCTYPE html>
<?php
// check form was submitted
if (isset($_POST["uploadSubmit"])) {
    // MySQL includes
    require "includes/create_required.php";
    require "includes/delete_term.php";
    require "includes/drop_temp.php";
    require "includes/upload_data.php";
    require "includes/upload_temp.php";

    // utility includes
    require "includes/convert_term.php";
    require "includes/execute_query.php";
    require "includes/get_file.php";

    // display minimal error messages
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        // get file
        $targetFilePath = get_file(basename($_FILES["fileSelect"]["name"]));

        // establish connection
        $conn = new mysqli(
            $_POST["databaseHost"],
            $_POST["databaseUsername"],
            $_POST["databasePassword"],
            $_POST["databaseName"]
        );
        $conn->autocommit(false);
        $conn->set_charset("utf8mb4");
        $conn->options(MYSQLI_OPT_LOCAL_INFILE, true);

        // ensure required tables exist
        create_required($conn);

        // convert selected term to start and end months
        $term = convert_term($_POST["termSelect"]);
        $strtMonth = $term[0];
        $endMonth = $term[1];

        // delete listings for selected term from database
        delete_term($conn, $strtMonth, $endMonth);

        // upload file to temp table
        upload_temp(
            $conn,
            $targetFilePath,
            $strtMonth,
            $endMonth
        );

        // upload data from temp table to database
        upload_data($conn);

        // drop temp table
        drop_temp($conn);

        // commit transaction
        $conn->commit();
        $conn->autocommit(true);

        // success alert
        $successAlert = "Course catalog updated.";
    }

    // error alert
    catch (Exception $e) {
        $errorAlert = $e->getMessage();
    }
}
?>

<html>

  <head>
    <!-- Title -->
    <title>Catalog Upload</title>

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap, Font Awesome, and custom CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />

    <style>
        /*-- Alert --*/
        .alert {
          margin-top: 3em;
        }

        /*-- Media Queries --*/
        @media screen and (max-width: 992px) {
          .display-4 {
            font-size: 240%;
          }

          .navbar-nav a {
            font-size: 1em;
          }

          .social a {
            padding-left: 0.4em;
            padding-right: 0em;
            font-size: 1.4em;
          }
        }

        @media screen and (max-width: 768px) {
          body {
            font-size: 0.8750em;
          }

          .navbar-collapse.in {
            display: block !important;
          }

          .navbar-nav a {
            margin-right: 1.5em;
          }

          .welcome {
            width: 85%;
          }

          .display-4 {
            font-size: 190%;
          }

          h3 {
            font-size: 180%;
          }

          .form-check-inline {
            padding-bottom: 0.8rem;
          }
        }

        @media screen and (max-width: 567px) {
          .navbar-collapse.in {
            display: block !important;
          }

          .welcome {
            width: 95%;
          }

          .display-4 {
            font-size: 140%;
          }

          h3 {
            font-size: 120%;
          }

          .form-check-inline {
            font-size: 95%;
            margin-right: 0.1em;
          }
        }
    </style>
  </head>

  <body data-spy="scroll" data-target=".navbar" data-offset="50">
    <!-- Navbar -->
    <div class="container-fluid padding">
      <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a href="https://www.lindenwood.edu" class="navbar-brand"><img src="img/logo.png" /> Lindenwood University</a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <div class="navbar-nav flex-row">
            <a href="#credentials" class="nav-item nav-link">Credentials</a>
            <a href="#catalog" class="nav-item nav-link">Catalog</a>
          </div>
          <div class="navbar-nav ml-auto flex-row social">
            <a href="https://www.twitter.com/lindenwoodu"><i class="fab fa-twitter"></i></a>
            <a href="https://www.instagram.com/LindenwoodUniversity"><i class="fab fa-instagram"></i></a>
            <a href="https://www.facebook.com/LindenwoodUniversity"><i class="fab fa-facebook"></i></a>
            <a href="https://www.youtube.com/LindenwoodU"><i class="fab fa-youtube"></i></a>
            <a href="https://www.linkedin.com/school/lindenwooduniversity"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>
      </nav>
    </div>

    <!-- Server alerts -->
    <?php
    if (isset($successAlert)) {
        echo '<div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> ' . $successAlert . '</div>';
    }
    if (isset($errorAlert)) {
        echo '<div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> ' . $errorAlert . '</div>';
    }
    ?>

    <!-- Welcome banner -->
    <div class="container-fluid padding">
      <div class="row welcome text-center">
        <div class="col-12">
          <h1 class="display-4">Success Scheduler</h1>
        </div>
        <hr />
        <div class="col-12">
          <h5 class="display-4">Real Experience. Real Success.</h5>
        </div>
      </div>
      <hr class="my-5" id="credentials" />
    </div>

    <!-- Credentials -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
      <div class="container-fluid padding">
        <h3>Database Credentials</h3>
        <div class="form-group form-row">
          <div class="col-sm-6 col-md-3">
            <label class="col-form-label" for="databaseHost">Host Name</label>
            <input class="form-control" type="text" id="databaseHost" name="databaseHost" placeholder="Enter host name" />
          </div>
          <div class="col-sm-6 col-md-3">
            <label class="col-form-label" for="databaseName">Database Name</label>
            <input class="form-control" type="text" id="databaseName" name="databaseName" placeholder="Enter database name" />
          </div>
          <div class="col-sm-6 col-md-3">
            <label class="col-form-label" for="databaseUsername">Username</label>
            <input class="form-control" type="text" id="databaseUsername" name="databaseUsername" placeholder="Enter username" />
          </div>
          <div class="col-sm-6 col-md-3">
            <label class="col-form-label" for="databasePassword">Password</label>
            <input class="form-control" type="password" id="databasePassword" name="databasePassword" placeholder="Enter password" />
          </div>
        </div>
        <hr class="my-5" id="catalog" />
      </div>

      <!-- Catalog upload -->
      <div class="container-fluid padding">
        <h3>Catalog Selection</h3>
        <div class="input-group form-group form-row">
          <div class="col-md-6">
            <label class="col-form-label" for="termSelect">Term</label>
            <select class="form-control" id="termSelect" name="termSelect" aria-describedby="termHelp">
              <option value="fall" selected>Fall</option>
              <option value="spring">Spring</option>
              <option value="summer">Summer</option>
            </select>
            <small id="termHelp" class="form-text text-muted">Current data for the selected term will be completely overwritten.</small>
          </div>
          <div class="col-md-6">
            <label class="col-form-label" for="fileSelect">Catalog</label>
            <input class="form-control-file" type="file" id="fileSelect" name="fileSelect" aria-describedby="fileHelp" />
            <small id="fileHelp" class="form-text text-muted">Only CSV files are supported.</small>
          </div>
        </div>
        <hr class="my-5" />
      </div>

      <!-- Catalog uplaod submit -->
      <div class="container-fluid padding">
        <div class="form-group form-row">
          <div class="col-12">
            <input class="form-control btn btn-primary btn-block" type="submit" id="uploadSubmit" name="uploadSubmit" value="Upload Course Catalog" />
          </div>
        </div>
      </div>
    </form>

    <!-- Footer -->
    <footer>
      <div class="container-fluid padding">
        <div class="row text-center">
          <div class="col-md-6 footer-style">
            <hr class="light" />
            <h5>Contact Us</h5>
            <hr class="light" />
            <p>(636) 949-2000</p>
            <p>209 S. Kingshighway</p>
            <p>St. Charles, MO 63301</p>
          </div>
          <div class="col-md-6 footer-style">
            <hr class="light" />
            <h5>Other Resources</h5>
            <hr class="light" />
            <p><a href="https://www.lindenwood.edu/about/directories/">Directory</a></p>
            <p><a href="https://lindenwood.bncollege.com/shop/lindenwood/home">Bookstore</a></p>
            <p><a href="https://www.lindenwood.edu/about/our-campus/campus-map/">Campus Map</a></p>
          </div>
          <div class="col-12">
            <hr class="light" />
            <p class="blockquote-footer">Justin Stoker, James Akins, Paul Downing, Joshua McKeown, Matthew Van Coutren</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- jQuery, Popper, and Bootstrap JavaScript -->
    <script src="jquery/dist/jquery.min.js"></script>
    <script src="popper/core/dist/umd/popper.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
  </body>

</html>
