<?php 
/**
 * Plugin Name: ZeroWP
 * Description: Framework wordpress
 * Plugin URI:  zerowp.ru
 * Author URI:  zerowp.ru/Author/ZeroRobot
 * Author:      ZeroRobot
 * Version:     1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'ZWP_PLUGIN_FILE' ) ) {
    define( 'ZWP_PLUGIN_FILE', __FILE__ );
}

// Версия плагина ZeroWP
define( 'ZWP_VERSION', '1.0.0' );



// Функция активации плагина ZeroWP
function activate_zerowp() {
    ZeroWP_Activator::activate();
}

// Функция деактивации плагина ZeroWP
function deactivate_zerowp() {
    ZeroWP_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_zerowp' );
register_deactivation_hook( __FILE__, 'deactivate_zerowp' );



// Автозагрузчик классов
$zerowp_include_classes = [];
function zerowp_autoload_classes($class) {
    global $zerowp_include_classes;
    if(file_exists(dirname( __FILE__ ) . "/classes/$class.php")) {
        $zerowp_include_classes[] = $class;
        include_once dirname( __FILE__ ) . "/classes/$class.php";
    } 
}

spl_autoload_register('zerowp_autoload_classes');



// Запуск плагина
function run_zerowp() {
    ZeroWP::instance()->run();
}
run_zerowp();




/**
 * Тест
 *

/**
 * Тест Zero_DIContainer
 */
$zdic_test = new Zero_DIContainer();
// $zdic_test->set('Zero_Block');

$zero_block_dic = $zdic_test->get('Zero_Block');

// add_action( 'admin_notices', [ $zero_block_dic, 'show' ] );
