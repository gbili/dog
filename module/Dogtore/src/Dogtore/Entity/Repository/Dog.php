<?php
namespace Dogtore\Entity\Repository;

class Dog extends \Doctrine\ORM\EntityRepository
{
    public function existsUserDogName(\User\Entity\User $user, $dogname)
    {
        $userDogsWithName = $this->findBy(array('user' => $user, 'name' => $dogname));
        $exists = !empty($userDogsWithName);
        return $exists;
    }
}
