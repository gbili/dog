<?php
namespace User\Controller;

class LoggedInController extends EntityUsingController
{

	/**
	 * @var \User\Entity\User 
	 */
	protected $user;

	/**
	* @var EntityManager
	*/
	protected $locale;
	
	/**
     * @return \User\Entity\User
	 */
	protected function getUser()
	{
		if (null === $this->user) {
			$this->user = $this->identity();
		}
		return $this->user;
	}

	/**
	* Sets the EntityManager
	*
	* @param EntityManager $em
	* @access protected
	* @return PostController
	*/
	protected function setLocale($lang)
	{
		$this->locale = $lang;
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
	protected function getLocale()
	{
		if (null === $this->locale) {
			$this->setLocale($this->getServiceLocator()->get('lang')->getLang());
		}
		return $this->locale;
	}
} 
