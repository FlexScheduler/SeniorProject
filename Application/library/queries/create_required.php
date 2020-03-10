<?php
function create_required($conn) {
    // ensure Sections table exists
    $sql = "
        CREATE TABLE IF NOT EXISTS
            Sections(
                deptID VARCHAR(16),
                crseID INT,
                secID VARCHAR(16),
                crseName VARCHAR(128) NOT NULL,
                numCredit int NOT NULL,
                strtDate DATE NOT NULL,
                endDate DATE NOT NULL,
                campus VARCHAR(32) NOT NULL,
                PRIMARY KEY(deptID, crseID, secID)
            )
            ENGINE=INNODB;";
    $result = execute_query($conn, $sql);


    // ensure ArrangedInstructors table exists
    $sql = "
        CREATE TABLE IF NOT EXISTS
            ArrangedInstructors(
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
                    ON DELETE CASCADE
            )
            ENGINE=INNODB;";
    $result = execute_query($conn, $sql);


    // ensure Sessions table exists
    $sql = "
        CREATE TABLE IF NOT EXISTS
            Sessions(
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
                    ON DELETE CASCADE
            ) ENGINE=INNODB;";
    $result = execute_query($conn, $sql);


    // ensure SessionInstructors table exists
    $sql = "
        CREATE TABLE IF NOT EXISTS
            SessionInstructors(
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
                )
                    REFERENCES Sessions(
                        deptID,
                        crseID,
                        secID,
                        dayID,
                        strtTime,
                        endTime
                    )
                    ON DELETE CASCADE
            ) ENGINE=INNODB;";
    $result = execute_query($conn, $sql);


    return;
}
?>
