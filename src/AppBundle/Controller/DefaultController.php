<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Services\User\UserFormFactory;
use AppBundle\Services\User\UserFormService;
use Exception\InvalidStateException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	/**
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws \LogicException
	 */
	public function indexAction(Request $request): Response
	{
		/** @var UserFormFactory $formFactory */
		$formFactory = $this->get('user.form.factory');
		$form = $formFactory->create();

		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			/** @var UserFormService $formProcessService */
			$formProcessService = $this->get('user.form.service');
			try {
				$formProcessService->processForm($form, new User);
				$this->addFlash('success', 'saved');

				return $this->redirectToRoute('user_form');
			} catch (InvalidStateException $e) {
				$this->addFlash('danger', $e->getMessage());
			} catch (\SoapFault $e) {
				$this->addFlash('danger', $e->getMessage());
			}
		}

		$soapUrl = $this->getParameter('soap_url');
		if ($soapUrl) {
			try {
				// @todo umi symfony nejak pres config??
				ini_set('soap.wsdl_cache_enabled', 0);

				$c = new \SoapClient($soapUrl);

				/** @noinspection PhpUndefinedMethodInspection */
				$users = $c->listUsers();
				dump('call soap');
			} catch (\SoapFault $e) {
				$this->addFlash('danger', $e->getMessage());
				$users = [];
			}
		}
		else {
			$users = $this->get('user.soap.service')->listUsers();
		}

		return $this->render('default/index.html.twig', array(
			'form' => $form->createView(),
			'users' => $users,
		));
	}


}
