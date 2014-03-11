<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Blog\Controller\Plugin;

/**
 * Flash Messenger - implement session-based messages
 */


use ArrayIterator;
use Countable;
use IteratorAggregate;
use Zend\Session\Container;
use Zend\Session\ManagerInterface as Manager;
use Zend\Stdlib\SplQueue;

/**
 * Flash Messenger - implement session-based messages
 */
class Messenger extends \Zend\Mvc\Controller\Plugin\AbstractPlugin implements IteratorAggregate, Countable
{
    /**
     * Default messages namespace
     */
    const NAMESPACE_DEFAULT = 'default';

    /**
     * @var Container
     */
    protected $container;

    /**
     * Messages from previous request
     * @var array
     */
    protected $messages = array();

    /**
     * @var Manager
     */
    protected $session;

    /**
     * Whether a message has been added during this request
     *
     * @var bool
     */
    protected $messageAdded = false;

    /**
     * Instance namespace, default is 'default'
     *
     * @var string
     */
    protected $namespace = self::NAMESPACE_DEFAULT;

    /**
     * Set the session manager
     *
     * @param  Manager        $manager
     * @return FlashMessenger
     */
    public function setSessionManager(Manager $manager)
    {
        $this->session = $manager;

        return $this;
    }

    /**
     * Retrieve the session manager
     *
     * If none composed, lazy-loads a SessionManager instance
     *
     * @return Manager
     */
    public function getSessionManager()
    {
        if (!$this->session instanceof Manager) {
            $this->setSessionManager(Container::getDefaultManager());
        }

        return $this->session;
    }

    /**
     * Get session container for flash messages
     *
     * @return Container
     */
    public function getContainer()
    {
        if ($this->container instanceof Container) {
            return $this->container;
        }

        $manager = $this->getSessionManager();
        $this->container = new Container('FlashMessenger', $manager);

        return $this->container;
    }

    /**
     * Change the namespace messages are added to
     *
     * Useful for per action controller messaging between requests
     *
     * @param  string         $namespace
     * @return FlashMessenger Provides a fluent interface
     */
    public function setNamespace($namespace = 'default')
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Get the message namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Add a message
     *
     * @param  string         $message
     * @return FlashMessenger Provides a fluent interface
     */
    public function addMessage($message, $class = null)
    {
        $container = $this->getContainer();
        $namespace = $this->getNamespace();

        if (!$this->messageAdded) {
            $this->getMessagesFromContainer();
            $container->setExpirationHops(1, null);
        }

        if (!isset($container->{$namespace})
            || !($container->{$namespace} instanceof SplQueue)
        ) {
            $container->{$namespace} = new SplQueue();
        }

        if (null !== $class) {
            $message = array($class => $message);
        }

        $container->{$namespace}->push($message);

        $this->messageAdded = true;

        return $this;
    }


    /**
     * Whether a specific namespace has messages
     *
     * @return bool
     */
    public function hasMessages()
    {
        $this->getMessagesFromContainer();

        return isset($this->messages[$this->getNamespace()]);
    }

    /**
     * Get messages from a specific namespace
     *
     * @return array
     */
    public function getMessages()
    {
        if ($this->hasMessages()) {
            return $this->messages[$this->getNamespace()]->toArray();
        }

        return array();
    }

    /**
     * Clear all messages from the previous request & current namespace
     *
     * @return bool True if messages were cleared, false if none existed
     */
    public function clearMessages()
    {
        if ($this->hasMessages()) {
            unset($this->messages[$this->getNamespace()]);

            return true;
        }

        return false;
    }

    /**
     * Clear all messages from the container
     *
     * @return bool True if messages were cleared, false if none existed
     */
    public function clearMessagesFromContainer()
    {
        $this->getMessagesFromContainer();
        if (empty($this->messages)) {
            return false;
        }
        unset($this->messages);
        $this->messages = array();

        return true;
    }

    /**
     * Check to see if messages have been added to the current
     * namespace within this request
     *
     * @return bool
     */
    public function hasCurrentMessages()
    {
        $container = $this->getContainer();
        $namespace = $this->getNamespace();

        return isset($container->{$namespace});
    }

    /**
     * Get messages that have been added to the current
     * namespace within this request
     *
     * @return array
     */
    public function getCurrentMessages()
    {
        if ($this->hasCurrentMessages()) {
            $container = $this->getContainer();
            $namespace = $this->getNamespace();

            return $container->{$namespace}->toArray();
        }

        return array();
    }

    /**
     * Get messages that have been added to the current
     * namespace in specific namespace
     *
     * @param  string $namespaceToGet
     * @return array
     */
    public function getCurrentMessagesFromNamespace($namespaceToGet)
    {
        $namespace = $this->getNamespace();
        $this->setNamespace($namespaceToGet);
        $messages = $this->getCurrentMessages();
        $this->setNamespace($namespace);

        return $messages;
    }

    /**
     * Clear messages from the current request and current namespace
     *
     * @return bool True if current messages were cleared, false if none existed.
     */
    public function clearCurrentMessages()
    {
        if ($this->hasCurrentMessages()) {
            $container = $this->getContainer();
            $namespace = $this->getNamespace();
            unset($container->{$namespace});

            return true;
        }

        return false;
    }

    /**
     * Clear messages from the current namespace
     *
     * @param  string $namespaceToClear
     * @return bool True if current messages were cleared from the given namespace, false if none existed.
     */
    public function clearCurrentMessagesFromNamespace($namespaceToClear)
    {
        $namespace = $this->getNamespace();
        $this->setNamespace($namespaceToClear);
        $cleared = $this->clearCurrentMessages();
        $this->setNamespace($namespace);

        return $cleared;
    }

    /**
     * Clear messages from the container
     *
     * @return bool True if current messages were cleared from the container, false if none existed.
     */
    public function clearCurrentMessagesFromContainer()
    {
        $container = $this->getContainer();

        $namespaces = array();
        foreach ($container as $namespace => $messages) {
            $namespaces[] = $namespace;
        }

        if (empty($namespaces)) {
            return false;
        }

        foreach ($namespaces as $namespace) {
            unset($container->{$namespace});
        }

        return true;
    }

    /**
     * Complete the IteratorAggregate interface, for iterating
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        if ($this->hasMessages()) {
            return new ArrayIterator($this->getMessages());
        }

        return new ArrayIterator();
    }

    /**
     * Complete the countable interface
     *
     * @return int
     */
    public function count()
    {
        if ($this->hasMessages()) {
            return count($this->getMessages());
        }

        return 0;
    }

    /**
     * Get messages from a specific namespace
     *
     * @param  string $namespaceToGet
     * @return array
     */
    public function getMessagesFromNamespace($namespaceToGet)
    {
        $namespace = $this->getNamespace();
        $this->setNamespace($namespaceToGet);
        $messages = $this->getMessages();
        $this->setNamespace($namespace);

        return $messages;
    }

    /**
     * Pull messages from the session container
     *
     * Iterates through the session container, removing messages into the local
     * scope.
     *
     * @return void
     */
    protected function getMessagesFromContainer()
    {
        if (!empty($this->messages) || $this->messageAdded) {
            return;
        }

        $container = $this->getContainer();

        $namespaces = array();
        foreach ($container as $namespace => $messages) {
            $this->messages[$namespace] = $messages;
            $namespaces[] = $namespace;
        }

        foreach ($namespaces as $namespace) {
            unset($container->{$namespace});
        }
    }
}
