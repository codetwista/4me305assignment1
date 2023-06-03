<?php
/**
 * Created by PhpStorm
 * User: codetwista
 * Date: 2/8/2023
 * Time: 4:21 AM
 */
?>
<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="ui one column doubling stackable grid container">
    <h1>My photo tweet urls</h1>
</div>
<div class="ui four column doubling stackable grid container">
    <?php if (! empty($photos)): ?>
    
    <?php foreach ($photos as $photo): ?>
    
    <div class="column">
        <div class="ui fluid card">
            <div class="image">
                <img src="<?= base_url('uploads/' . $photo->url) ?>" alt="photo">
            </div>
            <div class="content caption">
                <p class="caption"><?= $photo->caption ?></p>
                <a href="<?= $tweet ?>">Visit URL</a>
            </div>
        </div>
    </div>
    <?php endforeach ?>
    
    <?php else: ?>
    
    <p>There are no uploaded photos at the moment!</p>
    <?php endif ?>
    
</div>
<?= $this->endSection() ?>
