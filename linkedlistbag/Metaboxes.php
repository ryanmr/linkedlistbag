<?php

namespace LinkedList;

class Metaboxes {

	use \LinkedList\Singleton;

	private $metaboxes = array();

	public function initialize() {

		// add_action('admin_init', array($this, 'register'));
		$this->register();

	}

	public function register() {
		$this->metaboxes['linked-list'] = new \LinkedList\Metaboxes\LinkedListMetabox();

	}

}