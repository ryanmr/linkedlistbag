<?php

namespace LinkedList\Metaboxes;

class LinkedListMetabox extends AbstractMetabox {

	const SLUG = 'linked-list-metabox';
	const FUTURE_KEY = '_linked-list-future';

	public function __construct() {
		$this->register(self::SLUG);

		add_filter('wp_insert_post_data', array($this, 'save_content'), 11, 2);
		add_filter('wp_insert_post_data', array($this, 'save_future'), 12, 2);
	}

	public function add() {
		add_meta_box($this->get_slug(), 'Linked List', array($this, 'render'));
	}

	public function save($post_id, $post_object, $updated) {

		$this->save_fields($post_id, $post_object, $updated);

	}

	public function save_fields($post_id, $post_object, $updated) {

		if ( $this->verify_nonce() ) return;
		if ( $this->is_save_ineligible($post_id) ) return;

		$source_url = \LinkedList\Meta::SOURCE_URL;
		$via_url = \LinkedList\Meta::VIA_URL;

		$source_url_value = $this->is_post_key($source_url) ? $this->get_post_field($source_url) : '';
		$via_url_value = $this->is_post_key($via_url) ? $this->get_post_field($via_url) : '';


		// allow the saving to be overridden, true will save, false will not save
		$should_save = apply_filters('linked_list_save_fields', true);

		if ($should_save === false) {
			return;
		}

		$this->common_save($post_id, $source_url, $source_url_value);
		$this->common_save($post_id, $via_url, $via_url_value);

	}

	public function clean_future($post_id, $post) {

	}

	public function save_future($data, $post) {

		// only run this filter on xmlrpc requests
		if ( defined('XMLRPC_REQUEST') == false ) {
			return $data;
		}

		// check for link format (e.g. linked list)
		$format = get_post_format($post['ID']);
		$format = $format == '' ? 'standard' : $format;

		if ( $format != 'link' ) {
			// error_log(sprintf(' - "save_future" post format is `%1$s` ' . "\n", $format), 3, "/var/tmp/errors4.log");
			return $data;
		}

		// if flag meta key exists, it has already been done
		$meta = get_post_meta($post['ID'], self::FUTURE_KEY, true);

		if ( $meta == '1' && $post['ID'] != 0 ) {
			// error_log(sprintf(' - "save_future" is marked for future ' . "\n"), 3, "/var/tmp/errors4.log");
			return $data;
		}

		// calculate post time now, and add six hours
        $format = 'Y-m-d H:i:s';
        $original_date = $data['post_date'];
        $original_date_gmt = $data['post_date_gmt'];
        $new_date = date($format, strtotime('+6 hours', strtotime($original_date)));
        $new_date_gmt = date($format, strtotime('+6 hours', strtotime($original_date_gmt)));
        $data['post_date'] = $new_date;
        $data['post_date_gmt'] = $new_date_gmt;

        // schedule it for the future
        $data['post_status'] = 'future';

        // add meta flag
        update_post_meta($post['ID'], self::FUTURE_KEY, '1');

        return $data;
	}

	public function save_content($data, $post) {

		$content = $data['post_content'];

		// short circuit if special ignore tag is included anywhere in content
		if ( stripos($content, "<!--ignore-->") != false ) {
			// error_log(sprintf(' - "save_content" ignore tag found ' . "\n"), 3, "/var/tmp/errors4.log");
			return $data;
		}

		if ( trim($content) == '' ) {
			// error_log(sprintf(' - "save_content" trim caused blank ' . "\n"), 3, "/var/tmp/errors4.log");
			return $data;
		}

		$lines = explode("\n", $content);

		if ( count($lines) == 0 ) {
			// error_log(sprintf(' - "save_content" no lines ' . "\n"), 3, "/var/tmp/errors4.log");
			return $data;
		}

		// anchors and urls directly on the first line are handled
		$regex = '/^(?:<a href="([^"]+)"[^>]*>)|^((?:http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(?:\/\S*)?)/';

		// it has slashes
		$first_line = trim(stripslashes($lines[0]));

		$match = preg_match($regex, $first_line, $matches);

		if ( $match ) {

			$url = ( isset($matches[2]) ? $matches[2] : $matches[1] );
			$url = preg_replace('/&?utm_(.*?)\=[^&]+/im', '', $url);

			if ( !isset($post) || !isset($post['ID']) || $post['ID'] == 0 ) {
				// error_log(sprintf(' - "save_content" no post information available ' . "\n"), 3, "/var/tmp/errors4.log");
				return $data;
			}

			$result = $this->common_save($post['ID'], \LinkedList\Meta::SOURCE_URL, $url);

			// prevent save fields from overwriting a blank on top of just added value
			add_filter('linked_list_save_fields', function($bool){
				return false;
			});

			set_post_format($post['ID'], 'link');

			unset($lines[0]);
			$new_content = join("\n", $lines);
			$data['post_content'] = $new_content;
			$data['post_content_filtered'] = $new_content;
			// error_log(sprintf(' + "save_content" post_content saved, return %s ' . "\n", print_r($data, true)), 3, "/var/tmp/errors4.log");
			return $data;
		}

		// error_log(sprintf(' = "save_content" no matches found, returned ' . "\n"), 3, "/var/tmp/errors4.log");
		return $data;

	}

	public function render() {
		include(LL_CORE_VIEWS . '/metabox-linked-list.php');
	}

}
