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
 *
 */
class Paginator extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    protected $itemsPerPage = 30; //limit
    protected $pageFirstItem; //offset
    protected $page;
    protected $pagesCount;
    protected $totalItemsCount;

    /**
     * Grabs a param from route match by default.
     *
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function __invoke($paramName=null)
    {
        if (null !== $paramName) {
        }
        return $this;
    }

    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
        return $this;
    }

    public function getItemsPerPage()
    {
        if (null === $this->itemsPerPage) {
            throw new \Exception('itemsPerPage must be set');
        }
        return $this->itemsPerPage;
    }

    public function setPagesCount($count)
    {
        $this->pagesCount = (integer) $count;
        return $this;
    }

    public function getCurrentPage()
    {
        if (null === $this->page) {
            $this->page = (integer) $this->getController()->params()->fromRoute('id', 1);
        }
        return $this->page;
    }

    public function getPageFirstItem()
    {
        if (null === $this->pageFirstItem) {
            $this->pageFirstItem = (integer) ($this->getCurrentPage() * $this->getItemsPerPage()) - $this->getItemsPerPage();
        }
        return $this->pageFirstItem;
    }

    public function getPagesCount()
    {
        if (null === $this->pagesCount) {
            $pagesCount = $this->getTotalItemsCount() / $this->getItemsPerPage();
            $this->pagesCount = (integer) ceil($pagesCount);
        }
        return $this->pagesCount;
    }

    public function setTotalItemsCount($count)
    {
        $this->totalItemsCount = (integer) $count;
        return $this;
    }

    public function getTotalItemsCount()
    {
        if (null === $this->totalItemsCount) {
            throw new \Exception('Total items count must be set before passing to view helper');
        }
        return $this->totalItemsCount;
    }

    public function toArray()
    {
         return array(
            'totalItemsCount' => $this->getTotalItemsCount(),
            'pagesCount' => $this->getPagesCount(),
            'pageFirstItem' => $this->getPageFirstItem(),
            'currentPage' => $this->getCurrentPage(),
            'itemsPerPage' => $this->getItemsPerPage(),
        );
    }
}
