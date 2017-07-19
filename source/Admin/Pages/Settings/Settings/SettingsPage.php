<?php
namespace Setka\Editor\Admin\Pages\Settings\Settings;

use Setka\Editor\Admin\Notices\SettingsSavedSuccessfully\SettingsSavedSuccessfullyNotice;
use Setka\Editor\Admin\Prototypes\Pages;
use Setka\Editor\Plugin;
use Setka\Editor\Admin\Pages\Settings\Loader;
use Setka\Editor\Admin\Options;
use Setka\Editor\Admin\User\Capabilities;
use Symfony\Component\HttpFoundation\Request;

class SettingsPage extends Pages\SubMenuPage {

	use Pages\Traits\FormOptionSaverTrait;

	public function __construct() {
		$this->setParentSlug(Plugin::NAME);
		$this->setPageTitle(_x( 'Settings', 'Settings page title', Plugin::NAME ));
		$this->setMenuTitle($this->getPageTitle());
		$this->setCapability('manage_options');
		$this->setMenuSlug(Plugin::NAME . '-settings');

		$this->setName('settings');
	}

	public function lateConstruct() {
		// Tabs
		$tabs = $this->getParentPage()->buildTabs();
		if($tabs) {
			$this->setTabs($tabs);
			$this->prepareTabs();
		}
		unset($tabs);

		// Registration form
		$form = Loader::$symfony_form_factory->create(SettingsType::class);
		$this->setForm($form);

		$this->handleRequest();

		$attributes = array(
			'page' => $this,
			'form' => $form->createView(),
			'translations' => array(
				'post_types_description' => __('Enable Setka Editor for the following post types. You can also disable Setka Editor for all post types by unchecking all checkboxes below. In this case all previous post types created with Setka Editor continue working and displaying correctly but you will not be able to create a new post with Setka Editor.', Plugin::NAME),
				'roles_description' => __('Enable Setka Editor for the selected User Roles. You can also add or remove this permission by simply adding or removing %1$s capability to any User Role with <a href="https://wordpress.org/plugins/members/">Members</a> plugin.', Plugin::NAME),
				'roles_capability' => Capabilities\UseEditorCapability::NAME,
				'white_label' => __('Show “Created with Setka Editor” credits bellow the content', Plugin::NAME),
			)
		);
		$view = new Pages\Views\TwigPageView();
		$view->setTemplate('admin/settings/settings/page.html.twig');
		$view->setContext($attributes);
		$this->setView($view);
	}

	public function handleRequest() {
		$request = Request::createFromGlobals();
		$this->form->handleRequest($request);
		$this->form->isValid();

		if($this->form->isSubmitted()) {
			if($this->form->isValid()) {
				// Save data
				$data = $this->form->getData();

				// Save post types
				$option = new Options\EditorAccessPostTypes\Option();
				$option->updateValue($data['post_types']);
				unset($option);

				// Save user roles
				$roles = get_editable_roles();
				if(!empty($roles)) {

					// Delete cap from all roles
					foreach($roles as $role_key => $role_value) {
						$role = get_role($role_key);
						$role->remove_cap(Capabilities\UseEditorCapability::NAME);
					}

					// Maybe add our cap
					if(!empty($data['roles']) && is_array($data['roles'])) {
						foreach($roles as $role_key => $role_value) {
							if(array_search($role_key, $data['roles'], true) !== false) {
								$role = get_role($role_key);
								$role->add_cap(Capabilities\UseEditorCapability::NAME);
							}
						}
					}
				}
				unset($roles, $role, $role_key, $role_value);

				// Save White Label
				$whiteLabelOption = new Options\WhiteLabel\WhiteLabelOption();
				if($data['white_label'] == true) {
					$whiteLabelOption->updateValue('1');
				} else {
					$whiteLabelOption->updateValue('0');
				}
				unset($whiteLabelOption);

				// Show success notice
				\Setka\Editor\Admin\Notices\Loader::$notices[] = new SettingsSavedSuccessfullyNotice();
			} else {

			}
		}
		// Show Errors in notice!
	}
}
