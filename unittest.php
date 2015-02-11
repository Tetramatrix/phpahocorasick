<?php
/*
*      Copyright (c) 2014-2015 Chi Hoang 
*      All rights reserved
*/
require_once '/usr/share/php5/PEAR/PHPUnit/Autoload.php';
require_once("ahocorasick.php");

class unittest extends PHPUnit_Framework_TestCase
{   
  public function testexample1()
  {
    $tree = new  Ahocorasick\Ahocorasick();
    $tree->add ("a");
    $tree->add ("ab");
    $tree->add ("bab");
    $tree->add ("bc");
    $tree->add ("bca");
    $tree->add ("c");
    $tree->add ("caa");
    echo $tree->match ("abccab");
    $this->expectOutputString("a,ab,a,ab,c,c,bc"); 
  }
  
  public function testexample2()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("bot");
    $tree->add("otis");
    $tree->add("ott");
    $tree->add("otto");
    $tree->add("tea");
    echo $tree->match("botttea");
    $this->expectOutputString("'ott,bot,tea");
  }
  
  public function testexample3()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("he");
    $tree->add("she");
    $tree->add("his");
    $tree->add("hers");
    echo $tree->match("ushers");
    $this->expectOutputString("hers,he,she");
  }
  
  public function testexample4()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("ananas");
    $tree->add("antani");
    $tree->add("assassin");
    echo $tree->match ("banananassata");
    $this->expectOutputString("ananas");
  }
  
  public function testexample5()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("fast");
    $tree->add("sofa");
    $tree->add("so");
    $tree->add("take");
    echo $tree->match("takesofasofastfassofatakesossosofastakeso");
    $this->expectOutputString("so,take,fast,sofa,so,so,so,sofa,so,fast,sofa,so,sofa,so,take");
  }
  
  public function testexample6()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add ("bc");
    $tree->add ("abc");
    echo $tree->match ("tabc");
    $this->expectOutputString("bc,abc");
  }

  public function testexample7()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("hi");
    $tree->add("hips");
    $tree->add("hip");
    $tree->add("hit");
    $tree->add("chip");
    echo $tree->match("microchips");
    $this->expectOutputString("hi,hips,chips,chip");
  }
}
?>