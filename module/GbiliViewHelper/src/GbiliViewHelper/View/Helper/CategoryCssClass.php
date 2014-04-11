<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace GbiliViewHelper\View\Helper;

/**
 * View helper for translating messages.
 */
class CategoryCssClass extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Translate a message
     * @return string
     */
    public function __invoke($categorySlug = null)
    {
        $cssClass = array(
            'symptom'  => 'primary', 
            'cause'    => 'warning',
            'solution' => 'success', 
        );

        if (null === $categorySlug) {
            return $cssClass;
        }

        if (!isset($cssClass[$categorySlug])) {
            $categorySlug = $this->getView()->translate($categorySlug, null, 'en');
            if (!isset($cssClass[$categorySlug])) {
                throw new \Exception('You must translate the category slug in GbiliViewHelper/language/en.php before using CategoryCssClass : ' . $categorySlug);
            }
        }
        return $cssClass[$categorySlug];
    }
}
