<?php
namespace Blog\Entity\Repository;

class Media extends \Doctrine\ORM\EntityRepository
{
    public function getDefaultMedia($entity=null)
    {
        if ($entity instanceof \Dogtore\Entity\Dog) {
            $slug = 'default-thumbnail.jpg';
        } else if ($entity instanceof \GbiliUserModule\Entity\Profile) {
            $slug = 'profile-thumbnail.jpg';
        } else if ($entity instanceof \Blog\Entity\Post) {
            $slug = 'default-thumbnail.jpg';
        } else {
            $slug = 'default-thumbnail.jpg';
        }

        $media = $this->findBySlug($slug);
        if (!$media) {
            throw new \Exception('Missing default media for entity ' . get_class($entity) . '. Slug is ' . $slug);
        }

        return current($media);
    }

    public function getDefaultFile($entity=null)
    {
        $media = $this->getDefaultMedia($entity);
        return $media->getFile();
    }
}
