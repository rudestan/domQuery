<?php
/**
 * Class for bulding \DOMXpath to make XPath query.
 */

namespace domQuery;

use domQuery\Tokenizer;


class XPath {

    private $tokens = [];
    private $document = null;
    private $xpath = null;

    // XPath templates to create xpath string from token
    private $xpathTemplates = [
        Tokenizer::T_TAG       => "%tag%",
        Tokenizer::T_ATTR => [
            Tokenizer::M_EMPTY => "%tag%[@%name%='%value%']",
            Tokenizer::M_PIPE  => "%tag%[@%name%='%value%' or starts-with(@%name%, '%value%-')]",
            Tokenizer::M_STAR  => "%tag%[contains(@%name%, '%value%')]",
            Tokenizer::M_TILDE => "%tag%[contains(concat(' ', @%name%, ' '), ' %value% ')]",
            Tokenizer::M_DSIGN => "%tag%[substring(@%name%, string-length(@%name%) - string-length('%value%') + 1) = '%value%']",
            Tokenizer::M_EXCL  => "%tag%[not(@%name% and contains(@%name%, '%value%'))]",
            Tokenizer::M_CIRF  => "%tag%[starts-with(@%name%, '%value%')]"
        ],
        Tokenizer::T_CLASS     => "%tag%[contains(concat(' ', @class, ' '), ' %name% ')]",
        Tokenizer::T_ID        => "%tag%[contains(concat(' ', @id, ' '), ' %name% ')]",
        Tokenizer::T_PSEUDO    => "//%name% | //input[@type='%name%']",
    ];

    /**
     * @param \DOMDocument $document
     * @param null $tokens
     */
    public function __construct(\DOMDocument $document = null, $tokens = null) {
        $this->tokens = $tokens;
        $this->document = $document;
    }

    /**
     * @return \DOMNodeList
     */
    public function executeXpathQuery() {
        $domXpath = new \DOMXPath($this->document);
        return $domXpath->query($this->xpath);
    }

    public function buildXpath() {
        $this->xpath = null;

        $xpathItems = [];
        foreach($this->tokens as $token) {
            $xpathItem = $this->buildXpathFromToken($token);
            if(!$xpathItem) {
                continue;
            }

            $xpathItems[] = $xpathItem;
        }

        if(!empty($xpathItems)) {
            $this->xpath = '//'.implode('//', $xpathItems);
        }

        return $this->xpath;
    }

    /**
     * @param $tokens
     */
    public function setTokens($tokens) {
        $this->tokens = $tokens;
    }

    /**
     * @param \DOMDocument $document
     */
    public function setDocument(\DOMDocument $document) {
        $this->document = $document;
    }

    /**
     * @return string
     */
    public function getXpath() {
        return $this->xpath;
    }

    /**
     * @param $xpath
     */
    public function setXpath($xpath) {
        $this->xpath = $xpath;
    }

    /**
     * Method returns XPath template depending on token type and it modifier (if any).
     * @param $token
     * @return null|string
     */
    private function getXpathTemplateByToken($token) {
        if(!isset($this->xpathTemplates[$token['type']])) {
            return null;
        }

        $template = $this->xpathTemplates[$token['type']];
        if($token['type'] == Tokenizer::T_ATTR) {
            if(!isset($template[$token['modifier']])) {
                return null;
            }
            $template = $template[$token['modifier']];
        }

        return $template;
    }

    /**
     * Method replaces %key% values in template by corresponding values of token.
     * @param $template
     * @param $token
     * @return mixed
     */
    private function applyXpathTemplate($template, $token) {
        foreach($token as $key => $val) {
            $template = str_replace('%'.$key.'%', $val, $template);
        }
        return $template;
    }

    /**
     * Method builds an XPath from token values.
     * @param $token
     * @return null|string
     */
    private function buildXpathFromToken($token) {
        $xpathTemplate = $this->getXpathTemplateByToken($token);
        if(!$xpathTemplate) {
            return null;
        }

        $token['tag'] = strlen($token['tag']) ? $token['tag'] : '*';
        return $this->applyXpathTemplate($xpathTemplate, $token);
    }

} 