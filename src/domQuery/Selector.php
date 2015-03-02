<?php
/**
 *
 * The main domQuery selector class that queries the DOMDocument or HTML source.
 *
 */

namespace domQuery;

use domQuery\ArrayElement;
use domQuery\Element;
use domQuery\Tokenizer;
use domQuery\XPath;

class Selector {

    const DOM_VERSION = '1.0';
    const DOM_ENCODING = 'UTF-8';

    private $dom = null;

    /**
     * @param null $entity
     */
    public function __construct($entity = null) {
        if($entity) {
            $this->document($entity);
        }
    }

    /**
     * Static method creates a new instance of domQuery\Selector for faster usage
     * @param null $entity
     * @return Selector
     */
    public static function i($entity = null) {
        return new self($entity);
    }

    /**
     * Method creates DOMDocument from DOMElement or source.
     * @param $item
     */
    public function document($item) {
        $this->dom = new \DOMDocument(self::DOM_VERSION, self::DOM_ENCODING);
        if($item instanceof \DOMElement) {
            $this->dom->appendChild(
                $this->dom->importNode(
                    $item->cloneNode(true), true
                )
            );
        } else {
            @$this->dom->loadHTML($item);
        }
    }

    /**
     * @return \DOMDocument
     */
    public function getDom() {
        return $this->dom;
    }

    /**
     * @return string
     */
    public function getDomHTML() {
        return $this->dom->saveHTML();
    }

    /**
     * Method selects the DOMElements and returns domQuery\ArrayElement object.
     * @param $query
     * @return ArrayElement|null
     */
    public function q($query) {
        $nodes = $this->runQuery($query);

        $elements = new ArrayElement();
        foreach ($nodes as $node) {
            $element = new Element($node);
            $elements->append($element);
        }
        return $elements;
    }

    /**
     * Method creates tokenize the string and build domQuery\XPath from resulted tokens.
     * @param $query
     * @return \DOMNodeList|null
     */
    private function runQuery($query) {
        $tokenizer = new Tokenizer($query);
        $tokens = $tokenizer->tokenize();

        if(empty($tokens)) {
            return null;
        }

        $xpath = new XPath($this->dom, $tokens);
        $xpath->buildXpath();
        return $xpath->executeXpathQuery();
    }

} 