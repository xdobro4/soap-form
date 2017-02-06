<?php

namespace AppBundle\Services;

use Symfony\Component\Form\Form;

interface IFormService
{
	public function processForm(Form $form, $entity);
}
