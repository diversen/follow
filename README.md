### Follow CosCMS module

A simple module which enables a user to follow anything 
(like on stackoverflow.com)

### Example

    <?php

    moduleloader::includeModule('follow');


    $f = new follow ();
    $f->initJs();
    $f->showButton(session::getUserId(), 100, 'test');
    echo "<br />\n";

    $f->showButton(session::getUserId(), 111, 'test');
