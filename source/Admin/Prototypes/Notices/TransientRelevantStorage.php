<?php
namespace Setka\Editor\Admin\Prototypes\Notices;

class TransientRelevantStorage implements RelevantStorageInterface {

	/**
	 * @var \Setka\Editor\Admin\Prototypes\Transients\TransientInterface
	 */
	protected $storage;

	/**
	 * TransientRelevantStorage constructor.
	 *
	 * @param \Setka\Editor\Admin\Prototypes\Transients\TransientInterface $storage
	 */
	public function __construct( \Setka\Editor\Admin\Prototypes\Transients\TransientInterface $storage ) {
		$this->setStorage( $storage );
	}


	public function setStorage( $storage ) {
		$this->storage = $storage;
	}

	public function getStorage() {
		return $this->storage;
	}

	public function isRelevant( NoticeInterface $notice ) {
		$value = $this->getStorage()->getValue();
		if( $value === '1' || $value === true || $value === 1 ) {
			return true;
		}
		return false;
	}

}
