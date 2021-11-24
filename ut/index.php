<?php

    require_once($_SERVER["DOCUMENT_ROOT"] . "/ut/users.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/ut/loginout.php");
    echo '<h3>From /ut/users.php</h3>';
    execTestsUsers();
    execTestsLoginout();

?>