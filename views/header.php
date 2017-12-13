<?php
    declare(strict_types=1);

    require(__DIR__.'/../app/autoload.php');
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../assets/styles/main.css">
  <title><?php echo $config['title']; ?></title>
</head>
<body>

    <?php require(__DIR__.'/nav.php') ?>
