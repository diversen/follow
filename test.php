
    <?php

    moduleloader::includeModule('follow');


    $f = new follow ();
    $f->initJs();
    $f->getFollowHtml(session::getUserId(), 100, 'test');
    echo "<br />\n";

    $f->getFollowHtml(session::getUserId(), 111, 'test');
