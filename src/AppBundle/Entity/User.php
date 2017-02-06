<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
	// @see prop > $permission
	const PERMISSION_GUEST = 'guest';
	const PERMISSION_ADMIN = 'admin';

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 *
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=100)
	 *
	 * @Assert\NotBlank()
	 *
	 * @var string
	 */
	private $name;


	/**
	 * @ORM\Column(type="string", length=100)
	 *
	 * @Assert\NotBlank()
	 *
	 * @var string
	 */
	private $password;


	/**
	 * @ORM\Column(type="string", length=100, unique = true)
	 *
	 * @Assert\Email()
	 *
	 * @var string
	 */
	private $email;


	/**
	 * @ORM\Column(type="string")
	 *
	 * @Assert\NotBlank()
	 *
	 * @var string
	 */
	private $permission;

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword(string $password)
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail(string $email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getPermission(): string
	{
		return $this->permission;
	}

	/**
	 * @param string $permission
	 */
	public function setPermission(string $permission)
	{
		$this->permission = $permission;
	}
}
