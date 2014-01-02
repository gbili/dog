<?php
namespace Dogtore\Controller;

class DoggyController extends \User\Controller\LoggedInController
{
    /**
     *
     *
     */
    public function indexAction()
    {
        $category = $this->params('category');
        return new \Zend\View\Model\ViewModel(array(
            'doggies' => $this->getDoggies($category),
        ));
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

        $doggies = $this->getEntityManager()->getRepository('Blog\Entity\Post')->findByTitle($searchTerm);

        $responseContent = ((empty($doggies))? array('response' => false) : array('response' => true, 'doggies' => $doggies));
        $response->setContent(\Zend\Json\Json::encode($responseContent));
        
        return $response;
    }

    /**
     *
     * normal search
     */
    public function searchAction()
    { 
        $category = $this->params('category');
        $terms = $this->params('terms');
        $doggies = $this->getDoggies($category, $terms); 
        return new \Zend\View\Model\ViewModel(array(
            'doggies' => $doggies,
        ));
    }

    /**
     *
     * normal search
     */
    protected function search2Action()
    { 
        $query = $this->request->getQuery()->toArray();
        $isGet = !empty($query);
        if (!$isGet) {
            return new \Zend\View\Model\ViewModel(array(
                'doggies' => $this->getEntityManager()->getRepository('Blog\Entity\Post')->findAll(),
            ));
        }

        $form = new \Dogtore\Form\Search($this->getEntityManager());
        $form->setData($query);
        /*TODO : does not work, says category is empty
         * if (!$form->isValid()) {
            throw new \Exception('Data is not valid ' . print_r($form->getMessages(), true));
        }*/
        $validData = $query;//$form->getData();
        $category = $validData['category'];
        $term = $validData['term'];
        $doggies = $this->getDoggies($category, (('' !== $term)? $term : null)); 
        
        return new \Zend\View\Model\ViewModel(array(
            'doggies' => $doggies,
        ));
    }

    protected function getDoggies($categorySlug = null, $term = null)
    {
        if (null === $categorySlug) {
            return $this->getEntityManager()->getRepository('Blog\Entity\Post')->findAll();
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        // SELECT p FROM Blog\Entity\Post p INNER JOIN p.category c WHERE p.title LIKE ?1 AND c.slug = ?2
        $queryBuilder->select('p')
            ->from('Blog\Entity\Post', 'p')
            ->innerJoin('p.category', 'c')
            ->where('c.slug = ?1')
            ->andWhere('c.locale LIKE ?2')
            ->setParameter(1, $categorySlug) 
            ->setParameter(2, $this->getLocale()); 
        if (null !== $term) {
            $queryBuilder->innerJoin('p.data', 'd')
                ->andWhere('d.title LIKE ?3')
                ->setParameter(3, $term); 
        }
        return $queryBuilder->getQuery()->getResult();
    }
}
