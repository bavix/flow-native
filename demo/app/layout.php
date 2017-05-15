<!DOCTYPE html>
<html>
    <head>
        <title><?= $this->substr($this->title, 0, 5) ?></title>
    </head>
    <body>
        <?php $this->ext->blocks()->start('content', \Bavix\FlowNative\Extensions\APPEND);?>
            <h2>He</h2>
        <?php echo $this->ext->blocks()->end() ?>
    </body>
</html>
