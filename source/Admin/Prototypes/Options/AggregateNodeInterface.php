<?php
namespace Setka\Editor\Admin\Prototypes\Options;

/**
 * Use it with OptionInterface for making options with nested values.
 */
interface AggregateNodeInterface extends NodeInterface {

	public function getNodes();

	public function setNodes(array $nodes);

	public function addNode(NodeInterface $node);

	public function getNode($name);
}
