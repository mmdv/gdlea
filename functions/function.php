<?php
/**
 * 函数封装及测试
 * 使用小函数:
 * 数字key与value调换 array_flip($arr)
 * 生成范围数组 range(0,9) range('a','z') range('A','Z')
 *从数组中随机取数据,需要两个参数arry_rand($arr,$length)
 *数组合并 array_merge($arr1,$arr2);
 * join('',$arr);数据拼成字符串   json_encode();作用相同
 * 写入session
 *
 *
 * 执行流程
 * regist.php为入口,获取生成验证码的.php文件,img标签获取到getVerify.php,再由此文件引入封装好的functions/function.php生成验证码文件
 *点击submit事件,出发action执行doAction.php获取输入数据
 */

//随机颜色函数
function getRandColor($image){
    return imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
}
//回调函数,逐个取字符串,防止中文乱码
function addStr($str)
{
    $temp = [];
    for ($i = 0; $i < strlen($str); $i++) {
        $temp[$i] = mb_substr($str, $i, 1, 'utf-8');
    }
    return $temp;
}

/**\
 * 默认产生4位数字验证码
 * @param int $width        画布宽度
 * @param int $height      画布高度
 * @param int $type     验证码类型  1:数字 2:字母 3:数字+字母 4:汉字
 * @param int $length   验证码长度
 * @param int $pixel    像素干扰个数
 * @param int $line     线条干扰个数
 * @param int $arc      圆弧干扰个数
 * @param string $fontfile  字体文件路径
 * @param string $codeName  存入session的名字
 */
function getVerify($fontfile,$type=1,$width=200,$height=50,$length=4,$pixel=50,$line=3,$arc=2,$codeName='verifyCode')
//function getVerify($width=200,$height=50,$type=1,$length=4,$pixel=50,$line=3,$arc=2,$fontfile='../fonts/msyh.ttc') //原方式fontfile路径不对
{
//
//    创建画布
//        $width = 200;
//        $height = 50;
        $image = imagecreatetruecolor($width, $height);
    //创建颜色
        $white = imagecolorallocate($image, 255, 255, 255);
    //创建填充矩形
        imagefilledrectangle($image, 0, 0, $width, $height, $white);

        /**
         * 默认是4位验证码
         * 1-数字
         * 2-字母
         * 3-数字+字母
         * 4-汉字
         */

//        $type = 1;
//        $length = 4;
        switch ($type) {
            case 1:
    //        数字
                $string = join('', array_rand(range(0, 9), $length));//产生数组0-9并从中随机取出$length个返回成新数组,以空格转为字符串,或者json_encode
                break;
            case 2:
    //        字母
                $string = join('', array_rand(array_flip(array_merge(range('a', 'z'), range('A', 'Z'))), $length)); //flip,交换key和value,join链接为字符串
    //    var_dump($arr);
    //        print_r($arr);
                break;
            case 3:
    //        数字加字母
                $string = join('', array_rand(array_flip(array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'))), $length));
    //        var_dump($string);
                break;
            case 4:
    //        汉字:
                $str = "坚持追求自己的梦想在一长串满分的背后是黄雨桐对自身的严格要求和有序规划我会在前一天制定计划告诉自己明天要做什么并且督促自己一定要做完父母的学习不干预政策也培养了她独立学习的能力班主任高峻这样评价黄雨桐她是一个自信敢于追求梦想的女孩遇到困难时她会告诉自己再坚持一下不要放弃黄雨桐则时刻牢记着高峻那句习要熬得住寂寞时不时听说谁谁谁SAT考了多少分谁谁谁又参加了什么国际级的活动我就提醒自己别纠结于一时得失更别迷失在他人创造的神话中黄雨桐说";
    //        echo $str.'<hr>';
    //        echo mb_substr($str, 2, 1, 'utf-8');  //2表示起点,1表示一位,不是终点
                $arr = call_user_func('addStr', $str); //学到的回调函数用法
    //        $arr = explode(',',$str);//根据,把字符串拆分为数组
    //        $arr = implode(',',$str);//根据,把字符串拆分为数组
    //        var_dump($arr);
                $string = join('', array_rand(array_flip($arr), $length)); //array_rand(需要两个参数,有一个终止长度参数)
    //        var_dump($string);
                break;
            default :
                exit('非法参数');
                break;
        }

    //      将验证码存入session
    session_start();
    $_SESSION[$codeName] = $string;

        for ($i = 0; $i < $length; $i++) {
            $size = mt_rand(20, 28);
            $angle = mt_rand(-15, 15);
            $x = 20 + ceil($width / $length) * $i;
            $y = mt_rand(ceil($height / 3), ceil($height - 20));
            $color = getRandColor($image);
//            $fontfile = '../fonts/msyh.ttc';
    //    中文 mb_substr()防止乱码
            $text = mb_substr($string, $i, 1, 'utf-8');
            imagettftext($image, $size, $angle, $x, $y, $color, $fontfile, $text);
        }

//        $pixel = 50;
//        $line = 3;
//        $arc = 2;

    #添加像素干扰元素
        if ($pixel > 0) {
            for ($i = 0; $i <= $pixel; $i++) {
                imagesetpixel($image, mt_rand(0, $width), mt_rand(0, $height), getRandColor($image));
            }
        }

    #添加线段干扰元素
        if ($line > 0) {
            for ($i = 1; $i <= $line; $i++) {
                imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), getRandColor($image));
            }
        }

    #添加弧线
        if ($arc > 0) {
            for ($i = 1; $i <= $arc; $i++) {
                imagearc($image, mt_rand(0, $width / 2), mt_rand(0, $height / 2), mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, 360), mt_rand(0, 360), getRandColor($image));
            }
        }

        header("Content-Type: image/png; ");
        imagepng($image);
        imagedestroy($image);
}

//getVerify();