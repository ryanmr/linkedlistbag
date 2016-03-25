<?php

namespace LinkedList;

class Feeds {

	use \LinkedList\Singleton;

	public function __construct() {}

	public function initialize() {
		add_filter('the_content', array($this, 'add_permalinks'));
		add_filter('the_excerpt_rss', array($this, 'add_permalinks'));
		add_filter('the_title_rss', array($this, 'add_symbol'));
		add_filter('the_permalink_rss', array($this, 'add_source_url'));
	}

	public function add_symbol($content) {

		if ( !has_post_format('link') && !is_feed() ) return $content;

		$content = " → {$content}";

		return $content;
	}

	public function add_source_url($content) {
		
		if ( !has_post_format('link') ) return $content;

		$source = apply_filters('link_attribution_source', '');

		if ( !empty($source) ) {
			return $source;
		}

		return $content;
	}

	public function add_permalinks($content) {

		if ( !is_feed() || !has_post_format('link') ) return $content;

		$bsq = ' <span class="separator">▪</span> ';

	    $html = array();
	    $source = apply_filters('link_attribution_source', '');
	    $via = apply_filters('link_attribution_via', '');

	    $html[] = sprintf('<span class="permalink"><a href="%1$s">permalink</a></span>', get_permalink());
	    
	    if ( !empty($source) ) {
	      $source_html = '<span class="source"><a href="%1$s" target="_blank">source</a></span>';
	      $source_html = sprintf($source_html, $source);
	      $html[] = "{$source_html}";
	    }

	    if ( !empty($via) ) {
	      $via_html = '<span class="via"><a href="%1$s" target="_blank">via</a></span>';
	      $via_html = sprintf($via_html, $via);
	      $html[] = "{$via_html}";
	    }


	    $output = join($bsq, $html);

	    return "\n\n $content \n\n $output";
	}


}