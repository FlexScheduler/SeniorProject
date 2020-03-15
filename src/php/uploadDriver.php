<<<<<<< Updated upstream
<?php
// check form was submitted
if (isset($_POST["uploadSubmit"]) && !empty($_FILES["uploadFile"]["name"])) {
    // MySQL includes
    include "../lib/mysql/create_required.php";
    include "../lib/mysql/delete_term.php";
    include "../lib/mysql/drop_temp.php";
    include "../lib/mysql/upload_data.php";
    include "../lib/mysql/upload_temp.php";

    // utility includes
    include "../lib/utility/Connection.php";
    include "../lib/utility/convert_term.php";
    include "../lib/utility/execute_query.php";
    include "../lib/utility/get_file.php";

    // start session
    session_start();

    try {
        // get file
        $targetFilePath = get_file(basename($_FILES["uploadFile"]["name"]));

        // establish connection
        $conn = new Connection(
            $_SESSION["username"],
            $_SESSION["password"]
        );

        // ensure required tables exist
        create_required($conn->get_conn());

        // convert selected term to start and end months
        $term = convert_term($_POST["term"]);
        $strtMonth = $term[0];
        $endMonth = $term[1];

        // delete listings for selected term from database
        delete_term($conn->get_conn(), $strtMonth, $endMonth);

        // upload file to temp table
        upload_temp(
            $conn->get_conn(),
            $targetFilePath,
            $strtMonth,
            $endMonth
        );

        // upload data from temp table to database
        upload_data($conn->get_conn());

        // drop temp table
        drop_temp($conn->get_conn());

        // commit transaction
        $conn->get_conn()->commit();
        $conn->get_conn()->autocommit(TRUE);

        // success alert
        $message = "Success: Course catalog updated.";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

    // exception alert
    catch (Exception $e) {
        $message = $e->getMessage();
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}
?>

<html>

<head>
    <title>Course Catalog Upload</title>
</head>

<body>
    <!-- file upload form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>"
        onsubmit="return confirm('WARNING: This will replace all existing ' +
            'data for the selected term.');"
        method="post" enctype="multipart/form-data">
        <label for="uploadFile">Select course catalog to upload:</label>
        <input type="file" id="uploadFile" name="uploadFile"/>
        <br/>
        <br/>
        <label for="term">Select term to update:</label>
        <select id="term" name="term">
            <option value="fall">Fall</option>
            <option value="spring">Spring</option>
            <option value="summer">Summer</option>
        </select>
        <br/>
        <br/>
        <input type="submit" value="Upload Data" id="uploadSubmit"
            name="uploadSubmit"/>
    </form>

    <!-- link to home page -->
    <a href="index.html">Return to Home Page</a>
</body>

</html>
=======
<?php
// check form was submitted
if (isset($_POST["uploadSubmit"]) && !empty($_FILES["uploadFile"]["name"])) {
    // MySQL includes
    include "../lib/mysql/create_required.php";
    include "../lib/mysql/delete_term.php";
    include "../lib/mysql/drop_temp.php";
    include "../lib/mysql/upload_data.php";
    include "../lib/mysql/upload_temp.php";

    // utility includes
    include "../lib/utility/Connection.php";
    include "../lib/utility/convert_term.php";
    include "../lib/utility/execute_query.php";
    include "../lib/utility/get_file.php";

    // start session
    session_start();

    try {
        // get file
        $targetFilePath = get_file(basename($_FILES["uploadFile"]["name"]));

        // establish connection
        $conn = new Connection(
            $_SESSION["username"],
            $_SESSION["password"]
        );

        // ensure required tables exist
        create_required($conn->get_conn());

        // convert selected term to start and end months
        $term = convert_term($_POST["term"]);
        $strtMonth = $term[0];
        $endMonth = $term[1];

        // delete listings for selected term from database
        delete_term($conn->get_conn(), $strtMonth, $endMonth);

        // upload file to temp table
        upload_temp(
            $conn->get_conn(),
            $targetFilePath,
            $strtMonth,
            $endMonth
        );

        // upload data from temp table to database
        upload_data($conn->get_conn());

        // drop temp table
        drop_temp($conn->get_conn());

        // commit transaction
        $conn->get_conn()->commit();
        $conn->get_conn()->autocommit(TRUE);

        // success alert
        $message = "Success: Course catalog updated.";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

    // exception alert
    catch (Exception $e) {
        $message = $e->getMessage();
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}
?>

<html>

<head>
    <title>Course Catalog Upload</title>
</head>

<body>
    <!-- file upload form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>"
        onsubmit="return confirm('WARNING: This will replace all existing ' +
            'data for the selected term.');"
        method="post" enctype="multipart/form-data">
        <label for="uploadFile">Select course catalog to upload:</label>
        <input type="file" id="uploadFile" name="uploadFile"/>
        <br/>
        <br/>
        <label for="term">Select term to update:</label>
        <select id="term" name="term">
            <option value="fall">Fall</option>
            <option value="spring">Spring</option>
            <option value="summer">Summer</option>
        </select>
        <br/>
        <br/>
        <input type="submit" value="Upload Data" id="uploadSubmit"
            name="uploadSubmit"/>
    </form>

    <!-- link to home page -->
    <a href="index.html">Return to Home Page</a>
</body>

</html>
>>>>>>> Stashed changes
