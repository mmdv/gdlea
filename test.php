<?php
    phpinfo();
//    检测扩展是否已经开启
var_dump(extension_loaded('gd'));
//检测函数是否可用
var_dump(function_exists('gd_info'));