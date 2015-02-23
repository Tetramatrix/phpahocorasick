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
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add ("a");
    $tree->add ("ab");
    $tree->add ("bab");
    $tree->add ("bc");
    $tree->add ("bca");
    $tree->add ("c");
    $tree->add ("caa");
    echo $tree->match ("abccab");
    $this->expectOutputString("a,ab,bc,c,c,a,ab"); 
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
    $this->expectOutputString("bot,ott,tea");
  }
  
  public function testexample3()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("he");
    $tree->add("she");
    $tree->add("his");
    $tree->add("hers");
    echo $tree->match("ushers");
    $this->expectOutputString("he,she,hers");
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
    $this->expectOutputString("take,so,sofa,so,sofa,fast,so,sofa,take,so,so,so,sofa,fast,take,so");
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
    $this->expectOutputString("hi,hip,chip,hips");
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
    $this->expectOutputString("ab,bab,bc,d,abcde");
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
    $this->expectOutputString("The,e,pot,ha,ha,han,and,e");
  }
  
  public function testexample10()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("mercury");
    $tree->add("venus");
    $tree->add("earth");
    $tree->add("mars");
    $tree->add("jupiter");
    $tree->add("saturn");
    $tree->add("uranus");
    $tree->add("pluto");
    echo $tree->match("XXearthXXvenusaturnXXmarsaturn");
    $this->expectOutputString("earth,venus,saturn,mars,saturn");
  }
  public function testexample11()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("say");
    $tree->add("she");
    $tree->add("shr");
    $tree->add("he");
    $tree->add("her");
    echo $tree->match("yasherhs");
    $this->expectOutputString("she,he,her");
  }
  
  public function testexample12()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("AC");
    $tree->add("GTG");
    $tree->add("AACT");
    echo $tree->match("ACCGAGTGCGTGGACAAACTACGATTGTGGAATGAACT");
    $this->expectOutputString("AC,GTG,GTG,AC,AC,AACT,AC,GTG,AC,AACT");
  }
  public function testexample13()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("mercury");
    $tree->add("venus");
    $tree->add("earth");
    $tree->add("mars");
    $tree->add("jupiter");
    $tree->add("saturn");
    $tree->add("uranus");
    $tree->add("pluto");
    echo $tree->match("XXearthXXvenusaturnXXmarsaturn","ea*turn");
    $this->expectOutputString("earthXXvenusaturn,earthXXvenusaturnXXmarsaturn");
  }
  
  public function testexample14()
  {
    $tree = new Ahocorasick\Ahocorasick();
    $tree->add("AC");
    $tree->add("GTG");
    $tree->add("AACT");
    echo $tree->match("ACCGAGTGCGTGGACAAACTACGATTGTGGAATGAACT","AC*GT");
    $this->expectOutputString("ACCGAGT,ACCGAGTGCGT,ACCGAGTGCGTGGACAAACTACGATTGT,ACAAACTACGATTGT,ACTACGATTGT,ACGATTGT");
  }
}
?>