<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace GbiliViewHelper\Service;

/**
 *
 */
class ExpressivePregTransform
{
    protected $stringToTransform;

    /**
     * @param $string to transform. Allow null, then the string must be passed to underscoredString
     */
    public function setStringToTransform($string)
    {
        $this->stringToTransform = $string;
        return $this;
    }

    public function underscoreToSpace($underscoredString = null)
    {
        return $this->transform('_', ' ', $underscoredString);
    }

    public function spaceToUnderscore($spacedString = null)
    {
        return $this->transform(' ', '_', $spacedString);
    }

    public function transform($from, $to, $string = null)
    {
        $string = $this->getStringToTransform($string);

        //TODO remove this check once all routes are well configured
        if (0 < preg_match("/$to/", $string)) {
            throw new \Exception('This string will not reverse properly');
        }
        return preg_replace("/$from/", $to, $string);
    }

    protected function getStringToTransform($string)
    {
        if (null !== $string) {
            return $string;
        }
        if (null === $this->stringToTransform) {
            throw new \Exception('No subject string passed either to invoke nor to method');
        }
        return $this->stringToTransform;
    }
}
