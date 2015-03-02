domQuery
========

domQuery is a simple small library for selecting DOM Elements in a jQuery's manner. It is
not a full analogue of jQuery library, even not all selectors are yet implemented however
it already allows you to use main selectors and attribute modifiers (such as name|=, name~= etc.).

It is easy and fast to write a web-site grabber using domQuery, because
you do not have to use regular expressions - just use usual jQuery selectors with
domQuery and write only couple of lines of code.

Overall domQuery actually converts jQuery's selectors into DOMXPath query and the executes it.
Library will be updated with some new features, bug fixes etc.

There are some PHPUnit tests (in test dir) which uses html and selectors from jQuery's API documentation.

## Usage

You could create a new instance of domQuery in a regular way and then execute a query:

```php
 $dQ = new domQuery\Selector($doc);
 $dQ->q('.myClass');
``` 

Or just use static wrapper:

```php
 $dQ = domQuery\Selector::i($document)->q('.myClass');
```

The results returned as an Array of objects (array of domQuery\Element) so
you are able to work with them just like with a usual array, furthermore
if there are only one element in the resulted array - you can access to it's
methods without index e.g.:

```php
 $dQ = domQuery\Selector::i($document)->q('.myClass')->q('.mySubClass');
```

domQuery\Element is a wrapper around DOMElement, but it also includes some useful
methods such as text(), innerHTML() and outerHTML(). Also you are able to run q() method
from the domQuery\Element element and select it's child elements.

## Examples

```php

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

```

## In addition

List of jQuery's selectors: https://api.jquery.com/category/selectors/

Powerful jQuery's functionality implementation: https://github.com/TobiaszCudnik/phpquery

... and a lot of other different jQuery's selectors implementations on php :)