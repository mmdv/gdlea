<?php

/**
 * 函数类的分装,先创建类,由数组传入参数,再有其他.php文件调用
 * Class Captcha
 * count($arr)  直接数组内数据条目数
 * 封装先检测所需参数是否传递,制作功能函数
 */
    Class Captcha {
//        字体文件
        private $fontFile = '';
//        字体大小
        private $size = 20;
//        画布宽度
        private $width = 120;
//        画布高度
        private $height = 40;
//        验证码长度
        private $length = 4;
//      画布资源
        private $image = null;
//        干扰元素
//      雪花的个数
        private  $snow = 0;
//        像素个数
        private  $pixel = 0;
//        线段条数
        private  $line = 0;

    /**
     * 初始化数据
     * Captcha constructor.
     * @param array $config
     */
        public function __construct($config=array())
        {
            if(is_array($config)&&count($config)>0){
//              检测字体文件是否存在并且可读
                if(isset($config['fontfile'])&&is_file($config['fontfile'])&&is_readable($config['fontfile'])){
                    $this->fontFile = $config['fontfile'];
                }else{
                    return false;
                }
//                检测是否设置字体大小
                if(isset($config['size'])&&$config['size']>0){
                    $this->size = (int)$config['size'];//(int类型转换)
                }
//                检测是否设置画布宽度和高度
                if(isset($config['width'])&&$config['width']>0){
                    $this->width = (int)$config['width'];//(int类型转换)
                }
//                检测是否设置画布高度
                if(isset($config['height'])&&$config['height']>0){
                    $this->height = (int)$config['height'];//(int类型转换)
                }
//                检测是否设置验证码长度
                if(isset($config['length'])&&$config['length']>0){
                    $this->length = (int)$config['length'];//(int类型转换)
                }
//                配置干扰元素
                if(isset($config['snow'])&&$config['snow']>0){
                    $this->snow = (int)$config['snow'];
                }
                if(isset($config['pixel'])&&$config['pixel']>0){
                    $this->pixel = (int)$config['pixel'];
                }
                if(isset($config['line'])&&$config['line']>0){
                    $this->line = (int)$config['line'];
                }
                $this->image = imagecreatetruecolor($this->width,$this->height);
//                return $this->image;

            }else {
                return false;
            }
        }

    /**
     * 得到验证码
     * @return bool|string
     */
    public function getCaptcha(){
        $white = imagecolorallocate($this->image,255,255,255);
//        填充矩形
        imagefilledrectangle($this->image,0,0,$this->width,$this->height,$white);
//        生成验证码
        $str = $this->generateStr($this->length);
        if($str === false){
            return false;
        }
//        绘制验证码
        $fontfile = $this->fontFile;
        for($i=0;$i<$this->length;$i++){
            $size = $this->size;
            $angle = mt_rand(-30,30);
            $x = ceil($this->width/$this->length)*$i+mt_rand(5,10);
            $y = ceil($this->height/1.5);
            $color = $this->getRandColor();
//            $text = mb_substr($str,0,1,'utf-8');
            $text = $str[$i];//没有中文
            imagettftext($this->image,$size,$angle,$x,$y,$color,$fontfile,$text);
        }
//        * -- 像素和线段
        if($this->snow){
//            使用雪花做干扰元素
            $this->getSnow();
        }
        if($this->pixel){
//                使用像素做干扰元素
            $this->getPixel();
        }
        if($this->line){
//                使用线段做干扰元素
            $this->getLine();
        }

//        输出图像
        header('content-type:image/png');
        imagepng($this->image);
        imagedestroy($this->image);
        return strtolower($str);
    }

    /**
     * 产生验证码字符
     * @param int $length   验证码长度
     * @return bool|string 随机字符
     */
    private function generateStr($length = 4){
        if($length<1 || $length>30){
            return false;
        }
//        去掉0il1的数组
        $chars = array(
            'a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','t','x','y','z',
            'A','B','C','D','E','F','G','H','J','K','M','N','P','Q','R','T','X','Y','Z',
            1,2,3,4,5,6,7,8,9
        );
        $str = join('',array_rand(array_flip($chars),$length));
        return $str;
    }

//    生成随机颜色
    private function getRandColor(){
        return imagecolorallocate($this->image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
    }

//    雪花干扰
    private function getSnow(){
        for($i=0;$i<$this->snow;$i++) {
            imagestring($this->image,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'* ',$this->getRandColor());
        }
    }
//像素干扰
    private function getPixel(){
        for($i=0;$i<$this->pixel;$i++) {
            imagesetpixel($this->image,mt_rand(0,$this->width),mt_rand(0,$this->height),$this->getRandColor());
        }
    }
//线段干扰
    private function getLine(){
        for($i=0;$i<$this->line;$i++) {
            imageline($this->image,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$this->getRandColor());
        }
    }

    }

