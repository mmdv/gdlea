<?php
$logo = 'images/logo.jpg';
$filename = 'images/timg.jpg';
$dst_im = imagecreatefromjpeg($filename);
$src_im = imagecreatefromjpeg($logo);
imagecopymerge($dst_im,$src_im,0,0,0,0,450,470,50);
header('content-type:image/png');
imagejpeg($dst_im);
imagedestroy($dst_im);
imagedestroy($src_im);
