<?php
namespace ProcessWire;
$direct = $page->page_single;
$direct = $direct->httpUrl();
$session->redirect($direct);
?>
