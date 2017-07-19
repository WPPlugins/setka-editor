<?php
namespace Setka\Editor\Service\SetkaAccount;

use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Service\SetkaAPI\Actions;
use Setka\Editor\Admin\Cron;
use Setka\Editor\Admin\Options;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class SignIn {

	/**
	 * This function used for auth via Settings pages (WordPress automatically update and save Token).
	 *
	 * @param $token string New token to use.
	 * @param $update_token bool Should this function insert this token to DB or not. By default token updated in DB
	 * by WordPress (runned from Settings page). But if you are doing auth from script you should pass true (default).
	 *
	 * @return \Setka\Editor\Admin\Service\SetkaAPI\Prototypes\ActionInterface[]
	 */
	public static function sign_in_by_token( $token, $update_token = true ) {

		$responses = self::send_auth_requests($token);

		foreach($responses as $response) {
			/**
			 * @var $response \Setka\Editor\Admin\Service\SetkaAPI\Prototypes\ActionAbstract
			 */
			if($response->getErrors()->hasErrors()) {
				return $responses;
			}
		}
		unset($response);

		// Setup new account settings
		if($update_token) {
			self::setup_token($token);
		}

		self::setup_new_account($responses[Actions\GetCurrentThemeAction::class], $responses[Actions\GetCompanyStatusAction::class]);

		return $responses;
	}

	/**
	 * Send auth requests and return actions with validated responses.
	 *
	 * @param $token string Company token (license key).
	 *
	 * @return array Executed actions
	 */
	public static function send_auth_requests($token) {
		// API (request token details on Setka Server via API)
		$api = new SetkaAPI\API();
		$api->setAuthCredits(new SetkaAPI\AuthCredits($token));

		// Theme files
		$currentTheme = new Actions\GetCurrentThemeAction();
		$api->request($currentTheme);

		// Request for details of subscription
		$companyStatus = new Actions\GetCompanyStatusAction();
		$api->request($companyStatus);

		return array(
			Actions\GetCurrentThemeAction::class  => $currentTheme,
			Actions\GetCompanyStatusAction::class => $companyStatus,
		);
	}

	private static function setup_token( $token ) {
		$option = new Options\Token\Option();
		return $option->updateValue($token);
	}

	private static function setup_new_account( SetkaAPI\Actions\GetCurrentThemeAction $currentTheme, SetkaAPI\Actions\GetCompanyStatusAction $companyStatus ) {

		// Subscription info

		$_option = new Options\SubscriptionPaymentStatus\Option();
		$_option->updateValue( $companyStatus->getResponse()->content->get('payment_status') );

		$_option = new Options\SubscriptionStatus\Option();
		$_option->updateValue( $companyStatus->getResponse()->content->get('status') );

		$activeUntil = new Options\SubscriptionActiveUntil\Option();
		// Try to sync account on expiration date.
		$syncAccountTask = new Cron\Tasks\SyncAccount\SyncAccountTask();
		// Delete all previously events.
		$syncAccountTask->unRegisterHook();
		if( $companyStatus->getResponse()->isOk() ) {
			$activeUntil->updateValue( $companyStatus->getResponse()->content->get('active_until') );

			$datetime = \DateTime::createFromFormat(\DateTime::ISO8601, $activeUntil->getValue());
			if($datetime) {
				// Setup new event.
				$syncAccountTask->setTimestamp($datetime->getTimestamp());
				$syncAccountTask->register();
			}
		} else {
			$activeUntil->delete();
		}

		$_option = new Options\PlanFeatures\PlanFeaturesOption();
		$_option->updateValue($companyStatus->getResponse()->content->get('features'));

		// -------------------------------------------------------------------------------------------------------------

		$_option = new Options\SetkaPostCreated\Option();
		$_option->delete();

		// -------------------------------------------------------------------------------------------------------------

		// Theme info

		foreach( $currentTheme->getResponse()->content->get('theme_files') as $file ) {
			switch( $file['filetype'] ) {
				case 'css':
					$_option = new Options\ThemeResourceCSS\Option();
					$_option->updateValue( $file['url'] );
					break;

				case 'json':
					$_option = new Options\ThemeResourceJS\Option();
					$_option->updateValue( $file['url'] );
					break;
			}
		}
		unset( $file, $_option );

		$_option_css = new Options\EditorCSS\Option();
		$_option_js = new Options\EditorJS\Option();
		if( $currentTheme->getResponse()->isOk() ) {
			foreach( $currentTheme->getResponse()->content->get('content_editor_files') as $file ) {
				if( $file['filetype'] === 'css' ) {
					$_option_css->updateValue( $file['url'] );
				} elseif( $file['filetype'] === 'js' ) {
					$_option_js->updateValue( $file['url'] );
				}
			}
		}
		elseif( $currentTheme->getResponse()->getStatusCode() == Response::HTTP_FORBIDDEN ) {
			$_option_js->delete();
			$_option_css->delete();
		}
		unset( $file, $_option_css, $_option_js );

		$editorVersion = new Options\EditorVersion\Option();
		$editorVersion->updateValue( $currentTheme->getResponse()->content->get('content_editor_version') );
		unset( $editorVersion );

		// -------------------------------------------------------------------------------------------------------------

		// Plugins
		$_option = new Options\ThemePluginsJS\Option();
		if( $currentTheme->getResponse()->content->has('plugins') ) {
			$plugins = $currentTheme->getResponse()->content->get('plugins');
			$_option->updateValue( $plugins[0]['url'] );
		}
		else {
			$_option->delete();
		}
		unset( $_option, $plugins );

		// Report about successfully signed in
		$user_signed_up = new Cron\Tasks\UserSignedUp\Task();
		$user_signed_up->register();
	}
}
