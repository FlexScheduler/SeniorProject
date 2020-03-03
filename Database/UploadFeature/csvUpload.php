<html>
  <body>
    <form action="csvUpload.php" method="post" enctype="multipart/form-data">
      Select csv file to upload:
      <input type="file" name="csvFile" id="csvFile"><br>
      Enter database credentials:<br>
      Server: <input type="text" name="server"><br>
      Username: <input type="text" name="username"><br>
      Password: <input type="password" name="password"><br>
      Database: <input type="text" name="database"><br>
      <input type="submit" value="Upload Data" name="submit"><br>
    </form>

    <?php
    // check form was submitted
    if(isset($_POST["submit"])) {

      // establish conection
      $server = $_POST["server"];
      $username = $_POST["username"];
      $password = $_POST["password"];
      $database = $_POST["database"];
      $conn = new mysqli($server, $username, $password, $database);

      // check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // delete existing tables
      $sql = "
          DROP TABLES IF EXISTS
              CsvData,
              Sections,
              ArrangedInstructors,
              Sessions,
              SessionInstructors;";

      // run query
      if (!$conn->query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // ensure necessary tables exist
      $sql = "
          CREATE TABLE IF NOT EXISTS Sections(
              deptID VARCHAR(16),
              crseID INT,
              secID VARCHAR(16),
              crseName VARCHAR(128) NOT NULL,
              numCredit int NOT NULL,
              strtDate DATE NOT NULL,
              endDate DATE NOT NULL,
              campus VARCHAR(32) NOT NULL,
              PRIMARY KEY(deptID, crseID, secID)
          );
          CREATE TABLE IF NOT EXISTS ArrangedInstructors(
              deptID VARCHAR(16),
              crseID INT,
              secID VARCHAR(16),
              instrLName VARCHAR(32),
              instrFName VARCHAR(32),
              PRIMARY KEY(
                  deptID,
                  crseID,
                  secID,
                  instrLName,
                  instrFName
              ),
              FOREIGN KEY(deptID, crseID, secID)
              REFERENCES Sections(deptID, crseID, secID)
          );
          CREATE TABLE IF NOT EXISTS Sessions(
              deptID VARCHAR(16),
              crseID INT,
              secID VARCHAR(16),
              dayID CHAR(1),
              strtTime TIME,
              endTime TIME,
              bldgID VARCHAR(16) NOT NULL,
              rmNum VARCHAR(32) NOT NULL,
              PRIMARY KEY(
                  deptID,
                  crseID,
                  secID,
                  dayID,
                  strtTime,
                  endTime
              ),
              FOREIGN KEY(deptID, crseID, secID)
              REFERENCES Sections(deptID, crseID, secID)
          );
          CREATE TABLE IF NOT EXISTS SessionInstructors(
              deptID VARCHAR(16),
              crseID INT,
              secID VARCHAR(16),
              dayID CHAR(1),
              strtTime TIME,
              endTime TIME,
              instrLName VARCHAR(32),
              instrFName VARCHAR(32),
              PRIMARY KEY(
                  deptID,
                  crseID,
                  secID,
                  dayID,
                  strtTime,
                  endTime,
                  instrLName,
                  instrFName
              ),
              FOREIGN KEY(
                  deptID,
                  crseID,
                  secID,
                  dayID,
                  strtTime,
                  endTime
              ) REFERENCES Sessions(
                  deptID,
                  crseID,
                  secID,
                  dayID,
                  strtTime,
                  endTime
              )
          );";

      // run queries
      if (!$conn->multi_query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // create temporary table to import csv data
      $sql = "
          CREATE TABLE IF NOT EXISTS CsvData(
              deptID VARCHAR(16),
              crseID INT,
              secID VARCHAR(16),
              crseName VARCHAR(128) not null,
              crseDiv VARCHAR(128) not null,
              numCredit INT not null,
              stdntLim INT not null,
              stdntEnr INT not null,
              strtDate DATE not null,
              endDate DATE not null,
              offrDays1 VARCHAR(8),
              strtTime1 TIME,
              endTime1 TIME,
              instrName1 VARCHAR(64) not null,
              bldgID1 VARCHAR(16),
              rmNum1 VARCHAR(32),
              campus1 VARCHAR(32) not null,
              offrDays2 VARCHAR(8),
              strtTime2 TIME,
              endTime2 TIME,
              instrName2 VARCHAR(64),
              bldgID2 VARCHAR(16),
              rmNum2 VARCHAR(32),
              campus2 VARCHAR(32),
              primary key(deptID, crseID, secID)
          );";

      // run query
      if (!$conn->query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // import csv data into temporary table
      $conn->options(MYSQLI_OPT_LOCAL_INFILE, true);
      $fileLocation = $_FILES["csvFile"]["name"];
      $sql = "
          LOAD DATA LOCAL INFILE
              '" . $fileLocation . "'
          INTO TABLE
              CsvData
          FIELDS TERMINATED BY
              ','
          ENCLOSED BY
              '\"'
          LINES TERMINATED BY
              '\\n'
          IGNORE
              1 ROWS(
                  deptID,
                  crseID,
                  secID,
                  crseName,
                  crseDiv,
                  numCredit,
                  stdntLim,
                  stdntEnr,
                  @strtDate,
                  @endDate,
                  offrDays1,
                  @strtTime1,
                  @endTime1,
                  instrName1,
                  bldgID1,
                  rmNum1,
                  campus1,
                  offrDays2,
                  @strtTime2,
                  @endTime2,
                  instrName2,
                  bldgID2,
                  rmNum2,
                  campus2
              )
          SET
              strtDate = DATE_FORMAT(
                  STR_TO_DATE(@strtDate, '%m/%d/%Y'),
                  '%Y-%m-%d'
              ),
              endDate = DATE_FORMAT(
                  STR_TO_DATE(@endDate, '%m/%d/%Y'),
                  '%Y-%m-%d'
              ),
              strtTime1 = STR_TO_DATE(@strtTime1, '%l:%i%p'),
              endTime1 = STR_TO_DATE(@endTime1, '%l:%i%p'),
              strtTime2 = STR_TO_DATE(@strtTime2, '%l:%i%p'),
              endTime2 = STR_TO_DATE(@endTime2, '%l:%i%p');";

      // run query
      if (!$conn->query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // remove internal listings
      $sql = "
        DELETE
        FROM
            CsvData
        WHERE
            crseDiv = 'LU-INTERNAL';";

      // run query
      if (!$conn->query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // insert data from temporary table into sections table
      $sql = "
          INSERT INTO Sections(
              deptID,
              crseID,
              secID,
              crseName,
              numCredit,
              strtDate,
              endDate,
              campus
          )
          SELECT
              deptID,
              crseID,
              secID,
              crseName,
              numCredit,
              strtDate,
              endDate,
              campus1
          FROM
              CsvData;";

      // run query
      if (!$conn->query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // sort sections table
      $sql = "
          ALTER TABLE
              Sections
          ORDER BY
              deptID,
              crseID,
              secID ASC;";

      // insert data from temporary table into arranged instructors table
      $sql = "
          INSERT INTO ArrangedInstructors(
              deptID,
              crseID,
              secID,
              instrLName,
              instrFName
          )
          SELECT
              deptID,
              crseID,
              secID,
              SUBSTRING_INDEX(instrName1, ' ', 1),
              SUBSTRING_INDEX(instrName1, ' ', -1)
          FROM
              CsvData
          WHERE
              offrDays1 = 'ARR';
          INSERT INTO ArrangedInstructors(
              deptID,
              crseID,
              secID,
              instrLName,
              instrFName
          )
          SELECT
              deptID,
              crseID,
              secID,
              SUBSTRING_INDEX(instrName2, ' ', 1),
              SUBSTRING_INDEX(instrName2, ' ', -1)
          FROM
              CsvData
          WHERE
              offrDays1 = 'ARR' AND instrName2 != '';";

      // run queries
      if (!$conn->multi_query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // sort arranged instructors table
      $sql = "
          ALTER TABLE
              ArrangedInstructors
          ORDER BY
              deptID,
              crseID,
              secID,
              instrLName,
              instrFName ASC;";

      // run query
      if (!$conn->query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // split days1 into atmoic values
      $sql = "
          SELECT
              deptID,
              crseID,
              secID,
              offrDays1
          FROM
              CsvData
          WHERE
              offrDays1 != 'ARR';";

      // run query
      $result = $conn->query($sql);
      if (!$result) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // insert days1 data from temporary table into in class sessions table
      while($row = $result->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays1']); ++$i) {
          $sql = "
              INSERT INTO Sessions(
                  deptID,
                  crseID,
                  secID,
                  dayID,
                  strtTime,
                  endTime,
                  bldgID,
                  rmNum
              )
              SELECT
                  deptID,
                  crseID,
                  secID,
                  '" . substr($row['offrDays1'], $i, 1) . "',
                  strtTime1,
                  endTime1,
                  bldgID1,
                  rmNum1
              FROM
                  CsvData
              WHERE
                  deptID = '" . $row['deptID'] . "' AND
                  crseID = '" . $row['crseID'] . "' AND
                  secID = '" . $row['secID'] . "';";

          // run query
          if (!$conn->query($sql)) {
            die("MySQL Error: " . $conn->error);
          }

          // empty results
          while ($conn->more_results()) {
            $conn->next_result();
          }
        }
      }

      // free result
      $result->free_result();

      // split days2 into atmoic values
      $sql = "
          SELECT
              deptID,
              crseID,
              secID,
              offrDays2
          FROM
              CsvData
          WHERE
              offrDays1 != 'ARR' AND
              offrDays2 != '' AND(
                  offrDays1 != offrDays2 OR
                  strtTime1 != strtTime2 OR
                  endTime1 != endTime2
              );";

      // run query
      $result = $conn->query($sql);
      if (!$result) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // insert days2 data from temporary table into in class sessions table
      while($row = $result->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays2']); ++$i) {
          $sql = "
              INSERT INTO Sessions(
                  deptID,
                  crseID,
                  secID,
                  dayID,
                  strtTime,
                  endTime,
                  bldgID,
                  rmNum
              )
              SELECT
                  deptID,
                  crseID,
                  secID,
                  '" . substr($row['offrDays2'], $i, 1) . "',
                  strtTime2,
                  endTime2,
                  bldgID2,
                  rmNum2
              FROM
                  CsvData
              WHERE
                  deptID = '" . $row['deptID'] . "' AND
                  crseID = '" . $row['crseID'] . "' AND
                  secID = '" . $row['secID'] . "';";

          // run query
          if (!$conn->query($sql)) {
            die("MySQL Error: " . $conn->error);
          }

          // empty results
          while ($conn->more_results()) {
            $conn->next_result();
          }
        }
      }

      // free result
      $result->free_result();

      // sort in class session table
      $sql = "
          ALTER TABLE
              Sessions
          ORDER BY
              deptID,
              crseID,
              secID,
              dayID,
              strtTime,
              endTime ASC;";

      // run query
      if (!$conn->query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // split days1 into atmoic values
      $sql = "
          SELECT
              deptID,
              crseID,
              secID,
              offrDays1,
              instrName1
          FROM
              CsvData
          WHERE
              offrDays1 != 'ARR';";

      // run query
      $result = $conn->query($sql);
      if (!$result) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // insert days1 data from temporary table into in class instructors table
      while($row = $result->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays1']); ++$i) {
          $sql = "
              INSERT INTO SessionInstructors(
                  deptID,
                  crseID,
                  secID,
                  dayID,
                  strtTime,
                  endTime,
                  instrLName,
                  instrFName
              )
              SELECT
                  deptID,
                  crseID,
                  secID,
                  '" . substr($row['offrDays1'], $i, 1) . "',
                  strtTime1,
                  endTime1,
                  SUBSTRING_INDEX(instrName1, ' ', 1),
                  SUBSTRING_INDEX(instrName1, ' ', -1)
              FROM
                  CsvData
              WHERE
                  deptID = '" . $row['deptID'] . "' AND
                  crseID = '" . $row['crseID'] . "' AND
                  secID = '" . $row['secID'] . "';";

          // run query
          if (!$conn->query($sql)) {
            die("MySQL Error: " . $conn->error);
          }

          // empty results
          while ($conn->more_results()) {
            $conn->next_result();
          }
        }
      }

      // free result
      $result->free_result();

      // split days2 into atmoic values
      $sql = "
          SELECT
              deptID,
              crseID,
              secID,
              offrDays2,
              instrName2
          FROM
              CsvData
          WHERE
              offrDays1 != 'ARR' AND
              offrDays2 != '' AND
              instrName2 != '' AND(
                  offrDays1 != offrDays2 OR
                  strtTime1 != strtTime2 OR
                  endTime1 != endTime2 OR
                  instrName1 != instrName2
              );";

      // run query
      $result = $conn->query($sql);
      if (!$result) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // insert days2 data from temporary table into in class instructors table
      while($row = $result->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays2']); ++$i) {
          $sql = "
              INSERT INTO SessionInstructors(
                  deptID,
                  crseID,
                  secID,
                  dayID,
                  strtTime,
                  endTime,
                  instrLName,
                  instrFName
              )
              SELECT
                  deptID,
                  crseID,
                  secID,
                  '" . substr($row['offrDays2'], $i, 1) . "',
                  strtTime2,
                  endTime2,
                  SUBSTRING_INDEX(instrName2, ' ', 1),
                  SUBSTRING_INDEX(instrName2, ' ', -1)
              FROM
                  CsvData
              WHERE
                  deptID = '" . $row['deptID'] . "' AND
                  crseID = '" . $row['crseID'] . "' AND
                  secID = '" . $row['secID'] . "';";

          // run query
          if (!$conn->query($sql)) {
            die("MySQL Error: " . $conn->error);
          }

          // empty results
          while ($conn->more_results()) {
            $conn->next_result();
          }
        }
      }

      // free result
      $result->free_result();

      // sort in class instructors table
      $sql = "
          ALTER TABLE
              SessionInstructors
          ORDER BY
              deptID,
              crseID,
              secID,
              dayID,
              strtTime,
              endTime,
              instrLName,
              instrFName ASC";

      // run query
      if (!$conn->query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // drop temporary table
      $sql = "DROP TABLE CsvData;";

      // run query
      if (!$conn->query($sql)) {
        die("MySQL Error: " . $conn->error);
      }

      // empty results
      while ($conn->more_results()) {
        $conn->next_result();
      }

      // display success
      echo "Success: data from " . $_FILES["csvFile"]["name"] . " uploaded.";

      // close connection
      $conn->close();
    }
    ?>
  </body>
</html>
