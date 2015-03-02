<?php
namespace domQuery\Test;

use domQuery\Selector;

require_once __DIR__ . '/../src/domQuery/Selector.php';
require_once __DIR__ . '/../src/domQuery/Tokenizer.php';
require_once __DIR__ . '/../src/domQuery/XPath.php';
require_once __DIR__ . '/../src/domQuery/ArrayElement.php';
require_once __DIR__ . '/../src/domQuery/Element.php';


class SelectorTest extends \PHPUnit_Framework_TestCase {

    private $selector;

    public function setUp() {
        $this->selector = new Selector();
    }

    public function getDocument($selector) {
        $docMap = array(
            "a[hreflang|='en']"         => __DIR__ . "/data/Selector_1.html",
            "input[name*='man']"        => __DIR__ . "/data/Selector_2.html",
            "input[name~='man']"        => __DIR__ . "/data/Selector_3.html",
            "input[name$='letter']"     => __DIR__ . "/data/Selector_4.html",
            "input[value='Hot Fuzz']"   => __DIR__ . "/data/Selector_5.html",
            "input[name!='newsletter']" => __DIR__ . "/data/Selector_6.html",
            "input[name^='news']"       => __DIR__ . "/data/Selector_7.html",
            ":button"                   => __DIR__ . "/data/Selector_8.html",
            ":checkbox"                 => __DIR__ . "/data/Selector_9.html",
            ".myClass"                  => __DIR__ . "/data/Selector_10.html",
            ":file"                     => __DIR__ . "/data/Selector_11.html",
            ":image"                    => __DIR__ . "/data/Selector_12.html",
            ":password"                 => __DIR__ . "/data/Selector_13.html",
            ":radio"                    => __DIR__ . "/data/Selector_14.html",
            ":reset"                    => __DIR__ . "/data/Selector_15.html",
            ":text"                     => __DIR__ . "/data/Selector_16.html",
        );
        return file_get_contents($docMap[$selector]);
    }

    public function provider_getSelector()
    {
        return array(
            array("a[hreflang|='en']", 2),
            array("input[name*='man']", 3),
            array("input[name~='man']", 1),
            array("input[name$='letter']", 2),
            array("input[value='Hot Fuzz']", 1),
            array("input[name!='newsletter']", 2),
            array("input[name^='news']", 2),
            array(":button", 2),
            array(":checkbox", 2),
            array(".myClass", 2),
            array(":file", 1),
            array(":image", 1),
            array(":password", 1),
            array(":radio", 2),
            array(":reset", 1),
            array(":text", 1),
        );
    }

    /**
     * @dataProvider provider_getSelector
     */
    public function test_getSelector($selector, $expected)
    {
        $this->selector->document($this->getDocument($selector));
        $this->assertSame($expected, $this->selector->q($selector)->length());
    }

}