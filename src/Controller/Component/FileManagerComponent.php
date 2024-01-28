<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Utility\Inflector;
use Cake\Network\Exception\BadRequestException;

class Image {
    
    private $src_image, $src_w, $src_h;
    private $dst_image;
    
    public function __construct($src_image, $src_w, $src_h) {
        $this->src_image = $src_image;
        $this->src_w = $src_w;
        $this->src_h = $src_h;
    }
    
    public function width() {
        return $this->src_w;
    }
    
    public function height() {
        return $this->src_h;
    }
    
    public static function open($file) {
        if (file_exists($file) === false) {
            return false;
        }
        
        list($width, $height, $type) = getimagesize($file);
        
        if ($type === IMAGETYPE_PNG) {
            return new static(imagecreatefrompng($file), $width, $height);
        }
        
        if ($type === IMAGETYPE_JPEG) {
            return new static(imagecreatefromjpeg($file), $width, $height);
        }
        
        if ($type === IMAGETYPE_GIF) {
            return new static(imagecreatefromgif($file), $width, $height);
        }
        
        return false;
    }
    
    public function check_available_memory($file, $dst_w, $dst_h, $bloat = 1.68) {
        // Get maxmemory limit in Mb convert to bytes
        $max = ((int) ini_get('memory_limit') * 1024) * 1024;
        
        // get image size
        list($src_w, $src_h) = getimagesize($file);
        
        // Source GD bytes
        $src_bytes = ceil((($src_w * $src_h) * 3) * $bloat);
        
        // Target GD bytes
        $dst_bytes = ceil((($dst_w * $dst_h) * 3) * $bloat);
        
        $total = $src_bytes + $dst_bytes + memory_get_usage();
        
        if ($total > $max) {
            return false;
        }
        
        return $src_bytes + $dst_bytes;
    }
    
    public function sharpen() {
        // resource
        $res = $this->src_image;
        
        if (is_resource($this->dst_image)) {
            $res = $this->dst_image;
        }
        
        // define matrix
        $sharpen = array(
        array(0.0, -1.0, 0.0),
        array(-1.0, 5.0, -1.0),
        array(0.0, -1.0, 0.0)
        );
        
        // calculate the divisor
        $divisor = array_sum(array_map('array_sum', $sharpen));
        
        // apply the matrix
        imageconvolution($res, $sharpen, $divisor, 0);
        
        return $this;
    }
    
    public function blur() {
        // resource
        $res = $this->src_image;
        
        if (is_resource($this->dst_image)) {
            $res = $this->dst_image;
        }
        
        // define matrix
        $gaussian = array(
        array(1.0, 2.0, 1.0),
        array(2.0, 4.0, 2.0),
        array(1.0, 2.0, 1.0)
        );
        
        // calculate the divisor
        $divisor = array_sum(array_map('array_sum', $gaussian));
        
        // apply the matrix
        imageconvolution($res, $gaussian, $divisor, 0);
        
        return $this;
    }
    
    public function grayscale() {
        // resource
        $res = $this->src_image;
        
        if (is_resource($this->dst_image)) {
            $res = $this->dst_image;
        }
        
        imagefilter($res, IMG_FILTER_GRAYSCALE);
        
        return $this;
    }
    
    public function resize($dst_w, $dst_h, $force = false) {
        $ratio = 0;
        
        if (!$force) {
            // landscape
            if ($this->src_w > $this->src_h) {
                $ratio = $dst_w / $this->src_w;
                $dst_h = $this->src_h * $ratio;
            }
            // portrait
            if ($this->src_w < $this->src_h) {
                $ratio = $dst_h / $this->src_h;
                $dst_w = $this->src_w * $ratio;
            }
            // square : use smaller value as the match
            if ($this->src_w == $this->src_h) {
                if ($dst_w > $dst_h) {
                    $dst_w = $dst_h;
                } else {
                    $dst_h = $dst_w;
                }
            }
        }
        
        $this->dst_image = imagecreatetruecolor($dst_w, $dst_h);
        imagealphablending($this->dst_image, false);
        imagesavealpha($this->dst_image, true);
        
        $params = array(
        'dst_image' => $this->dst_image,
        'src_image' => $this->src_image,
        'dst_x' => 0,
        'dst_y' => 0,
        'src_x' => 0,
        'src_y' => 0,
        'dst_w' => $dst_w,
        'dst_h' => $dst_h,
        'src_w' => $this->src_w,
        'src_h' => $this->src_h
        );
        
        call_user_func_array('imagecopyresampled', array_values($params));
        
        $this->src_w = $dst_w;
        $this->src_h = $dst_h;
        
        return $this;
    }
    
    public function crop($dst_w, $dst_h, $src_x, $src_y) {
        
        // if the source image is square use smaller dest size
        // @link http://srccd.com/posts/the-move-to-anchor-cms
        if ($this->src_w == $this->src_h) {
            if ($dst_w > $dst_h) {
                $dst_w = $dst_h;
            } else {
                $dst_h = $dst_w;
            }
        }
        
        $this->dst_image = imagecreatetruecolor($dst_w, $dst_h);
        imagealphablending($this->dst_image, false);
        imagesavealpha($this->dst_image, true);
        $params = array(
        'dst_image' => $this->dst_image,
        'src_image' => $this->src_image,
        'dst_x' => 0,
        'dst_y' => 0,
        'src_x' => $src_x,
        'src_y' => $src_y,
        'dst_w' => $dst_w,
        'dst_h' => $dst_h,
        'src_w' => $dst_w,
        'src_h' => $dst_h
        );
        
        call_user_func_array('imagecopyresampled', array_values($params));
        
        $this->src_w = $dst_w;
        $this->src_h = $dst_h;
        
        return $this;
    }
    
    public function flush() {
        $this->src_image = $this->dst_image;
    }
    
    public function rotate(float $direction) {
        $white = 16777215;
        
        $degrees = $direction == 'cw' ? 270 : ($direction == 'ccw' ? 90 : NULL);
        if (!$degrees) {
            $degrees = 0;
        }
        
        $this->dst_image = imagerotate($this->src_image, $degrees, $white);
        if ($degrees != 0) {
            $tmp = $this->src_w;
            $this->src_w = $this->src_h;
            $this->src_h = $tmp;
        }
        
        //getimagesize($file)
        return $this;
    }
    
    public function palette($percentages = array(0.2, 0.7, 0.5)) {
        
        // Now set dimensions on where to pull color values from (based on percentages).
        $dimensions[] = ($this->src_w - ($this->src_w * $percentages[0]));
        $dimensions[] = ($this->src_h - ($this->src_h * $percentages[0]));
        
        $dimensions[] = ($this->src_w - ($this->src_w * $percentages[0]));
        $dimensions[] = ($this->src_h - ($this->src_h * $percentages[1]));
        
        $dimensions[] = ($this->src_w - ($this->src_w * $percentages[1]));
        $dimensions[] = ($this->src_h - ($this->src_h * $percentages[1]));
        
        $dimensions[] = ($this->src_w - ($this->src_w * $percentages[1]));
        $dimensions[] = ($this->src_h - ($this->src_h * $percentages[0]));
        
        $dimensions[] = ($this->src_w - ($this->src_w * $percentages[2]));
        $dimensions[] = ($this->src_h - ($this->src_h * $percentages[2]));
        
        // Here we'll pull the color values of certain pixels around the image based on our dimensions set above.
        for ($k = 0; $k < 10; $k++) {
            $newk = $k + 1;
            $rgb[] = imagecolorat($this->src_image, $dimensions[$k], $dimensions[$newk]);
            $k++;
        }
        
        // Almost done! Now we need to get the individual r,g,b values for our colors.
        foreach ($rgb as $colorvalue) {
            $r[] = dechex(($colorvalue >> 16) & 0xFF);
            $g[] = dechex(($colorvalue >> 8) & 0xFF);
            $b[] = dechex($colorvalue & 0xFF);
        }
        
        return array(
        strtoupper($r[0] . $g[0] . $b[0]),
        strtoupper($r[1] . $g[1] . $b[1]),
        strtoupper($r[2] . $g[2] . $b[2]),
        strtoupper($r[3] . $g[3] . $b[3]),
        strtoupper($r[4] . $g[4] . $b[4]));
    }
    
    public function output($type = 'png', $file = null) {
        $type = mb_strtolower($type);
        if ($type == 'png') {
            imagepng($this->dst_image, $file, 9);
        } elseif ($type == 'jpeg' or $type == 'jpg') {
            imagejpeg($this->dst_image, $file, 100);
        } elseif ($type == 'gif') {
            imagegif($this->dst_image, $file);
        }
        
        imagedestroy($this->dst_image);
    }
}

class FileManagerComponent extends Component {
    
    public $controller;
    public $upload_dir;
    public $upload_url;
    public $thumbnailSize;
    public $imageMaxWidth;
    
    public function initialize(array $config) {
        parent::initialize($config);
        $this->controller = $this->_registry->getController();
        $this->upload_dir = dirname($this->get_server_var('SCRIPT_FILENAME')) . '/files/';
        $this->upload_url = str_replace("/webroot", "", $this->get_full_url()) . '/files/';
        
        $this->thumbnailSize = 200;
        $this->imageMaxWidth = 1000;
        if(!empty($config['imageMaxWidth'])){
            $this->imageMaxWidth = $config['imageMaxWidth'];
        }
        setlocale(LC_CTYPE,'en_US.UTF-8');
    }
    
    protected function getUploadDir($dirname = null) {
        if ($dirname != null && $dirname !== "") {
            return $this->upload_dir . $dirname . "/";
        }
        return $this->upload_dir;
    }
    
    protected function getUploadUrl($dirname = null) {
        if ($dirname != null && $dirname !== "") {
            return $this->upload_url . $dirname . "/";
        }
        return $this->upload_url;
    }
    
    public function generate_unique_filename($filename = "") {
        $extension = "";
        if ($filename != "") {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            if ($extension != "") {
                $extension = "." . $extension;
            }
        }
        $randNumber = rand(10, 99);
        return md5(date('Y-m-d H:i:s:u').$randNumber) . $extension;
    }
    
    protected static function get_server_var($id) {
        return @$_SERVER[$id];
    }
    
    protected static function get_full_url() {
        $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0 ||
        !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
        return
        ($https ? 'https://' : 'http://') .
        (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] . '@' : '') .
        (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'] .
        ($https && $_SERVER['SERVER_PORT'] === 443 ||
        $_SERVER['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER['SERVER_PORT']))) .
        substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }
    
    public function upload($file, $uniqiue_name, $dirname = null) {
        if (!is_dir($this->getUploadDir($dirname))) {
            mkdir($this->getUploadDir($dirname), 0755, true);
        }
        
        $filename = "";
        if ($uniqiue_name) {
            $filename = $this->generate_unique_filename($file['name']);
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            // Added rtrim to remove file extension before adding again
            $filename = rtrim($file['name'], '.' . $ext) . '.' . $ext;
        }
        
        $filepath = $this->getUploadDir($dirname) . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filepath;
        }
        
        return false;
    }
    
    public function processImage($file, $uniqiue_name = true, $dirname = null, $makeThumbnail = false, $resize = true) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
         if(!in_array($ext, ['jpg', 'jpeg', 'png'])){
            if(in_array($ext, ['gif'])){
                $resize = false;
            } else {
                throw new BadRequestException();
            }
         }
        if ($filepath = $this->upload($file, $uniqiue_name, $dirname)) {
            $filename = basename($filepath);
            
            list($fwidth, $fheight, $ftype) = getimagesize($filepath);
        
            if($fwidth * $fheight > 3300 * 3300) {
                return ['success' => false, 'msg'=>'Image dimension is too big! Must be less than 3300x3300.'];
            }
            
            $imgUrl = $this->getUploadUrl($dirname) . $filename;
            $image = Image::open($filepath);
            
            // resize image
            if ($resize && isset($this->imageMaxWidth) && $this->imageMaxWidth > 0) {
                $width = intval($this->imageMaxWidth);
                $height = intval($this->imageMaxWidth);
                
                // resize larger images
                if (($width and $height) and ( $image->width() > $width or $image->height() > $height)
                ) {
                    $image->resize($width, $width);
                    $image->output($ext, $filepath);
                }
            }
            
            $imgSize = array($image->width(), $image->height());
            if ($makeThumbnail) {
                $this->makeThumbnail($dirname, $filename, $this->thumbnailSize, $this->thumbnailSize);
                $thmbUrl = $this->getUploadUrl($dirname) . "thumbnails/" . $filename;
                return ['link' => $imgUrl, "thmbUrl" => $thmbUrl, 'size' => $imgSize, 'success' => true, 'filepath' => $filepath];
            }
            
            
            return ['link' => $imgUrl, 'size' => $imgSize, 'success' => true, 'filepath' => $filepath];
        }
        return ['success' => false];
    }
    
    public function processFile($file, $dirname = null, $uniqiue_name = false) {
        if ($filepath = $this->upload($file, $uniqiue_name, $dirname)) {
            $filename = basename($filepath);
            $fileUrl = $this->getUploadUrl($dirname) . $filename;
            return ['link' => $fileUrl, 'success' => true, 'type' => $file['type'], 'size' => $file['size']];
        }
        return ['success' => false];
    }
    
    public function makeThumbnail($dirname, $imagename, $width, $height) {
        $srcFilePath = $this->getUploadDir($dirname) . $imagename;
        $image = Image::open($srcFilePath);
        
        //$image->flush();
        $image->resize(intval($width), intval($height));
        
        if (!is_dir($this->getUploadDir($dirname) . "thumbnails/")) {
            mkdir($this->getUploadDir($dirname) . "thumbnails/");
        }
        
        $ext = pathinfo($srcFilePath, PATHINFO_EXTENSION);
        $filename = $this->getUploadDir($dirname) . "thumbnails/" . $imagename;
        // Added rtrim to remove file extension before adding again
        //$filename = rtrim($srcFilePath, '.' . $ext) . '.' . $ext;
        $image->output($ext, $filename);
    }
    
    public function resizeImage($fileUrl, $width = null, $height = null) {
        
        //remove get parameters if exists
        $url = strtok($fileUrl, '?');
        //$filename = basename($url);
        $dir = strstr($url, "/files/");
        
        $srcFileUrl = dirname($this->get_server_var('SCRIPT_FILENAME')) . $dir;
        
        if($width == null) {
            $width = $this->imageMaxWidth;
        }
        if($height == null) {
            $height = $this->imageMaxWidth;
        }
        
        $image = Image::open($srcFileUrl);
        $image->resize(intval($width), intval($height));
        
        $ext = pathinfo($srcFileUrl, PATHINFO_EXTENSION);
        $image->output($ext, $srcFileUrl);
    }
    
    public function cropAndResizeImage($srcFileUrl, $dstFileAppendix, $width, $height, $x, $y, $newWidth = null, $newHeight = null) {
         //remove get parameters if exists
        $url = strtok($srcFileUrl, '?');
        //$filename = basename($url);
        $dir = strstr($url, "/files/");
        $srcFilePath = dirname($this->get_server_var('SCRIPT_FILENAME')) . $dir;
        
        $image = Image::open($srcFilePath);
        $image->crop($width, $height, $x, $y);
        // resize larger images
        if ($newWidth && $newHeight && ($image->width() > $newWidth || $image->height() > $newHeight )) {
            $image->flush();
            $image->resize($newWidth, $newHeight);
        }
        $ext = pathinfo($srcFilePath, PATHINFO_EXTENSION);
        // Added rtrim to remove file extension before adding again
        $filename = rtrim($srcFilePath, '.' . $ext) . $dstFileAppendix . '.' . $ext;
        
        $image->output($ext, $filename);
    }
    
    public function loadImages($dirname = null) {
        $images = [];
        
        // Image types.
        $image_types = [
        "image/gif",
        "image/jpeg",
        "image/pjpeg",
        "image/jpg",
        "image/pjpg",
        "image/png",
        "image/x-png"
        ];
        
        // Filenames in the uploads folder.
        $fnames = scandir($this->getUploadDir($dirname));
        
        // Check if folder exists.
        if ($fnames) {
            // Go through all the filenames in the folder.
            foreach ($fnames as $name) {
                // Filename must not be a folder.
                if (!is_dir($name)) {
                    // Check if file is an image.
                    if (in_array(mime_content_type(static::getUploadDir($dirname) . $name), $image_types)) {
                        // Build the image.
                        $img = array('url' => static::getUploadUrl($dirname) . $name, 'thumb' => static::getUploadUrl($dirname) . $name, 'name' => $name);
                        // Add to the array of image.
                        array_push($images, $img);
                    }
                }
            }
        }
        // Folder does not exist, respond with a JSON to throw error.
        else {
            return ['success' => false, 'msg' => 'Images folder does not exist!'];
        }
        
        return ['success' => true, 'images' => $images];
        //return json_encode(array('success' => true, 'token' => $token, 'link' => $imgUrl, 'size' => $imgSize, 'alt' => ''));
    }
    
    public function deleteFile($fileUrl) {
        
        if (empty($fileUrl)) {
            return ['success' => false, 'msg' => 'Error! Empty request.'];
        }
        
        //remove get parameters if exists
        $url = strtok($fileUrl, '?');
        //$filename = basename($url);
        $dir = strstr($url, "/files/");
        
        $resource = dirname($this->get_server_var('SCRIPT_FILENAME')) . $dir;
        file_exists($resource) and unlink($resource);
        
        return ['success' => true];
    }
    
    public function appendFileName($src, $appendix) {
        $ext = pathinfo($src, PATHINFO_EXTENSION);
        return rtrim($src, '.' . $ext) . $appendix . '.' . $ext;
    }
    
    public function _checkDeletedImage($entity, $field, $clear = true){
        if (!isset($this->request->data['delete_'.$field.'_image'])) { return; }
        if ($this->request->data['delete_'.$field.'_image'] != 'true' && $_FILES[$field.'_file']['size'] == 0) return;
        
        $this->request->data[$field.'_image'] = null;

        if (!$entity[$field.'_image']) return;
        if($clear) {
          $this->deleteFile($entity[$field. '_image']);
        }
    }

    public function _cropAndProcessImage($entity, $field, $width, $height){
        if (!isset($_FILES[$field . '_file'])) return true;
        if (!$_FILES[$field . '_file']['size']) return true;
        
        $result = $this->processImage($_FILES[$field . '_file'], true, 'product', false, false, false);
        if(!$result['success']) {
            $this->Flash->error($result['msg']);
            return false;
        }

        $ratio = $this->request->data['ratio_' .$field];
        $size = [
            'x' => $this->request->data['x_'.$field] / $ratio,
            'y' => $this->request->data['y_'.$field] / $ratio,
            'width' => $this->request->data['width_'.$field] / $ratio,
            'height' => $this->request->data['height_'.$field] / $ratio
        ];
        $this->cropAndResizeImage($result['link'], '', $size['width'], $size['height'], $size['x'], $size['y'], $width, $height);

        // $this->request->data[$field.'_image'] = $this->makeProgressive($result['link']);
        $this->request->data[$field.'_image'] = $result['link'];

        return true;
    }
    
    public function validateFile($filepath, $type = 'image') {
      $image_types = [
        "image/gif",
        "image/jpeg",
        "image/pjpeg",
        "image/jpg",
        "image/pjpg",
        "image/png",
        "image/x-png"
      ];

      $file_types = [
        "text/plain",
//        "application/x-shockwave-flash",
        "application/zip",
        "application/x-rar-compressed",
//        "audio/mpeg",
        "application/pdf",
        "application/vnd.ms-excel",
        "application/vnd.ms-powerpoint",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
      ];
      $types = [];
      switch ($type) {
          case "file":
            $types = array_merge($image_types, $file_types);
            break;
        default:
          $types = $image_types;
        break;
      }
      return in_array(mime_content_type($filepath), $types);
    }
    
}