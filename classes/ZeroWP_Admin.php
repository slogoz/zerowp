<?php

/**
 * @package    ZeroWP
 */
class ZeroWP_Admin {

	private $plugin_name;

	private $version;



	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		wp_enqueue_style( 
			$this->plugin_name,
			plugin_dir_url( ZWP_PLUGIN_FILE ) . "admin/css/{$this->plugin_name}.css",
			array(), $this->version, 'all'
		);

	}

	public function enqueue_scripts() {

		wp_enqueue_script( 
			$this->plugin_name,
			plugin_dir_url( ZWP_PLUGIN_FILE ) . "admin/js/{$this->plugin_name}.js",
			array( 'jquery' ), $this->version, false );

	}

}
