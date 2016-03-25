<?php

namespace LinkedList;

class Core {

	use \LinkedList\Singleton;

	public function initialize() {

		// See you in the country.

/*		global $___COUNT;
		$___COUNT = 0;

		add_action('all', function(){
			global $___COUNT;
			$___COUNT++;

			$who = $_SERVER['HTTP_USER_AGENT'];

			if ( stripos($who, "nexus") == false ) {
				$who = "";
			} else {
				$who = "Android";
			}

			error_log(sprintf(' - %1$s %2$s %3$s' . "\n", $___COUNT, current_filter(), $who), 3, "/var/tmp/errors2.log");

		});

		add_filter('content_save_pre', function($content){

			$who = $_SERVER['HTTP_USER_AGENT'];

			if ( stripos($who, "nexus") == false ) {
				$who = "";
			} else {
				$who = "Android";
			}

			error_log(sprintf(' = %3$s %1$s %2$s ' . "\n", "content_save_pre:", $content, $who), 3, "/var/tmp/errors2.log");

			return $content;

		}, 20, 1);*/

	}


}