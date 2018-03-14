<?php

/*
 * This file is a part of Cyberlink.
 *
 * (c) Maja Persson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../autoload.php';

if (isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);

    $users = getUsers($pdo);

    foreach ($users as $user) {
        if ($email === $user['email']) {
            $password = '';

            for ($i=0; $i < 10; $i++) {
                $keys = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $random_int = random_int(0, strlen($keys)-1);
                $password .= $keys[$random_int];
            }
            updatePassword($pdo, $password, $user['id']);

            $subject = 'Cyberlink: Reset password';
            $username = $user['username'];
            $message ="<html>
            <head>
              <style type=\"text/css\">
                body {
                  font-family: sans-serif;
                  font-size: 1.2em;
                  height: 100vh;
                }
                .content {
                  padding: 2em;
                  margin: auto;
                  max-width: 50%;
                  text-align: center;
                }
                .password {
                  background-color: #17a2b8;
                  border-radius: 10px;
                  color: white;
                  display: inline-block;
                  padding: 1em;
                  font-size: 1.5em;
                  font-weight: bold;
                }
              </style>
            </head>
              <body>
                <div class=\"content\">
                <h1>Hello $username!</h1>
                <p>This is your code for Cyberlink:</p>
                <div class=\"password\">$password</div>
                <p>Please enter this code as password to log in.</p>
                </div>
              </body>
            </html>";

            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';

            mail($email, $subject, $message, implode("\r\n", $headers));

            $_SESSION['reset_success'] = 'Your new password has been sent to your email address.';
            redirect('/reset_password.php');
        }
    }

    if (!isset($_SESSION['reset_success'])) {
        $_SESSION['reset_fail'] = 'The entered email address is not registered.';
        redirect('/reset_password.php');
    }
}
