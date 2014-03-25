<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Blog\Form\Element;

use RuntimeException;
use ReflectionMethod;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class Proxy extends \DoctrineModule\Form\Element\Proxy
{

    /**
     * Class Metadata
     * @var 
     */
    protected $metadata;

    /**
     * This will set the attribute value by calling
     * the property getter on the valueOption represented
     * object
     *
     * When passing: array(
     *      'attributes' => array(
     *          'data-img-src' => 'src'
     *          'someattribute' => 'property'
     *      )
     *  )
     * Each value option
     * @var array('attribute-name' => 'propertyname')
     */
    protected $attributes;

    /**
     * This will inject the the label_attribute
     * into each option
     */
    protected $labelAttributes;

    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['attributes'])) {
            $this->setAttributes($options['attributes']);
        }
        if (isset($options['value_option_label_attributes'])) {
            $this->setLabelAttributes($options['value_option_label_attributes']);
        }
    }

    protected function setLabelAttributes($labelAttributes)
    {
        $this->labelAttributes = $labelAttributes;
        return $this;
    }

    protected function pushLabelAttributes($option)
    {
        $option['label_attributes'] = $this->labelAttributes;
        return $option;
    }

    protected function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Add attributes array to option array
     *
     * @param targetInstance $object
     * @param array $option label value option array
     * @return array option with attributes added
     */
    protected function pushAttributes($object, $option)
    {
        foreach ($this->attributes as $attribute => $property) {
            if (!isset($option['attributes']) || !is_array($option['attributes'])) {
                $option['attributes'] = array();
            }
            $option['attributes'][$attribute] = $this->getData($object, $property);
        }
        return $option;
    }

    /**
     * Load value options
     *
     * @throws \RuntimeException
     * @return void
     */
    protected function loadValueOptions()
    {
        if (!($om = $this->objectManager)) {
            throw new RuntimeException('No object manager was set');
        }

        if (!($targetClass = $this->targetClass)) {
            throw new RuntimeException('No target class was set');
        }

        $this->metadata = $om->getClassMetadata($targetClass);

        $metadata   = $this->metadata;
        $identifier = $metadata->getIdentifierFieldNames();
        $objects    = $this->getObjects();
        $options    = array();

        if ($this->displayEmptyItem || empty($objects)) {
            $options[''] = $this->getEmptyItemLabel();
        }

        if (!empty($objects)) {
            foreach ($objects as $key => $object) {
                $label = $this->getData($object, $this->property);
                
                if (count($identifier) > 1) {
                    $value = $key;
                } else {
                    $value = current($metadata->getIdentifierValues($object));
                }

                $option = array('label' => $label, 'value' => $value);

                if (null !== $this->attributes) {
                    $option = $this->pushAttributes($object, $option);
                }
                if (null !== $this->labelAttributes) {
                    $option = $this->pushLabelAttributes($option);
                }

                $options[] = $option;
            }
        }

        $this->valueOptions = $options;
    }

    protected function getData($object, $property)
    {
        if (null !== ($generatedLabel = $this->generateLabel($object))) {
            return $generatedLabel;
        } 

        if ($property) {
            return $this->getDataFromGetterMethod($object, $property);
        }

        if (!is_callable(array($object, '__toString'))) {
            throw new RuntimeException(
                sprintf(
                    '%s must have a "__toString()" method defined if you have not set a property'
                    . ' or method to use.',
                    $targetClass
                )
            );
        }
        return (string) $object;
    }

    protected function getDataFromGetterMethod($object, $property)
    {
        if ($this->isMethod == false && !$this->metadata->hasField($property)) {
            throw new RuntimeException(
                sprintf(
                    'Property "%s" could not be found in object "%s"',
                    $property,
                    $this->targetClass
                )
            );
        }

        $getter = 'get' . ucfirst($property);
        if (!is_callable(array($object, $getter))) {
            throw new RuntimeException(
                sprintf('Method "%s::%s" is not callable', $this->targetClass, $getter)
            );
        }

        return $object->{$getter}();
    }
}
