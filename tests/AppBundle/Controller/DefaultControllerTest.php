<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
	public function testIndex()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains('Create user', $crawler->filter('#form_save')->text());

	}

	public function formTestCases()
	{
		return [
			[
				[
					'form[name]' => 'name',
					'form[password]' => 'pass',
					'form[email]' => 'email', // invalid email
					'form[permission]' => 'guest',
				], // form data
				0, // expected count
			],
			[
				[
					'form[name]' => 'name',
					'form[password]' => 'pass',
					'form[email]' => 'email@eail.com',
					'form[permission]' => 'admin',
				], // form data
				1, // expected data
			],
		];
	}

	/**
	 * @dataProvider formTestCases
	 *
	 * @param array $data
	 * @param int $expectedCount
	 */
	public function testForm(array $data, int $expectedCount)
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/');

		// form test
		$button = $crawler->selectButton('form_save');
		$form = $button->form($data);
		$client->submit($form);

		// table test
		$crawler = $client->reload();
		$this->assertCount($expectedCount, $crawler->filter('tr'));
	}
}
