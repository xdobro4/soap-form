<?php

namespace SoapBundle\Controller;

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
	 * @throws \UnexpectedValueException
	 * @throws \InvalidArgumentException
	 */
	public function indexAction(Request $request)
	{
		// @todo umi symfony nejak pres config??
		ini_set('soap.wsdl_cache_enabled', 0);

		// replace cos it need url
		$wsdl = $this->getWsdl($request->getUri());
		$server = new \SoapServer($wsdl);

		$server->setObject($this->get('user.soap.service'));

		$response = new Response();
		$response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

		ob_start();
		$server->handle();
		$response->setContent(ob_get_clean());

		return $response;
	}

	private function getWsdl($uri): string
	{
		$content = file_get_contents(__DIR__ . '/user.wsdl');
		$content = str_replace('@soap_url', $uri, $content); // by uri :]
		return 'data://text/plain;base64,' . base64_encode($content);
	}
}
