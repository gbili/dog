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
class TermsHighlighter extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Translate a message
     * @return string
     */
    public function __invoke($description, array $terms=array())
    {
        if (empty($terms)) {
            return $description;
        }

        $patters = array();
        $replacements = array();
        foreach ($terms as $term) {
            $patterns[] = "/$term/i";
            $replacements[] = "<strong class=\"highlighted-term\">$term</strong>";
        }
        return preg_replace($patterns, $replacements, $description);
    }
}
