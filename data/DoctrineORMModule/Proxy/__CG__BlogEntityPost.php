<?php

namespace DoctrineORMModule\Proxy\__CG__\Blog\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Post extends \Blog\Entity\Post implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'id', 'slug', 'uniqueslug', 'locale', 'translated', 'data', 'categoryslug', 'user', 'lft', 'lvl', 'rgt', 'root', 'parent', 'children');
        }

        return array('__isInitialized__', 'id', 'slug', 'uniqueslug', 'locale', 'translated', 'data', 'categoryslug', 'user', 'lft', 'lvl', 'rgt', 'root', 'parent', 'children');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Post $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function hasId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasId', array());

        return parent::hasId();
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function isOwnedBy(\User\Entity\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isOwnedBy', array($user));

        return parent::isOwnedBy($user);
    }

    /**
     * {@inheritDoc}
     */
    public function setSlug($slug)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSlug', array($slug));

        return parent::setSlug($slug);
    }

    /**
     * {@inheritDoc}
     */
    public function getSlug()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSlug', array());

        return parent::getSlug();
    }

    /**
     * {@inheritDoc}
     */
    public function setUniqueslug($uniqueslug)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUniqueslug', array($uniqueslug));

        return parent::setUniqueslug($uniqueslug);
    }

    /**
     * {@inheritDoc}
     */
    public function getUniqueslug()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUniqueslug', array());

        return parent::getUniqueslug();
    }

    /**
     * {@inheritDoc}
     */
    public function setData(\Blog\Entity\PostData $postData = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setData', array($postData));

        return parent::setData($postData);
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getData', array());

        return parent::getData();
    }

    /**
     * {@inheritDoc}
     */
    public function __call($method, $params)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__call', array($method, $params));

        return parent::__call($method, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function setCategoryslug($slug)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCategoryslug', array($slug));

        return parent::setCategoryslug($slug);
    }

    /**
     * {@inheritDoc}
     */
    public function getCategoryslug()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCategoryslug', array());

        return parent::getCategoryslug();
    }

    /**
     * {@inheritDoc}
     */
    public function setParent(\Blog\Entity\Post $parent = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setParent', array($parent));

        return parent::setParent($parent);
    }

    /**
     * {@inheritDoc}
     */
    public function hasParent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasParent', array());

        return parent::hasParent();
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getParent', array());

        return parent::getParent();
    }

    /**
     * {@inheritDoc}
     */
    public function unsetParent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'unsetParent', array());

        return parent::unsetParent();
    }

    /**
     * {@inheritDoc}
     */
    public function setRoot(\Blog\Entity\Post $root = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRoot', array($root));

        return parent::setRoot($root);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoot()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRoot', array());

        return parent::getRoot();
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getChildren', array());

        return parent::getChildren();
    }

    /**
     * {@inheritDoc}
     */
    public function hasChildren()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasChildren', array());

        return parent::hasChildren();
    }

    /**
     * {@inheritDoc}
     */
    public function addChild(\Blog\Entity\Post $child)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addChild', array($child));

        return parent::addChild($child);
    }

    /**
     * {@inheritDoc}
     */
    public function addChildren(\Doctrine\Common\Collections\Collection $children)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addChildren', array($children));

        return parent::addChildren($children);
    }

    /**
     * {@inheritDoc}
     */
    public function removeChild(\Blog\Entity\Post $child)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeChild', array($child));

        return parent::removeChild($child);
    }

    /**
     * {@inheritDoc}
     */
    public function removeChildren(\Doctrine\Common\Collections\Collection $children)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeChildren', array($children));

        return parent::removeChildren($children);
    }

    /**
     * {@inheritDoc}
     */
    public function removeAllChildren()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeAllChildren', array());

        return parent::removeAllChildren();
    }

    /**
     * {@inheritDoc}
     */
    public function setTranslated(\Blog\Entity\TranslatedPost $translated = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTranslated', array($translated));

        return parent::setTranslated($translated);
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTranslated', array());

        return parent::getTranslated();
    }

    /**
     * {@inheritDoc}
     */
    public function hasTranslated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasTranslated', array());

        return parent::hasTranslated();
    }

    /**
     * {@inheritDoc}
     */
    public function setUser(\User\Entity\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUser', array($user));

        return parent::setUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getUser()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUser', array());

        return parent::getUser();
    }

    /**
     * {@inheritDoc}
     */
    public function setLocale($locale)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLocale', array($locale));

        return parent::setLocale($locale);
    }

    /**
     * {@inheritDoc}
     */
    public function hasLocale()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasLocale', array());

        return parent::hasLocale();
    }

    /**
     * {@inheritDoc}
     */
    public function getLocale()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLocale', array());

        return parent::getLocale();
    }

    /**
     * {@inheritDoc}
     */
    public function reuseLocales($one, $other)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'reuseLocales', array($one, $other));

        return parent::reuseLocales($one, $other);
    }

    /**
     * {@inheritDoc}
     */
    public function untideDependencies()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'untideDependencies', array());

        return parent::untideDependencies();
    }

}
