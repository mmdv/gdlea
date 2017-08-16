<?php
$width = 200;
$height = 50;
$image = imagecreatetruecolor($width,$height);
$white = imagecolorallocate($image,255,255,255);

imagefilledrectangle($image,0,0,$width,$height,$white);
//快速创建字符串 $string = 'asdfhijkl'
$string = join('',array_merge(range(0,9),range('a','z'),range('A','Z')));
$length = 4;
for($i= 0;$i<$length;$i++){
    $randColor = imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
    $size = mt_rand(20,28);
    $angle = mt_rand(-15,15);
    $x = 20+40*$i;
    $y = 30;
    $fontFile = 'fonts/msyh.ttc';
    $text = str_shuffle($string)[0];//打乱字符串,取一个
    imagettftext($image,$size,$angle,$x,$y,$randColor,$fontFile,$text);
}

header('content-type:image/png');
imagepng($image);
imagedestroy($image);
?>