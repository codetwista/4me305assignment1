<?php
/**
 * Created by PhpStorm
 * User: codetwista
 * Date: 2/7/2023
 * Time: 5:42 PM
 */
?>
<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="ui one column doubling stackable grid container">
    <div class="column">
        <h1>Upload photo</h1>
    </div>
</div>
<div class="ui two column doubling stackable grid container">
    <div class="column">
        <?php if (! empty($upload_errors)): ?>

        <div class="ui negative message">
            <i class="close icon"></i>
            <div class="header">
                Photo upload failed!
            </div>
        <?php foreach ($upload_errors as $error): ?>

            <li><?= esc($error) ?></li>
        <?php endforeach ?>

        </div>
        <?php endif ?>
        
        <?php if (session()->getFlashdata('notice')): ?>
        
            <?= session()->getFlashdata('notice') ?>
    
        <?php endif ?>
        
        <?= form_open_multipart('upload', 'class="ui form"') ?>
        
        <h3 class="ui dividing header">Browse to select photo</h3>
        <div class="field">
            <label for="photo">Photo</label>
            <input type="file" name="photo" id="photo">
        </div>
        <div class="field">
            <label for="caption">Caption</label>
            <input type="text" name="caption" id="caption" placeholder="Photo caption"
                   value="<?= set_value('caption') ?>">
        </div>
        <button class="ui green button" type="submit">Upload photo</button>
    </div>
</div>
<?= $this->endSection() ?>
