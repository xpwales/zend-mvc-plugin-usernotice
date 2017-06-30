<?php

namespace Xpwales\Zend\Mvc\Plugin\UserNotice\Item;

class NoticeItem
{
	const TYPE_INFO    = 'info';
	const TYPE_WARNING = 'warning';
	const TYPE_ERROR   = 'error';

	/**
	 * @var string
	 */
	private $type = null;

	/**
	 * @var null|string
	 */
	private $title = null;

	/**
	 * @var array
	 */
	private $messages = [];

}//end class