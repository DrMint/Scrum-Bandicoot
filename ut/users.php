<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/users.php");
    
    function assertTrue($assertion) {
        if ($assertion) {
            return 'pass';
        } else {
            return 'failed';
        }
    }

    function createUser() {
        $username = 'ut-test';
        $user = new User($username);
        return assertTrue($user->slug === $username);
    }

    function retrieveExistingUser() {
        global $rootFolder;

        // Make sure the user ut-test exists
        $username = 'ut-test';
        $hash = '123456789';
        $filePath = $rootFolder . $username . '.json';;
        $file = fopen($filePath, 'w');
        fwrite($file, json_encode( ['hash' => $hash] ));
        fclose($file);

        // Retrieve it
        $user = new User($username);

        // The retrieved hash should be equal to the one we've set
        return assertTrue($user->hash === $hash);
    }


    function execTests() {
        echo 'createUser: ' . createUser() . '<br>';
        echo 'retrieveExistingUser: ' . retrieveExistingUser() . '<br>';
    }

 ?>