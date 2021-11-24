<?php

    require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/users.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/ut.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/crypto.php");

    function sessionstart() {

        if (session_status() == PHP_SESSION_NONE) {
            return assertTrue(session_start());
        }

    }

    function hashDiff() {
        $password = 'motdepasse';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return assertTrue($hash != $password);
    }

    function identitylog() {
        $username = 'ut-test2';
        $password = 'ut-password';
        $user = new User($username);
        $user->setHash(generateHash($password));
        $user->write();

        return assertTrue(password_verify($password, $user->hash));
    }

    function quitsession() {
        session_start();
        session_destroy();
        return assertTrue(header('Location: /login') === 'Location: /login');
    }


    function execTestsLoginout() {
        echo 'sessionstart: ' . sessionstart() . '<br>';
        echo 'identitylog: ' . identitylog() . '<br>';
        echo 'hashDiff: ' . hashDiff() . '<br>';
        //echo 'quitsession: ' . quitsession() . '<br>';
    }

 ?>