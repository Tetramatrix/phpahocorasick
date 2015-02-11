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
    $this->expectOutputString("a,ab,a,ab,c,bc,c"); 
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
    $this->expectOutputString("ott,bot,tea");
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
    $this->expectOutputString("take,sofa,so,fast,sofa,so,take,fast,sofa,so,so,so,take,fast,sofa,so,so");
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
    $this->expectOutputString("hips,hip,hi,chip");
  }
  
  public function testexample8()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("ab");
    $tree->add("bc");
    $tree->add("bab");
    $tree->add("d");
    $tree->add("abcde");
    echo $tree->match("xbabcdex");
    $this->expectOutputString("abcde,ab,bab,ab,bc,d");
  }
  
  public function testexample9()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("The");
    $tree->add("han");
    $tree->add("and");
    $tree->add("pork");
    $tree->add("port");
    $tree->add("pot");
    $tree->add("ha");
    $tree->add("e");
    echo $tree->match("The pot had a handle");
    $this->expectOutputString("The,e,pot,ha,han,and,ha,e");
  }
}
?>