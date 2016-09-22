<?php

namespace Hk4w\Member\Entity;

use Docapost\Acl\Entity\RoleAwareInterface;
use Docapost\Base\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @author  Florian POINTILLART
 * @since   2016-01-22
 *
 * @ORM\Entity
 * @ORM\Table(name="member_user")
 */
class User extends AbstractEntity implements RoleAwareInterface
{
    /**
     * User ID
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="`id`", type="integer", nullable=false, options={"unsigned":true})
     */
    protected $id;

    /**
     * Name (Login)
     *
     * @var string
     *
     * @ORM\Column(name="`username`", type="string", length=25, nullable=false, unique=true)
     */
    protected $username = '';

    /**
     * Password
     *
     * @var string
     *
     * @ORM\Column(name="`password`", type="string", length=255)
     */
    protected $password = '';

    /**
     * Password validity
     *
     * @var \DateTime
     *
     * @ORM\Column(name="`password_validity`", type="datetime", nullable=false)
     */
    protected $passwordValidity;

    /**
     * Password Reset Token
     *
     * @var string
     *
     * @ORM\Column(name="`password_reset_token`", type="string", length=255)
     */
    protected $passwordResetToken = '';

    /**
     * Name (Login)
     *
     * @var string
     *
     * @ORM\Column(name="`email`", type="string", length=255, nullable=false, unique=true)
     */
    protected $email;

    /**
     * Firstname
     *
     * @var string
     *
     * @ORM\Column(name="`firstname`", type="string", length=45)
     */
    protected $firstname;

    /**
     * Lastname
     *
     * @var string
     *
     * @ORM\Column(name="`lastname`", type="string", length=45)
     */
    protected $lastname;

    /**
     * Enable
     *
     * @var boolean
     *
     * @ORM\Column(name="`enable`", type="boolean")
     */
    protected $enable = true;

    /**
     * Group
     *
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     */
    protected $group;

    /**
     * Site
     *
     * @ORM\OneToOne(targetEntity="\Docapost\Member\Entity\Site")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
     */
    protected $site;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPasswordValidity()
    {
        return $this->passwordValidity;
    }

    public function setPasswordValidity($passwordValidity)
    {
        $this->passwordValidity = $passwordValidity;
        return $this;
    }

    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken($passwordResetToken)
    {
        $this->passwordResetToken = $passwordResetToken;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEnable()
    {
        return $this->enable;
    }

    public function setEnable($enable)
    {
        $this->enable = $enable;
        return $this;
    }

    public function isEnable()
    {
        return $this->enable ? true : false;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup(Group $group = null)
    {
        $this->group = $group;
        return $this;
    }

    public function getName()
    {
        if ($this->lastname || $this->firstname) {
            return trim($this->firstname . ' ' . $this->lastname);
        } else {
            return $this->username;
        }
    }

    public function getRole()
    {
        return $this->getGroup() ? $this->getGroup()->getRole() : null;
    }

    /**
     * @return \Docapost\Member\Entity\Site
     */
    public function getSite() {
        return $this->site;
    }

    /**
     * @param \Docapost\Member\Entity\Site $site
     * @return \Docapost\Member\Entity\User
     */
    public function setSite($site) {
        $this->site = $site;
        return $this;
    }


    public function __toString()
    {
        return $this->username;
    }
}