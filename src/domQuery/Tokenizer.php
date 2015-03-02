<?php
/**
 * Query Selectors tokenizer. Very similar to jQuery's Sizzle.
 */

namespace domQuery;


class Tokenizer {

    private $str = '';
    private $tokens = [];

    // Token types
    const T_ID     = 1;
    const T_CLASS  = 2;
    const T_TAG    = 3;
    const T_ATTR   = 4;
    const T_PSEUDO = 5;

    // Attr modifiers

    const M_EMPTY = null;
    const M_PIPE  = '|';
    const M_STAR  = '*';
    const M_TILDE = '~';
    const M_DSIGN = '$';
    const M_EXCL  = '!';
    const M_CIRF  = '^';


    private $supportedPseudos = [
        'button', 'checkbox', 'file', 'image', 'password', 'radio', 'reset', 'text'
    ];

    private $captureRegexMasks = [
        'tag', 'name', 'value', 'modifier'
    ];

    private $tokenRegexes = [
        self::T_ID     => "/^(?P<tag>[\w\*]+)*#(?P<name>(?:\.|[\w-]|[\\\u00A1-\\\uFFFF])+)/",
        self::T_CLASS  => "/^(?P<tag>[\w\*]+)*\.(?P<name>(?:\.|[\w-]|[\\\u00A1-\\\uFFFF])+)/",
        self::T_ATTR   => "/^(?P<tag>[\w\*]+)*\[[\s]*(?P<name>[\w\-]+)[\s]*(?P<modifier>[\|\*\~\$\!\^])*[\s]*=[\s]*[\'\"][\s]*(?P<value>[\w\-\s]+)[\s]*[\'\"][\s]*\]/",
        self::T_TAG    => "/^(?:(?P<tag>[\w\*]+)(?=\s|$))/",
        self::T_PSEUDO => "/^(?P<tag>[\w\*]+)*\:(?:(?P<name>[\w\*]+)(?=\s|$))/"
    ];

    /**
     * @param null $str
     */
    public function __construct($str = null) {
        if($str) {
            $this->setSelectorString($str);
        }
    }

    /**
     * @param $str
     */
    public function setSelectorString($str) {
        $this->str = $str;
    }

    /**
     * @return array
     */
    public function tokenize() {
        $str = $this->str;
        $token = true;
        while($token) {
            if(!$token = $this->executeExpressions($str))
                break;

            $this->tokens[] = $token;
            $str = $this->cutTokenFromString($str, $token);
        }
        return $this->tokens;
    }

    /**
     * @param $str
     * @return array|bool
     */
    private function executeExpressions($str) {
        foreach($this->tokenRegexes as $type => $pattern) {
            if(preg_match($pattern, $str, $matches, PREG_OFFSET_CAPTURE)) {
                $token = [
                    'type' => $type,
                    'raw'  => $matches[0][0]
                ];

                foreach($this->captureRegexMasks as $maskName) {
                    if(isset($matches[$maskName]) && isset($matches[$maskName][0])) {
                        $token[$maskName] = $matches[$maskName][0];
                    }
                }

                // filter only supported pseudos
                if($type == self::T_PSEUDO && !in_array($token['name'], $this->supportedPseudos)) {
                    return false;
                }

                return $token;
            }
        }
        return false;
    }

    /**
     * @param $string
     * @param $token
     * @return string
     */
    private function cutTokenFromString($string, $token) {
        $lastPart  = substr($string, strlen($token['raw']));
        return trim($lastPart);
    }

    /**
     * Debug method to display the resulted tokens.
     * @throws \Exception
     */
    public function dump() {
        if(empty($this->tokens)) {
            throw new \Exception("No tokens!");
        }

        $selector = [];
        foreach($this->tokens as $token) {
            $selector[] = trim($token['raw']);
            switch($token['type']) {
                case self::T_ATTR: echo "T_ATTR\n"; break;
                case self::T_CLASS: echo "T_CLASS\n"; break;
                case self::T_ID: echo "T_ID\n"; break;
                case self::T_TAG: echo "T_TAG\n"; break;
            }
            echo "name: ".$token['name']."\n";
            echo "----------\n";

        }
        echo "\n";
        echo "selector: \"".implode("|", $selector)."\"\n";
    }

} 