<?php

moduleloader::includeModule('follow');



$f = new follow ();
$f->initJs();
$f->showButton(session::getUserId(), 100, 'test');
echo "<br />\n";

$f->showButton(session::getUserId(), 111, 'test');