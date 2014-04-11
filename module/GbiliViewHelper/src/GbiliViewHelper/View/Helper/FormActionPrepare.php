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
class FormActionPrepare extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Prepare form with the current matched url and add classes to it
     * if classes is empty and replace false: set 'form-horizontal' as class
     * if classes not empty and replace false: add 'form-horizontal' to classes param
     * if classes is empty and replace true: no classes are added at all
     * if classes not empty and replace true: only param classes are added, not 'form-horizontal'
     *
     * @param \Zend\Form\Form $form     Form to preare
     * @param array           $classes  classes to add 
     * @param boolean         $replace  change the behavior of classes addition
     * @return string
     */
    public function __invoke(\Zend\Form\Form $form, $classes = null, $replace = false)
    {
        if (!$form->hasAttribute('action')) {
            $this->addAction($form);
        }

        if (is_string($classes)) {
            $classes = array($classes);
        }

        if (!empty($classes)) {
            if ($replace) {
                $this->addClasses($form, $classes);
            } else {
                $classes[] = 'form-horizontal';
                $this->addClasses($form, $classes);
            }
        } else if (!$replace) {
            $this->addClasses($form, array('form-horizontal'));
        }
        $form->prepare();
    }


    public function addAction(\Zend\Form\Form $form)
    {
        $form->setAttribute('action', $this->view->url(null, array(), true));
    }

    public function addClasses(\Zend\Form\Form $form, array $classes)
    {
        $formClasses = explode(' ', $form->getAttribute('class'));
        $allClasses = array_merge($classes, $formClasses);
        $allClasses = array_unique($allClasses);
        $form->setAttribute('class', implode(' ', $allClasses));
    }
}
