#udPattern

Quick documentation

##TestPattern($check, $easyTest, $delimiter)
Test a pattern against the registered patterns in the object.
returns     returns a positive number for any accepted match
            returns a negative number for any rejected match
            returns 0 for any any test that doesn't match anything unless 
            $easyTest is set to false, then it returns false for any 
            unmatched test
$check      the pattern to test
$easyTest   if set to false the test will return false if no matching found
$delimiter  the delimiter used

##CleanPattern($pattern, $delimiter)
Clean up a pattern. Removes redundant information and reorders the pattern.
returns     a clean pattern
$pattern    the pattern to clean
$delimiter  the delimiter used

##CheckPattern($pattern1, $pattern2, $delimiter)
Check is $pattern2 can be matched against $pattern1. Any wildcard in $pattern2 
will be ignored.
returns     true if match
            false if not
$delimiter  the delimiter used

##SetPatternArray($array)
Set a pre-defined array of patterns.
$array      a one-dimension array with patterns
$delimiter  the delimiter used

##SetPatternString($pattern, $delimiter)
Set a string of patterns
$pattern    a string of patterns
$delimiter  the delimiter used to separate patterns

##ClearPatterns()
Clear the internal pattern array

##Add($pattern, $delimiter)
Add a pattern to the internal array. All patterns that are added will be cleaned
and tested for redundancy.
$pattern    the pattern to add
$delimiter  the delimiter used


#Validation order
The validation of a pattern is made in the added order. First added is first checked.
If you use the Add function then the added pattern vill be tested against previously added patterns. If the new pattern is redundant then it will not be added.

#Patterns
* \* can be matched against any string
* % can be matched against any string that doesn't contain the delimiter used
* ? can be matched against one character that isn't equal to the delimiter

* patterns that starts with \*.zzz.zzz match zzz.zzz
* patterns that starts with %.zzz.zzz doesn't match zzz.zzz
* patterns like zzz\*.zzz match zzzzz.zzz and zzz.zzz.zzz
* patterns like zzz%.zzz match zzzzz.zzz but not zzz.zzz.zzz
* patterns like \*%.zzz.zzz match zzz.zzz.zzz and zzz.zzz.zzz.zzz 
                               and .zzz.zzz but not zzz.zzz
* patterns like \*?.zzz.zzz match zzz.zzz.zzz and zzz.zzz.zzz.zzz 
                               but not .zzz.zzz and zzz.zzz

#Cleaning
* \*\* is redundant and replaced with \*
* %% is redundant and replaced with %
* .\*.\* is redundant and replaced with .\*
* \*.\*. is redundant and replaced with \*.

* ?\* is reordered to \*?
* ?% is reordered to %?
* %\* is reordered to \*%


Note! In the sections Patterns and Cleaning the default $delimiter (.) is used.