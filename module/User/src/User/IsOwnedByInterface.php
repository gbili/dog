<?php
namespace User;

interface IsOwnedByInterface
{
    /**
     * @return boolean
     */
    public function isOwnedBy(Entity\User $user);
}
