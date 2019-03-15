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
    
     * @ORM\Column(type="string", length=8)
     */
    protected $sex;

    public function __construct()
    {
        parent::__construct();
        $this->addRole("ROLE_USER");
       
        // si username et password ne sont pas defini on leur assigne une valeur arbitraire 
        if ($this->username === null)
        {
            $this->username = bin2hex(random_bytes(32));
        }

        if ($this->password === null)
        {
            $this->password = bin2hex(random_bytes(32));
            $this->plainPassword = $this->password;
        }
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