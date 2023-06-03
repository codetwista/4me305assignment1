<?php
/**
 * Created by PhpStorm
 * User: codetwista
 * Date: 2/5/2023
 * Time: 5:16 PM
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <header>
        <div class="ui container">
            <div class="ui secondary menu">
                <h3 class="logo-text">Web 2.0 Development</h3>
                <div class="right menu">
                    <a class="item" href="<?= base_url() ?>">
                        Home
                    </a>
                    <?php if (session()->has('isLoggedIn')): ?>

                    <?php if (current_url() !== base_url('welcome')): ?>
                    <a class="item" href="<?= base_url('welcome') ?>">Profile</a>
                    <?php endif; ?>

                    <a class="item" href="<?= base_url('upload') ?>">
                        Upload photo
                    </a>
                    
                    <a class="item" href="<?= base_url('twitter/login') ?>">
                        Tweet photo
                    </a>
                    
                    <a class="ui item" href="<?= base_url('logout') ?>">
                        Logout
                    </a>
                    <?php else: ?>
                    
                    <?php if (current_url() == base_url('login')): ?>
    
                    <a class="ui item" href="<?= base_url('register') ?>">
                        Register
                    </a>
                    
                    <?php elseif (current_url() == base_url('register')): ?>
                    
                    <a class="ui item" href="<?= base_url('login') ?>">
                        Login
                    </a>
                    <?php else: ?>
        
                    <a class="ui item" href="<?= base_url('login') ?>">
                        Login
                    </a>
                    <a class="ui item" href="<?= base_url('register') ?>">
                        Register
                    </a>
                    <?php endif ?>

                    
                    <?php endif ?>
                    
                </div>
            </div>
        </div>
    </header>
    <main>
        <?= $this->renderSection('content') ?>
    </main>
    <footer></footer>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>
    <script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>
