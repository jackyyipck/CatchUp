<?php

header("Content-Type: text/xml; charset=utf-8");
mysql_set_charset('utf8');

//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
$response_row_node->addChild('error', @$_REQUEST['error_msg']);

echo $response_row_node->asXML();

?>