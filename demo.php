<?php

require_once('./src/domQuery/Selector.php');
require_once('./src/domQuery/Element.php');
require_once('./src/domQuery/ArrayElement.php');
require_once('./src/domQuery/Tokenizer.php');
require_once('./src/domQuery/XPath.php');


$doc = '
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>class demo</title>
</head>
<body>

<div class="notMe">div class="notMe"</div>
<div class="myClass">div class="myClass"</div>
<span class="myClass">span class="myClass"</span>
<div>
    <div class="parentClass">
        Here goes parent text of the class!
        <p id="idSubElement">
            here is sub element!
        </p>
    </div>
</div>

</body>
</html>
';

// 1. Length and text()

// get the length

$els = domQuery\Selector::i($doc)->q('.myClass');
echo "count is: ".$els->length(); // 2 , there are two elements (DIV and SPAN) with class 'myClass'
echo "\n";

if($els->length()) {
    foreach($els as $el) {
        echo $el->text()."\n"; // echo the text of each element: 1 - div class="myClass", 2 - span class="myClass"
    }
    echo "\n";
}

// 2. Nested selectors

// let's select P element with the id = idSubElement

// there are two ways to do it:

$els = domQuery\Selector::i($doc)->q('.parentClass #idSubElement');

// or

$els = domQuery\Selector::i($doc)->q('.parentClass')->q('#idSubElement');

echo trim($els[0]->text()); // echo trimmed text "here is sub element!"


