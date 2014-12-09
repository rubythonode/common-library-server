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
     * @param $filename
     * @return string|null
     */
    public static function getExtension($filename) {

        $ext = NULL;
        if(file_exists($filename)) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
        }
        else {
            $ext = substr($filename, strrpos($filename, '.')+1);
        }

        return strtolower($ext);
    }
} 