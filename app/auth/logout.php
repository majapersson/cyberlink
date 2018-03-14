<?php

/*
 * This file is a part of Cyberlink.
 *
 * (c) Maja Persson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

    declare(strict_types=1);

    require __DIR__.'/../autoload.php';

    unset($_SESSION['user']);

    redirect('/');
