<?php
class Example extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
	$this->setBrowser("*firefox");
	$this->setBrowserUrl("http://localhost/timex_php/");  
  }

  public function testMyTestCase()
  {
    $this->open("/timex_php/signin.php");
    $this->type("name=username", "123-45-6789");
    $this->type("name=password", "12345");
    $this->click("name=submitted");
    $this->waitForPageToLoad("10000");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ("Timesheets List" == $this->getText("css=h1.title")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->verifyTextPresent("Employee :Mike Dover");
  }
}
?>