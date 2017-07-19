<?php
namespace Setka\Editor\Admin\Service\SetkaAPI;

class Endpoints {

	const API = 'https://editor.setka.io';
	const API_DEV = 'http://editor-dev.setka.io';

	const CURRENT_THEME  = '/api/v1/wordpress/current_theme.json';
	const COMPANY_STATUS = '/api/v1/wordpress/company_status.json';
	const SAVE_SNIPPET   = '/api/v1/wordpress/snippets.json';
	const SETUP_STATUSES = '/api/v1/wordpress/setup_statuses/update_status.json';
	const SIGN_UP        = '/api/v1/wordpress/signups.json';
	const RESEND_TOKEN_LETTER = '/api/v1/wordpress/signups/resend_token_letter';
}
