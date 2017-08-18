<?php
    $filename = 'images/timg.jpg';
    if($fileInfo = getimagesize($filename)){
        list($src_w,$src_h) = $fileInfo;
    }else {
        exit('文件不是真实图片');
}

//等比例缩放
//设置高度宽度
$dst_w = 300;
$dst_h = 600;

//等比例算法,来自php网页
$ratio_orig = $src_w/$src_h;
if ($dst_w/$dst_h > $ratio_orig) {
    $dst_w = $dst_h*$ratio_orig;
} else {
    $dst_h = $dst_w/$ratio_orig;
}

//创建原画布资源和目标画布资源
$src_image = imagecreatefromjpeg($filename);
$dst_image = imagecreatetruecolor($dst_w,$dst_h);
imagecopyresampled($dst_image,$src_image,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);

imagejpeg($dst_image,'images/test.jpg');
imagedestroy($src_image);
imagedestroy($dst_image);