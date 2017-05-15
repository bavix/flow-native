<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$helper = new \Bavix\FlowNative\Helper();

$native = new \Bavix\FlowNative\FlowNative($helper);
$native->addFolder('app', __DIR__ . '/app');

$helper->add('substr', 'mb_substr');

die($native->render('app:content.php', [
    'title'   => 'hello world',
    'welcome' => 'Flow Native'
]));
