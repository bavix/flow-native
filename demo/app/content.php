<?php

$this->ext->blocks()->extends('app:layout.php');

$this->ext->blocks()->start('content', \Bavix\FlowNative\Extensions\Blocks::APPEND);
?>
    <h1><?php echo __FILE__; ?></h1>
<?php
echo $this->ext->blocks()->end();
