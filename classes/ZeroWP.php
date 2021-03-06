<?php

class ZeroWP {

	protected $loader;

	protected $plugin_name;

	protected $version;

	protected static $_instance = null;



	public static function instance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function __construct() {
		if ( defined( 'ZWP_VERSION' ) ) {
			$this->version = ZWP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'zerowp';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		$this->loader = new ZeroWP_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new ZeroWP_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new ZeroWP_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	private function define_public_hooks() {

		$plugin_public = new ZeroWP_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
