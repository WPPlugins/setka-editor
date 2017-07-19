<?php
namespace Setka\Editor\Admin\Prototypes\Options;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Option interface (<code>update_option()</code>, <code>delete_option()</code>)
 * represents single option with setting. Each object have default preset of:
 *   * Name
 *   * Group (for WordPress settings screens)
 *   * Constraint (validation-sanitization rules)
 *   * Autoloaded flag
 *
 * You can change all of this properties dynamically. For example, if you are using single setting
 * on multiple admin settings screens you can change the option group by calling <code>setGroup()</code>.
 *
 * You can store custom values in this objects before inserting it into database. This is handy to store
 * some value inside of object, work with it, validate it and push it to the DB.
 */
interface OptionInterface extends NodeInterface {

	public function getGroup();
	public function setGroup( $group );

	public function getValidator();
	public function setValidator( ValidatorInterface $validator );

	public function isAutoload();
	public function setAutoload( $autoload );
	public function enableAutoload();
	public function disableAutoload();

	public function delete();
	public function flush();
	public function updateValue( $value, $autoload = null );

	public function register();
}
