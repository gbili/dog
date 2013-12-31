<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="translated_post_datas")
 * @ORM\Entity
 */
class TranslatedPostData
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="PostData", mappedBy="translated")
     */
    private $translations;

    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(PostData $translation)
    {
        $translation->setTranslated($this);
        $this->translations->add($translation);
    }

    public function addTranslations(\Doctrine\Common\Collections\Collection $translations)
    {
        foreach ($translations as $translation) {
            $this->addTranslation($translation);
        }
    }

    public function removeTranslation(Post $translation)
    {
        $translation->setTranslated(null);
        $this->translations->removeElement($translation);
    }

    public function removeTranslations(\Doctrine\Common\Collections\Collection $translations)
    {
        foreach ($translations as $translation) {
            $this->removeTranslation($translation);
        }
    }
}
