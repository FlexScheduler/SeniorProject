<?php
function upload_temp($conn, $targetFilePath, $strtMonth, $endMonth) {
    // create temp table
    $sql = "
        CREATE TABLE IF NOT EXISTS
            Temp(
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
    $result = execute_query($conn, $sql);


    // upload data from file to temp table
    $sql = "
        LOAD DATA LOCAL INFILE
            '" . $targetFilePath . "'
        INTO TABLE
            Temp
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
    $result = execute_query($conn, $sql);
    

    // delete internal listings
    $sql = "
        DELETE
        FROM
            Temp
        WHERE
            crseDiv = 'LU-INTERNAL';";
    $result = execute_query($conn, $sql);


    // delete listings from terms not selected
    $sql = "
        DELETE
        FROM
            Temp
        WHERE
        MONTH(strtDate) < " . $strtMonth . " OR
        MONTH(endDate) > " . $endMonth . ";";
    $result = execute_query($conn, $sql);


    return;
}
?>
