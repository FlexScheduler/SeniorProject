<html>
  <body>
    <?php

    // establish conection
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "senprojtest";
    $conn = new mysqli($server, $username, $password, $database);

    // check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // delete existing tables
    $sql = "DROP TABLES IF EXISTS
        ArrangedInstructors,
        CsvData,
        InClassInstructors,
        InClassSessions,
        Sections";

    // run query
    if (!$conn->query($sql)) {
      die("MySQL Error: " . $conn->error);
    }

    // empty results
    while ($conn->more_results()) {
      $conn->next_result();
    }

    // ensure necessary tables exist
    $sql = "CREATE TABLE IF NOT EXISTS Sections(
          deptID VARCHAR(16),
          crseID INT,
          secID VARCHAR(16),
          crseName VARCHAR(128) NOT NULL,
          numCredit int NOT NULL,
          strtDate DATE NOT NULL,
          endDate DATE NOT NULL,
          campus VARCHAR(32) NOT NULL,
          PRIMARY KEY(deptID, crseID, secID)
        ); CREATE TABLE IF NOT EXISTS ArrangedInstructors(
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
        ); CREATE TABLE IF NOT EXISTS InClassSessions(
          deptID VARCHAR(16),
          crseID INT,
          secID VARCHAR(16),
          dayID CHAR(1),
          timeFrm TIME,
          timeTo TIME,
          rmAbbrv VARCHAR(16) NOT NULL,
          PRIMARY KEY(
              deptID,
              crseID,
              secID,
              dayID,
              timeFrm,
              timeTo
          ),
          FOREIGN KEY(deptID, crseID, secID)
          REFERENCES Sections(deptID, crseID, secID)
        ); CREATE TABLE IF NOT EXISTS InClassInstructors(
          deptID VARCHAR(16),
          crseID INT,
          secID VARCHAR(16),
          dayID CHAR(1),
          timeFrm TIME,
          timeTo TIME,
          instrLName VARCHAR(32),
          instrFName VARCHAR(32),
          rmNum VARCHAR(32),
          PRIMARY KEY(
              deptID,
              crseID,
              secID,
              dayID,
              timeFrm,
              timeTo,
              instrLName,
              instrFName,
              rmNum
          ),
          FOREIGN KEY(
              deptID,
              crseID,
              secID,
              dayID,
              timeFrm,
              timeTo
          ) REFERENCES InClassSessions(
              deptID,
              crseID,
              secID,
              dayID,
              timeFrm,
              timeTo
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
    $sql = "CREATE TABLE IF NOT EXISTS CsvData(
          deptID VARCHAR(16),
          crseID INT,
          secID VARCHAR(16),
          crseName VARCHAR(128) not null,
          crseDiv VARCHAR(128),
          numCredit INT not null,
          stdntLim INT,
          stdntEnr INT,
          strtDate DATE not null,
          endDate DATE not null,
          offrDays1 VARCHAR(8) not null,
          timeFrm1 TIME,
          timeTo1 TIME,
          instrName1 VARCHAR(64) not null,
          rmAbbrv1 VARCHAR(16) not null,
          rmNum1 VARCHAR(32) not null,
          campus1 VARCHAR(32) not null,
          offrDays2 VARCHAR(8),
          timeFrm2 TIME,
          timeTo2 TIME,
          instrName2 VARCHAR(64),
          rmAbbrv2 VARCHAR(16),
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
    $fileLocation = "SPSEM20EnrollmentReportCSV.csv";
    $sql = "LOAD DATA LOCAL INFILE '" . $fileLocation . "'
        INTO TABLE CsvData
        FIELDS TERMINATED BY ','
        ENCLOSED BY '\"'
        LINES TERMINATED BY '\\n'
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
                @timeFrm1,
                @timeTo1,
                instrName1,
                rmAbbrv1,
                rmNum1,
                campus1,
                offrDays2,
                @timeFrm2,
                @timeTo2,
                instrName2,
                rmAbbrv2,
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
            timeFrm1 = STR_TO_DATE(@timeFrm1, '%l:%i%p'),
            timeTo1 = STR_TO_DATE(@timeTo1, '%l:%i%p'),
            timeFrm2 = STR_TO_DATE(@timeFrm2, '%l:%i%p'),
            timeTo2 = STR_TO_DATE(@timeTo2, '%l:%i%p');";

    // run query
    if (!$conn->query($sql)) {
      die("MySQL Error: " . $conn->error);
    }

    // empty results
    while ($conn->more_results()) {
      $conn->next_result();
    }

    // remove internal listings
    $sql = "DELETE
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
    $sql = "INSERT INTO Sections(
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
    $sql = "ALTER TABLE Sections ORDER BY deptID, crseID, secID ASC;";

    // insert data from temporary table into arranged instructors table
    $sql = "INSERT INTO ArrangedInstructors(
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
    $sql = "ALTER TABLE ArrangedInstructors
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
    $sql = "SELECT deptID, crseID, secID, offrDays1
            FROM CsvData
            WHERE offrDays1 != 'ARR';";

    // run query
    $result = $conn->query($sql);
    if (!$result) {
      die("MySQL Error: " . $conn->error);
    }

    // empty results
    while ($conn->more_results()) {
      $conn->next_result();
    }

    // insert session1 data from temporary table into in class sessions table
    while($row = $result->fetch_assoc()) {
      for ($i = 0; $i < strlen($row['offrDays1']); ++$i) {
        $sql = "INSERT INTO InClassSessions(
            deptID,
            crseID,
            secID,
            dayID,
            timeFrm,
            timeTo,
            rmAbbrv
        )
        SELECT
            deptID,
            crseID,
            secID,
            '" . substr($row['offrDays1'], $i, 1) . "',
            timeFrm1,
            timeTo1,
            rmAbbrv1
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
    $sql = "SELECT deptID, crseID, secID, offrDays2
            FROM CsvData
            WHERE offrDays1 != 'ARR' AND
            offrDays2 != '' AND
            (offrDays1 != offrDays2 OR (timeFrm1 != timeFrm2 AND
                                        timeTo1 != timeTo2));";

    // run query
    $result = $conn->query($sql);
    if (!$result) {
      die("MySQL Error: " . $conn->error);
    }

    // empty results
    while ($conn->more_results()) {
      $conn->next_result();
    }

    // insert session2 data from temporary table into in class sessions table
    while($row = $result->fetch_assoc()) {
      for ($i = 0; $i < strlen($row['offrDays2']); ++$i) {
        $sql = "INSERT INTO InClassSessions(
            deptID,
            crseID,
            secID,
            dayID,
            timeFrm,
            timeTo,
            rmAbbrv
        )
        SELECT
            deptID,
            crseID,
            secID,
            '" . substr($row['offrDays2'], $i, 1) . "',
            timeFrm2,
            timeTo2,
            rmAbbrv2
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
    $sql = "ALTER TABLE InClassSessions
        ORDER BY
            deptID,
            crseID,
            secID,
            dayID,
            timeFrm,
            timeTo ASC;";

    // run query
    if (!$conn->query($sql)) {
      die("MySQL Error: " . $conn->error);
    }

    // empty results
    while ($conn->more_results()) {
      $conn->next_result();
    }

    // split days1 into atmoic values
    $sql = "SELECT deptID, crseID, secID, offrDays1, instrName1
            FROM CsvData
            WHERE offrDays1 != 'ARR';";

    // run query
    $result = $conn->query($sql);
    if (!$result) {
      die("MySQL Error: " . $conn->error);
    }

    // empty results
    while ($conn->more_results()) {
      $conn->next_result();
    }

    // insert session1 data from temporary table into in class instructors table
    while($row = $result->fetch_assoc()) {
      for ($i = 0; $i < strlen($row['offrDays1']); ++$i) {
        $sql = "INSERT INTO InClassInstructors(
            deptID,
            crseID,
            secID,
            dayID,
            timeFrm,
            timeTo,
            instrLName,
            instrFName,
            rmNum
        )
        SELECT
            deptID,
            crseID,
            secID,
            '" . substr($row['offrDays1'], $i, 1) . "',
            timeFrm1,
            timeTo1,
            SUBSTRING_INDEX(instrName1, ' ', 1),
            SUBSTRING_INDEX(instrName1, ' ', -1),
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
    $sql = "SELECT deptID, crseID, secID, offrDays2, instrName2
            FROM CsvData
            WHERE offrDays1 != 'ARR' AND offrDays2 != '' AND instrName2 != ''
            AND (offrDays1 != offrDays2 OR timefrm1 != timefrm2 OR timeto1 != timeto2 OR rmNum1 != rmNum2);";

    // run query
    $result = $conn->query($sql);
    if (!$result) {
      die("MySQL Error: " . $conn->error);
    }

    // empty results
    while ($conn->more_results()) {
      $conn->next_result();
    }

    // insert session2 data from temporary table into in class instructors table
    while($row = $result->fetch_assoc()) {
      for ($i = 0; $i < strlen($row['offrDays2']); ++$i) {
        $sql = "INSERT INTO InClassInstructors(
            deptID,
            crseID,
            secID,
            dayID,
            timeFrm,
            timeTo,
            instrLName,
            instrFName,
            rmNum
        )
        SELECT
            deptID,
            crseID,
            secID,
            '" . substr($row['offrDays2'], $i, 1) . "',
            timeFrm2,
            timeTo2,
            SUBSTRING_INDEX(instrName2, ' ', 1),
            SUBSTRING_INDEX(instrName2, ' ', -1),
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

    // sort in class instructors table
    $sql = "ALTER TABLE InClassInstructors
        ORDER BY
            deptID,
            crseID,
            secID,
            dayID,
            timeFrm,
            timeTo,
            instrLName,
            instrFName,
            rmNum ASC";

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

    // close connection
    $conn->close();

    ?>
  </body>
</html>
