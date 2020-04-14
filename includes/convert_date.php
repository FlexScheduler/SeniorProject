<?php
function convert_date($strtDate)
{
    $term = "";
    $strt = new DateTime($strtDate);
    $strtMonth = date_format($strt, "n");
    switch ($strtMonth) {
        case 7:
            $term = "fall";
            break;
        case 8:
            $term = "fall";
            break;
        case 9:
            $term = "fall";
            break;
        case 12:
            $term = "spring";
            break;
        case 1:
            $term = "spring";
            break;
        case 2:
            $term = "spring";
            break;
        case 4:
            $term = "summer";
            break;
        case 5:
            $term = "summer";
            break;
        case 6:
            $term = "summer";
            break;
        default:
            $term = "ONLINE";
            break;
    }

    return ($term);
}
