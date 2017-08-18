<?php

/*
 * 文字水印效果
 * */
$filename = 'images/timg.jpg';
$fileinfo= getimagesize($filename);
$mime = $fileinfo['mime'];
//echo $mime;
$createFun = str_replace('/','createfrom',$mime);
$outFun = str_replace('/','',$mime);
$image = $createFun($filename);
$red = imagecolorallocatealpha($image,255,0,0,60); //第四个参数表示透明度
$fontfile ='fonts/msyh.ttc';
imagettftext($image,30,0,0,30,$red,$fontfile,'hello');
header('content-type:'.$mime);
$outFun($image);
imagedestroy($image);

//imagecolorallocatealpha()   透明度
