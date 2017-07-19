<?php
namespace Setka\Editor\Admin\Prototypes\Cron;

/**
 * Single cron task interface with config (default schedule of execution)
 * and handler triggered by WordPress.
 */
interface TaskInterface {

	public function execute();

	public function register();
	public function unRegister();
	public function unRegisterHook();

	public function getTimestamp();
	public function setTimestamp( $timestamp );
	public function immediately();

	public function isOnce();
	public function setOnce( $once );

	public function getRecurrence();
	public function setRecurrence( $recurrence );

	public function getHook();
	public function setHook( $hook );

	public function getArgs();
	public function setArgs( $args );
}
