<?php
/*
    This file is part of InfoTownMedia Plugin of EC-CUBE3
    
    Copyright(c) 2015- Hiroshi Sawai All Rights Reserved.
    
    http://www.info-town.co.jp/
    
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
    */
namespace Plugin\InfoTownMedia\Service;

use Eccube\Application;

/**
 * Handle image that will be posted to WP API.
 * @package Plugin\InfoTownMedia\Service
 */
class ImageService
{
    /**
     * @var \Eccube\Application $app
     */
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get img markup. img src is absolute url(ie <img src="/upload/save_image/example.png">).
     * @param $imageSaveUrlPath absolute url of image directory(image_save_realdir ie /upload/save_image).
     * @return array Img markup list(ie <img src="/upload/save_image/example.png">).
     */
    public function getAllImg($imageSaveUrl)
    {
        $imgList = [];
        $data    = $this->getAllUrlPathAndTimestamp($imageSaveUrl);
        $total   = count($data['url']);
        for ($i = 0; $i < $total; $i++) {
            array_push(
                $imgList,
                '<img class="infotown-attachment" src="'.$data['url'][$i].'" data-id="'.$data['timestamp'][$i].'">'
            );
        }

        return $imgList;

    }

    /**
     * Get all image url path of images directory.
     * Image directory is almost same to image_save_realdir.
     * @param string $imageSaveUrl Absolute url of image save directory.
     * @return array Associated array of absolute url list of image and updated timestamp.
     */
    private function getAllUrlPathAndTimestamp($imageSaveUrl)
    {
        $urlList = [];
        // Absolute physical path of image files.
        $pathList = $this->scanImageDir($this->app['config']['image_save_realdir']);
        // Timestamp.
        $updateAt = $this->getUpdateAt($pathList);
        // Get absolute url of image file from absolute physical path of image file.
        foreach ($pathList as $path) {
            $pos = mb_strpos($path, $imageSaveUrl);
            array_push($urlList, mb_substr($path, $pos));
        };
        // Sort file updated timestamp.
        array_multisort($updateAt, SORT_DESC, SORT_NUMERIC, $urlList);

        $data = [
            'url'       => $urlList,
            'timestamp' => $updateAt,
        ];

        return $data;

    }

    /**
     * Create file raw data.
     * @param $data Image raw data.
     * @param $type Image format.
     * @param $name Image file name.
     * @return mixed
     */
    public function createFileByRaw($data, $type, $name)
    {
        $extension = ( preg_match('/jpe?g/', $type) ) ? 'jpg' : 'png';
        $name      = substr($name, 0, strrpos($name, '.')).'_'.time().'.'.$extension;
        $path      = $this->getImagePath($name, true);
        $fp        = fopen($path, 'w');
        fwrite($fp, $data);
        fclose($fp);

        return $path;
    }

    /**
     * Get physical path of image file.
     * @param string $filename target file name.
     * @param boolean $dry if it is true then return path in the case of file not exists.
     * @return mixed(string|boolean) physical path of image. if file do not exist, then return false.
     */
    private function getImagePath($filename, $dry = false)
    {
        $path = $_SERVER['DOCUMENT_ROOT'].'/'
            .ltrim(rtrim($this->app['config']['image_save_urlpath'], '/'), '/').'/'.$filename;
        if (file_exists($path) || $dry === true) {
            return $path;
        }

        return false;
    }

    /**
     * Get timestamp that file has been updated.
     * @param array $pathList target physical file path.
     * @return array timestamp of file.
     */
    private function getUpdateAt($pathList)
    {
        $updateAt = [];
        foreach ($pathList as $path) {
            array_push($updateAt, filemtime($path));
        }

        return $updateAt;
    }

    /**
     * Get all physical file path in directory.
     * @param $dir target directory physical path.
     * @return array image file physical path.
     */
    private function scanImageDir($dir)
    {
        $files = scandir($dir);
        $files = array_filter(
            $files,
            function ($file) {
                return !in_array($file, array('.', '..'));
            }
        );

        $list = array();
        foreach ($files as $file) {
            $fullpath = rtrim($dir, '/').'/'.$file;
            if (is_file($fullpath)) {
                $list[] = $fullpath;
            }
            if (is_dir($fullpath)) {
                $list = array_merge($list, $this->scanImageDir($fullpath));
            }
        }

        return $list;
    }
}