<?php
/**
 * Created by PhpStorm
 * User: codetwista
 * Date: 2/7/2023
 * Time: 4:36 PM
 */
?>
<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="ui one column doubling stackable grid container">
    <div class="column">
        <?php if (session()->getFlashdata('notice')): ?>

            <div class="ui success message">
                <i class="close icon"></i>
                <div class="header">
                    Photo tweet successful!
                </div>

                <?= session()->getFlashdata('notice') ?>
            </div>
        <?php endif ?>

        <h1>My photos</h1>
    </div>
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
                <?php if (session()->has('oauthToken')): ?>

                <div class="ui divider"></div>
                <?= form_open_multipart('tweet', 'class="ui form"') ?>
                <?= form_hidden('caption', $photo->caption) ?>
                <?= form_hidden('photo', $photo->url) ?>

                <button class="ui primary button" type="submit">Tweet photo</button>
                <?= form_close() ?>

                <?php endif ?>

            </div>
        </div>
    </div>
    <?php endforeach ?>
    
    <?php else: ?>
    
    <p>There are no uploaded photos available at the moment!<br>
        <a href="<?= base_url('upload') ?>" class="ui green button">Upload photos</a>
    </p>
    <?php endif ?>
    
</div>
<?= $this->endSection() ?>
