<?php

namespace Xpwales\Zend\Mvc\Plugin\UserNotice\Item;

use Zend\Stdlib\PriorityQueue;

class NoticeItem implements \Serializable
{
	const LEVEL_SUCCESS   = 'success';
	const LEVEL_INFO      = 'info';
	const LEVEL_WARNING   = 'warning';
	const LEVEL_EMERGENCY = 'emergency';

	/**
	 * @var string
	 */
	private $level = null;

	/**
	 * @var null|string
	 */
	private $title = null;

	/**
	 * @var PriorityQueue
	 */
	private $messages = null;

	//
	// Level
	//

	/**
	 * @param string $level
	 *
	 * @throws Exception\InvalidArgumentException on empty level value
	 * @throws Exception\InvalidArgumentException on unrecognised level value
	 *
	 * @return $this
	 */
	public function setLevel($level)
	{
		$level = strtolower($level);

		if (empty($level)) {
			$msg = 'Level cannot be set with empty value';
			throw new Exception\InvalidArgumentException($msg);
		}

		switch ($level) {
			case static::LEVEL_SUCCESS:
			case static::LEVEL_INFO:
			case static::LEVEL_WARNING:
			case static::LEVEL_EMERGENCY:
				$this->level = $level;
			break;

			default:
				$msg = sprintf('Cannot set level with "%s"; level unrecognised', $level);
				throw new Exception\InvalidArgumentException($msg);
			break;

		}//end switch

		return $this;
	}

	/**
	 * @throws Exception\RuntimeException on unset level
	 *
	 * @return string
	 */
	public function getLevel()
	{
		if (null === $this->level) {
			$msg = 'Level must be set before access';
			throw new Exception\RuntimeException($msg);
		}

		return $this->level;
	}

	//
	// Title (optional)
	//

	/**
	 * @param string|null $title
	 *
	 * @return $this
	 */
	public function setTitle($title)
	{
		$title       = (string) $title ?: null;
		$this->title = $title;

		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return bool
	 */
	public function hasTitle()
	{
		return null !== $this->title;
	}

	//
	// Messages
	//

	/**
	 * @param string $message
	 * @param int    $priority (optional)
	 *
	 * @throws Exception\InvalidArgumentException on empty message value
	 *
	 * @return $this
	 */
	public function addMessage($message, $priority=1)
	{
		$message = (string) $message;

		if (empty($message) === true) {
			$exMsg = 'Message cannot be set with empty value';
			throw new Exception\InvalidArgumentException($exMsg);
		}

		$messages = $this->getMessageContainer();

		$messages->insert($message, (int) $priority);

		return $this;
	}

	/**
	 * @return array
	 */
	public function getMessages()
	{
		$messages = iterator_to_array( $this->getMessageContainer() );

		return $messages;
	}

	/**
	 * Lazy load container
	 *
	 * @return PriorityQueue
	 */
	private function getMessageContainer()
	{
		if (null === $this->messages) {
			$this->messages = new PriorityQueue();
		}

		return $this->messages;
	}

	//
	// Serialization
	//

	/**
	 * @inheritdoc
	 *
	 * @see \Serializable
	 */
	public function serialize()
	{
		$data             = [];
		$data['level']    = $this->getLevel();
		$data['title']    = $this->getTitle();
		$data['messages'] = $this->getMessages();

		return serialize($data);
	}

	/**
	 * @inheritdoc
	 *
	 * @see \Serializable
	 */
	public function unserialize($serialized)
	{
		$data = unserialize($serialized);

		$this->setLevel($data['level'])
		     ->setTitle($data['title']);

		foreach ($data['messages'] as $msg) {
			$this->addMessage($msg);
		}//end foreach
	}

}//end class