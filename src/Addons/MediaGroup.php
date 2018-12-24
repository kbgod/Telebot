<?php
/**
 * Created by PhpStorm.
 * User: Bogdan
 * Date: 22.08.2018
 * Time: 21:28
 */

namespace Telebot\Addons;


class MediaGroup
{

    private $MediaGroupArray = [];
    private $MediaGroupType;
    private $MediaGroupAttaches = [];

    const TYPE_VIDEO = 'video';
    const TYPE_PHOTO = 'photo';

    public function __construct($type)
    {
        $this->MediaGroupType = $type;
    }

    public function add($media, $caption = '', $width = null, $height = null, $duration = null)
    {
        $MediaGroupNode = ['type' => $this->MediaGroupType, 'caption' => $caption];
        if (file_exists($media)) $MediaGroupNode['media'] = $this->attach($media);
        else $MediaGroupNode['media'] = $media;
        if ($this->MediaGroupType == self::TYPE_VIDEO) {
            $MediaGroupNode['width'] = $width;
            $MediaGroupNode['height'] = $height;
            $MediaGroupNode['duration'] = $duration;

        }
        $this->MediaGroupArray[] = $MediaGroupNode;
        return $this;
    }

    private function attach($path)
    {
        $this->MediaGroupAttaches[basename($path)] = new \CURLFile($path);
        return 'attach://' . basename($path);
    }

    public function build(&$fields)
    {
        $fields['media'] = json_encode($this->MediaGroupArray);
        $fields = array_merge($fields, $this->MediaGroupAttaches);
    }

}