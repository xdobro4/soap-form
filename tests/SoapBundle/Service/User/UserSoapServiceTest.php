<?php

namespace Tests\SoapBundle\Services\User;

use Doctrine\ORM\EntityManager;
use SoapBundle\Services\User\UserSoapService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Mockery;
use Mockery\Mock;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class UserSoapServiceTest extends WebTestCase
{
	/** @var Container */
	public static $container;

	public static function setUpBeforeClass()
	{
		//start the symfony kernel
		$kernel = static::createKernel();
		$kernel->boot();

		//get the DI container
		self::$container = $kernel->getContainer();
	}

	public function createUserUsecases(): array
	{
		return [
			[
				[
					'name' => 'name',
					'email' => 'email',
					'password' => 'password',
					'permission' => 'permission',
				], // data
				\SoapFault::class, // throw
				'~email~', // throw
				FALSE, // return
			],
			[
				[
					'name' => '',
					'email' => 'email@fewg.com',
					'password' => 'password',
					'permission' => 'permission',
				], // data
				\SoapFault::class, // throw
				'~name~', // throw
				FALSE, // return
			],
			[
				[
					'name' => 'name',
					'email' => 'email@gmail.com',
					'password' => '',
					'permission' => 'permission',
				], // data
				\SoapFault::class, // throw
				'~password~', // throw
				FALSE, // return
			],
			[
				[
					'name' => 'name',
					'email' => 'email@fewg.com',
					'password' => 'pass',
					'permission' => 'permission',
				], // data
				FALSE, // throw
				'', // throw
				[
					'name' => 'name',
					'email' => 'email@fewg.com',
					'permission' => 'permission',
				], // return
			],
		];
	}

	/**
	 * @dataProvider createUserUsecases
	 *
	 * @param array $data
	 * @param string|bool $throw
	 * @param string $throwRegExp
	 * @param array|bool $return
	 */
	public function testCreateUser(array $data, $throw, string $throwRegExp, $return)
	{
		/** @var EntityManager|Mock $em */
		$em = Mockery::mock(EntityManager::class);
		$em->shouldReceive('persist')
			->once()
			->andReturnSelf();
		$em->shouldReceive('flush')
			->once()
			->andReturnSelf();

		/** @var RecursiveValidator|Mock $validator */
		$validator = Mockery::mock(RecursiveValidator::class);
		$validator->shouldReceive('validate')
			->once()
			->andReturn([]);

		if ($throw) {
			$this->expectException($throw);
			$this->expectExceptionMessageRegExp($throwRegExp);
		}

		/** @var RecursiveValidator $validator */
		$validator = self::$container->get('validator');

		$service = new UserSoapService($em, $validator, 'salt');
		$result = $service->createUser($data['name'], $data['email'], $data['password'], $data['permission']);

		if ($return) {
			$this->assertEquals($result, $return);
		}
	}
}
