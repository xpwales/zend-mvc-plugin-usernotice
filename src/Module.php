<?php

namespace Xpwales\Zend\Mvc\Plugin\UserNotice;

use Zend\ServiceManager\Factory\InvokableFactory;

class Module
{
	/**
	 * @return array
	 */
	public function getConfig()
	{
		return [
			'controller_plugins' => [
				'aliases' => [
					'usernotice' => UserNotice::class,
					'userNotice' => UserNotice::class,
					'UserNotice' => UserNotice::class,
					'Xpwales\Zend\Mvc\Controller\Plugin\UserNotice' => UserNotice::class,
				],
				'factories' => [
					UserNotice::class => InvokableFactory::class,
				],
			],
		];
	}

}//end class