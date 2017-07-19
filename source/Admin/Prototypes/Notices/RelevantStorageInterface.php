<?php
namespace Setka\Editor\Admin\Prototypes\Notices;

interface RelevantStorageInterface {

	public function setStorage( $storage );
	public function getStorage();

	/**
	 * @param NoticeInterface $notice An notice which need to be checked.
	 *
	 * @return bool True if relevant. False if not relevant
	 */
	public function isRelevant( NoticeInterface $notice );
}
