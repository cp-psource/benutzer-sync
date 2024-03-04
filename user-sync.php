<?php
/*
Plugin Name: Benutzer Synchronisation
Plugin URI: https://cp-psource.github.io/benutzer-sync/
Description: Benutzersynchronisierung - Mit diesem Plugin kannst Du eine Master-Seite erstellen, von der aus Du eine Benutzerliste mit beliebig vielen anderen Seiten synchronisieren kannst. Sobald diese aktiviert ist, kannst Du <a href="admin.php?page=user-sync">hier starten</a>
Version: 1.2.3
Author: PSOURCE
Author URI: https://github.com/cp-psource
Text Domain: user-sync
Domain Path: /languages


Copyright 2020-2024 PSOURCE (https://github.com/cp-psource)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * @@@@@@@@@@@@@@@@@ PS UPDATER 1.3 @@@@@@@@@@@
 **/
require 'psource/psource-plugin-update/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
 
$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/cp-psource/benutzer-sync',
	__FILE__,
	'benutzer-sync'
);
 
//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');

/**
 * @@@@@@@@@@@@@@@@@ ENDE PS UPDATER 1.3 @@@@@@@@@@@
 **/

/**
* Plugin main class
**/

class User_Sync {

    var $debug_mode;
    var $plugin_dir;
    var $plugin_url;
    var $error;
    var $options;

	/**
	 * PHP 8 constructor
	 **/
	function __construct() {

        load_plugin_textdomain( 'user-sync', false, basename( dirname( __FILE__ ) ) . '/languages' );

        $this->plugin_dir = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );

        // Check for safe mode
        if ( ini_get( 'safe_mode' ) ) {
            //notice for safe mode
            $this->safe_mode_notice = __( "HINWEIS: Dein Server arbeitet im abgesicherten Modus. Wenn Du eine große Anzahl von Benutzern für die Synchronisierung hast und Deine PHP-Einstellungen für 'max_execution_time' von geringem Wert sind, kann dies zu Problemen bei der Verbindung von Unterwebsites und der vollständigen Synchronisierung führen.", 'user-sync' );
        } else {
            // set unlimit time
            set_time_limit(0);
        }

        add_action( 'admin_init', array( &$this, 'admin_init' ) );

        //rewrite old options from old version of plugin
        $this->rewrite_options();
        $this->options = $this->get_options();

        add_action( 'admin_menu', array( &$this, 'admin_page' ) );

        //actions only for master site
        if ( "central" == $this->options['status'] ) {
            add_action( 'profile_update', array( &$this, 'user_change_data' ), 20 );
            add_action( 'user_register', array( &$this, 'user_change_data' ), 20 );
            add_action( 'run_user_change_data_event', array( &$this, 'user_change_data_event' ), 20 );
            add_action( 'delete_user', array( &$this, 'user_delete_data' ), 20 );
            add_action( 'after_password_reset', array( &$this, 'user_password_reset' ), 20, 2 );
            add_action( 'run_user_password_reset_event', array( &$this, 'user_password_reset_event' ), 20, 2 );

        }

        add_action( 'wp_ajax_user_sync_sync_all', array( &$this, 'sync_all_subsite' ) );

        add_action( 'wp_ajax_nopriv_user_sync_api', array( &$this, 'user_sync_ajax_action' ) );
        add_action( 'wp_ajax_user_sync_api', array( &$this, 'user_sync_ajax_action' ) );

        add_action( 'wp_ajax_nopriv_user_sync_settings', array( &$this, 'edit_settings' ) );
        add_action( 'wp_ajax_user_sync_settings', array( &$this, 'edit_settings' ) );

        add_action('admin_enqueue_scripts', array($this,'register_scripts_styles_admin'));

        // adds privacy policy text suggestion
        add_action( 'admin_init', array( $this, 'privacy_policy_suggested_text' ) );
	}

    /**
     * Creating admin menu
     **/
    function admin_page() {
        add_menu_page( __( 'Benutzer Sync', 'user-sync' ), __( 'Benutzer Sync', 'user-sync' ), 'manage_options', 'user-sync', array( &$this, 'plugin_page' ), $this->plugin_url . 'images/icon.png' );
    }

    /**
     * set options
     **/
    function set_options( $section, $values ) {
        $options = get_option( 'user_sync_options' );
        $options[$section] = $values;
        update_option( "user_sync_options", $options );
        $this->options = $this->get_options();
    }

    /**
     * get options
     **/
    function get_options() {
       return $options = get_option( 'user_sync_options' );
    }

    /**
     * Rewrite plugin option from old version
     **/
    function rewrite_options() {

        if ( get_option( "user_sync_status" ) ) {
            $this->set_options( "status", get_option( "user_sync_status" ) );
            delete_option( 'user_sync_status' );
        }

        if ( get_option( "user_sync_key" ) ) {
            $this->set_options( "key", get_option( "user_sync_key" ) );
            delete_option( 'user_sync_key' );
        }

        if ( get_option( "user_sync_sub_urls" ) ) {
            $this->set_options( "sub_urls", get_option( "user_sync_sub_urls" ) );
            delete_option( 'user_sync_sub_urls' );
        }

        if ( get_option( "user_sync_url_c" ) ) {
            $this->set_options( "central_url", get_option( "user_sync_url_c" ) );
            delete_option( 'user_sync_url_c' );
        }

        if ( get_option( "user_sync_deleted_users" ) ) {
            $this->set_options( "deleted_users", get_option( "user_sync_deleted_users" ) );
            delete_option( 'user_sync_deleted_users' );
        }
    }

    /**
     * Write log
     **/
    function write_log( $message ) {
        if ( isset($this->options['debug_mode']) && '1' == $this->options['debug_mode'] ) {
            if ( "central" == $this->options['status'] ) {
                $site_type = "[M] ";
                $file = $this->plugin_dir . "log/errors_m.log";
            } else {
                $site_type = "[S] ";
                $file = $this->plugin_dir . "log/errors_s.log";
            }

            $handle = fopen( $file, 'ab' );
            $data = date( "[Y-m-d H:i:s]" ) . $site_type . $message . "***\r\n";
            fwrite($handle, $data);
            fclose($handle);
        }
    }

    /**
     * Adding css style and script for admin page
     **/
    function register_scripts_styles_admin($hook) {
        if( $hook == 'toplevel_page_user-sync' ) {
            wp_register_style('user-sync-admin', $this->plugin_url.'/css/admin.css');
            wp_enqueue_style('user-sync-admin');

            wp_register_script( 'jquery-tooltips', $this->plugin_url . 'js/jquery.tools.min.js', array('jquery') );
            wp_enqueue_script( 'jquery-tooltips' );

            wp_register_script( 'user-sync', $this->plugin_url . 'js/admin.js', array('jquery') );
            wp_enqueue_script( 'user-sync' );
        }
        global $wp_version;

        if ( $wp_version >= 3.8 ) {
            wp_register_style( 'user-sync-mp6', $this->plugin_url.'/css/mp6.css');
            wp_enqueue_style('user-sync-mp6');
        }
    }

    /**
     * plugin actions
     **/
    function admin_init() {
        if ( isset( $_POST['usync_action'] ) )
            switch( $_POST['usync_action'] ) {
                //Saving choose of site "Central" or "Subsite"
                case "install":
                    //creating additional options for central site
                    if ( "central" == $_POST['user_sync_status'] ) {
                        $this->set_options( 'status', $_POST['user_sync_status'] );
                        $this->set_options( 'key', $this->gener_key() );
                        $this->set_options( 'sub_urls', '' );
                    }

                    //creating additional options for sub site
                    if ( "sub" == $_POST['user_sync_status'] ) {
                        $this->set_options( 'status', $_POST['user_sync_status'] );
                        $this->set_options( 'key', '' );
                        $this->set_options( 'central_url', '' );
                        $this->set_options( 'deleted_users', '' );
                    }

                    //set debug mode
                    if ( isset ( $_POST['debug'] ) && '1' == $_POST['debug'] )
                        $this->set_options( 'debug_mode', '1' );

                    wp_redirect( add_query_arg( array( 'page' => 'user-sync'), 'admin.php' ) );
                    exit;
                break;

                //delete all plugin options
                case "uninstall":
                    $this->uninstall( );
                    wp_redirect( add_query_arg( array( 'page' => 'user-sync', 'updated' => 'true', 'dmsg' => urlencode( __( "Optionen werden gelöscht!", 'user-sync' ) ) ), 'admin.php' ) );
                    exit;
                break;

                //Creating additional options of Subsite and Saving URL of Central site and Security Key
                case "sub_site":
                    if ( ! empty( $_POST['user_sync_url_c']) && ! empty( $_POST['user_sync_key'] ) ) {

                        $this->set_options( 'central_url', $_POST['user_sync_url_c'] );
                        $this->set_options( 'key', $_POST['user_sync_key'] );

                        //connect Subsite to Master site
                        $result = $this->connect_new_subsite( $_POST['user_sync_url_c'], $_POST['user_sync_key'] );

                        if ( "ok" == $result ) {
                            //Call Synchronization when activating new Subsite
                            $result = $this->sync_new_subsite( $_POST['user_sync_url_c'], $_POST['user_sync_key'] );

                            if ( "ok" == $result ) {
                                wp_redirect( add_query_arg( array( 'page' => 'user-sync', 'updated' => 'true', 'dmsg' => urlencode( __( 'Subseite erfolgreich mit Master-Seite verbunden und Synchronisierung abgeschlossen.', 'user-sync' ) ) ), 'admin.php' ) );
                                exit;
                            } else {
                                wp_redirect( add_query_arg( array( 'page' => 'user-sync', 'updated' => 'true', 'dmsg' => urlencode( __( 'Es gab ein Synchronisierungsproblem.', 'user-sync' ) ) ), 'admin.php' ) );
                                exit;
                            }
                        } else {
                            $this->set_options( 'central_url', '' );
                            $this->set_options( 'key', '' );

                            wp_redirect( add_query_arg( array( 'page' => 'user-sync', 'updated' => 'true', 'dmsg' => urlencode( __( 'Es gab ein Verbindungsproblem. Bitte überprüfe die URL und den Schlüssel der Master-Seite.', 'user-sync' ) ) ), 'admin.php' ) );
                            exit;
                        }
                    }
                break;

                //Removing Subsite from Central list
                case "remove_settings":
                    $p = base64_encode( get_option( 'siteurl' ) );

                    $hash = md5( $p . $this->options['key'] );

                    //delete url from Sub list on central site
                    $this->send_request( $this->options['central_url'], "user_sync_action=delete_subsite&hash=". $hash . "&p=" . $p );

                    //reset options of Sub site
                    $this->set_options( 'central_url', '' );
                    $this->set_options( 'key', '' );
                    $this->set_options( 'deleted_users', '' );

                    wp_redirect( add_query_arg( array( 'page' => 'user-sync', 'updated' => 'true', 'dmsg' => urlencode( __( 'Subseite und Einstellungen wurden erfolgreich von der Master Seite entfernt!', 'user-sync' ) ) ), 'admin.php' ) );
                    exit;
                break;

                //Call function for Synchronization of all Subsites
                case "sync_all":

                    $this->sync_all_subsite();

                    wp_redirect( add_query_arg( array( 'page' => 'user-sync', 'updated' => 'true', 'dmsg' => urlencode( __( 'Synchronisation aller Subseiten abgeschlossen.', 'user-sync' ) ) ), 'admin.php' ) );
                    exit;
                break;
            }

    }

    /**
     * Deleting options and Sub url
     **/
    function uninstall() {
        //check type of blog
        if ( "sub" == $this->options['status'] && '' != $this->options['key'] && '' != $this->options['central_url'] ) {
            $p = base64_encode( get_option( 'siteurl' ) );

            $hash = md5( $p . $this->options['key'] );

            //delete url from Sub list on central site
            $this->send_request( $this->options['central_url'], "user_sync_action=delete_subsite&hash=". $hash . "&p=" . $p );
        }

        delete_option( 'user_sync_options' );
    }

    /**
     * Editing settings of Sub site
     **/
    function edit_settings() {
        $sub_urls = $this->options['sub_urls'];

        $array_id = $this->get_index_by_url( base64_decode($_REQUEST['url']), $sub_urls );

        if ( -1 != $array_id ) {
            if ( "0" == $_POST['replace_user'] || "1" == $_POST['replace_user'] )
                $sub_urls[$array_id]['param']['replace_user'] = $_POST['replace_user'];

            if ( "0" == $_POST['overwrite_user'] || "1" == $_POST['overwrite_user'] )
                $sub_urls[$array_id]['param']['overwrite_user'] = $_POST['overwrite_user'];

            $this->set_options( 'sub_urls', $sub_urls );
        }
    }

    /**
     * Generating key of security
     **/
    function gener_key() {
        return wp_generate_password( 15 );
    }

    /**
     * Sending request on URL
     **/
    function send_request( $url, $param, $blocking = true ) {

        $url = $url . "/wp-admin/admin-ajax.php?action=user_sync_api";

        $args =  array(
            'method'    => 'POST',
            'timeout'   => apply_filters('user_sync_timeout', 10),
            'blocking'  => $blocking,
            'sslverify' => false,
            'body'      => $param
        );

        //writing some information in the plugin log file
        $this->write_log( "02 - sending request - url={$url};;" );

        $response = wp_remote_post( $url, $args );

        if( is_wp_error( $response ) ) {
            //writing some information in the plugin log file
            $this->write_log( "04 - sending request: something went wrong={$response->get_error_message()};;" );

//           echo 'Something went wrong!';

            return false;
        } else {
            //writing some information in the plugin log file
            $this->write_log( "03 - sending request - response={$response["body"]};;" );
//            var_dump($response["body"]);
//            exit;
            return $response["body"];
        }

    }

    /**
     * Checking key of security on Subsite
     **/
    function check_key( $url, $key ) {
        //generate rendom string
        $str = substr( md5( uniqid( rand(), true ) ), 0, 10);

        //get hash from Subsite
        $hash = $this->send_request( $url, "str=" . $str );

        //checking hash from Subsite and Central site
        if ( trim($hash) == md5( $str . "" . $key ) ) {
            //writing some information in the plugin log file
            $this->write_log( "05 - checking key true;;" );

            return true;
        } else {
            //writing some information in the plugin log file
            $this->write_log( "06 - checking key false;;" );

            return false;
        }
    }

    /**
     *  Get all Users ID
     **/
    function get_all_users_id() {
        global $wpdb;

        $sql = apply_filters('user_sync_custom_sql', "SELECT ID FROM {$wpdb->users}");
        $rows = $wpdb->get_results( $sql );

        foreach( $rows as $row ) {
            $result[] = $row->ID;
        }
        return $result;
    }

    /**
     *  Get array index by URL
     **/
    function get_index_by_url( $url, $arr ) {
        $i = 0;
        foreach ( $arr as $one ) {
            if ( $url == $one['url'] )
                return $i;

            $i++;
        }

        return -1;
    }


    /**
     * Update other data of user
     **/
    function update_other_user_data( $userdata, $user_id ) {
        Global $wpdb;

        //Update password - becouse if add password "wp_update_user" will be double md5 - wrong password
        $result = $wpdb->query( $wpdb->prepare( "
            UPDATE {$wpdb->users} SET
            user_pass = '%s' WHERE ID = '%d'",
            $userdata['user_pass'], $user_id ) );
		unset($userdata['user_pass']);

        //Update email on blank if email is duplicate
        if ( "temp@temp.temp" == $userdata['user_email'] )
            $result = $wpdb->query( $wpdb->prepare( "
                UPDATE {$wpdb->users} SET
                user_email = '%s' WHERE ID = '%d'",
                "", $user_id ) );
		unset($userdata['user_email']);

        //Update user Role
        $result = $wpdb->query( $wpdb->prepare( "
            UPDATE {$wpdb->usermeta} SET
            meta_value = '%s' WHERE user_id = '%d' AND meta_key = '{$wpdb->base_prefix}capabilities'",
            serialize( $userdata['wp_capabilities'] ), $user_id ) );
		unset($userdata['wp_capabilities']);

        //Update user Level
        $result = $wpdb->query( $wpdb->prepare( "
            UPDATE {$wpdb->usermeta} SET
            meta_value = '%s' WHERE user_id = '%d' AND meta_key = '{$wpdb->base_prefix}user_level'",
            $userdata['wp_user_level'], $user_id ) );
		unset($userdata['wp_user_level']);


        foreach( $userdata as $k => $v ) {
        	update_user_meta($user_id,$k,$v);
        }

    }

    /**
     *  Synchronization user
     **/
    function sync_user( $users_id, $urls, $blocking = true ) {
        $key        = $this->options['key'];
        $urls       = (array) $urls;
        $users_id   = (array) $users_id;

        $errors = array('sites' => array(), 'users' => array());

        foreach ( $urls as $one ) {
            //Checking key of security from Subsite
            if ( $this->check_key( $one['url'], $key ) ) {
                foreach ( $users_id as $user_id ) {
                    //get all information about user
                    $userdata = $this->_get_user_data( $user_id );

                    $p = array ( 'param' => array( 'replace_user' => $one['param']['replace_user'], 'overwrite_user' => $one['param']['overwrite_user'] ),
                                'userdata' => $userdata );

                    $p =  base64_encode( serialize ( $p ) );
                    $hash = md5( $p . $key );

                    //writing some information in the plugin log file
                    $this->write_log( "09 - user sync" );

                    //sent information about user and hash to Subsite
                    $result = $this->send_request( $one['url'], "user_sync_action=sync_user&hash=". $hash . "&p=" . $p, $blocking );

                    if(!$result)
                        $errors['users'][] = $user_id;

                    //Update last Sync date
                    $sub_urls = $this->options['sub_urls'];
                    $array_id = $this->get_index_by_url( $one['url'], $sub_urls );
                    $sub_urls[$array_id]['last_sync'] = date( "m.d.y G:i:s" );
                    $this->set_options( 'sub_urls', $sub_urls );
                }
            }
            else {
                $errors['sites'][] = $one['url'];
            }
        }

        return $errors;
    }

    /**
     * Synchronization when user edit profile
     **/
    function user_change_data( $userID ) {
        $this->write_log( time() . " Daten für Benutzer ändern: {$userID}" );
        wp_schedule_single_event( time(), 'run_user_change_data_event', array( $userID ) );
    }

    function user_change_data_event( $userID ) {
        $this->write_log( time() . " Ausführen eines geplanten Synchronisierungsereignisses für den Benutzer: {$userID}" );
        //Call Synchronization function with ID of changed user and array of all Subsite URLs
        $this->sync_user( $userID, $this->options['sub_urls'], false );
    }

	/**
	 * Password reset
	 *
	 */
	function user_password_reset( $user, $new_password ) {
        $this->write_log( time() . " Passwort für Benutzer geändert: {$user->ID}" );
        wp_schedule_single_event( time(), 'run_user_password_reset_event', array( $user, $new_password ) );

    }

    function user_password_reset_event( $user, $new_password ) {
        $this->write_log( time() . " Running scheduled Password Change event for user: {$user->ID}" );
        $this->sync_user( $user->ID, $this->options['sub_urls'], false );
    }

    /**
     * Synchronization when user deleting
     **/
    function user_delete_data( $userID ) {
        $user_data = $this->_get_user_data( $userID );

        $status = $this->options['status'];

        if ( "sub" == $status ) {
            //Adding login of user to list of deleted users on Sub site
            $deleted_users = (array) $this->options['deleted_users'];

            if ( false === array_search( $user_data['user_login'], $deleted_users ) ) {
                $deleted_users[] = $user_data['user_login'];
                $this->set_options( 'deleted_users', $deleted_users );
            }
        } elseif ( "central" == $status ) {
            $key      = $this->options['key'];
            $sub_urls = $this->options['sub_urls'];


            //Deleting user from all Subsite
            if ( false !== $sub_urls )
                foreach ( $sub_urls as $one ) {
                    //Checking key of security from Subsite
                    if ( $this->check_key( $one['url'], $key ) ) {

                        $p      = array( 'user_login' => $user_data['user_login'], 'overwrite_user' => $one['param']['overwrite_user'] );
                        $p      = base64_encode( serialize ( $p ) );
                        $hash   = md5( $p . $key );


                        $this->send_request( $one['url'], "user_sync_action=sync_user_delete&hash=". $hash . "&p=" . $p );

                        //Update last Sync date
                        $sub_urls = $this->options['sub_urls'];
                        $array_id = $this->get_index_by_url( $one['url'], $sub_urls );
                        $sub_urls[$array_id]['last_sync'] = date( "m.d.y G:i:s" );
                        $this->set_options( 'sub_urls', $sub_urls );
                    }
                }
        }
    }

    /**
     *  Connect new subsite to master site
     **/
    function connect_new_subsite( $central_url, $key ) {
        $replace_user   = 0;
        $overwrite_user = 0;

        //Settings of Sub site
        if ( isset( $_POST['replace_user'] ) && "1" == $_POST['replace_user'] )
            $replace_user = 1;

        if ( isset( $_POST['overwrite_user'] ) && "1" == $_POST['overwrite_user'] )
            $overwrite_user = 1;

        $p = array ( 'url' => get_option( 'siteurl' ), 'replace_user' => $replace_user, 'overwrite_user' => $overwrite_user );

        //writing some information in the plugin log file
        $this->write_log( "01 - new subsite conection - central_url={$central_url};; replace_user={$replace_user};; overwrite_user={$overwrite_user};;" );

        $p =  base64_encode( serialize ( $p ) );

        $hash = md5( $p . $key );

        $result = $this->send_request( $central_url, "user_sync_action=connect_new_subsite&hash=". $hash . "&p=" . $p );

        return $result;
    }

    /**
     *  Synchronization when activating new Subsite
     **/
    function sync_new_subsite( $central_url, $key ) {

        $p = array ( 'url' => get_option( 'siteurl' ) );

        //writing some information in the plugin log file
        $this->write_log( "01_2 - sync users for new subsite" );

        $p =  base64_encode( serialize ( $p ) );

        $hash = md5( $p . $key );

        $result = $this->send_request( $central_url, "user_sync_action=sync_new_subsite&hash=". $hash . "&p=" . $p );

        return $result;
    }

    /**
     *  Synchronization of all Subsites
     **/
    function sync_all_subsite() {
        $users_per_request = apply_filters('user_sync_sync_all_users_per_request', 50);

        //Get all users ID
        $users_id = $this->get_all_users_id();
        $sites = $this->options['sub_urls'];

        $users_end = 1;
        $sites_end = 1;
        $redirect_url = false;

        if(isset($_REQUEST['page'])) {
            $users_start_index = $_REQUEST['page'] < 2 ? 0 : (($users_per_request * $_REQUEST['page']-1)-1);

            $users_id = array_slice($users_id, $users_start_index, $users_per_request);

            $users_end = count($users_id) < $users_per_request ? 1 : 0;
        }
        if(isset($_REQUEST['site'])) {
            $sites = array_slice($sites, ($_REQUEST['site']-1), 1);

            $sites_end = !$sites ? 1 : 0;

            if($sites_end)
                $redirect_url = add_query_arg( array( 'page' => 'user-sync', 'updated' => 'true', 'dmsg' => urlencode( __( 'Synchronisation aller Subseiten abgeschlossen.', 'user-sync' ) ) ), 'admin.php' );
        }

        //Call Synchronization for all Subsites
        $errors = (count($sites) && count($users_id)) ? $this->sync_user( $users_id, $sites ) : false;

        if(defined('DOING_AJAX')) {
            wp_send_json_success(array('errors' => $errors, 'users_end' => $users_end, 'sites_end' => $sites_end, 'redirect_url' => $redirect_url));
        }
    }

    /**
     * Ajax function for changes data on remote site
     **/
    function user_sync_ajax_action() {
        $key = $this->options['key'];
        //writing some information in the plugin log file
        $this->write_log( "0-10 - ajax actions" );

        if ( false === $key ) {
            //writing some information in the plugin log file
            $this->write_log( "1-10 - key not exist" );
            die( "" );
        }


        //action for checking security key on Subsite
        if ( isset ( $_REQUEST['str'] ) && "" != $_REQUEST['str'] ) {
            die( md5( $_REQUEST['str'] . $key ) );
        }

        if ( isset ( $_REQUEST['user_sync_action'] ) && "" == $_REQUEST['user_sync_action'] ) {
            //writing some information in the plugin log file
            $this->write_log( "2-10 - user_sync_action not exist" );
            die( "" );
        }

        if ( isset ( $_REQUEST['p'] ) && "" != $_REQUEST['p'] && isset ( $_REQUEST['hash'] ) && "" != $_REQUEST['hash'] ) {
            //checking hash sum
            if ( $_REQUEST['hash'] == md5( $_REQUEST['p'] . $key ) ) {
                $p = base64_decode( $_REQUEST['p'] );

                global $wpdb;

                switch( $_REQUEST[ 'user_sync_action' ] ) {
                    //action for Synchronization user
                    case "sync_user":
                        $p = unserialize( $p );
                        //writing some information in the plugin log file
                        $this->write_log( "5-10 - start sync_user" );

                        $user_sync_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->users} WHERE user_login = '%s'", $p['userdata']['user_login'] ) );

                        if( $user_sync_id ) {
                            //Update user

                            //writing some information in the plugin log file
                            $this->write_log( "10 - update user" );

                            //checking settings of overwrite user and flag of users that sync from master site
                            if ( 1 == $p['param']['overwrite_user'] && "1" != get_user_meta( $user_sync_id, "user_sync", true ) ) {

                                $user_sync_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->users} WHERE user_login = '%s'", $p['userdata']['user_login'] . "_sync" ) );

                                //if user exist we have new ID in $user_sync_id and we can use code below for Update user data
                                if( ! $user_sync_id ) {

                                    //writing some information in the plugin log file
                                    $this->write_log( "11 - don't overwrite user" );

                                    //changing user login adding  _sync
                                    $p['userdata']['user_login'] = $p['userdata']['user_login'] . "_sync";

                                    //checking email of user on duplicate
                                    if ( $user_sync_id != email_exists( $p['userdata']['user_email'] ) && false != email_exists( $p['userdata']['user_email'] ) )
                                        $p['userdata']['user_email'] = $this->generate_email($p['userdata']['user_login']);

                                    //user password
                                    $user_sync_pass  = $p['userdata']['user_pass'];

                                    //delete user ID and user_pass for Insert new user
                                    unset( $p['userdata']['ID'] );
                                    unset( $p['userdata']['user_pass'] );

                                    //Insert new user
                                    //TODO: try use real password
                                    $p['userdata']['user_pass'] = '';
                                    $user_sync_last_id = wp_insert_user( $p['userdata'] );

                                    //adding user password back for updating it
                                    $p['userdata']['user_pass'] = $user_sync_pass;

                                    //Update other data of user
                                    $this->update_other_user_data( $p['userdata'], $user_sync_last_id );

                                    //writing some information in the plugin log file
                                    $this->write_log( "12 - don't overwrite user - ok" );

                                    return;
                                }
                            }

                            //writing some information in the plugin log file
                            $this->write_log( "13 - overwrite user" );

                            $p['userdata']['ID'] = $user_sync_id;

                            //user password
                            $user_sync_pass  = $p['userdata']['user_pass'];

                            //delete user_pass for update user
                            unset( $p['userdata']['user_pass'] );


                            //checking email of user on duplicate
                            if ( $user_sync_id != email_exists( $p['userdata']['user_email'] ) && false != email_exists( $p['userdata']['user_email'] ) )
                                $p['userdata']['user_email'] = $this->generate_email($p['userdata']['user_login']);

                            //update user data
                            $p['userdata']['user_pass'] = '';
                            $user_sync_last_id = wp_insert_user( $p['userdata'] );

                            //adding user password back for updating it
                            $p['userdata']['user_pass'] = $user_sync_pass;

                            //Update other data of user
                            $this->update_other_user_data( $p['userdata'], $user_sync_last_id );

                            //writing some information in the plugin log file
                            $this->write_log( "14 - overwrite user - ok" );

                        } else {

                            //writing some information in the plugin log file
                            $this->write_log( "15 - insert user - step 1" );

                            if ( 1 == $p['param']['replace_user'] ) {
                                $deleted_users = $this->options['deleted_users'];

                                //writing some information in the plugin log file
                                $this->write_log( "16 - do not replace deleted users" );

                                if ( is_array( $deleted_users ) && false !== array_search( $p['userdata']['user_login'], $deleted_users ) )
                                    return;
                            }

                            //writing some information in the plugin log file
                            $this->write_log( "17 - insert user - step 2" );

                            //user password
                            $user_sync_pass  = $p['userdata']['user_pass'];

                            //delete user ID and user_pass for Insert new user
                            unset( $p['userdata']['ID'] );
                            unset( $p['userdata']['user_pass'] );

                            //checking email of user on duplicate
                            if ( $user_sync_id != email_exists( $p['userdata']['user_email'] ) && false != email_exists( $p['userdata']['user_email'] ) )
                                $p['userdata']['user_email'] = $this->generate_email($p['userdata']['user_login']);

                            //Insert new user
                            $p['userdata']['user_pass'] = '';
                            $user_sync_last_id = wp_insert_user( $p['userdata'] );

                            //adding user password back for updating it
                            $p['userdata']['user_pass'] = $user_sync_pass;

                            //Update other data of user
                            $this->update_other_user_data( $p['userdata'], $user_sync_last_id );

                            //flag for users that sync from master site
                            add_user_meta( $user_sync_last_id, "user_sync", "1", false );

                            //writing some information in the plugin log file
                            $this->write_log( "18 - insert user - ok" );
                        }

                        die( "ok" );
                    break;

                    //action for Synchronization when deleting user
                    case "sync_user_delete":
                        $p = unserialize( $p );

                        //checking that user exist
                        $user_sync_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->users} WHERE user_login = '%s'", $p['user_login'] ) );

                        if( $user_sync_id ) {
                            //Update user
                            //checking settings of overwrite user and flag of users that sync from master site
                            if ( 1 == $p['overwrite_user'] && "1" != get_user_meta( $user_sync_id, "user_sync", true ) ) {

                                $user_sync_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->users} WHERE user_login = '%s'", $p['user_login'] . "_sync" ) );

                                if( ! $user_sync_id )
                                    return;

                            }

                            //deleting user
                            $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->usermeta} WHERE user_id = %d", $user_sync_id ) );
                            $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->users} WHERE ID = %d", $user_sync_id ) );
                        }

                        die( "ok" );
                    break;

                    //action for Synchronization when activating new Subsite
                    case "connect_new_subsite":
                        $p = unserialize( $p );

                        $sub_urls = $this->options['sub_urls'];

                        $p = array (
                                'url'       => $p['url'],
                                'last_sync' => '',
                                'param'     =>
                                    array(
                                        'replace_user'      => $p['replace_user'],
                                        'overwrite_user'    => $p['overwrite_user']
                                        ) );

                        if ( is_array( $sub_urls ) ) {
                            if ( -1 ==  $this->get_index_by_url( $p['url'], $sub_urls ) ) {
                                 $sub_urls[] = $p;
                                 $this->set_options( 'sub_urls', $sub_urls );
                            }
                        } else {
                            $sub_urls = array();
                            $sub_urls[] = $p;
                            $this->set_options( 'sub_urls', $sub_urls );
                        }

                        //writing some information in the plugin log file
                        $this->write_log( "07 - added new sub site" );

                        die( "ok" );
                    break;

                    //action for Synchronization when activating new Subsite
                    case "sync_new_subsite":
                        $p = unserialize( $p );

                        $sub_urls = $this->options['sub_urls'];

                        //Get all users ID
                        $users_id = $this->get_all_users_id();

                        //writing some information in the plugin log file
                        $this->write_log( "08 - count of users= ". count( $users_id ) . ";;" );

                        $array_id = $this->get_index_by_url( $p['url'], $sub_urls );

                        //Call Synchronization user function
                        $this->sync_user( $users_id, array( $sub_urls[$array_id] ) );

                        die( "ok" );
                    break;

                    //action for deleting Subsite URL from Central site
                    case "delete_subsite":
                        $sub_urls = $this->options['sub_urls'];

                        $array_id = $this->get_index_by_url( $p, $sub_urls );

                        if ( -1 != $array_id ) {
                            array_splice( $sub_urls, $array_id, 1 );

                            $this->set_options( 'sub_urls', $sub_urls );
                        }

                        die( "ok" );
                    break;
                }

            }
            //writing some information in the plugin log file
            $this->write_log( "4-10 - hash sum error" );
        }
        //writing some information in the plugin log file
        $this->write_log( "3-10 - p or hash not set" );
    }

    /**
     * Generate Email for the domain
     * We dont want to have users with the same email and cause errors
     *
     * @param String $user_name - current username
     *
     * @return String
     */
    function generate_email( $user_name ){
		$url        = site_url();
		$url_info   = pathinfo($url);

		if ( $url_info['dirname'] ){
			$domain = $url_info['dirname'];
		} else {
			$domain = $url_info['basename'];
		}

		$domain     = str_replace( "www.", "", $domain );
		$user_count = get_user_count();

        if ( empty( $user_name ) ){
            $user_name = 'test';
        }

		return $user_name.'_'.$user_count.'@'.$domain;
	}

    /**
     *  Tempalate of pages
     **/
    function plugin_page() {
        global $wpdb;

        //Display status message
        if ( isset( $_GET['updated'] ) ) {
            ?><div id="message" class="updated fade"><p><?php _e( urldecode( $_GET['dmsg']), 'user-sync' ) ?></p></div><?php
        }

        switch( $this->options['status'] ) {
            case "sub":
                require_once( $this->plugin_dir . "page-sub.php" );
            break;

            case "central":
                require_once( $this->plugin_dir . "page-central.php" );
            break;

            default:
                require_once( $this->plugin_dir . "page-main.php" );
            break;
        }
    }


    /**
     *  Get user data
     **/
    private function _get_user_data( $user_id ) {
        global $wpdb;

        $data = get_userdata( $user_id );

        if ( !empty( $data->data ) )
            $user_data = (array) $data->data;
        else
            $user_data = (array) $data;

        $user_meta = get_user_meta( $user_id );

        $keys = array();
        // replace empty array on empty string
        foreach ( $user_meta as $key => $value ) {
            $keys[] = $key;
        }

        foreach ( $keys as $key ) {
            $user_meta[$key] = get_user_meta( $user_id, $key, true );
        }

        $prefix_fix = array('capabilities', 'user_level');
        foreach ($prefix_fix as $key) {
            if(isset($user_meta[$wpdb->get_blog_prefix() . $key])) {
                $user_meta['wp_' . $key] = $user_meta[$wpdb->get_blog_prefix() . $key];
                if($wpdb->get_blog_prefix() != 'wp_')
                    unset($user_meta[$wpdb->get_blog_prefix() . $key]);
            }
        }

        return array_merge( $user_data, $user_meta );
    }


    function bp_users_activate($signup_ids, $result) {
        var_dump($signup_ids, $result);
        die();
    }

    /**
	 * Adds the Privacy Policy Suggested Text
	 *
	 * @uses function_exists
	 * @uses ob_start
	 * @uses ob_get_clean
	 * @uses wp_add_privacy_policy_content
	 */
	public function privacy_policy_suggested_text() {
		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
			ob_start();
			include dirname( __FILE__ ) . '/policy-text.php';
			$content = ob_get_clean();
			if ( ! empty( $content ) ) {
				wp_add_privacy_policy_content( __( 'Benutzersynchronisation', 'user-sync' ), $content );
			}
		}
	}
}

$user_sync = new User_Sync();

