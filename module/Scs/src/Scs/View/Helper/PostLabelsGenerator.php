<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Scs\View\Helper;

/**
 * View helper for translating messages.
 */
class PostLabelsGenerator extends \Zend\View\Helper\AbstractHelper
{
    /**
     *
     * @return string
     */
    public function __invoke(array $post)
    {
        $dataToCssConvertiblePostKeys = array(
            'parent_post_count'  => 'parent_post_category_slug', //i=0
            'child_post_count'   => 'child_post_category_slug',  //i=1
            'post_category_name' => 'post_category_slug',        //i=2
        );

        $labels = array();
        $missingLabelsPosition = array();
        $generatedLabelsCssClasses = array();
        $i = 0;

        foreach ($dataToCssConvertiblePostKeys as $dataKey => $cssConvertibleKey) {
            if (null === $post[$cssConvertibleKey]) {
                $missingLabelsPosition[] = $i;
                $i++;
                continue;
            }
            $cssClass = $this->view->cssClass($post[$cssConvertibleKey]);
            $generatedLabelsCssClasses[$i] = $cssClass;
            $labels[$i] = $this->getLabel($post[$dataKey], $cssClass);
            $i++;
        }
        if (!empty($missingLabelsPosition)) {
            $labels = $this->getMissingLabels($missingLabelsPosition, $generatedLabelsCssClasses, $labels);
        }
        krsort($labels);
        return implode('&nbsp;', $labels);
    }

    protected function getLabel($text, $cssclass = 'default')
    {
        return "<span class=\"label label-$cssclass\">$text</span>\n";
    }

    protected function getMissingLabels($missingLabelsPosition, $generatedLabelsCssClasses, $labels)
    {
        $cssClasses = $this->view->cssClass();
        $missingCssClasses = array_diff($cssClasses, $generatedLabelsCssClasses);
        foreach ($missingLabelsPosition as $position) {
            $labels[$position] = $this->getLabel('0', array_pop($missingCssClasses));
        }
        return $labels;
    }
}
