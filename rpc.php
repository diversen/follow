<?php


print_r($_POST);
$f = new follow();

$res = $f->toggleFollow(session::getUserId(), $_POST['parent_id'], $_POST['reference']);
echo $f->status;
die;