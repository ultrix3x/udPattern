<?php
// Example
include_once("udPattern.php");
$check = new udPattern();
$check->Add("www.www.www", ".");
$check->Add("%.www.www", ".");
$check->Add("-*", ".");
if(($test = $check->TestPattern($_SERVER['HTTP_HOST'],false,".")) !== false) {
  if($test < 0) {
    echo "The server does not accept this address";
  } elseif($test > 0) {
    echo "The address is accepted by the server";
  } else {
    echo "The server uses easyTest and has accepted the address without any matching found";
  }
} else {
  echo "Failed to match the address";
}
?>