<?php
/**
 * Created by PhpStorm
 * User: codetwista
 * Date: 2/6/2023
 * Time: 9:55 AM
 */
?>
<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="ui two column doubling stackable grid container">
    <div class="column">
        <h1>Welcome</h1>
    </div>
</div>
<div class="ui two column doubling stackable grid container">
    <p>The purpose of this assignment is to demonstrate skills and knowledge on architecture choice and
        implementation of the following selected web frameworks and database:<br>
        &bull; Semantic UI: HTML UI-based frontend framework
        (<a href="https://semantic-ui.com" target="_blank">https://semantic-ui.com</a>)
        <br>
        &bull; CodeIgniter: PHP backend framework
        (<a href="https://codeigniter.com/" target="_blank">https://codeigniter.com/</a>)
        <br>
        &bull; MySQL: relational database
        (<a href="https://mysql.com/" target="_blank">https://mysql.com/</a>)
    </p>
    <p>This simple web application allows users tweet photos directly to their Twitter profiles.</p>
</div>
<?= $this->endSection() ?>
