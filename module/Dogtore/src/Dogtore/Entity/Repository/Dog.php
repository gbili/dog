<?php
namespace Dogtore\Entity\Repository;

class Dog extends \Doctrine\ORM\EntityRepository
{
    public function existsUserDogName(\GbiliUserModule\Entity\UserDataInterface $userdata, $dogname)
    {
        $userDogsWithName = $this->findBy(array('userdata' => $userdata, 'name' => $dogname));
        $exists = !empty($userDogsWithName);
        return $exists;
    }
}
