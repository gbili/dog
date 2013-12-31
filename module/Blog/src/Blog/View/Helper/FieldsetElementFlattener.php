<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Blog\View\Helper;

/**
 * View helper for translating messages.
 */
class FieldsetElementFlattener extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Translate a message
     *
     * @param  string $message
     * @param  string $textDomain
     * @param  string $locale
     * @throws Exception\RuntimeException
     * @return string
     */
    public function __invoke(\Zend\Form\Fieldset $formOrFieldset)
    {
        return $this->getFlattenedElementsArray($formOrFieldset);
    }

    public function getFlattenedElementsArray(\Zend\Form\Fieldset $formOrFieldset)
    {
        $elements = (($formOrFieldset instanceof \Zend\Form\Form)? array() : $formOrFieldset->getElements());
        foreach ($formOrFieldset->getFieldsets() as $fieldset) {
            $elements += $this->getFlattenedElementsArray($fieldset);
        }
        return $elements + (($formOrFieldset instanceof \Zend\Form\Fieldset)? $formOrFieldset->getElements() : array());
    }
}
