<html>

<head>
    <title>Course Catalog Upload</title>
</head>

<body>
    <?php
    // check form was submitted
    if (isset($_POST["submit"]) && !empty($_FILES["file"]["name"])) {
        // query includes
        include "library/queries/create_required.php";
        include "library/queries/delete_term.php";
        include "library/queries/drop_temp.php";
        include "library/queries/upload_data.php";
        include "library/queries/upload_temp.php";

        // utility includes
        include "library/utilities/Connection.php";
        include "library/utilities/convert_term.php";
        include "library/utilities/execute_query.php";
        include "library/utilities/get_file.php";

        // start session
        session_start();

        try {
            // get file
            $targetFilePath = get_file(basename($_FILES["file"]["name"]));

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

    <!-- file upload form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>"
        onsubmit="return confirm('WARNING: This will replace all existing ' +
            'data for the selected term.');"
        method="post" enctype="multipart/form-data">
        <label for="file">Select course catalog to upload:</label>
        <input type="file" id="file" name="file"/>
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
        <input type="submit" value="Upload Data" id="submit" name="submit"/>
    </form>

    <!-- link to home page -->
    <a href="index.html">Return to Home Page</a>
</body>

</html>
