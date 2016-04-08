<?php
function casiPalindromo($palabra)
{
	$reverse = strrev($palabra);
	return $prom;
}
<?php
$word = "level";  // declare a varibale
echo "String: " . $word . "<br>";
$reverse = strrev($word); // reverse the word
if ($word == $reverse) // compare if  the original word is same as the reverse of the same word
    echo 'Output: This string is a palindrome';
else
    echo 'Output: This is not a palindrome';
