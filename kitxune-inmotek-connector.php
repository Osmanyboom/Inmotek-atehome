<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://kitxune.com
 * @since             1.0.0
 * @package           Kitxune_Inmotek_Connector
 *
 * @wordpress-plugin
 * Plugin Name:       kitxune-inmotek-connector
 * Plugin URI:        https://kitxune.com
 * Description:       Show Inmotek data in your page.
 * Version:           1.0.0
 * Author:            Kitxune Studio S.L
 * Author URI:        https://kitxune.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kitxune-inmotek-connector
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

add_filter('init', 'my_rewrite_rules');
function my_rewrite_rules() {
	add_rewrite_rule('^comprar/([^/]*)/?', 'index.php?page_id=19563&inm=$matches[1]','top');
}

add_filter('query_vars', 'foo_my_query_vars');
function foo_my_query_vars($vars){
    $vars[] = 'inm';
    return $vars;
}

function disable_canonical_redirect_for_front_page( $redirect ) {
    if ( is_page() && $front_page = get_option( 'page_on_front' ) ) {
        if ( is_page( $front_page ) )
            $redirect = false;
    }

    return $redirect;
}
add_filter( 'redirect_canonical', 'disable_canonical_redirect_for_front_page' );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('KITXUNE_INMOTEK_CONNECTOR_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kitxune-inmotek-connector-activator.php
 */
function activate_kitxune_inmotek_connector()
{
    /* $htaccess = get_home_path() . ".htaccess";

    $lines = array();
    $lines[] = "Options +FollowSymLinks";
    $lines[] = "RewriteEngine On"
    $lines[] =

    insert_with_markers($htaccess, "Kitxune Inmotek Connector", $lines); */
    require_once plugin_dir_path(__FILE__) . 'includes/class-kitxune-inmotek-connector-activator.php';
    Kitxune_Inmotek_Connector_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kitxune-inmotek-connector-deactivator.php
 */
function deactivate_kitxune_inmotek_connector()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-kitxune-inmotek-connector-deactivator.php';
    Kitxune_Inmotek_Connector_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_kitxune_inmotek_connector');
register_deactivation_hook(__FILE__, 'deactivate_kitxune_inmotek_connector');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-kitxune-inmotek-connector.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kitxune_inmotek_connector()
{

    $plugin = new Kitxune_Inmotek_Connector();
    $plugin->run();
}

/* function hook_kitxune_inmotek_connector_ajax()
{
wp_enqueue_script('vue-app-checker', plugin_dir_url(__FILE__) . 'public/vue/js/app.js');
wp_enqueue_script('vue-chunk-vendors-checker', plugin_dir_url(__FILE__) . 'public/vue/js/chunk-vendors.js');
wp_localize_script(
'vue-app-checker',
'calls_vue_app_checker',
array(
'ajaxurl' => admin_url('admin-ajax.php'),
'fail_message' => __('La conexión con el servidor ha fallado', 'vue-app-checker'),
'success_message' => __('Todo correcto. ', 'vue-app-checker')
)
);
wp_register_style('app-style', plugin_dir_url(__FILE__) . 'public/vue/css/app.css');
wp_register_style('chunk-vendors-style', plugin_dir_url(__FILE__) . 'public/vue/css/chuck-vendors.css');
} */

/* function check_kitxune_inmotek_connector_ajax()
{
echo $_POST['atehome_data'];
} */

// Get path to main .htaccess for WordPress

add_action('wp_enqueue_scripts', 'loadStyles');
function loadStyles()
{
    wp_register_style('app-style', plugin_dir_url(__FILE__) . 'public/vue/css/app.css');
    wp_register_style('chunk-vendors-style', plugin_dir_url(__FILE__) . 'public/vue/css/chunk-vendors.css');
}

function sendInterestContactMail($parameters) {
    $to = "inmobiliaria@atehome.es";
    $subject = "INMOBILIARIA: Interés en propiedad " . $parameters[property_id];
    $message = 
        "Contacto desde web<br/>Para propiedad <a href='https://atehome.testing-robler.com" . 
        $parameters[property_id]  . 
        "'>" . 
        $parameters[property_id] . 
        "</a><br/><strong>Nombre y Apellidos:</strong> " . 
        $parameters[contact_name] . " " . 
        $parameters[contact_surname] . 
        "<br/><strong>Email:</strong> " . 
        $parameters[contact_email] . 
        "<br/><strong>Teléfono:</strong> " . 
        $parameters[contact_phone] . 
        "<br/><strong>Notas:</strong> " . 
        $parameters[contact_notes] .
        "<br/>";
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
    wp_mail($to, $subject, $message, $headers );
}

function ktx_api_check_call($req)
{
    $extension = $req['extension'];
    $ext_real = "";
    $url_control = false;
    switch ($extension) {
        case 0:
            $ext_real = 'property_search';
            break;
        case 1:
            $ext_real = 'property';
            break;
        case 2:
            $ext_real = 'num_property_search';
            break;
        case 3:
            $ext_real = 'outstanding';
            break;
        case 4:
            $ext_real = 'locations';
            break;
        case 5:
            $ext_real = 'zones';
            break;
        case 6:
            $ext_real = 'subzones';
            break;
        case 7:
            $ext_real = 'types';
            break;
        case 8:
            $ext_real = 'promotions';
            break;
        case 9:
            $ext_real = 'contact';
            break;
        case 10:
            $url_control = true;
            sendInterestContactMail($req['parameters']);
            break;
		case 11:
            $ext_real = 'property_search';
            break;
		case 12:
			$ext_real = 'provinces';
			break;
		case 13:
			$ext_real = 'locations';
			break;
		case 14:
			$ext_real = 'zones';
			break;
		case 15:
			$ext_real = 'subzones';
			break;
        case 'url_control':
            $url_control = true;
            break;

    }
    if (!$url_control) {
        $curl = curl_init();
        // Extension type to change Inmotek API calls
        $option_string = "";
        $counter = 0;
        foreach ($req['parameters'] as $key => $value) {
            if ($counter > 0) {
                $option_string = $option_string . "&";
            }
            ;
            $option_string = $option_string . $key . "=" . $value;
            $counter = $counter + 1;
        }
        ;

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.inmotek.net/v3/' . $ext_real,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $option_string,
            CURLOPT_HTTPHEADER => array(
                'token: 779d8512b4a4271d83b3ff53a5ca0fff',
                'agency-id: 1888',
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response_object = json_decode($response, true, JSON_UNESCAPED_SLASHES);
        $response_object['options'] = $option_string;
        return $response_object;
    } else {

        return $response_object;
    }

}

add_action('rest_api_init', 'ktx_api_init');

function ktx_api_init()
{
    // route url: domain.com/wp-json/$namespace/$route
    $namespace = 'ktx-api/v1';
    $route = 'inmotek';

    register_rest_route($namespace, $route, array(
        'methods' => "POST",
        'callback' => 'ktx_api_check_call',
    ));
    /* add_rewrite_rule( '^comprar/inmueble/(\d+)/?$', 'index.php?inmueble=$matches[1]', 'top' ); */

}

add_shortcode('render-properties', 'renderProperties');

function renderProperties()
{
    wp_enqueue_style('app-style', plugin_dir_url(__FILE__) . 'public/vue/css/app.css');
    wp_enqueue_style('chunk-vendors-style', plugin_dir_url(__FILE__) . 'public/vue/css/chunk-vendors.css');
    wp_enqueue_script('vue-app-checker', plugin_dir_url(__FILE__) . 'public/vue/js/app.js');
    wp_enqueue_script('vue-chunk-vendors-checker', plugin_dir_url(__FILE__) . 'public/vue/js/chunk-vendors.js');

    $str = "<div id='app'>"
        . "</div>";
    return $str;
}

/* add_action( 'admin_enqueue_scripts', 'hook_kitxune_inmotek_connector_ajax' );
add_action( 'wp_ajax_nopriv_check_kitxune_inmotek_connector_ajax', 'check_kitxune_inmotek_connector_ajax' );
add_action( 'wp_ajax_check_kitxune_inmotek_connector_ajax', 'check_kitxune_inmotek_connector_ajax' ); */

run_kitxune_inmotek_connector();
