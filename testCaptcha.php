<?php

    require_once 'extend/captcha.php';

    $config = array(
        'fontfile'=>'fonts/msyh.ttc',
        'snow'=>'20',
        'pixel'=>100,
        'line'=>3
    );
    $captcha = new Captcha($config);
    session_start();
    $_SESSION['varifyNameCap'] = $captcha->getCaptcha();

