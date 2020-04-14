<?php
    // decode academic data
    if (isset($_POST["pAcademicData"])) {
        $academicData = stripcslashes($_POST["pAcademicData"]);
        $academicData = json_decode($academicData, true);
    }

    // decode courses data
    if (isset($_POST["pCourseData"])) {
        $courseData = stripcslashes($_POST["pCourseData"]);
        $courseData = json_decode($courseData, true);
    }

    // decode personal data
    if (isset($_POST["pPersonalData"])) {
        $personalData = stripcslashes($_POST["pPersonalData"]);
        $personalData = json_decode($personalData, true);
    }
