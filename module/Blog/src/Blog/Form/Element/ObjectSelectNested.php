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

class ObjectSelectNested extends \Zend\Form\Element\Select
{
    /**
     * @var ObjectManager 
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $targetClass;

    /**
     * @var array
     */
    protected $queryParam;

    /**
     * Allows a user to further refine the query builder construction, and make a better query
     */
    protected $queryBuilderCallback;

    /**
     * @var string 
     */
    protected $property;

    /**
     * @var string 
     */
    protected $indentMultiplyer = 1;

    /**
     * @var string 
     */
    protected $indentChars = '-';

    /**
     * Allow user to decide what the multiplyer should be
     */
    protected $indentMultiplyerCallback;

    /**
     * @var array 
     */
    protected $requiredOptions = array(
        'property',
        'target_class',
        'object_manager',
        'query_param',
    );

    protected $optionalOptions = array(
        'indent_multiplyer',
        'indent_chars',
        'indent_multiplyer_callback',
        'query_builder_callback',
    );

    public function getObjectManager()
    {
        if (null === $this->objectManager) {
            throw new \Exception('ObjectManager not set');
        }
        return $this->objectManager;
    }

    public function setObjectManager($objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

    public function getTargetClass()
    {
        if (null === $this->targetClass) {
            throw new \Exception('Target class not set');
        }
        return $this->targetClass;
    }

    public function setTargetClass($targetClass)
    {
        $this->targetClass = $targetClass;
        return $this;
    }

    public function getQueryParam($nameOrValue = 'value')
    {
        return (('name' === $nameOrValue)? key($this->queryParam) : current($this->queryParam));
    }

    public function setQueryParam(array $queryParam)
    {
        $this->queryParam = $queryParam;
        return $this;
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function setProperty($property)
    {
        $this->property = $property;
        return $this;
    }

    public function getIndentChars()
    {
        return $this->indentChars;
    }

    public function setIndentChars($indentChars)
    {
        $this->indentChars = $indentChars;
        return $this;
    }

    public function getIndentMultiplyer()
    {
        return $this->indentMultiplyer;
    }

    public function setIndentMultiplyer($indentMultiplyer)
    {
        $this->indentMultiplyer = $indentMultiplyer;
        return $this;
    }

    public function setIndentMultiplyerCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new \Exception('Indent multiplyer callback is not callable');
        }
        $this->indentMultiplyerCallback = $callback;
        return $this;
    }

    public function doQueryBuilderCallback($queryBuilder, $nextParamNum)
    {
        if (null !== $this->queryBuilderCallback) {
            return call_user_func($this->queryBuilderCallback, $queryBuilder, $nextParamNum);
        }
    }

    public function setQueryBuilderCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new \Exception('The passed callback is not callable');
        }
        $this->queryBuilderCallback = $callback;
        return $this;
    }

    /**
     * @param  array|\Traversable $options
     * @return ObjectSelect
     */
    public function setOptions($options)
    {
        foreach ($this->requiredOptions as $key) {
            if (!isset($options[$key])) {
                throw new \Exception('Required option is not present in options array');
            }
            $setOptionMethod = $this->getOptionSetterMethod($key);
            $this->$setOptionMethod($options[$key]);
        }
        foreach ($this->optionalOptions as $key) {
            if (isset($options[$key])) {
                $setOptionMethod = $this->getOptionSetterMethod($key);
                $this->$setOptionMethod($options[$key]);
            }
        }
        return parent::setOptions($options);
    }

    public function getOptionSetterMethod($option)
    {
        return array_reduce(explode('_', $option), function ($result, $element) {
            return $result . ucfirst($element); 
        }, 'set');

    }

    /**
     * {@inheritDoc}
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            $flatNodesArray = $this->getTreeAsFlatArray($this->getTree());
            $options = array();
            foreach ($flatNodesArray as $node) {
                $options[] = array('label' => $this->getIndentedLabel($node), 'value' => $node['id']);
            }
            $this->setValueOptions($options);
        }
        return $this->valueOptions;
    }

    public function getIndentedLabel($node)
    {
        $multiplyBy = $node['lvl'] * $this->getIndentMultiplyer();
        if (null !== $this->indentMultiplyerCallback) {
            $multiplyBy = call_user_func($this->indentMultiplyerCallback, $multiplyBy, $node, $this->getIndentMultiplyer(), $this);
        }
        return str_repeat($this->getIndentChars(), $multiplyBy) . ' ' . $node[$this->getProperty()];
    }

    public function getTree()
    {
        $queryArray = $this->getTreeQuery()->getArrayResult();
        $repo = $this->getObjectManager()->getRepository($this->getTargetClass());
        return $repo->buildTree($queryArray);
    }

    public function getTreeQuery()
    {
        $paramNum = 1;
        $queryBuilder = $this->getObjectManager()->createQueryBuilder();
        $queryBuilder->select('node')
            ->from($this->getTargetClass(), 'node');
        $queryBuilder->where('node.' . $this->getQueryParam('name') . ' = ?' . $paramNum)
            ->orderBy('node.root, node.lft', 'ASC')
            ->setParameter($paramNum, $this->getQueryParam('value'));
        //Allow user to specify further query refinement
        $this->doQueryBuilderCallback($queryBuilder, ++$paramNum);
        return $queryBuilder->getQuery();
    }

    public function getTreeAsFlatArray(array $nodes, $depth = null)
    {
        if (null === $depth) {
            $depth = 0;
        }
        $return = array();
        $children = null;
        foreach ($nodes as $node) {
            $children = $node['__children'];
            unset($node['__children']);
            $return[] = $node;
            if (null !== $children) {
                $return = array_merge($return, $this->getTreeAsFlatArray($children, $depth + 1));
            }
            $children = null;
        }
        return $return;
    }
}
