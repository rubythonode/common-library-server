<?php
/**
 * Created by PhpStorm.
 * User: gsshin
 * Date: 2014-12-09
 * Time: 오전 9:06
 */

class FileHelper {

    /**
     * 해당 파일 경로 또는 이름을 가지고 확장자를 가져온다.
     * 무조건 소문자로 리턴한다.
     *
     * @param $full_path
     * @return string|null
     */
    public static function getExtension($full_path) {

        $ext = null;
        if(is_file($full_path)) {
            $ext = pathinfo($full_path, PATHINFO_EXTENSION);
        }
        else {
            $ext = substr($full_path, strrpos($full_path, '.')+1);
        }

        return strtolower($ext);
    }

    /**
     * @param $full_path
     * @return string|null
     */
    public static function getFileName($full_path) {
        $file_name = null;
        if(is_file($full_path)) {
            $file_name = pathinfo($full_path, PATHINFO_BASENAME);
        }

        return $file_name;
    }

    /**
     * @param $full_path
     * @return string|null
     */
    public static function getFilePath($full_path) {
        $file_path = null;
        if(is_file($full_path)) {
            $file_path = pathinfo($full_path, PATHINFO_DIRNAME);
        }

        return $file_path;
    }

    /**
     * @param string $full_path     파일 경로
     * @param int $width            가로 크기
     * @param int $height           세로 크기
     * @param string $target_path   저장 경로
     * @return string/null
     */
    public static function getResizedImage($full_path, $width=0, $height=0, $target_path) {

        if(is_file($full_path)) {

            $image = null;

            if(Phalcon\Image\Adapter\Imagick::check()) {
                $image = new \Phalcon\Image\Adapter\Imagick($full_path);
            }
            else if(Phalcon\Image\Adapter\GD::check()) {
                $image = new \Phalcon\Image\Adapter\GD($full_path);
            }

            if(!$image) {
                return null;
            }

            if($width==0 and $height==0) {
                // 가로, 세로 값이 둘다 0일 경우 이미지 변환 연산을 하지 않음.
                return $full_path;
            }

            if($width==0) {
                $width = ceil($height * $image->getWidth() / $image->getHeight());
            }
            else if($height==0) {
                $height = ceil($width * $image->getHeight() / $image->getWidth());
            }

            if($width==0 or $height==0) {
                return null;
            }

            if($image->getWidth()/$image->getHeight() > $width / $height) {
                // 가로 자름
                $image->crop((int)($image->getHeight() * $width / $height), $image->getHeight(), ($image->getWidth()-ceil($image->getHeight() * $width / $height)) / 2);
            }
            else {
                // 세로 자름
                $image->crop($image->getWidth(), (int)($image->getWidth() * $height / $width), null, ($image->getHeight() - (int)($image->getHeight() * $height / $width)) / 2);
            }

            if(!$target_path) {
                $target_path = FileHelper::getFilePath($full_path);
            }
            else if(!is_dir($target_path)) {
                mkdir($target_path, 0777, true);
            }

            $new_full_path = rtrim($target_path, "/")."/".FileHelper::getFileName($full_path);
            if($image->resize($width, $height)->save($new_full_path)) {
                return $new_full_path;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }
} 