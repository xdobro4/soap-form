<?php

namespace AppBundle\Services\User;

use AppBundle\Entity\User;
use AppBundle\Services\IFormService;
use Doctrine\ORM\EntityManager;
use Exception\InvalidStateException;
use SoapBundle\Services\User\UserSoapService;
use Symfony\Component\Form\Form;

class UserFormService implements IFormService
{
	/** @var EntityManager */
	private $userSoapService;

	public function __construct(UserSoapService $userSoapService)
	{
		$this->userSoapService = $userSoapService;
	}

	/**
	 * @param Form $form
	 * @param User $entity
	 *
	 * @return array
	 * @throws InvalidStateException
	 * @throws \SoapFault
	 */
	public function processForm(Form $form, $entity): array
	{
		if (!$form->isSubmitted() || !$form->isValid()) {
			throw new InvalidStateException('invalid form');
		}

		$data = $form->getData();

		return $this->userSoapService->createUser($data['name'], $data['email'], $data['password'], $data['permission']);
	}
}
