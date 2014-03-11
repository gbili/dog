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

    public function installAction()
    {
        if (!$this->identity()->isAdmin()) {
            throw new \Exception('You must be admin to install');
        }
        $insertCategoriesSql = "INSERT INTO `categories` (`id`, `parent_id`, `locale`, `name`, `slug`, `content`, `lft`, `lvl`, `rgt`, `root`, `user_id`, `uniqueslug`) VALUES (2,NULL,'en','Uncategorized','uncategorized','',1,0,8,2,1,'uncategorized'),(3,NULL,'es','Sin CategorÃ­a','sin-categoria','',1,0,8,3,1,'sin-categoria'),(4,NULL,'fr','Non CatÃ©gorisÃ©','non-categorise','',1,0,8,4,1,'non-categorise'),(5,NULL,'it','Senza Categoria','senza-categoria','',1,0,2,5,1,'senza-categoria'),(6,NULL,'de','Keine Kategorie','keine-kategorie','',1,0,2,6,1,'keine-kategorie'),(7,2,'en','Symptom','symptom','Symptoms are what you see.',2,1,3,2,1,'symptom'),(8,2,'en','Cause','cause','Causes are what will enable you to find a solution. If you don\'t know the cause of a symptom, then you have no clue to what should be done to avoid the symptom.',4,1,5,2,1,'cause'),(9,2,'en','Solution','solution','Solutions will prevent the symptom. A solution can be found only if you know the cause of the symptom.',6,1,7,2,1,'solution'),(10,3,'es','SÃ­ntoma','sintoma','Los sÃ­ntomas, son lo que puedes ver.',2,1,3,3,1,'sintoma'),(11,3,'es','Causa','causa','Las causas permiten encontrar la soluciÃ³n. Si no conoces la causa de un sÃ­ntoma, no puedes encontrar una soluciÃ³n duradera.',4,1,5,3,1,'causa'),(12,3,'es','SoluciÃ³n','solucion','Las soluciones evitan los sÃ­ntomas. Solo puedes encontrar la soluciÃ³n si conoces la causa del sintoma.',6,1,7,3,1,'solucion'),(13,4,'fr','SymptÃ´me','symptome','Les symptÃ´mes sont ce que l\'on peut voir.',2,1,3,4,1,'symptome'),(14,4,'fr','Cause','Cause','Les causes vous permettent de trouver une solution. Si vous ne connaÃ®ssez pas la cause du symptÃ´me, alors vous n\'avez aucune piste vers la solution.',4,1,5,4,1,'cause'),(15,4,'fr','Solution','solution','Les solutions prÃ©viennent le symptÃ´me. La solution ne peut Ãªtre trouvÃ©e que si vous connaissez la cause du symptÃ´me.',6,1,7,4,1,'solution');";
        $req = new \Scs\Req\Scs();
        $req->insertUpdateData($insertCategoriesSql);
        $this->messenger()->addMessage('Install succeed', 'success');
        return new \Zend\View\Model\ViewModel(array());
    }
}
