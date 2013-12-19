<?php
namespace User\Controller;

class LoggedInController extends EntityUsingController
{

	/**
	 * @var \User\Entity\User 
	 */
	protected $user;
	
	
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
} 
