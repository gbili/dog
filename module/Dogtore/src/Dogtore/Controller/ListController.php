<?php
namespace Dogtore\Controller;

class ListController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     *
     * @var \Doctrine\ORM\EntityMangager
     */
    protected $em;

    /**
     *
     * @return \Doctrine\ORM\EntityMangager
     */
    public function getEntityManager()
    {
        if (null === $this->em) {
           throw new \Dogtore\Exception\GetBeforeSetException('Must be set from factory'); 
        }
        return $this->em;
    }

    /**
     *
     * @param \Doctrine\ORM\EntityMangager $em
     * @return \Malouer\Controller\MeasurementController
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
        return $this;
    }

    /**
     *
     *
     */
    public function indexAction()
    {
        return new \Zend\View\Model\ViewModel(array(
            'doggies' => $this->getEntityManager()->getRepository('Blog\Entity\Post')->findAll()
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
    protected function searchAction()
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

    protected function getDoggies($categorySlug, $term = null)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        // SELECT p FROM Blog\Entity\Post p INNER JOIN p.category c WHERE p.title LIKE ?1 AND c.slug = ?2
        $queryBuilder->select('p')
            ->from('Blog\Entity\Post', 'p')
            ->innerJoin('p.category', 'c')
            ->where('c.slug = ?1')
            ->setParameter(1, $categorySlug); 
        if (null !== $term) {
            $queryBuilder->andWhere('p.title LIKE ?2')
                ->setParameter(2, $term); 
        }
        return $queryBuilder->getQuery()->getResult();
    }
}
