<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$helper = new \Bavix\FlowNative\Helper();
$helper->add('substr', 'mb_substr');

$native = new \Bavix\FlowNative\FlowNative($helper);
$native->addFolder('app', __DIR__ . '/app');

die($native->render('app:layout.php', [
    'title'   => 'hello world',
    'welcome' => 'Flow Native'
]));
