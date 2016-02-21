<?php

namespace phpOMS\Utils\Barcode;

use phpOMS\Datatypes\Exception\InvalidEnumValue;

abstract class C128Abstract
{
    protected static $CHECKSUM = 0;

    protected static $CODEARRAY = [];

    protected static $CODE_START = '';
    protected static $CODE_END   = '';

    protected $orientation = 0;
    protected $size        = 0;
    protected $dimension   = ['width' => 0, 'height' => 0];
    protected $content     = 0;
    protected $showText    = true;
    protected $margin      = ['top' => 0.0, 'right' => 4, 'bottom' => 0.0, 'left' => 4];
    protected $background  = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0];
    protected $front       = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0];

    public function __construct(string $content = '', int $size = 20, int $orientation = OrientationType::HORIZONTAL)
    {
        $this->content = $content;
        $this->setSize($size);
        $this->setOrientation($orientation);
    }

    public function setOrientation(int $orientation)
    {
        if (!OrientationType::isValidValue($orientation)) {
            throw new InvalidEnumValue($orientation);
        }

        $this->orientation = $orientation;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setSize(int $size)
    {
        if ($size < 0) {
            throw new \OutOfBoundsException($size);
        }

        $this->size = $size;
    }

    protected function generateCodeString()
    {
        $keys       = array_keys(static::$CODEARRAY);
        $values     = array_flip($keys);
        $codeString = '';
        $length     = strlen($this->content);
        $checksum   = static::$CHECKSUM;

        for ($pos = 1; $pos <= $length; $pos++) {
            $activeKey = substr($this->content, ($pos - 1), 1);
            $codeString .= static::$CODEARRAY[$activeKey];
            $checksum += $values[$activeKey] * $pos;
        }

        $codeString .= static::$CODEARRAY[$keys[($checksum - (intval($checksum / 103) * 103))]];
        $codeString = static::$CODE_START . $codeString . static::$CODE_END;

        return $codeString;
    }

    public function get()
    {
        $codeString = static::$CODE_START . $this->generateCodeString() . static::$CODE_END;

        return $this->createImage($codeString, 20);
    }

    protected function createImage(string $codeString, int $codeLength = 20)
    {
        for ($i = 1; $i <= strlen($codeString); $i++) {
            $codeLength = $codeLength + (int) (substr($codeString, ($i - 1), 1));
        }

        if ($this->orientation === OrientationType::HORIZONTAL) {
            $imgWidth  = $codeLength;
            $imgHeight = $this->size;
        } else {
            $imgWidth  = $this->size;
            $imgHeight = $codeLength;
        }

        $image    = imagecreate($imgWidth, $imgHeight);
        $black    = imagecolorallocate($image, 0, 0, 0);
        $white    = imagecolorallocate($image, 255, 255, 255);
        $location = 0;
        $length   = strlen($codeString);
        imagefill($image, 0, 0, $white);

        for ($position = 1; $position <= $length; $position++) {
            $cur_size = $location + (int) (substr($codeString, ($position - 1), 1));

            if ($this->orientation === OrientationType::HORIZONTAL) {
                imagefilledrectangle($image, $location, 0, $cur_size, $imgHeight, ($position % 2 == 0 ? $white : $black));
            } else {
                imagefilledrectangle($image, 0, $location, $imgWidth, $cur_size, ($position % 2 == 0 ? $white : $black));
            }

            $location = $cur_size;
        }

        return $image;
    }
}
