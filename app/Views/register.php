<?php
/**
 * Created by PhpStorm
 * User: codetwista
 * Date: 2/6/2023
 * Time: 6:19 PM
 */
?>
<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="ui two column doubling stackable grid container">
    <div class="column">
        <h1>Register</h1>
        <?php if (! empty($signup_errors)): ?>

            <div class="ui negative message">
                <i class="close icon"></i>
                <div class="header">
                    Registration failed!
                </div>
                <p>Please review the flagged form field(s).</p>
                <?php foreach ($signup_errors as $error): ?>

                    <li><?= esc($error) ?></li>
                <?php endforeach ?>

            </div>
        <?php endif ?>

        <?php if (session()->getFlashdata('notice')): ?>

            <?= session()->getFlashdata('notice') ?>

        <?php endif ?>
        
        <?= form_open('register', 'class="ui form"') ?>
        
        <h3 class="ui dividing header">Account Information</h3>
        <div class="field">
            <div class="two fields">
                <div class="field">
                    <label for="first_name">First name</label>
                    <input type="text" name="first_name" id="first_name" placeholder="First name"
                           value="<?= set_value('first_name') ?>" autofocus>
                </div>
                <div class="field">
                    <label for="last_name">Last name</label>
                    <input type="text" name="last_name" id="last_name" placeholder="Last name"
                           value="<?= set_value('last_name') ?>">
                </div>
            </div>
        </div>
        <div class="field">
            <label for="email_address">Email address</label>
            <input type="email" name="email_address" id="email_address" placeholder="Email address"
                   value="<?= set_value('email_address') ?>">
        </div>
        <div class="field">
            <label for="screen_name">Twitter screen name</label>
            <input type="text" name="screen_name" id="screen_name" placeholder="Twitter screen name"
                   value="<?= set_value('screen_name') ?>">
        </div>
        <div class="field">
            <div class="two fields">
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password">
                </div>
                <div class="field">
                    <label for="confirm_password">Confirm password</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password">
                </div>
            </div>
        </div>
        <button class="ui green button" type="submit">Register</button>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>
