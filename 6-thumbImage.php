<?php

    $filename = 'images/timg.jpg';
//    getimagessize();得到图片信息
$fileInfo = getImagesize($filename);
//var_dump($fileInfo);
//得到原始图片的宽度高度
list($src_w,$src_h) = $fileInfo;

//imagecopyresampled()
//创建100*100
$dst_w=100;
$dst_h=100;
//创建目标画布资源
$dst_image = imagecreatetruecolor($dst_w,$dst_h);
//通过图片文件创建画布资源  imagecreatefromjpeg()|imagecreatefrompng()|imagecreatefromgif();
$src_image = imagecreatefromjpeg($filename);
//var_dump($src_image);
imagecopyresampled($dst_image,$src_image,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);
imagejpeg($dst_image,'images/thumb.jpg');
imagedestroy($src_image);
imagedestroy($dst_image);
/**
 * png
 * gif
 * getimagesize()
 * imagecopyresampled()
 * imagecreatefromjpeg()/png/gif
 */