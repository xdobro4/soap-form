<?php


namespace SoapBundle\Services\User;


use Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator\RecursiveValidator;

trait TValidate
{
	/** @var RecursiveValidator */
	private $validator;

	private function validate($entity)
	{
		$err = $this->validator->validate($entity);

		if (count($err) > 0) {
			throw new InvalidArgumentException((string) $err);
		}
	}
}
