<?php
// udPattern is CopyRight 2003, Thomas Björk
//
// This code may not be used in any commercial product without a
// written agreement from the author.
// Author : Thomas Björk
// E-mail : thomas@unidev.biz

class udPattern {
  var $patterns;
  
  function udPattern() {
    // Register "destructor"
    register_shutdown_function(array(&$this, "finalize"));
    $this->patterns = array();
  }
  
  function finalize() {
    unset($this->patterns);
  }

  function TestPattern($check, $easyTest = true, $delimiter = ".") {
    $result = ($easyTest === true)?0:false;
    for($i = 0; $i < count($this->patterns); $i++) {
      $pattern = $this->patterns[$i];
      if(preg_match("/^[+-]/U", $pattern, $match)) {
        if($match[0] == "-") {
          $sign = -1;
        } else {
          $sign = 1;
        }
      } else {
        $sign = 1;
      }
      $pattern = preg_replace("/^[+-]/U", "", $pattern, 1);
      if($this->CheckPattern($pattern, $check, $delimiter)) {
        return $sign * ($i + 1);
      }
    }
    return $result;
  }
    
  function CleanPattern($pattern, $delimiter = ".") {
    $check = preg_quote("*$delimiter*$delimiter,$delimiter*$delimiter*,%*,?*,?%,**,%%,%*");
    $check = str_replace(",","|",$check);
    while(preg_match("/$check/U",$pattern,$matches)) {
      switch($matches[0]) {
        case "$delimiter*$delimiter*" : 
          $pattern = str_replace("$delimiter*$delimiter*","$delimiter*",$pattern);
          break;
        case "*$delimiter*$delimiter" : 
          $pattern = str_replace("*$delimiter*$delimiter","*$delimiter",$pattern);
          break;
        case "%*" : 
          $pattern = str_replace("%*","*%",$pattern);
          break;
        case "?*" : 
          $pattern = str_replace("?*","*?",$pattern);
          break;
        case "?%" : 
          $pattern = str_replace("?%","%?",$pattern);
          break;
        case "**" : 
          $pattern = str_replace("**","*",$pattern);
          break;
        case "%%" : 
          $pattern = str_replace("%%","%",$pattern);
          break;
        case "%*" : 
          $pattern = str_replace("%*","*%",$pattern);
          break;
        default :
          echo serialize($matches)." ".$pattern;
        exit;
      }
    }
    return $pattern;
  }
  
  function CheckPattern($p1, $p2, $delimiter = ".") {
    $p1 = preg_replace("/^[+-]/U", "", $p1, 1);
    $p2 = preg_replace("/^[+-]/U", "", $p2, 1);
    if(preg_match_all("/(^\*\.)|[\*%\?]/U", $p1, $p1keys, PREG_PATTERN_ORDER)) {
      $p1 = str_replace(".","\.",$p1);
      $p1 = str_replace("?", "(.)", $p1);
      $p1 = preg_replace("/^\*\\\.|\*/U", "(.*?)", $p1);
      $p1 = str_replace("%", "(.*?)", $p1);
      if(preg_match_all("/$p1/U", $p2, $matches, PREG_PATTERN_ORDER)) {
        if(count($p1keys[0]) + 1 == count($matches)) {
          for($i = 0; $i < count($p1keys); $i++) {
            switch($p1keys[0][$i]) {
              case "*" : 
                break;
              case "*$delimiter" :
                if(preg_match("/^(?:.*)".preg_quote($delimiter)."(.*?)$/U",$matches[$i+1][0],$m)) {
                  if(preg_match("/^(?:.*)".preg_quote($delimiter)."(.*?)$/U",$m[1],$z)) {
                    if(strlen($z[1]) > 0) {
                      return false;
                    }
                  }
                }
                break;
              case "%" :
                if(preg_match("/".preg_quote($delimiter)."/U",$matches[$i+1][0],$m)) {
                  return false;
                }
                break;
              case "?" :
                if($matches[$i+1][0] == $delimiter) {
                  return false;
                } elseif(strlen($matches[$i+1][0]) === 0) {
                  return false;
                }
                break;
              case "" :
                if(strlen($matches[$i+1][0]) != 0) {
                  return false;
                }
                break;
              default :
                echo "[".$p1keys[0][$i]." / ".$matches[$i+1][0]." Argyle]";
            }
          }
        } else {
          return false;
        }
        return true;
      } else {
        return false;
      }
    } else {
      if($p1 == $p2) {
        return true;
      } else {
        return false;
      }
    }
  }
  
  function SetPatternArray($patterns) {
    $this->patterns = $patterns;
  }
  
  function SetPatternString($patterns, $delimiter = ";") {
    $delimiter = preg_quote($delimiter);
    $this->patterns = split($delimiter, $patterns);
  }
  
  function ClearPatterns() {
    unset($this->patterns);
    $this->patterns = array();
  }
  
  function Add($pattern, $delimiter = ".") {
    $pattern = $this->CleanPattern($pattern, $delimiter);
    foreach($this->patterns as $p) {
      if($this->CheckPattern($p, $pattern, $delimiter)) {
        return false;
      }
    }
    $this->patterns[] = $pattern;
    return true;
  }
  
}
?>