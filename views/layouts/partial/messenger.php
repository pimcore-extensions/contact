<div class="row">
    <div class="span10 offset1">
        <?php foreach($this->messages as $message): ?>
        <div class="alert <?=$message['type'] ? 'alert-success' : 'alert-error'?>">
            <?=$message['body']?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
