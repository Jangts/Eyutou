<?php
class Controller {
    private
    $options = [
        'total'         =>  10,
        'width'         =>  240,
        'height'        =>  150,
        'mark_width'    =>  50,
        'mark_height'   =>  50,
        'quality_web'   =>  60,
        'quality'       =>  8
    ],
    $im = null,
    $im_fullbg = null,
    $im_bg = null,
    $im_slide = null,
    $position = [0, 0];

    public function main(){
        $this->make();
    }

    public function make(){
        error_reporting(0);
        if(!isset($_SESSION)){
            session_start();
        }
        $this->init();
        $this->createBackground();
        $this->createSlide();
        $this->merge();
        $this->imgout();
        $this->destroy();
    }

    private function init(){
        $index = mt_rand(1, $this->options['total']);
        $imgsrc = __DIR__.'/_dv_imgs/'.$index.'.png';
        $this->im_fullbg = imagecreatefrompng($imgsrc);
        $this->im_bg = imagecreatetruecolor($this->options['width'], $this->options['height']);
        $this->im_slide = imagecreatetruecolor($this->options['mark_width'], $this->options['height']);

        imagecopy($this->im_bg, $this->im_fullbg, 0, 0, 0, 0, $this->options['width'], $this->options['height']);
        
        $_SESSION['_logger_dvcode_err'] = 0;
        $_SESSION['_logger_dvcode_r'] = $this->position[0] = mt_rand(50, $this->options['width'] - $this->options['mark_width'] - 1);
        $this->position[1] = mt_rand(0, $this->options['height'] - $this->options['mark_height'] - 1);
    }

    private function createBackground(){
        $filename = __DIR__.'/_dv_mark/bgimg.png';
        $imgsrc = imagecreatefrompng($filename);
        header('Content-Type: image/png');
        // imagealphablending($imgsrc, true);
        imagecolortransparent($imgsrc, 0);
        // imagepng($im);
        // exit;
        imagecopy($this->im_bg, $imgsrc, $this->position[0], $this->position[1], 0, 0, $this->options['mark_width'], $this->options['mark_height']);
        imagedestroy($imgsrc);
    }

    private function createSlide(){
        $filename = __DIR__.'/_dv_mark/slider.png';
        $imgsrc = imagecreatefrompng($filename);
        imagecopy($this->im_slide, $this->im_fullbg,0, $this->position[1], $this->position[0], $this->position[1], $this->options['mark_width'], $this->options['mark_height']);
        imagecopy($this->im_slide, $imgsrc,0, $this->position[1], 0, 0, $this->options['mark_width'], $this->options['mark_height']);
        imagecolortransparent($this->im_slide, 0);
        // header('Content-Type: image/png');
        // imagepng($this->im_slide);
        // exit;
        imagedestroy($imgsrc);
    }

    private function merge(){
        $this->im = imagecreatetruecolor($this->options['width'], $this->options['height'] * 3);
        imagecopy($this->im, $this->im_bg, 0, 0, 0, 0, $this->options['width'], $this->options['height']);
        imagecopy($this->im, $this->im_slide, 0, $this->options['height'], 0, 0, $this->options['mark_width'], $this->options['height']);
        imagecopy($this->im, $this->im_fullbg, 0, $this->options['height'] * 2, 0, 0, $this->options['width'], $this->options['height']);
        imagecolortransparent($this->im, 0);
    }

    private function imgout(){
        if(!$_GET['nowebp']&&function_exists('imagewebp')){
            // 优先webp格式，超高压缩率
            $type = 'webp';
            $quality = $this->options['quality_web'];      // 图片质量 0-100
        }else{
            $type = 'png';
            $quality = $this->options['quality'];       // 图片质量 0-9
        }
        header('Content-Type: image/'.$type);
        $func = "image".$type;
        $func($this->im, null, $quality);
    }


    function check($offset=''){
        if(!$_SESSION['_logger_dvcode_r']){
            $_SESSION['_logger_dvcode_check'] = 'error';
            echo "error";
            return false;
        }
        if(!$offset){
            $offset = $_REQUEST['tn_r'];
        }
        $ret = abs($_SESSION['_logger_dvcode_r']-$offset)<=$this->_fault;
        if($ret){
            unset($_SESSION['_logger_dvcode_r']);
        }else{
            $_SESSION['_logger_dvcode_err']++;
            if($_SESSION['_logger_dvcode_err']>10){//错误10次必须刷新
                unset($_SESSION['_logger_dvcode_r']);
            }
        }
        $_SESSION['_logger_dvcode_check'] = 'ok';
        echo "ok";
        return $ret;
    }

    private function destroy(){
        imagedestroy($this->im);
        imagedestroy($this->im_fullbg);
        imagedestroy($this->im_bg);
        imagedestroy($this->im_slide);
    }
}