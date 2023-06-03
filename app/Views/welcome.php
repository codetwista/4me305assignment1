<?php
/**
 * Created by PhpStorm
 * User: codetwista
 * Date: 2/5/2023
 * Time: 5:33 PM
 */
?>
<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="ui one column doubling stackable grid container">
    <div class="column">
        <?php if (session()->getFlashdata('notice')): ?>

            <?= session()->getFlashdata('notice') ?>

        <?php endif ?>

        <h1>User profile</h1>
        <table class="ui celled table">
            <thead>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Email</th>
                <th>Twitter screen name</th>
                <th>Photo counter</th>
                <th>Registered</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td data-label="First name"><?= ucfirst($user->first_name) ?></td>
                <td data-label="Last name"><?= ucfirst($user->last_name) ?></td>
                <td data-label="Email"><?= strtolower($user->email_address) ?></td>
                <td data-label="Email">@<?= strtolower($user->screen_name) ?></td>
                <td data-label="Email"><?= count($photos) ?></td>
                <td data-label="Email"><?= date('j F, Y', strtotime($user->created_at)) ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>