<?php
/***************************************************************
*
*  (c) 2015 Chi Hoang (info@chihoang.de)
*  All rights reserved
*  
***************************************************************/

require_once ( "ahocorasick.php" );
//////////////////////////////
//$tree = new Ahocorasick\Ahocorasick ();
//$tree->add ("a");
//$tree->add ("ab");
//$tree->add ("bab");
//$tree->add ("bc");
//$tree->add ("bca");
//$tree->add ("c");
//$tree->add ("caa");
//echo $tree->match ("abccab");

//////////////////////////////
//$tree = new Ahocorasick\ahocorasick ();
//$tree->add ("bc");
//$tree->add ("abc");
//echo $tree->match ("tabc");

//////////////////////////////
//$tree = new Ahocorasick\Ahocorasick ();
//$tree->add("ananas");
//$tree->add("antani");
//$tree->add("assassin");
//echo $tree->match ("banananassata");

//////////////////////////////  
//$tree = new Ahocorasick\ahocorasick ();
//$tree->add("he");
//$tree->add("she");
//$tree->add("his");
//$tree->add("hers");
//echo $tree->match("ushers");


/////////////////////////////
//$tree = new Ahocorasick\Ahocorasick();
//$tree->add("bot");
//$tree->add("otis");
//$tree->add("ott");
//$tree->add("otto");
//$tree->add("tea");
//echo $tree->match("botttea");


$tree = new Ahocorasick\Ahocorasick();
$tree->add("fast");
$tree->add("sofa");
$tree->add("so");
$tree->add("take");
echo $tree->match("takesofasofastfassofatakesossosofastakeso");

//$tree = new Ahocorasick\Ahocorasick();
//$tree->add("one");
//$tree->add("two");
//$tree->add("three");
//$tree->add("four");
//echo $tree->match("Hey one! How are you?");


//$tree = new Ahocorasick\Ahocorasick();
//$tree->add("hi");
//$tree->add("hips");
//$tree->add("hip");
//$tree->add("hit");
//$tree->add("chip");
//echo $tree->match("microchips");

//$tree = new Ahocorasick\Ahocorasick();
//$tree->add("ab");
//$tree->add("bc");
//$tree->add("bab");
//$tree->add("d");
//$tree->add("abcde");
//echo $tree->match("xbabcdex");


?>