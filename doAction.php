<?php

    header('content-type:text/html;charset=utf-8');
    session_start();
    print_r($_POST);
    echo "<hr>";
    print_r($_SESSION);