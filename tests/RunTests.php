<?php
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class Example extends PHPUnit_Extensions_SeleniumTestCase
{
  function setUp()
  {
	$this->setBrowser("*firefox");
	$this->setBrowserUrl("http://localhost/timex_php/");  
  }

  function testMyTestCase()
  {
    $this->open("/");
    $this->type("q", "selenium rc");
    $this->click("btnG");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("Results * for selenium rc"));
  }
}

?>