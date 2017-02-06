<?php

namespace SoapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

	/**
	 * @throws \InvalidArgumentException
	 * @throws \UnexpectedValueException
	 *
	 * @throws \LogicException
	 */
	public function indexAction()
	{
		$server = new \SoapServer(__DIR__ . '/user.wsdl');

		$server->setObject($this->get('user.soap.service'));

		$response = new Response();
		$response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

		ob_start();
		$server->handle();
		$response->setContent(ob_get_clean());

		return $response;
	}
}
