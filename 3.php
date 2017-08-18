<?php
//可以用特殊字体连改变验证码内容
$width = 200;
$height = 50;
$image = imagecreatetruecolor($width,$height);
$white = imagecolorallocate($image,255,255,255);
imagefilledrectangle($image,0,0,$width,$height,$white);

function getRandColor($image){
    return imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
}

//快速创建字符串 $string = 'asdfhijkl'
$string = join('',array_merge(range(0,9),range('a','z'),range('A','Z')));
$length = 4;

//得到字体宽度,高度,以此计算干扰元素范围
$textWidth = imagefontwidth(28);
$textHeight = imagefontheight(28);
for($i= 0;$i<$length;$i++){
//    $randColor = imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
    $randColor = getRandColor($image);
    $size = mt_rand(20,28);
    $angle = mt_rand(-15,15);
//    $x = 20+40*$i;
//    $y = 30;
    $x = ($width/$length)*$i+$textWidth;
    $y = mt_rand($height/2,$height-$textHeight);

    $fontFile = 'fonts/wupic.ttf';
    $text = str_shuffle($string)[0];//打乱字符串,取一个
    imagettftext($image,$size,$angle,$x,$y,$randColor,$fontFile,$text);
}

//添加干扰元素

#添加像素当做干扰元素
for($i=1;$i<=50;$i++){
    imagesetpixel($image,mt_rand(0,$width),mt_rand(0,$height),getRandColor($image));
}

#绘制线段作为干扰元素
for($i=1;$i<=3;$i++){
    imageline($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),getRandColor($image));
}

#绘制圆弧
for($i=1;$i<=3;$i++){
    imagearc($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width/2),mt_rand(0,$height/2),mt_rand(0,360),mt_rand(0,360),getRandColor($image));
}

header('content-type:image/png');
imagepng($image);
imagedestroy($image);
/**
 * 学会到官网看函数
 * imagesetpixel
 * imageline
 * imagearc
 * imagefontwidth
 * imagefotheight
 */
?>


