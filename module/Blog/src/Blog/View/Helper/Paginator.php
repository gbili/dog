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
class Paginator extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Holds the output string of self::renderPagination()
     * @var string
     */
    protected $rendering;

    /**
     * Controller Plugin with page and pagesCount vars
     * @var \Zend\Mvc\Controller\Plugin\AbstractPlugin
     */
    protected $paginatorPlugin;

    /**
     * Translate a message
     *
     * @param  string $message
     * @param  string $textDomain
     * @param  string $locale
     * @throws Exception\RuntimeException
     * @return string
     */
    public function __invoke($param = null)
    {
        if (null !== $param) {
            $this->setPaginatorPlugin($param);
        }
        return $this->getRendering();
    }

    public function getRendering()
    {
        if (null === $this->rendering) {
            ob_start();
            $this->renderPagination();
            $this->rendering = ob_get_clean();
        }
        return $this->rendering;
    }

    public function getPaginatorPlugin()
    {
        if (null === $this->paginatorPlugin) {
            throw new \Exception('Paginator plugin not set');
        }
        return $this->paginatorPlugin;
    }

    public function setPaginatorPlugin(\Blog\Controller\Plugin\Paginator $paginator)
    {
        $this->paginatorPlugin = $paginator;
        return $this;
    }

    public function renderPagination()
    {
        $view = $this->getView();
        $plugin     = $this->getPaginatorPlugin();
        $page       = (integer) $plugin->getCurrentPage();
        $pagesCount = (integer) $plugin->getPagesCount();
?>
        <ul class="pagination">
<?php if (1 === $page) :?>
            <li class="disabled">
                <span>&laquo;</span>
            </li>
<?php else : ?>
            <li>
                <a href="<?php echo $view->url(null, array('id' => $page-1), true)?>">&laquo;</a>
            </li>
<?php endif ?>
<?php for ($i=1; $i<=$pagesCount; $i++) : ?>
    <?php if ($i === $page) :?>
            <li class="active">
                <span><?php echo $i ?></span>
            </li>
    <?php else : ?>
            <li>
                <a href="<?php echo $view->url(null, array('id' => $i), true)?>"><?php echo $i ?></a>
            </li>
    <?php endif ?>
<?php endfor ?>
<?php if ($pagesCount === $page) :?>
            <li class="disabled">
                <span>&raquo;</span>
            </li>
<?php else : ?>
            <li>
                <a href="<?php echo $view->url(null, array('id' => $page+1), true)?>">&raquo;</a>
            </li>
<?php endif ?>
    </ul><?php
    }
}
