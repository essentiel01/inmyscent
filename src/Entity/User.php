<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Sex
     * @ORM\Column(type="string")
     */
    protected $sex;

    public function __construct()
    {
        parent::__construct();
        $this->addRole("ROLE_ADMIN");
    }

    public function setSex(String $sex)
    {
        $this->sex = $sex;

        return $this;
    }

    public function getSex()
    {
        return $this->sex;
    }
}