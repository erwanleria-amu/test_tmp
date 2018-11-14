<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 */
class Role
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="role_name", type="string", length=255, unique=true)
     */
    private $roleName;

    /**
     * @var string
     *
     * @ORM\Column(name="role_color", type="string", length=255, unique=true)
     */
    private $roleColor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isAdmin", type="boolean")
     */
    private $isAdmin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isModerator", type="boolean")
     */
    private $isModerator;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set roleName
     *
     * @param string $roleName
     *
     * @return Role
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * Get roleName
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     *
     * @return Role
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set isModerator
     *
     * @param boolean $isModerator
     *
     * @return Role
     */
    public function setIsModerator($isModerator)
    {
        $this->isModerator = $isModerator;

        return $this;
    }

    /**
     * Get isModerator
     *
     * @return boolean
     */
    public function isModerator()
    {
        return $this->isModerator;
    }

    /**
     * Set roleColor
     *
     * @param string $roleColor
     *
     * @return Role
     */
    public function setRoleColor($roleColor)
    {
        $this->roleColor = $roleColor;

        return $this;
    }

    /**
     * Get roleColor
     *
     * @return string
     */
    public function getRoleColor()
    {
        return $this->roleColor;
    }

    /**
     * Get isAdmin
     *
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Get isModerator
     *
     * @return boolean
     */
    public function getIsModerator()
    {
        return $this->isModerator;
    }
}
