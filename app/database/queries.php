<?php
/*
* Checks database for user depending on with parameters are set
*
* Gets user array based in id if $id is set
* Gets user from username OR email if only $name is set
* Gets user
*
* @param PDO $pdo
* @param int $id
* @param string $name
* @param string $email
*/

function getUser ($pdo, int $id = null, string $name = null, string $email = null) {
    // Get user from ID
    if (!empty($id)) {
        $query = $pdo->prepare('SELECT * FROM users WHERE id=:id;');
        if(!$query){
            die(var_dump($pdo->errorInfo()));
        }
        $query-> bindParam(':id', $id, PDO::PARAM_INT);
        $query-> execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    // Get user from username/email (for login)
    if (!empty($name) && empty($email)){
        $query = $pdo->prepare('SELECT * FROM users WHERE username=:username OR email=:email;');
        if(!$query){
            die(var_dump($pdo->errorInfo()));
        }
        $query-> bindParam(':username', $name, PDO::PARAM_STR);
        $query-> bindParam(':email', $name, PDO::PARAM_STR);
        $query-> execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    // Get user from name AND email (for sign up)
    if (!empty($name) && !empty($email)) {
        // Check if username exists
        $query = $pdo->prepare('SELECT * FROM users WHERE username=:username');
        if(!$query){
            die(var_dump($pdo->errorInfo()));
        }
        $query-> bindParam(':username', $name, PDO::PARAM_STR);
        $query-> execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($user)) {
            $_SESSION['errors']['user'] = 'The username is already taken.';
            unset($user);
        }

        // Check if email exists
        $query = $pdo->prepare('SELECT * FROM users WHERE email=:email;');
        if(!$query){
            die(var_dump($pdo->errorInfo()));
        }
        $query-> bindParam(':email', $email, PDO::PARAM_STR);
        $query-> execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(!empty($user)) {
            $_SESSION['errors']['user'] = 'The email is already in use.';
            unset($user);
        }
    }
}
