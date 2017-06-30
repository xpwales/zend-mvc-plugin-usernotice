<?php

namespace Xpwales\Zend\Mvc\Plugin\UserNotice;

use Xpwales\Zend\Mvc\Plugin\UserNotice\Item\NoticeItem;
use Xpwales\Zend\Mvc\Plugin\UserNotice\Item\NoticeItemFactoryInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;
use Zend\Session\ManagerInterface as Manager;
use Zend\Stdlib\PriorityQueue;

class UserNotice extends AbstractPlugin implements \IteratorAggregate, \Countable, NoticeItemFactoryInterface
{
	/**
	 * @var Container
	 */
	protected $container = null;

	/**
	 * @var Manager
	 */
	protected $session = null;

	//
	// Notice items
	//

	/**
	 * @inheritdoc
	 *
	 * @see \IteratorAggregate
	 */
	public function getIterator()
	{
		$noticeItems = $this->getNoticeItems();

		return $noticeItems;
	}

	/**
	 * @inheritdoc
	 *
	 * @see \Countable
	 */
	public function count()
	{
		$noticeItems = $this->getNoticeItems();

		return $noticeItems->count();
	}

	/**
	 * @param NoticeItem $noticeItem
	 * @param int        $priority (optional)
	 *
	 * @return $this
	 */
	public function addNoticeItem(NoticeItem $noticeItem, $priority=1)
	{
		$noticeItems = $this->getNoticeItems();

		$noticeItems->insert($noticeItem, (int) $priority);

		return $this;
	}

	/**
	 * @return PriorityQueue
	 */
	private function getNoticeItems()
	{
		$container = $this->getContainer();

		if (isset($container->{'noticeItems'}) === false) {
			$container->{'noticeItems'} = new PriorityQueue();
		}

		return $container->{'noticeItems'};
	}

	/**
	 * @return $this
	 */
	public function clearNoticeItems()
	{
		$container = $this->getContainer();

		if (isset($container->{'noticeItems'}) === true) {
			unset($container->{'noticeItems'});
		}

		return $this;
	}

	//
	// Item factory
	//

	/**
	 * @inheritdoc
	 *
	 * @see NoticeItemFactoryInterface
	 */
	public function createNoticeItem()
	{
		$noticeItem = new NoticeItem();

		return $noticeItem;
	}

	//
	// Session manager
	//

	/**
	 * Set the session manager
	 *
	 * @param  Manager $manager
	 *
	 * @return $this
	 */
	public function setSessionManager(Manager $manager)
	{
		$this->session = $manager;

		return $this;
	}

	/**
	 * Retrieve the session manager
	 *
	 * If none composed, lazy-loads a SessionManager instance
	 *
	 * @return Manager
	 */
	public function getSessionManager()
	{
		if (!$this->session instanceof Manager) {
			$this->setSessionManager(Container::getDefaultManager());
		}

		return $this->session;
	}

	//
	// Container
	//

	/**
	 * Get session container for notice items
	 *
	 * @return Container
	 */
	public function getContainer()
	{
		if ($this->container instanceof Container) {
			return $this->container;
		}

		$manager = $this->getSessionManager();
		$this->container = new Container('XpwalesUserNotice', $manager);

		$this->container->setExpirationHops(1);

		return $this->container;
	}

}//end class