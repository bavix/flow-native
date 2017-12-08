<!DOCTYPE html>
<html>
    <head>
        <?php $title .= ' - ' . \crc32($this->title)?>
        <title><?=$this->title?></title>
    </head>
    <body>
        <?php $this->ext->blocks()->start('content', \Bavix\FlowNative\Extensions\Blocks::APPEND);?>
            <h2>He</h2>
        <?php echo $this->ext->blocks()->end() ?>
    </body>
</html>
