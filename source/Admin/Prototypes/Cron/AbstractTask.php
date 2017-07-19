<?php
namespace Setka\Editor\Admin\Prototypes\Cron;

/**
 * Default class for creating cron tasks in plugin.
 */
abstract class AbstractTask implements TaskInterface {

	protected $timestamp = 0;

	protected $once = false;

	protected $recurrence = 'hourly';

	protected $hook;

	protected $args = array();

	abstract public function execute();

	public function register() {
		if($this->isOnce()) {
			wp_schedule_single_event(
				$this->getTimestamp(),
				$this->getHook(),
				$this->getArgs()
			);
		} else {
			wp_schedule_event(
				$this->getTimestamp(),
				$this->getRecurrence(),
				$this->getHook(),
				$this->getArgs()
			);
		}
	}

	/**
	 * Unschedule event by timestamp.
	 */
	public function unRegister() {
		wp_unschedule_event(
			$this->getTimestamp(),
			$this->getHook(),
			$this->getArgs()
		);
	}

	/**
	 * Unschedule all events attached to the specified hook.
	 */
	public function unRegisterHook() {
		return wp_clear_scheduled_hook($this->getHook(), $this->getArgs());
	}

	/**
	 * @return int
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @param int $timestamp
	 */
	public function setTimestamp( $timestamp ) {
		$this->timestamp = (int)$timestamp;
	}

	public function immediately() {
		$this->setTimestamp(time());
	}

	/**
	 * @return boolean
	 */
	public function isOnce() {
		return $this->once;
	}

	/**
	 * @param boolean $once
	 */
	public function setOnce( $once ) {
		$this->once = (bool)$once;
	}

	/**
	 * @return mixed
	 */
	public function getRecurrence() {
		return $this->recurrence;
	}

	/**
	 * @param mixed $recurrence
	 */
	public function setRecurrence( $recurrence ) {
		$this->recurrence = $recurrence;
	}

	/**
	 * @return mixed
	 */
	public function getHook() {
		return $this->hook;
	}

	/**
	 * @param mixed $hook
	 */
	public function setHook( $hook ) {
		$this->hook = $hook;
	}

	/**
	 * @return mixed
	 */
	public function getArgs() {
		return $this->args;
	}

	/**
	 * @param mixed $args
	 */
	public function setArgs( $args ) {
		$this->args = $args;
	}
}
