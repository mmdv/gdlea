<?php
/**
 * 指定缩放比例
 * 最大跨度和高度,等比例缩放
 *可以对缩略图文件加前缀
 * 选择是否删除缩略图源文件
 */
/**
 * 返回图片信息
 * @param $filename 文件名
 * @return mixed    包含图片宽度,高度,创建和输出字符串以及后缀
 */
function getImageInfo($filename){
    if(@!$info = getimagesize($filename)){  //@???
        exit('文件不是图片');
    }
    $fileInfo['width'] = $info[0];
    $fileInfo['height'] = $info[1];
    $mime = image_type_to_mime_type($info[2]);  //getimagesize数组返回的数字转回后缀类型(png,jpeg),官网可查
    $creatFun = str_replace('/','createfrom',$mime);
    $outFun = str_replace('/','',$mime);
    $fileInfo['createFun'] = $creatFun;
    $fileInfo['outFun'] = $outFun;
    $fileInfo['ext'] = strtolower(image_type_to_extension($info[2]));
    return $fileInfo;
}

/**
 * 形成缩略图函数
 * @param string $filename  文件名
 * @param string $dest  缩略图保存路径 'thumb'
 * @param string $pre   默认前缀 'thumb_'
 * @param bool $delSourse   默认图片不删除 false
 * @param float $scale  默认缩放比例  0.5
 * @param null $dst_w   目标文件宽度
 * @param null $dst_h   目标文件高度
 * @return string   最终文件保存路径以及文件名
 */

function thumb($filename,$dest='thumb',$pre="thumb_",$delSourse=false,$scale=0.5,$dst_w=null,$dst_h=null) {

//    $filename = '../images/timg.jpg';

//    $scale = 0.5;//缩放比例
//    $dst_w = 200;
//    $dst_h = 300;
//    $dest = 'thumb';
//    $pre = 'thumb_';
//    $delSourse = false;

    $fileInfo = getImageInfo($filename);
    $src_w = $fileInfo['width'];
    $src_h = $fileInfo['height'];

    //如果指定最大宽度与高度,按照等比例缩放

    if(is_numeric($dst_w)&&is_numeric($dst_h)){  //如果是数值
        //等比例算法,来自php官网
        $ratio_orig = $src_w/$src_h;
        if ($dst_w/$dst_h > $ratio_orig) {
            $dst_w = $dst_h*$ratio_orig;
        } else {
            $dst_h = $dst_w/$ratio_orig;
        }
    }else{
    //    没指定最大宽度高度,按照默认缩放比例
        $dst_w = ceil($src_w * $scale);
        $dst_h = ceil($src_h * $scale);
    }

    $dst_image = imagecreatetruecolor($dst_w,$dst_h);
    $src_image = $fileInfo['createFun']($filename);
    imagecopyresampled($dst_image,$src_image,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);

    //检测目标目录是否存在,不存在则创建
    if($dest && !file_exists($dest)){
        mkdir($dest,0777,true);
    }

    $randNum = mt_rand(10000,99999);
    $dstName = "{$pre}{$randNum}".$fileInfo['ext'];

    $destination = $dest? $dest .'/' .$dstName : $dstName;
//    echo $destination;
    $fileInfo['outFun']($dst_image,$destination);

    imagedestroy($src_image);
    imagedestroy($dst_image);
    if($delSourse){
        @unlink($filename);//@错误抑制符
    }
    return $destination;
}

/**
 * 文字水印
 * @param $filename
 * @param $fontfile
 * @param int $r
 * @param int $g
 * @param int $b
 * @param int $alpha
 * @param boolean @param
 * @param int $size
 * @param string $text
 * @param string $dest
 * @param string $pre
 * @param int $angle
 * @param int $x
 * @param int $y
 * @return string
 */
function waterText($filename,$fontfile,$r=255,$g=0,$b=0,$alpha=60,$size=30,$text="hello00000",$dest='waterText',$pre='waterText_',$angle=0,$x=0,$y=30,$delSourse=false) {

//    $filename = '../images/timg.jpg';
//    $r = 255;
//    $g = 0;
//    $b = 0;
//    $alpha = 60;
//    $size = 30;
//    $angle = 0;
//    $x = 0;
//    $y = 30;
//    $fontfile = '../fonts/msyh.ttc';
//    $text = 'hello00000000';
    $fileInfo = getImageInfo($filename);
    $image = $fileInfo['createFun']($filename);
    $color = imagecolorallocatealpha($image,$r,$g,$b,$alpha);
    imagettftext($image,$size,$angle,$x,$y,$color,$fontfile,$text);
//    $dest = 'waterText';
    if($dest && !file_exists($dest)) {
        mkdir($dest,0777,true);
    }
//    $pre = 'waterText_';
    $randNum = mt_rand(10000,99999);
    $dstName = "{$pre}{$randNum}".$fileInfo['ext'];
    $destniation = $dest? $dest.'/'.$dstName:$dstName;
    $fileInfo['outFun']($image,$destniation);
    imagedestroy($image);
    if($delSourse){
        @unlink($filename);//@错误抑制符
    }
    return $destniation;
}

function waterPicc($dstName,$srcName,$pos=0,$pct=50,$dest='waterPic',$pre='waterPic',$delSourse=false){
    //    $dstName = 'images/timg.jpg';
    //    $srcName = 'logo.png';
    //    $pos = 0;
    //    $pct = 50;
        $dstInfo = getImageInfo($dstName);
        $srcInfo = getImageInfo($srcName);
        $dst_im = $dstInfo['createFun']($dstName);
        $src_im = $srcInfo['createFun']($srcName);
        $dst_w = $dstInfo['width'];
        $dst_h = $dstInfo['height'];
        $src_w = $srcInfo['width'];
        $src_h = $srcInfo['height'];

        switch($pos){
            case 0:
                $x = 0;
                $y = 0;
                break;
            case 1:
                $x = ($dst_w-$src_w)/2;
                $y = 0;
                break;
            case 2:
                $x = $dst_w-$src_w;
                $y = 0;
                break;
            case 3:
                $x = 0;
                $y = ($dst_h-$src_h)/2;
                break;
            case 4:
                $x = ($dst_w-$src_w)/2;
                $y = ($dst_h-$src_h)/2;
                break;
            case 5:
                $x = $dst_w-$src_w;
                $y = ($dst_h-$src_h)/2;
                break;
            case 6:
                $x = 0;
                $y = $dst_h-$src_h;
                break;
            case 7:
                $x = ($dst_w-$src_w)/2;
                $y = $dst_h-$src_h;
                break;
            case 8:
                $x = $dst_w-$src_w;
                $y = $dst_h-$src_h;
                break;
            default:
                $x = 0;
                $y = 0;
                break;
        }
        imagecopymerge($dst_im,$src_im,$x,$y,0,0,$src_w,$src_h,$pct);
    //    $dest = 'waterPic';
        if($dest && !file_exists($dest)) {
            mkdir($dest,0777,true);
        }
    //    $pre = 'waterPic';
        $randNum = mt_rand(10000,99999);
        $dstName = "{$pre}_{$randNum}".$dstInfo['ext'];
        $destniation = $dest? $dest.'/'.$dstName:$dstName;
        $dstInfo['outFun']($dst_im,$destniation);
        imagedestroy($dst_im);
        imagedestroy($src_im);
    if($delSourse){
        @unlink($dstName,$srcName);//@错误抑制符
    }
        return $destniation;
}