<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$native = new \Bavix\FlowNative\FlowNative();
$native->addFolder('app', __DIR__ . '/app');

die($native->render('app:content.php', [
    'title'   => 'hello world',
    'welcome' => 'Flow Native'
]));
