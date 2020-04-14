<?php
function convert_term($term)
{
    $strtMonth;
    $endMonth;
    switch ($term) {
        case "fall":
            $strtMonth = 8;
            $endMonth = 12;
            break;
        case "spring":
            $strtMonth = 1;
            $endMonth = 5;
            break;
        case "summer":
            $strtMonth = 5;
            $endMonth = 8;
            break;
    }

    return (array($strtMonth, $endMonth));
}
