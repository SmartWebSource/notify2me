<?php

/**
 * We will use this function for styling validation error message
 * @param string
 * @return string
 */
function validation_error($message, $elementId = '', $optional = false) {
    if ($message == '' && $optional == true) {
        return '';
    }
    $myMessage = $message == "" ? "*" : "[$message]";
    $elmId = $elementId != '' ? "id=ve-" . trim($elementId) : '';
    return "<small $elmId class='validation-error'>$myMessage</small>";
}

function validationHints() {
    return "<small class='validation-error-hints pull-left'><i>All fields marked with an asterisk (*) are required.</i></small>";
}

/**
 * Display pagination summery
 *
 * @param int $totalData
 * @param int $dataPerPage
 * @param int $currentPage
 */
function getPaginationSummery($totalData, $dataPerPage, $currentPage) {
    $paginationSummery = "";
    if ($totalData > $dataPerPage) {
        if ($currentPage == 1) {
            $paginationSummery = "Showing 1 to $dataPerPage records of $totalData";
        } else {
            if (($totalData - $currentPage * $dataPerPage) > $dataPerPage) {
                $from = ($currentPage - 1) * $dataPerPage + 1;
                $to = $currentPage * $dataPerPage;
                $paginationSummery = "Showing $from to $to records of $totalData";
            } else {
                $from = ($currentPage - 1) * $dataPerPage + 1;
                $to = ($totalData - ($currentPage - 1) * $dataPerPage) + ($currentPage - 1) * $dataPerPage;
                $paginationSummery = "Showing $from to $to records of $totalData";
            }
        }
    }
    return $paginationSummery;
}


function toastMessage($message, $type = 'success') {
    return ['message' => $message, 'type' => $type];
}

function formatAmount($value) {
    return number_format((float) $value, 2, '.', '');
}