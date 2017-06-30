<?php

namespace Xpwales\Zend\Mvc\Plugin\UserNotice\Item;

interface NoticeItemFactoryInterface
{
	/**
	 * @return NoticeItem
	 */
	public function createNoticeItem();

}//end class