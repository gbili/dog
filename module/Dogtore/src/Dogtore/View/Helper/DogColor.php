<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Dogtore\View\Helper;

/**
 * View helper for translating messages.
 */
class DogColor extends \Zend\View\Helper\AbstractHelper
{
    protected $optionsParam;

    /**
     * Translate a message
     * @return string
     */
    public function __invoke($hexColor, $decreaseBy=20)
    {
        $hexRgb = str_split($hexColor, 2);
        $newHexRgb = array_map(function ($hex) use ($decreaseBy){
            $max = hexdec('FF');
            $min = hexdec('00');
            $number = hexdec($hex);
            $newNumber = $number - $decreaseBy;
            if ($min > $newNumber) {
                $newNumber = $min;
            }
            $newHex = (string) dechex($newNumber);
            if ( 2 > strlen($newHex)) {
                $newHex = '0' . $newHex;
            }
            return $newHex;
        }, $hexRgb);
        $decreasedHexColor = implode('', $newHexRgb);
        return $decreasedHexColor;
    }
}
