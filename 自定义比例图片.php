<?php
header('tonteng-type:text/html;charset=utf-8');
$filename = 'images/dragon.png';
$fileInfo = getimagesize($filename);
if($fileInfo){
    list($src_w,$src_h) = $fileInfo;
}else {
    die('文件不是真实图片');
}

$src_image = imagecreatefrompng($filename);
//50*50
$dst_image_50 = imagecreatetruecolor(50,50);
//270*270
$dst_image_270 = imagecreatetruecolor(270,270);
imagecopyresampled($dst_image_50,$src_image,0,0,0,0,50,50,$src_w,$src_h);
imagecopyresampled($dst_image_270,$src_image,0,0,0,0,270,270,$src_w,$src_h);
imagepng($dst_image_50,'images/thumb_50x50.jpg');
imagepng($dst_image_270,'images/thumb_270x270.jpg');
imagedestroy($dst_image_50);
imagedestroy($dst_image_270);
imagedestroy($src_image);