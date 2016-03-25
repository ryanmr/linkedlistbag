<?php

namespace LinkedList;

class Meta {

	use \LinkedList\Singleton;

	const SOURCE_URL = 'linked-list-source-url';
	const VIA_URL = 'linked-list-via-url';

	public function initialize() {

		add_action('init', array($this, 'add_filters'));

	}

	public function add_filters() {
		add_filter('link_attribution_source', array($this, 'filter_source'));
		add_filter('link_attribution_via', array($this, 'filter_via'));
	}

	public function filter_source($blank) {
		return $this->get_source_url();
	}

	public function filter_via($blank) {
		return $this->get_via_url();
	}

	public function get_source_url($post_id = null) {
		return $this->get_meta(self::SOURCE_URL);
	}

	public function get_via_url($post_id = null) {
		return $this->get_meta(self::VIA_URL);
	}

	private function get_meta($key, $post_id = null) {

		if ( $post_id == null && get_the_ID() != false ) {
			$post_id = get_the_ID();
		} 

		$value = get_post_meta($post_id, $key, true);

		if ( empty($value) ) {
			$value = '';
		}

		return $value;

	}

}