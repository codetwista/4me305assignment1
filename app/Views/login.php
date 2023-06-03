<?php
/**
 * Created by PhpStorm
 * User: codetwista
 * Date: 2/7/2023
 * Time: 12:51 PM
 */
?>
<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="ui two column doubling stackable grid container">
    <div class="column">
        <h1>Log in</h1>
        <?php if (! empty($login_errors)): ?>

            <div class="ui negative message">
                <i class="close icon"></i>
                <div class="header">
                    Login failed!
                </div>
                <p>Please review the flagged form field(s).</p>
                <?php foreach ($login_errors as $error): ?>

                    <li><?= esc($error) ?></li>
                <?php endforeach ?>

            </div>
        <?php endif ?>

        <?php if (session()->getFlashdata('notice')): ?>

            <?= session()->getFlashdata('notice') ?>

        <?php endif ?>
        
        <?= form_open('login', 'class="ui form"') ?>
        
        <h3 class="ui dividing header">Account Information</h3>
        <div class="field">
            <div class="field">
                <label for="email_address">Email address</label>
                <input type="email" name="email_address" id="email_address" placeholder="Email address"
                       value="<?= set_value('email_address') ?>" autofocus>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password">
            </div>
        </div>
        <button class="ui green button" type="submit">Login</button>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>
