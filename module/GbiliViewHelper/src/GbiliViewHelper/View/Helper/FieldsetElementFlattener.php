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
class FieldsetElementFlattener extends \Zend\View\Helper\AbstractHelper
{
    /**
     *
     * @return string
     */
    public function __invoke(\Zend\Form\Fieldset $formOrFieldset)
    {
        return $this->getFlattenedElementsArray($formOrFieldset);
    }

    public function getFlattenedElementsArray($fieldset)
    {
        $elements = array();
        foreach ($fieldset->getFieldsets() as $innerFieldset) {
            $elements += $this->getFlattenedElementsArray($innerFieldset);
        }
        $fieldsetElements = $this->getElementsIndexedByName($fieldset->getElements());
        return (($fieldset instanceof \Zend\Form\Form)? $elements + $fieldsetElements : $fieldsetElements + $elements);
    }

    public function getElementsIndexedByName($elements)
    {
        $elementsIndexedByName = array();
        foreach ($elements as $element) {
            $elementsIndexedByName[$element->getAttribute('name')] = $element;
        }
        return $elementsIndexedByName;
    }
}
