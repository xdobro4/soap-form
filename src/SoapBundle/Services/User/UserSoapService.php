<?php

namespace SoapBundle\Services\User;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserSoapService
{
	use TValidate;

	/** @var EntityManager */
	private $em;

	/** @var string */
	private $salt;

	public function __construct(EntityManager $em, ValidatorInterface $validator, string $salt)
	{
		$this->em = $em;
		$this->salt = $salt;
		$this->validator = $validator;
	}

	public function createUser(string $name, string $email, string $password, string $permission): array
	{
		$entity = new User;

		// fill entity
		$entity->setName($name);
		$entity->setPassword($password);
		$entity->setEmail($email);
		$entity->setPermission($permission);

		try {
			// validation entity
			$this->validate($entity);

			// create users pass hash
			$hash = password_hash($this->salt . $password, PASSWORD_BCRYPT);
			$entity->setPassword($hash);

			// persistence to DB
			$this->em->persist($entity);
			$this->em->flush();
		} catch (InvalidArgumentException $e) {
			throw new \SoapFault('DATA', $e->getMessage());
		} catch (ORMInvalidArgumentException $e) {
			throw new \SoapFault('ORM', 'save error');
		} catch (OptimisticLockException $e) {
			throw new \SoapFault('ORM', 'save error');
		} catch (DBALException $e) {
			throw new \SoapFault('DBAL', 'save error');
		}

		// return for soap
		return [
			'name' => $name,
			'email' => $email,
			'permission' => $permission,
		];
	}

	public function listUsers(int $offset = NULL, int $limit = NULL): array
	{
		try {
			return $this->em->getRepository('AppBundle:User')->findBy([], ['id' => Criteria::DESC], $limit, $offset);
		} catch (\Exception $e) { // @todo specifikovat presneji
			throw new \SoapFault('ORM', 'read error');
		}
	}
}
