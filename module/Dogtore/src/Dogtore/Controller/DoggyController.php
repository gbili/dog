<?php
namespace Dogtore\Controller;

class DoggyController extends \Zend\Mvc\Controller\AbstractActionController
{
    protected $terms = array();

    /**
     *
     *
     */
    public function indexAction()
    {
        $viewVars = array();

        $form = $this->getSearchFormCopy();
        $viewVars[] = 'form';

        $relation = $this->params()->fromRoute('related');
        $postSlug = $this->params()->fromRoute('post_slug');

        if (null !== $relation && null !== $postSlug) {
            $method = (('children' === $relation)? 'getChildrenDoggies' : 'getParentDoggy');
            $posts = $this->$method($postSlug);
        } else {
            $posts = $this->getDoggies();
        }
        $viewVars[] = 'posts';

        if (empty($posts)) {
            $messages = array('warning' => 'Woff, wff!!! There are no posts in your language, be the first one to post.');
            $viewVars[] = 'messages';
        }

        return new \Zend\View\Model\ViewModel(compact($viewVars));
    }

    public function getSearchFormCopy()
    {
        return new \Dogtore\Form\Search('search-posts');
    }

    /**
     *
     * Ajax search
     */
    protected function ajaxsearchAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        if (!$request->isPost()) {
            return $response;
        }

        $postData = $request->getPost();
        $searchTerm = $postData['search_term'];
        
        //Category not used currently
        $category = $postData['category'];

        $posts = $this->em()->getRepository('Blog\Entity\Post')->findByTitle($searchTerm);

        $responseContent = ((empty($posts))? array('response' => false) : array('response' => true, 'posts' => $posts));
        $response->setContent(\Zend\Json\Json::encode($responseContent));
        
        return $response;
    }

    /**
     *
     * normal search
     */
    public function searchAction()
    { 
        $form = $this->getSearchFormCopy();
        $viewVars[] = 'form';

        if ('GET' === $this->request->getMethod()) {
            $form->setData($this->request->getQuery());
            if ($form->isValid()) {
                $formValidData = $form->getData();
                $posts = $this->getDoggies((($form->hasCategory())? $formValidData['c'] : null), (($form->hasTerms())? $formValidData['t'] : null)); 
            }
        }
        if (!isset($posts)) {
            $posts = $this->getDoggies(); 
        }
        $viewVars[] = 'posts';

        if (empty($posts)) {
            $messages = array('warning' => 'Woff, wff!!! No posts match your search, care to write a little one? Some dog may be thankful');
            $viewVars[] = 'messages';
        }

        $terms = $this->getTerms();
        $viewVars[] = 'terms';
        
        return new \Zend\View\Model\ViewModel(compact($viewVars));
    }

    protected function getDoggies($categorySlug = null, $termPhrase = null)
    {
        $req = new \Dogtore\Req\Doggy();
        $conditions = [];

        $conditions[] = array('post_locale' => array('=' => $this->locale()));

        if (null !== $termPhrase) {
            $phraseParts = explode(' ', $termPhrase);
            $this->setTerms($phraseParts);
            $conditions['or'] = array();
            foreach ($phraseParts as $term) {
                $conditions['or'][] = array('post_title' => array('like' => '%'. $term . '%'));
                $conditions['or'][] = array('post_content' => array('like' => '%'. $term . '%'));
            }
        }
        if (null !== $categorySlug) {
            $conditions[] = array('lvl1_category_slug' => array('=' => $categorySlug));
        }

        return $req->getPostsWithLevel1Category(((empty($conditions))? [] : ['and' => $conditions]));
    }

    protected function getParentDoggy($postSlug)
    {
        $req = new \Dogtore\Req\Doggy();
        $conditions = [];

        $conditions[] = array('post_locale' => array('=' => $this->locale()));
        $conditions[] = array('child_post_slug' => array('=' => $postSlug));

        return $req->getPostsWithLevel1Category(((empty($conditions))? [] : ['and' => $conditions]));
    }

    protected function getChildrenDoggies($postSlug)
    {
        $req = new \Dogtore\Req\Doggy();
        $conditions = [];

        $conditions[] = array('post_locale' => array('=' => $this->locale()));
        $conditions[] = array('parent_post_slug' => array('=' => $postSlug));

        return $req->getPostsWithLevel1Category(((empty($conditions))? [] : ['and' => $conditions]));
    }

    public function setTerms(array $terms)
    {
        $this->terms = $terms;
        return $this;
    }

    public function getTerms()
    {
        return $this->terms;
    }
}
