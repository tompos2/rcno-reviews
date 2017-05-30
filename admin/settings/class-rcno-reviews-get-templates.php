<?php

class Rcno_Reviews_Get_Templates {

	public $layouts = array();

	public function __construct() {

		$this->get_layouts_list();
	}


	/**
	 * Create a list of available layouts locally and globally.
	 */
	public function get_layouts_list() { //@TODO: Find a clean way to invoke this, or move it.

		// First create a list of all globally available layouts.
		$dir_name = WP_PLUGIN_DIR . '/rcno-reviews/public/templates/';

		$this->add_layout_to_list( $dir_name );

		// Then also add layouts available locally from the current theme (if applicable).
		$dir_name = get_stylesheet_directory() . '/rcno-templates/';

		$this->add_layout_to_list( $dir_name );
	}

	public function add_layout_to_list( $dir_name ) {
		if ( is_dir( $dir_name ) ) {
			if ( $handle = opendir( $dir_name ) ) {
				// Walk through all folders in that directory:
				while ( false !== ( $file = readdir( $handle ) ) ) {
					if ( $file !== '.' && $file !== '..' && $file !== '.svn' ) {
						if ( preg_match( "/plugin/", $dir_name ) ) {
							$base_url = plugins_url() . '/' . 'rcno-reviews' . '/public/templates/' . $file;
							$local    = false;
						} else {
							$base_url = get_template_directory_uri() . '/rcno-templates/' . $file;
							$local    = true;
						}

						$this->layouts[ $file ] = array(
							'path'  => $dir_name . $file,
							'url'   => $base_url,
							'local' => $local
						);

						$this->get_layout_meta( $dir_name, $file );
					}
				}
			}
		}
	}

	public function get_layout_meta( $dir_name, $file ) {
		// Param parsing inspired by http://stackoverflow.com/questions/11504541/get-comments-in-a-php-file
		$params   = array();
		$filename = $dir_name . $file . '/review.php';

		$docComments = array_filter(
			token_get_all( file_get_contents( $filename ) ),
			"f_comment"
		);

		$fileDocComment = array_shift( $docComments );

		$regexp = "/.*\:.*\n/";
		preg_match_all( $regexp, $fileDocComment[1], $matches );

		foreach ( $matches[0] as $match ) {
			$param                       = explode( ": ", $match );
			$params[ trim( $param[0] ) ] = trim( $param[1] );
		}

		$this->layouts[ $file ]['description'] = $params['Description'];
		$this->layouts[ $file ]['title']       = $params['Layout Name'];
		$this->layouts[ $file ]['author']      = $params['Author'];
		$this->layouts[ $file ]['author_mail'] = $params['Author Mail'];
		$this->layouts[ $file ]['author_url']  = $params['Author URL'];
		$this->layouts[ $file ]['version']     = $params['Version'];
		if ( file_exists( $dir_name . $file . '/logo.png' ) ) {
			$this->layouts[ $file ]['logo'] = $this->layouts[ $file ]['url'] . '/logo.png';
		}
		if ( file_exists( $dir_name . $file . '/screenshot.png' ) ) {
			$this->layouts[ $file ]['screenshot'] = $this->layouts[ $file ]['url'] . '/screenshot.png';
		}

	}

}

function f_comment( $entry ){
	return $entry[0] === T_COMMENT;
}

$templates = new Rcno_Reviews_Get_Templates();
$layouts = $templates->layouts;

function layout_list() {

	$templates = new Rcno_Reviews_Get_Templates();
	$layouts = $templates->layouts;

	$list = array();

	foreach ($layouts as $layout) {
		$name = strtolower( str_replace(' ', '_', $layout['title']) );
		$list[$name] = array(
			'screenshot' => $layout['screenshot'],
			'title'      => $layout['title'],
			'author'     => $layout['author'],
			'version'    => $layout['version'],
		);
	}

	return $list;
}

