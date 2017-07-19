<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits\Aggregate;

use Setka\Editor\Admin\Prototypes\Options\NodeInterface;

trait NodesTrait {

	/**
	 * @var \Setka\Editor\Admin\Prototypes\Options\ValueNodeInterface[]|\Setka\Editor\Admin\Prototypes\Options\AggregateNodeInterface[]
	 */
	protected $nodes;

	/**
	 * @return \Setka\Editor\Admin\Prototypes\Options\NodeInterface[]
	 */
	public function getNodes() {
		return $this->nodes;
	}

	public function setNodes( array $nodes ) {
		$this->nodes = array();

		if( !empty( $nodes ) ) {
			foreach( $nodes as $node ) {
				$this->addNode( $node );
			}
		}
	}

	public function addNode( NodeInterface $node ) {
		$this->nodes[ $node->getName() ] = $node;
	}

	public function getNode( $name ) {
		if( isset( $this->nodes[$name] ) ) {
			return $this->nodes[$name];
		}
		throw new \Exception( 'This node not exists' );
	}
}
