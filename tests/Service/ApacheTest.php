<?php
/**
 * Part of the Joomla Virtualisation Test Suite
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Virtualisation;

use Joomla\Virtualisation\ServerConfig;
use Joomla\Virtualisation\Service\Service;
use Joomla\Virtualisation\ServiceFactory;

class ApacheTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var  Service  The object under test
	 */
	protected $service;

	public function setUp()
	{
		$serverFactory = new ServiceFactory();
		$serverFactory->setConfiguration(new ServerConfig(__DIR__ . '/../fixtures/j25.xml'));
		$this->service = $serverFactory->getWebserver();
	}

	public function testServiceSetupIsGeneratedAsAnArraySuitableForDockerCompose()
	{
		$expected = [
			'apache-5.2' => [
				'build'   => 'tests/tmp/docker/apache-5.2',
				'volumes' => [
					'tests/tmp/docker/apache-5.2/conf:/etc/apache2/sites-enabled',
					'tests/tmp/docker/apache-5.2/html:/var/www/html',
					getcwd() . '/vendor:/usr/local/lib/php/vendor',
				],
				'links'   => [
					'mysql-latest',
				],
			],
		];

		$result = $this->service->getSetup();

		$this->assertEquals($expected, $result);
	}

	public function testFilesystemIsSetUp()
	{
		$this->service->prepare();

		/*
		 * @todo Check files
		 */
	}
}
