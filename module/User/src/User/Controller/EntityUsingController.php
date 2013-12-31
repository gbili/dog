<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class EntityUsingController extends AbstractActionController
{
    protected $locale = null;

	/**
	* @var EntityManager
	*/
	protected $entityManager;
	
	/**
	* Sets the EntityManager
	*
	* @param EntityManager $em
	* @access protected
	* @return PostController
	*/
	protected function setEntityManager(\Doctrine\ORM\EntityManager $em)
	{
		$this->entityManager = $em;
		return $this;
	}
	
	/**
	* Returns the EntityManager
	*
	* Fetches the EntityManager from ServiceLocator if it has not been initiated
	* and then returns it
	*
	* @access protected
	* @return EntityManager
	*/
	protected function getEntityManager()
	{
		if (null === $this->entityManager) {
			$this->setEntityManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
		}
		return $this->entityManager;
	}

	/**
	* Returns the EntityManager
	*
	* Fetches the EntityManager from ServiceLocator if it has not been initiated
	* and then returns it
	*
	* @access protected
	* @return EntityManager
	*/
	protected function getLocale()
	{
		if (null === $this->locale) {
			$this->setLocale($this->getServiceLocator()->get('lang')->getLang());
		}
		return $this->locale;
	}

    protected function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }
} 
