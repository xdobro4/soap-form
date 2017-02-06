<?php

namespace AppBundle\Services\User;

use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;

class UserFormFactory
{
	/** @var FormBuilder */
	private $formBuilder;

	public function __construct(FormFactory $formBuilder)
	{
		$this->formBuilder = $formBuilder->createBuilder();
	}

	public function create(): Form
	{
		$form = $this->formBuilder
			->add('name', TextType::class, [
				'required' => TRUE,
			])
			->add('password', PasswordType::class, [
				'required' => TRUE,
			])
			->add('email', EmailType::class, [
				'required' => TRUE,
			])
			->add('permission', ChoiceType::class, [
				'required' => TRUE,
				'choices' => [
					User::PERMISSION_GUEST => User::PERMISSION_GUEST,
					User::PERMISSION_ADMIN => User::PERMISSION_ADMIN,
				],
			])
			->add('save', SubmitType::class, array('label' => 'Create user'))
			->getForm();

		return $form;
	}
}
