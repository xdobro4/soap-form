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

	/** @var bool */
	private $soapUrl;

	public function __construct(UserSoapService $userSoapService, $soapUrl)
	{
		$this->userSoapService = $userSoapService;
		$this->soapUrl = $soapUrl;
	}

	/**
	 * @param Form $form
	 * @param User $entity
	 *
	 * @throws InvalidStateException
	 * @throws \SoapFault
	 */
	public function processForm(Form $form, $entity)
	{
		if (!$form->isSubmitted() || !$form->isValid()) {
			throw new InvalidStateException('invalid form');
		}

		$data = $form->getData();

		if ($this->soapUrl !== NULL) {
			$client = new \SoapClient($this->soapUrl);

			$client->createUser($data['name'], $data['email'], $data['password'], $data['permission']);
		}
		else {
			$this->userSoapService->createUser($data['name'], $data['email'], $data['password'], $data['permission']);
		}
	}
}
