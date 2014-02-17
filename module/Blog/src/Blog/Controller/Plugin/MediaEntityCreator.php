<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog\Controller\Plugin;

/**
 *
 */
class MediaEntityCreator extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    /**
     * Create medias from array of Blog\Entity\File 
     *
     * @param array $files instances of Blog\Entity\File 
     */
    public function __invoke(array $files = array())
    {
        $controller = $this->getController();
        $objectManager = $controller->em();
        $config        = $controller->getServiceLocator()->get('Config');
        $publicDir     = $config['blog_constants']['IMAGES_SRC_DIRPATH'];
        $locale        = $controller->locale();

        $createdMedias = array();
        foreach ($files as $file) {
            $media = new \Blog\Entity\Media();
            $basename = $file->getBasename();
            $media->setSlug($basename);
            $media->setAlt($basename);
            $media->setFile($file);
            $media->setPublicdir($publicDir);
            $media->setLocale($locale);
            $media->setDate(new \DateTime());
            $media->setUser($controller->identity());

            $objectManager->persist($media);
            $objectManager->flush();

            $createdMedias[] = $media;
        }
        return $createdMedias;
    }
}
