<?php
namespace domQuery\Test;

use domQuery\Tokenizer;

require_once __DIR__ . '/../src/domQuery/Tokenizer.php';

class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    private $tokenizer;

    public function setUp() {
        $this->tokenizer = new Tokenizer();
    }

    public function provider_getSelector()
    {
        return array(
            array(
                    '#id',
                    '[{"type":1,"raw":"#id","tag":"","name":"id"}]'
            ),
            array(
                    '.class',
                    '[{"type":2,"raw":".class","tag":"","name":"class"}]'
            ),
            array(
                    'tag',
                    '[{"type":3,"raw":"tag","tag":"tag"}]'
            ),
            array(
                    'input[type="submit"]',
                    '[{"type":4,"raw":"input[type=\"submit\"]","tag":"input","name":"type","value":"submit","modifier":""}]'
            ),
            array(
                    'tag .class',
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":2,"raw":".class","tag":"","name":"class"}]'
            ),
            array(
                    'tag #id',
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"}]'
            ),
            array(
                    '#id tag',
                    '[{"type":1,"raw":"#id","tag":"","name":"id"},{"type":3,"raw":"tag","tag":"tag"}]'
            ),
            array(
                    '#id .class',
                    '[{"type":1,"raw":"#id","tag":"","name":"id"},{"type":2,"raw":".class","tag":"","name":"class"}]'
            ),
            array(
                    '.class #id',
                    '[{"type":2,"raw":".class","tag":"","name":"class"},{"type":1,"raw":"#id","tag":"","name":"id"}]'
            ),
            array(
                    '.class tag',
                    '[{"type":2,"raw":".class","tag":"","name":"class"},{"type":3,"raw":"tag","tag":"tag"}]'
            ),
            array(
                    'tag input[type="submit"]',
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":4,"raw":"input[type=\"submit\"]","tag":"input","name":"type","value":"submit","modifier":""}]'
            ),
            array(
                    'tag #id input[type="submit"]',
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":4,"raw":"input[type=\"submit\"]","tag":"input","name":"type","value":"submit","modifier":""}]'
            ),
            array(
                    'tag #id .class input[type="submit"]',
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":2,"raw":".class","tag":"","name":"class"},{"type":4,"raw":"input[type=\"submit\"]","tag":"input","name":"type","value":"submit","modifier":""}]'
            ),
            array(
                    'tag #id .class tag #id input[type="submit"]',
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":2,"raw":".class","tag":"","name":"class"},{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":4,"raw":"input[type=\"submit\"]","tag":"input","name":"type","value":"submit","modifier":""}]'
            ),
            array(
                    'tag #id .class tag #id p[data="test"] b',
                    '[{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":2,"raw":".class","tag":"","name":"class"},{"type":3,"raw":"tag","tag":"tag"},{"type":1,"raw":"#id","tag":"","name":"id"},{"type":4,"raw":"p[data=\"test\"]","tag":"p","name":"data","value":"test","modifier":""},{"type":3,"raw":"b","tag":"b"}]'
            ),
        );
    }
    /**
     * @dataProvider provider_getSelector
     */
    public function test_getSelector($selector, $expected)
    {
        $this->tokenizer->setSelectorString($selector);
        $this->assertSame($expected, json_encode($this->tokenizer->tokenize()));
    }
}