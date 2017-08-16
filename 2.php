<?php

//    1.创建画布
//imagecreatetruecolor($width,$height); 创建画布,返回资源,返回图像标识
$width = 500;
$height = 300;
$image = imagecreatetruecolor($width,$height);

//2.创建颜色
//imagecolorallocate();创建颜色
$red = imagecolorallocate($image,255,0,0);
$blue = imagecolorallocate($image,0,0,255);
$white = imagecolorallocate($image,255,255,255);

//3.开始绘画
//横着写一个字符
//imagechar() 水平绘制一个字符
imagechar($image,5,50,100,'k',$red);
//imagecharup()垂直绘制字符
imagecharup($image,5,100,200,'i',$white);
//水平绘制字符串
imagestring($image,5,200,150,'imooc',$blue);
//4.高速浏览器以图片格式显示
header('content-type:image/jpeg');
//5.输出图像imagejpeg()
imagejpeg($image);
//6.销毁资源
imagedestroy($image);