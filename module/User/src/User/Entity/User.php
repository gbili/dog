<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Replace User\Entity\Profile with dogtore's
 */
use Dogtore\Entity\Profile;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="\Blog\Entity\Post", mappedBy="user")
     */
    private $posts;

    /**
     * @var string
     *
     * @ORM\Column(name="uniquename", type="string", length=64, nullable=false, unique=true)
     */
    private $uniquename;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=64, nullable=false, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity="Profile", inversedBy="user")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $profile;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false, unique=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=64, nullable=false, unique=false)
     */
    private $role;

    public function __construct()
    {
    }

    public function isAdmin()
    {
        return $this->getRole() === 'admin';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setUniquename($uniquename)
    {
        $this->uniquename = $uniquename;
    }

    public function getUniquename()
    {
        return $this->uniquename;
    }

    public function setProfile(Profile $profile)
    {
        $this->profile = $profile;
    }

    public function getProfile()
    {
        if (null === $this->profile) {
            $this->profile = new Profile(); 
        }
        return $this->profile;
    }

    public function hasProfile()
    {
        return $this->profile instanceof Profile;
    }

    public function setPassword($clearPassword)
    {
        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $this->password = $bcrypt->create($clearPassword);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function isThisPassword($unknownClearPassword)
    {
        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        return $bcrypt->verify($unknownClearPassword, $this->getPassword());
    }

    public function hydrate($data)
    {
        foreach ($data as $method => $param) {
            if ('id' === $method) continue;
            $method = 'set' . ucfirst($method);
            $this->$method($param);
        }
    }

    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add post
     * @param \Blog\Entity\Post $posts
     */
    public function addPost(\Blog\Entity\Post $post)
    {
        $post->setCategory($this);
        $this->posts->add($post);
    }

    /**
     * Add post
     * @param \Doctrine\Common\Collections\Collection $posts
     */
    public function addPosts(\Doctrine\Common\Collections\Collection $posts)
    {
        foreach ($posts as $post) {
            $this->addPost($post);
        }
    }

    /**
     * Remove posts
     * @param \Blog\Entity\Post $posts
     */
    public function removePost(\Blog\Entity\Post $post)
    {
        $this->posts->removeElement($post);
        $post->setCategory(null);
    }

    /**
     * Remove posts
     * @param \Blog\Entity\Post $posts
     */
    public function removePosts(\Doctrine\Common\Collections\Collection $posts)
    {
        foreach ($posts as $post) {
            $this->removePost($post);
        }
    }
}
