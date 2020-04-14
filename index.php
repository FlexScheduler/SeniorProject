<!DOCTYPE html>
<?php
    // includes
    require "includes/execute_query.php";
    require "includes/convert_date.php";

    // define database connection
    define("DB_HOST", "sql309.epizy.com");
    define("DB_USER", "epiz_25500412");
    define("DB_PASS", "TmoFcb8z1QSHsG");
    define("DB_NAME", "epiz_25500412_FlexScheduler");
?>

<html>

  <head>
    <!-- Title -->
    <title>Flex Scheduler</title>

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Charset -->
    <meta charset="utf-8">

    <!-- Bootstrap, Font Awesome, and custom CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />

    <style>
        /*-- Alert --*/
        .alert {
          margin-top: 0.5em;
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
            font-size: 90%;
            margin-right: 0.075em;
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
            <a href="#academic" class="nav-item nav-link">Academic</a>
            <a href="#courses" class="nav-item nav-link">Courses</a>
            <a href="#personal" class="nav-item nav-link">Personal</a>
            <a href="#schedules" class="nav-item nav-link">Schedules</a>
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

    <!-- Welcome banner -->
    <div class="container-fluid padding">
      <div class="row welcome text-center">
        <div class="col-12">
          <h1 class="display-4">Flex Scheduler</h1>
        </div>
        <hr />
        <div class="col-12">
          <h5 class="display-4">Real Experience. Real Success.</h5>
        </div>
      </div>
      <hr class="my-5" id="academic" />
    </div>

    <!-- Academic filters -->
    <div class="container-fluid padding">
      <form id="academicForm">
        <h3>Academic Filters</h3>
        <div class="form-group form-row">
          <div class="col-md-6">
            <label class="col-form-label" for="termSelect">Term</label>
            <select class="form-control select" id="termSelect" name="termSelect">
              <option value="fall" selected>Fall</option>
              <option value="spring">Spring</option>
              <option value="summer">Summer</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="col-form-label" for="campusSelect">Campus</label>
            <select class="form-control select" id="campusSelect" name="campusSelect">
              <?php
                  // establish connection
                  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                  $conn->set_charset("utf8mb4");

                  // query departments
                  $sql = "SELECT DISTINCT campus FROM Sections;";
                  $result = execute_query($conn, $sql);

                  // populate dropdown
                  while ($row = $result->fetch_assoc()) {
                      if ($row["campus"] != "ONLINE") {
                          echo "<option value='" . $row["campus"] . "'>" .
                              ucwords(strtolower($row["campus"])) .
                              "</option>";
                      }
                  }

                  // close connection
                  $conn->close();
              ?>
            </select>
          </div>
        </div>
        <div class="form-group form-row">
          <div class="col-md-4">
            <label class="col-form-label" for="minimumCreditSelect">Minimum Credits</label>
            <input class="form-control" type="number" id="minimumCreditSelect" name="minimumCreditSelect" min="1" max="23" value="12" />
          </div>
          <div class="col-md-4">
            <label class="col-form-label" for="maximumCreditSelect">Maximum Credits</label>
            <input class="form-control" type="number" id="maximumCreditSelect" name="maximumCreditSelect" min="2" max="24" value="18" />
          </div>
          <div class="col-md-4">
            <label class="col-form-label" for="onlineSelect">Online</label>
            <select class="form-control select" id="onlineSelect" name="onlineSelect">
              <option value="ONLINE" selected>Show Online</option>
              <option value="null">Hide Online</option>
            </select>
          </div>
        </div>
      </form>
      <hr class="my-5" id="courses" />
    </div>

    <!-- Course filters -->
    <div class="container-fluid padding">
      <form id="courseForm">
        <h3>Select Courses</h3>
        <div class="form-group form-row">
          <div class="col-md-6 col-lg-3">
            <label class="col-form-label" for="departmentSelect">Department</label>
            <select class="custom-select form-control" data-live-search="true" id="departmentSelect" name="departmentSelect" aria-describedby="departmentHelp">
            <option selected value="ALL">Select Department</option>
            <?php
                // establish connection
                $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $conn->set_charset("utf8mb4");

                // query departments
                $sql = "SELECT DISTINCT deptID, strtDate, campus FROM Sections;";
                $result = execute_query($conn, $sql);

                // populate dropdown
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["deptID"] .
                        "' data-term='" . convert_date($row["strtDate"]) .
                        "' data-campus='" . $row["campus"] . "'>" .
                        $row["deptID"] .
                        "</option>";
                }

                // close connection
                $conn->close();
            ?>
            </select>
            <small id="departmentHelp" class="form-text text-muted">Leave blank to select all.</small>
          </div>
          <div class="col-md-6 col-lg-3">
            <label class="col-form-label" for="courseSelect">Course</label>
            <select class="custom-select form-control" id="courseSelect" name="courseSelect" aria-describedby="courseHelp" disabled>
            <option selected value="ALL">Select Course</option>
            <?php
                // establish connection
                $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $conn->set_charset("utf8mb4");

                // query courses
                $sql = "SELECT DISTINCT deptID, crseID, crseName, strtDate, campus FROM Sections;";
                $result = execute_query($conn, $sql);

                // populate dropdown
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["crseID"] . " - " . $row["crseName"] .
                        "' data-term='" . convert_date($row["strtDate"]) .
                        "' data-dept='" . $row["deptID"] .
                        "' data-crse='" . $row["crseID"] .
                        "' data-campus='" . $row["campus"] . "'>" .
                        $row["crseID"] . " - " . $row["crseName"] .
                        "</option>";
                }

                // close connection
                $conn->close();
            ?>
            </select>
            <small id="courseHelp" class="form-text text-muted">Select a department first. Leave blank to select all.</small>
          </div>
          <div class="col-md-6 col-lg-3">
            <label class="col-form-label" for="instructorSelect">Instructor</label>
            <select class="custom-select form-control" id="instructorSelect" name="instructorSelect" aria-describedby="instructorHelp" disabled>
            <option selected value="ALL">Select Instructor</option>
            <?php
                // establish connection
                $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $conn->set_charset("utf8mb4");

                // query arranged instructors
                $sql = "SELECT DISTINCT deptID, crseID, instrLName, instrFName FROM ArrangedInstructors;";
                $result = execute_query($conn, $sql);

                // populate dropdown
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["instrLName"] . ", " . $row["instrFName"] .
                        "' data-dept='" . $row["deptID"] .
                        "' data-crse='" . $row["crseID"] .
                        "' data-online='ONLINE'>" .
                        $row["instrLName"] . ", " . $row["instrFName"] .
                        "</option>";
                }

                // query session instructors
                $sql = "SELECT DISTINCT deptID, crseID, instrLName, instrFName FROM SessionInstructors;";
                $result = execute_query($conn, $sql);

                // populate dropdown
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["instrLName"] . ", " . $row["instrFName"] .
                        "' data-dept='" . $row["deptID"] .
                        "' data-crse='" . $row["crseID"] .
                        "' data-online='null'>" .
                        $row["instrLName"] . ", " . $row["instrFName"] .
                        "</option>";
                }

                // close connection
                $conn->close();
            ?>
            </select>
            <small id="instructorHelp" class="form-text text-muted">Select a course first. Leave blank to select all.</small>
          </div>
          <div class="col-md-6 col-lg-3">
            <label class="col-form-label" for="courseSubmit">&nbsp;</label>
            <input class="form-control btn btn-secondary" type="button" id="courseSubmit" name="courseSubmit" value="Add Course" />
          </div>
        </div>
      </form>
    </div>

    <!-- Course table -->
    <div class="container-fluid padding">
      <div class="table-hover table-striped">
        <table class="table course" id="course" name="course">
          <thead class="thead-dark">
            <tr>
              <th>Department</th>
              <th>Course</th>
              <th>Instructor</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <hr class="my-5" id="personal" />
    </div>

    <!-- Personal schedule -->
    <div class="container-fluid padding">
      <form id="personalForm">
        <h3>Personal Schedule</h3>
        <div class="form-group form-row">
          <div class="col-12">
            <label class="col-form-label" for="nameSelect">Name</label>
            <input class="form-control" type="text" id="nameSelect" name="nameSelect" placeholder="Enter event name" />
          </div>
        </div>
        <div class="form-group form-row">
          <div class="col-md-6">
            <label class="col-form-label" for="timeStartSelect">Start Time</label>
            <div class="input-group" id="timeStartSelect" name="timeStartSelect">
              <select class="form-control time-input select" id="timeStartHour" name="timeStartHour">
                <option selected hidden value="null">Hour</option>
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
              <select class="form-control time-input select" id="timeStartMinute" name="timeStartMinute">
                <option selected hidden value="null">Minute</option>
                <option value="00">00</option>
                <option value="15">15</option>
                <option value="30">30</option>
                <option value="45">45</option>
              </select>
              <select class="form-control select" id="timeStartPeriod" name="timeStartPeriod">
                <option value="AM" selected>AM</option>
                <option value="PM">PM</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label class="col-form-label" for="timeEndSelect">End Time</label>
            <div class="input-group" id="timeEndSelect" name="timeEndSelect">
              <select class="form-control time-input select" id="timeEndHour" name="timeEndHour">
                <option selected hidden value="null">Hour</option>
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
              <select class="form-control time-input select" id="timeEndMinute" name="timeEndMinute">
                <option selected hidden value="null">Minute</option>
                <option value="00">00</option>
                <option value="15">15</option>
                <option value="30">30</option>
                <option value="45">45</option>
              </select>
              <select class="form-control select" id="timeEndPeriod" name="timeEndPeriod">
                <option value="AM" selected>AM</option>
                <option value="PM">PM</option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group form-row">
          <div class="col-md-8">
            <div class="form-check form-check-inline">
              <label class="form-check-label mr-0">
                <input class="form-check-input" type="checkbox" id="monday" name="monday" value="M" />Monday
              </label>
            </div>
            <div class="form-check form-check-inline">
              <label class="form-check-label mr-0">
                <input class="form-check-input" type="checkbox" id="tuesday" name="tuesday" value="T" />Tuesday
              </label>
            </div>
            <div class="form-check form-check-inline">
              <label class="form-check-label mr-0">
                <input class="form-check-input" type="checkbox" id="wednesday" name="wednesday" value="W" />Wednesday
              </label>
            </div>
            <div class="form-check form-check-inline">
              <label class="form-check-label mr-0">
                <input class="form-check-input" type="checkbox" id="thursday" name="thursday" value="R" />Thursday
              </label>
            </div>
            <div class="form-check form-check-inline">
              <label class="form-check-label mr-0">
                <input class="form-check-input" type="checkbox" id="friday" name="friday" value="F" />Friday
              </label>
            </div>
          </div>
          <div class="col-md-4">
            <input class="form-control btn btn-secondary" type="button" id="personalSubmit" name="personalSubmit" value="Add Event" />
          </div>
        </div>
      </form>
    </div>

    <!-- Personal schedule table -->
    <div class="container-fluid padding">
      <div class="table-hover table-striped">
        <table class="table personal" id="personal" name="personal">
          <thead class="thead-dark">
            <tr>
              <th>Name</th>
              <th>Days</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <hr class="my-5" id="schedules" />
    </div>

    <!-- Submit -->
    <div class="container-fluid padding">
      <form>
        <div class="form-group form-row">
          <div class="col-12">
            <input class="form-control btn btn-primary btn-block" type="button" id="submitAll" name="submitAll" value="Create Schedules" />
          </div>
        </div>
      </form>
    </div>

    <!--TODO Schedules -->
    <div class="container-fluid padding">
    </div>

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
            <p class="blockquote-footer">Justin Stoker, James Akins, Paul Downing, Josh McKeown, Matt Van Coutren</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- jQuery, Popper, and Bootstrap JavaScript -->
    <script src="jquery/dist/jquery.min.js"></script>
    <script src="popper/dist/umd/popper.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Table Functions -->
    <script>
    $(document).ready(function() {
        // term select
        window.term = $("#termSelect").val();
        $("#termSelect").on("change", function() {
            window.term = $("#termSelect").val();
            $("#departmentSelect").trigger("reset");
        });

        // campus select
        window.campus = $("#campusSelect").val();
        $("#campusSelect").on("change", function() {
            window.campus = $("#campusSelect").val();
            $("#departmentSelect").trigger("reset");
        });

        // minimum credit select
        window.minCredit = $("#minimumCreditSelect").val();
        $("#minimumCreditSelect").on("change", function() {
            window.minCredit = $("#minimumCreditSelect").val();
        });

        // maximum credit select
        window.maxCredit = $("#maximumCreditSelect").val();
        $("#maximumCreditSelect").on("change", function() {
            window.maxCredit = $("#maximumCreditSelect").val();
        });

        // online select
        window.online = $("#onlineSelect").val();
        $("#onlineSelect").on("change", function() {
            window.online = $("#onlineSelect").val();
            $("#departmentSelect").trigger("reset");
        });

        // department select
        $("#departmentSelect").on("reset", function() {
            $(this).children().hide();
            $(this).children("option[data-term='" + $("#termSelect").val() + "']" + "[data-campus='" + $("#campusSelect").val() + "']").show();
            $(this).children("option[value='ALL']").show().prop("selected", true);
            $("#departmentSelect").trigger("change");
            $("#courseSelect").trigger("change");
            hide_duplicates();
        });

        // course select
        $("#departmentSelect").on("change", function() {
            if ($(this).val() != "ALL") {
                $("#courseSelect").prop("disabled", false);
                $("#courseSelect").children().hide();
                $("#courseSelect").children("option[data-term='" + $("#termSelect").val() + "'][data-dept='" + $(this).val() + "']").filter(
                    "[data-campus='" + $("#campusSelect").val() + "'], [data-campus='" + $("#onlineSelect").val() + "']").show();
                $("#courseSelect").children("option[value='ALL']").show().prop("selected", true);
                hide_duplicates();
            }
            else {
                $("#courseSelect").children().hide();
                $("#courseSelect").children("option[value='ALL']").show().prop("selected", true);
                $("#courseSelect").prop("disabled", true);
            }
            $("#instructorSelect").children().hide();
            $("#instructorSelect").children("option[value='ALL']").show().prop("selected", true);
            $("#instructorSelect").prop("disabled", true);
        });

        // instructor select
        $("#courseSelect").on("change", function() {
            if ($(this).val() != "ALL") {
                $("#instructorSelect").prop("disabled", false);
                $("#instructorSelect").children().hide();
                if ($("#onlineSelect").val() == "ONLINE") {
                    $("#instructorSelect").children("option[data-dept='" + $("#departmentSelect").val() + "'][data-crse='" + $(this).find(
                        "option:selected").data("crse") + "']").show();
                }
                else {
                    $("#instructorSelect").children("option[data-dept='" + $("#departmentSelect").val() + "'][data-crse='" + $(this).find(
                        "option:selected").data("crse") + "'][data-online='null']").show();
                }
                $("#instructorSelect").children("option[value='ALL']").show().prop("selected", true);
                hide_duplicates();
            }
            else {
                $("#instructorSelect").children().hide();
                $("#instructorSelect").children("option[value='ALL']").show().prop("selected", true);
                $("#instructorSelect").prop("disabled", true);
            }
        });

        // course submit
        $("#courseSubmit").on("click", function() {
            // add new row
            var newRow = "<tr>";
            newRow += "<td>" + $("#departmentSelect").val() + "</td>";
            newRow += "<td>" + $("#courseSelect").val() + "</td>";
            newRow += "<td>" + $("#instructorSelect").val() + "</td>";
            newRow += "<td><button class='btn btn-danger type='button' onclick='$(this).closest(\"tr\").remove();'>Remove</button></td>";
            newRow += "</tr>";
            $("#course > tbody").append(newRow);

            // reset form
            $("#departmentSelect").trigger("reset");
        });

      	// personal submit
      	$("#personalSubmit").on("click", function() {
            // validate form
            var alert = "";
            if ($("#nameSelect").val() == "") {
                alert = "Please enter a name.";
            }
            else if (
                !$("#monday").is(":checked") &&
                !$("#tuesday").is(":checked") &&
                !$("#wednesday").is(":checked") &&
                !$("#thursday").is(":checked") &&
                !$("#friday").is(":checked")
            ) {
                alert = "Please select at least one day.";
            }
            else if ($("#timeStartHour").val() == "null") {
                alert = "Please select a starting hour.";
            }
            else if ($("#timeStartMinute").val() == "null") {
                alert = "Please select a starting minute.";
            }
            else if ($("#timeEndHour").val() == "null") {
                alert = "Please select an ending hour.";
            }
            else if ($("#timeEndMinute").val() == "null") {
                alert = "Please select an ending minute.";
            }
            else if (
                $("#timeStartPeriod").val() == "PM" &&
                $("#timeEndPeriod").val() == "AM"
            ) {
                alert = "Please enter an ending time after the selected start time.";
            }
            else if (
                $("#timeStartPeriod").val() == $("#timeEndPeriod").val() &&
                $("#timeStartHour").val() > $("#timeEndHour").val() &&
                $("#timeStartHour").val() != "12"
            ) {
                alert = "Please enter an ending time after the selected start time.";
            }
            else if (
                $("#timeStartPeriod").val() == $("#timeEndPeriod").val() &&
                $("#timeStartHour").val() == $("#timeEndHour").val() &&
                $("#timeStartMinute").val() >=  $("#timeEndMinute").val()
            ) {
                alert = "Please enter an ending time after the selected start time.";
            }

            // error alert
            if (alert != "") {
                $(this).after(
                '<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" ' +
                            'data-dismiss="alert" aria-hidden="true">' +
                        '&times;' +
                    '</button>ERROR! ' +
                    alert +
                 '</div>');
            }

            // add new row
            else {
            		var newRow = "<tr>";
            		newRow += "<td>" + $("#nameSelect").val() + "</td>";
            		newRow += "<td>";
            		$("#personalForm label input[type='checkbox']").each(function() {
              			if ($(this).is(":checked")) {
              				newRow += $(this).val();
              			}
            		});
            		newRow += "</td><td>";
            		newRow += $("#timeStartHour").val() + ":";
                newRow += $("#timeStartMinute").val() + $("#timeStartPeriod").val();
                newRow += "</td><td>";
                newRow += $("#timeEndHour").val() + ":";
                newRow += $("#timeEndMinute").val() + $("#timeEndPeriod").val();
                newRow += "<td><button class='btn btn-danger type='button' onclick='$(this).closest(\"tr\").remove();'>Remove</button></td>";
            		newRow += "</td></tr>";
            		$("#personal > tbody").append(newRow);

                // reset form
                $("#nameSelect").val("");
                $("#timeStartHour").children("option[value='null']").prop("selected", true);
                $("#timeStartMinute").children("option[value='null']").prop("selected", true);
                $("#timeStartPeriod").children("option[value='AM']").prop("selected", true);
                $("#timeEndHour").children("option[value='null']").prop("selected", true);
                $("#timeEndMinute").children("option[value='null']").prop("selected", true);
                $("#timeEndPeriod").children("option[value='AM']").prop("selected", true);
                $("#monday").prop("checked", false);
                $("#tuesday").prop("checked", false);
                $("#wednesday").prop("checked", false);
                $("#thursday").prop("checked", false);
                $("#friday").prop("checked", false);
            }
      	});

        // set forms
        $("#departmentSelect").trigger("reset");
        sort_instructors();

        // hide duplicates
        function hide_duplicates() {
            var map = {};
            $(".custom-select option").each(function() {
                if ($(this).css("display") != "none") {
                    if (map[$(this).text()]) {
                        $(this).hide();
                    }
                    else {
                        map[$(this).text()] = true;
                    }
                }
            });
        }

        // sort instructors
        function sort_instructors() {
            var options = $("#instructorSelect option:not(:selected)");
            var selected = $("#instructorSelect option:selected");
            var val = $("#instructorSelect").val();
            options.sort(function(a, b) {
                var name1 = a.text.split(',');
                var name2 = b.text.split(',');
                if (name1[0] > name2[0]) {
                    return (1);
                }
                else if (name1[0] < name2[0]) {
                    return (-1);
                }
                else if (name1[1] > name2[1]) {
                    return (1);
                }
                else if (name1[1] < name2[1]) {
                    return (-1);
                }
                return (0);
            })
            $("#instructorSelect").empty().append(selected).append(options);
            $("#instructorSelect").val(val);
        }

        // create schedules
        $("#submitAll").on("click", function() {
            /*TODO
            var AcademicData;
            AcademicData = $.toJSON(storeAcademic());
            $.ajax({
                type: "POST",
                url: "includes/create_schedules.php",
                data: "pAcademicData=" + AcademicData,
                success: function(response) {
                    //TODO THIS IS JUST TESTINTG
                    //alert(response);
                }
            });*/

            var CourseData;
            CourseData = JSON.parse(JSON.stringify(storeCourses()));
            $.ajax({
                type: "POST",
                url: "includes/create_schedules.php",
                data: "pCourseData=" + CourseData,
                success: function(response) {
                    //TODO THIS IS JUST TESTINTG
                    //alert(response);
                }
            });

            var PersonalData;
            PersonalData = JSON.parse(JSON.stringify(storePersonal()));
            $.ajax({
                type: "POST",
                url: "includes/create_schedules.php",
                data: "pPersonalData=" + PersonalData,
                success: function(response) {
                    //TODO THIS IS JUST TESTINTG
                    //alert(response);
                }
            });
        });

        //TODO store academic
        function storeAcademic() {
        }

        // store courses
        function storeCourses() {
            var CourseData = new Array();
            $("#course tr").each(function(row, tr) {
                CourseData[row] = {
                    "courseDept": $(tr).find("td:eq(0)").text(),
                    "courseID": $(tr).find("td:eq(1)").text(),
                    "courseInstr": $(tr).find("td:eq(2)").text()
                }
            });
            CourseData.shift();
            return (CourseData);
        }

        // store personal
        function storePersonal() {
            var PersonalData = new Array();
            $("#personal tr").each(function(row, tr) {
                PersonalData[row] = {
                    "personalName": $(tr).find("td:eq(0)").text(),
                    "personalDays": $(tr).find("td:eq(1)").text(),
                    "personalStartTime": $(tr).find("td:eq(2)").text(),
                    "personalEndTime": $(tr).find("td:eq(3)").text()
                }
            });
            PersonalData.shift();
            return (PersonalData);
        }
    });
    </script>
  </body>

</html>
