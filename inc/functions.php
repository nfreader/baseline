<?php

//Baseline functions that are always useful/required

/**
 * singular
 *
 * Based on the input, outputs the singular or plural of the specified unit
 *
 * @param $value (int) The value we're looking at
 * @param $one (string) The output if the value is one
 * @param $many (string) The output if the value is greater than one
 *
 * @return string
 *
 */

function singular($value, $one, $many) {
  if ($value == 1) {
    return number_format($value)." $one";
  } else {
    return number_format($value)." $many";
  }
}

/**
 * icon
 *
 * Renders the necessary HTML for a FontAwesome icon!
 *
 * @param $icon (string) The name of JUST the icon.
 * See @link http://fontawesome.io/icons/ for a full listing
 *
 * @param $class (string) An optional class to add to the icon.
 * Technically could be a part of $icon but where's the fun in that?!
 *
 * @return (string) The HTML for a FontAwesome icon!
 */

function icon($icon, $class = '') {
  return "<i class='fa fa-".$icon." ".$class."'></i> ";
}

/**
 * pick
 *
 * Grabs one item from a list or an array of choices
 *
 * @param $list (mixed) Either a comma separated list or an array of choices to
 * pick from
 *
 * @return (string) A random item from the specified list
 */

function pick($list) {
  if (!is_array($list)) {
    $list = explode(',',$list);
  }
  return $list[floor(rand(0,count($list)-1))];
}

/**
 * returnError
 *
 * Formats an error message to be displayed to the end-user
 *
 * @param $msg (string) The error message
 *
 * @return (mixed) Returns an error message formatted as specified by the
 * application.
 * 
 */

function returnError($msg) {
  return json_encode(array('message'=>$msg,'level'=>0),JSON_FLAGS);
}

/**
 * returnMessage
 *
 * Formats a message to be displayed to the end-user
 *
 * @param $msg (string) The message
 *
 * @return (mixed) Returns a message formatted as specified by the
 * application.
 * 
 */

function returnMessage($msg) {
  return json_encode(array('message'=>$msg,'level'=>2),JSON_FLAGS);
}

/**
 * returnSuccess
 *
 * Formats a success message to be displayed to the end-user
 *
 * @param $msg (string) The message
 *
 * @return (mixed) Returns a success message formatted as specified by the
 * application.
 * 
 */

function returnSuccess($msg) {
return json_encode(array('message'=>$msg,'level'=>1),JSON_FLAGS);
}

/**
 * relativeTime
 *
 * Formats a date stamp to a relative timestamp
 *
 * @param $date (string) The date in question
 * @param $postfix (string) The string to come after the relative date.
 * Defaults to 'ago' (or 'just now' if the date is within 30 seconds from now)
 *
 * @return (string) A relative date
 * 
 */

function relativeTime($date, $postfix = 'ago') {
  $diff = time() - strtotime($date);
  if ($diff < 0) {
    $diff = strtotime($date) - time();
    $postfix = 'from now';
  }
  if ($diff >= 604800) {
    $diff = round($diff/604800);
    $return = $diff." week". ($diff != 1 ? 's' : '');
  }
  elseif ($diff >= 86400) {
    $diff = round($diff/86400);
    $return = $diff." day". ($diff != 1 ? 's' : '');
  }
  elseif ($diff >= 3600) {
    $diff = round($diff/3600);
    $return = $diff." hour". ($diff != 1 ? 's' : '');
  }
  elseif ($diff >= 60) {
    $diff = round($diff/60);
    $return = $diff." minute". ($diff != 1 ? 's' : '');
  }
  elseif ($diff <= 30) {
   return "just now";
  }
  else {
    $return = $diff." second". ($diff != 1 ? 's' : '');
  }
  return "$return $postfix";
}

/**
 * timeStamp
 *
 * Returns the HTML for a relative timestamp with a 'title' attribute that
 * gives the actual time. 
 *
 * @param $date (string) The date in question
 *
 * @return (string) (HTML) The HTML with the relative timestamp visible and the
 * actual date added as a title attribute
 * 
 */

function timestamp($date) {
  if(defined('DATE_FORMAT')){
    $format = 'l jS \of F Y h:i:s A';
  } else {
    $format = DATE_FORMAT;
  }
  return "<span class='time' data-toggle='tooltip' title='".date($format,strtotime($date))."'>".relativeTime($date)."</span>";
}

function alert($msg) {
  switch($msg['level']) {
    case 2:
    default:
      $level = 'info';
    break;

    case 1:
      $level = 'success';
    break;

    case 2:
      $level = 'danger';
    break;
  }
  $return = "<div class='alert alert-$level alert-dismissable' role='alert'>";
  $return.= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
  $return.= $msg['message'];
  $return.= "</div>";
  return $return;
}