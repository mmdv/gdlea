<?php
$filename = 'images/timg.jpg';
if($fileInfo = getimagesize($filename)){
//    print_r($fileInfo);
    $mime =$fileInfo['mime'];
    list($src_w,$src_h) = $fileInfo;
}else {
    exit('文件不是真实图片');
}

//image/jpeg image/png image/gif
$creatFun = str_replace('/','createfrom',$mime);
$outFun = str_replace('/','',$mime);
//echo $creatFun;exit;

//等比例缩放
//设置高度宽度
$dst_w = 300;
$dst_h = 600;

//等比例算法,来自php官网
$ratio_orig = $src_w/$src_h;
if ($dst_w/$dst_h > $ratio_orig) {
    $dst_w = $dst_h*$ratio_orig;
} else {
    $dst_h = $dst_w/$ratio_orig;
}

//创建原画布资源和目标画布资源
//$src_image = imagecreatefromjpeg($filename);
$src_image = $creatFun($filename);
$dst_image = imagecreatetruecolor($dst_w,$dst_h);
imagecopyresampled($dst_image,$src_image,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);

//imagejpeg($dst_image,'images/test.jpg');
$outFun($dst_image,'images/test1_thumb.jpg');
imagedestroy($src_image);
imagedestroy($dst_image);