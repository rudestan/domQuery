<?php
/**
 *
 * An domQuery Element class is a class that makes manipulation with DOMElement easier. This class
 * also allows to make further use of selectors by calling ->q() method.
 *
 */

namespace domQuery;

use domQuery\Selector;

class Element {

    private $element = null;

    private $innerHTML   = null;
    private $outerHTML   = null;

    /**
     * @param \DOMElement $element
     */
    public function __construct(\DOMElement $element) {
        $this->element = $element;
    }

    /**
     * Method returns inner HTML source of the DOMElement as string.
     * @return null|string
     */
    public function innerHTML() {
        if(!$this->innerHTML) {
            $this->innerHTML = '';
            $children = $this->element->childNodes;
            foreach ($children as $child) {
                $this->innerHTML .= $child->ownerDocument->saveHTML($child);
            }
        }
        return $this->innerHTML;
    }

    /**
     * Method returns outer HTML source of the DOMElement as string
     * @return null|string
     */
    public function outerHTML() {
        if(!$this->outerHTML) {
            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->appendChild($dom->importNode($this->element->cloneNode(true), true));
            $this->outerHTML = $dom->saveHTML();
        }
        return $this->outerHTML;
    }

    /**
     * Shorten wrapper method for outerHTML()
     * @return null|string
     */
    public function html() {
        return $this->outerHTML();
    }

    /**
     * Shorten wrapper for DOMElement::textContent property.
     * @return string
     */
    public function text() {
        return $this->element->textContent;
    }

    /**
     * Forward method to execute selector for current element.
     * @param $selector
     * @return ArrayElement|null
     */
    public function q($selector) {
        $dQ = new Selector($this->element);
        return $dQ->q($selector);
    }

    /**
     * Shorten wrapper for DOMElement::getAttribute() method.
     * @param $attributeName
     * @return string
     */
    public function attr($attributeName) {
        return $this->element->getAttribute($attributeName);
    }

    /**
     * Shorten wrapper for DOMElement::tagName property.
     * @return string
     */
    public function tag() {
        return $this->element->tagName;
    }
} 