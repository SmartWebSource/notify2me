<?php

use App\Country;
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

function get_timezones() 
{
    //flip key and value because user filters by full country name
    $countries = array_flip(Country::countryNames());
    $timezone_by_country = array();
    foreach ($countries as $country => $country_code)
    {
        //get all timezones by country code
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY,$country_code);
        $timezone_offsets = array();

        //for each timezone get UTC offset
        foreach( $timezones as $timezone )
        {
            $tz = new DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
        }

        //sort by offset, use ksort for sorting by timezone
        asort($timezone_offsets);

        $timezone_list = array();
        foreach( $timezone_offsets as $timezone => $offset )
        {
            //UTC - or UTC +
            $offset_prefix = $offset < 0 ? '-' : '+';
            //offset to 11:00 format
            $offset_formatted = gmdate( 'H:i', abs($offset) );
            //formatted string
            $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
            
            //timezone to datetime
            $t = new DateTimeZone($timezone);
            $c = new DateTime(null, $t);
            //format hour:minute AM/PM
            $current_time = $c->format('g:i A');

            //remove underscores, dashes, continents/areas and add abbreviation periods
            $pretty_timezone = pretty_timezone_name($timezone);
            $timezone_list[$timezone] = "$current_time - $pretty_timezone (${pretty_offset})";
        }
        $timezone_by_country[$country] = $timezone_list;
    }

    return $timezone_by_country;
}

function pretty_timezone_name($name)
{
    $name = str_replace('/', ', ', $name);
    $name = str_replace('_', ' ', $name);
    $name = str_replace('St ', 'St. ', $name);
    $name = str_replace(
        array("America,","Pacific,","Asia,","Europe,","Antarctica,","Africa,","Atlantic,"),
        array(""),
        $name
    );
    return $name;
}