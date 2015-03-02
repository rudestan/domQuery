<?php
namespace domQuery\Test;

use domQuery\XPath;

require_once __DIR__ . '/../src/domQuery/Xpath.php';
require_once __DIR__ . '/../src/domQuery/Tokenizer.php';

class XpathTest extends \PHPUnit_Framework_TestCase
{
    private $xpath;

    public function setUp() {
        $this->xpath = new XPath();
    }

    public function provider_xpath()
    {
        return array(
            array(
                    // #id
                    '[{"type":1,"raw":"#id","tag":"","name":"id"}]',
                    "//*[contains(concat(' ', @id, ' '), ' id ')]"
            ),

            array(
                    // .class
                    '[{"type":2,"raw":".class","tag":"","name":"class"}]',
                    "//*[contains(concat(' ', @class, ' '), ' class ')]"
            ),

            array(
                    // tag
                    '[{"type":3,"raw":"tag","tag":"tag"}]',
                    '//tag'
            ),

            array(
                    // input[type="submit"]
                    '[{"type":4,"raw":"input[type=\"submit\"]","tag":"input","name":"type","value":"submit","modifier":""}]',
                    "//input[@type='submit']"
            ),

            array(
                    // tag .class
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":2,"raw":".class","tag":"","name":"class"}]',
                    "//tag//*[contains(concat(' ', @class, ' '), ' class ')]"
            ),

            array(
                    // tag #id
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"}]',
                    "//tag//*[contains(concat(' ', @id, ' '), ' id ')]"
            ),

            array(
                    // #id tag
                    '[{"type":1,"raw":"#id","tag":"","name":"id"},{"type":3,"raw":"tag","tag":"tag"}]',
                    "//*[contains(concat(' ', @id, ' '), ' id ')]//tag"
            ),

            array(
                    // #id .class
                    '[{"type":1,"raw":"#id","tag":"","name":"id"},{"type":2,"raw":".class","tag":"","name":"class"}]',
                    "//*[contains(concat(' ', @id, ' '), ' id ')]//*[contains(concat(' ', @class, ' '), ' class ')]"
            ),

            array(
                    // .class #id
                    '[{"type":2,"raw":".class","tag":"","name":"class"},{"type":1,"raw":"#id","tag":"","name":"id"}]',
                    "//*[contains(concat(' ', @class, ' '), ' class ')]//*[contains(concat(' ', @id, ' '), ' id ')]"
            ),

            array(
                    // .class tag
                    '[{"type":2,"raw":".class","tag":"","name":"class"},{"type":3,"raw":"tag","tag":"tag"}]',
                    "//*[contains(concat(' ', @class, ' '), ' class ')]//tag"
            ),

            array(
                    // tag input[type="submit"]
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":4,"raw":"input[type=\"submit\"]","tag":"input","name":"type","value":"submit","modifier":""}]',
                    "//tag//input[@type='submit']"
            ),

            array(
                    // tag #id input[type="submit"]
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":4,"raw":"input[type=\"submit\"]","tag":"input","name":"type","value":"submit","modifier":""}]',
                    "//tag//*[contains(concat(' ', @id, ' '), ' id ')]//input[@type='submit']"
            ),

            array(
                    // tag #id .class input[type="submit"]
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":2,"raw":".class","tag":"","name":"class"},{"type":4,"raw":"input[type=\"submit\"]","tag":"input","name":"type","value":"submit","modifier":""}]',
                    "//tag//*[contains(concat(' ', @id, ' '), ' id ')]//*[contains(concat(' ', @class, ' '), ' class ')]//input[@type='submit']"
            ),

            array(
                    // tag #id .class tag #id input[type="submit"]
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":2,"raw":".class","tag":"","name":"class"},{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":4,"raw":"input[type=\"submit\"]","tag":"input","name":"type","value":"submit","modifier":""}]',
                    "//tag//*[contains(concat(' ', @id, ' '), ' id ')]//*[contains(concat(' ', @class, ' '), ' class ')]//tag//*[contains(concat(' ', @id, ' '), ' id ')]//input[@type='submit']"
            ),

            array(
                    // tag #id .class tag #id p[data="test"] b
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":2,"raw":".class","tag":"","name":"class"},{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":4,"raw":"p[data=\"test\"]","tag":"p","name":"data","value":"test","modifier":""},{"type":3,"raw":"b","tag":"b"}]',
                    "//tag//*[contains(concat(' ', @id, ' '), ' id ')]//*[contains(concat(' ', @class, ' '), ' class ')]//tag//*[contains(concat(' ', @id, ' '), ' id ')]//p[@data='test']//b"
            ),
        );
    }
    /**
     * @dataProvider provider_xpath
     */
    public function test_xpath($tokens, $expected)
    {
        $this->xpath->setTokens(json_decode($tokens, true));
        $this->assertSame($expected, $this->xpath->buildXpath());
    }
}