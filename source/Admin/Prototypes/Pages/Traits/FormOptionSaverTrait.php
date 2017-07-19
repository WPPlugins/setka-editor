<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Traits;

use Symfony\Component\Form\FormInterface;

trait FormOptionSaverTrait {

	/**
	 * @var FormInterface
	 */
	protected $form;

	/**
	 * @var object An object (instance) which holds this form data.
	 */
	protected $formEntity;

	/**
	 * @return FormInterface
	 */
	public function getForm() {
		return $this->form;
	}

	/**
	 * @param FormInterface $form
	 */
	public function setForm(FormInterface $form) {
		$this->form = $form;
	}

	/**
	 * @return object
	 */
	public function getFormEntity() {
		return $this->formEntity;
	}

	/**
	 * @param object $formEntity
	 */
	public function setFormEntity($formEntity) {
		$this->formEntity = $formEntity;
	}

	/**
	 * Be sure to call it only from $this->lateConstruct()
	 * to prevent illegal access to the page handling.
	 */
	public function handleRequest() {}
}
