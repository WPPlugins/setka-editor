<?php
namespace Setka\Editor\Admin\Service;

/**
 * Class FunctionProxy
 * This class acts as proxy for WordPress functions (or any function) accessible through a global twig object.
 *
 * This class inspired by Blogwerk Theme created by Tom Forrer. And this class not the similar as the original
 * but do almost the same stuff.
 */
class FunctionsProxy {

	/**
	 * Call a non-existent method on the instance of this class:
	 * act as a proxy to the function residing in the global namespace.
	 *
	 * @param string $function the function name
	 * @param mixed $arguments function arguments
	 * @return mixed|string if the function outputs something, return empty string, otherwise return the function result
	 *
	 * @throws \Exception if requested function not exist.
	 */
	public function __call($function, $arguments) {
		if(!function_exists($function)) {
			throw new \Exception('call to unexisting function ' . $function);
		}

		// start output buffering
		ob_start();
		$result = call_user_func_array($function, $arguments);

		// check if the function output something
		if (!ob_get_length()) {
			ob_end_clean();
		} else {
			ob_end_flush();

			// return empty string to be evaluated by the twig expression
			$result = '';
		}
		return $result;
	}
}
