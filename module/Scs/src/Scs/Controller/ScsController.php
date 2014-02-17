<?php
namespace Scs\Controller;

/**
 * scs : Symptom cause solution
 */
class ScsController extends \Zend\Mvc\Controller\AbstractActionController
{
    const MESSAGE_NO_POSTS_IN_LANGUAGE  = 'scs_scs_messages_no_posts_in_language';
    const MESSAGE_NO_RELATED_POSTS      = 'scs_scs_messages_no_related_posts';
    const MESSAGE_NO_POSTS_MATCH_SEARCH = 'scs_scs_messages_no_posts_match_search';

    protected $terms = array();

    //TODO create a plugin that holds the messages, and scs needs
    //only to pass the key and gets the desired message
    protected $messages = array(
        self::MESSAGE_NO_POSTS_IN_LANGUAGE  => 'There are no posts in your language, be the first one to post.',
        self::MESSAGE_NO_RELATED_POSTS      => 'There are no related posts, you can write one, if you like.',
        self::MESSAGE_NO_POSTS_MATCH_SEARCH => 'No posts match your search, care to write one? Someone will be thankful.',
    );

    /**
     *
     *
     */
    public function indexAction()
    {
        $form = $this->getSearchFormCopy();

        $posts = $this->getDoggies();

        if (empty($posts)) {
            $messages = array('warning' => $this->messages[self::MESSAGE_NO_POSTS_IN_LANGUAGE]);
        }

        $viewVars = array('form', 'posts', 'messages');
        return new \Zend\View\Model\ViewModel(compact($viewVars));
    }

    public function relatedAction()
    {
        $viewVars = array();

        $form = $this->getSearchFormCopy();

        $relation = $this->params()->fromRoute('related');
        $postSlug = $this->params()->fromRoute('post_slug');

        $method = (('children' === $relation)? 'getChildrenDoggies' : 'getParentDoggy');
        $posts = $this->$method($postSlug);

        if (empty($posts)) {
            $messages = array('warning' => $this->messages[self::MESSAGE_NO_RELATED_POSTS]);
        }

        $viewVars = array('form', 'posts', 'messages');

        return new \Zend\View\Model\ViewModel(compact($viewVars));
    }

    public function getSearchFormCopy()
    {
        return new \Scs\Form\Search('search-posts');
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

        if (empty($posts)) {
            $messages = array('warning' => $this->messages[self::MESSAGE_NO_POSTS_MATCH_SEARCH]);
        }

        $terms = $this->getTerms();

        $viewVars = array('form', 'terms', 'posts', 'messages');
        
        return new \Zend\View\Model\ViewModel(compact($viewVars));
    }

    protected function getDoggies($categorySlug = null, $termPhrase = null)
    {
        $req = new \Scs\Req\Scs();
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

        $posts = $req->getPostsWithLevel1Category(((empty($conditions))? [] : ['and' => $conditions]));
        return $posts;
    }

    protected function getParentDoggy($postSlug)
    {
        $req = new \Scs\Req\Scs();
        $conditions = [];

        $conditions[] = array('post_locale' => array('=' => $this->locale()));
        $conditions[] = array('child_post_slug' => array('=' => $postSlug));

        return $req->getPostsWithLevel1Category(((empty($conditions))? [] : ['and' => $conditions]));
    }

    protected function getChildrenDoggies($postSlug)
    {
        $req = new \Scs\Req\Scs();
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
