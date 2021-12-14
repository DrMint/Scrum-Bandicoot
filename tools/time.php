<?php

  function secondsToHumanReadable($seconds) {
    if ($seconds < 60) return $seconds . " s";
    $minutes = round($seconds / 60);
    if ($minutes < 60) return $minutes . " mins";
    $hours = round($minutes / 60);
    if ($hours < 24) return $hours . " hours";
    $days = round($hours / 24);
    return $days . " days";
  }

  function convertDateToSecond($date) {
    str_replace('-', '.', $date);
    return strtotime($date);
  }

?>