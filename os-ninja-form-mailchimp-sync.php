<?php

/*
 * Plugin Name:OS Ninja Form Mailchimp sync AND PODS
 * Plugin URI: bibliotecadeterminus.xyz
 * Description: Sincroniza un formulario de ninja form con mailchimp
 * Version: 1.0.0
 * Author: Oscar Sanchez
 * Author URI: bibliotecadeterminus.xyz
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 * Text Domain: wpos-additional
 * Domain Path: /languages/
 */

require 'vendor/autoload.php';

use OsNinjaFormSync\Util\FileLogger;
use OsNinjaFormSync\Model\MailChimpIntegration;

add_action('admin_menu', 'newsletter_plugin_create_menu');

function newsletter_plugin_create_menu() {

    //create new top-level menu
    add_menu_page('MAILCHIMP API', 'Mailchimp', 'administrator', __FILE__, 'os_newsletter_options' , plugins_url('/images/icon.png', __FILE__) );

    //call register settings function
    add_action( 'admin_init', 'register_options_newsletter' );
}


function register_options_newsletter() {
    register_setting( 'os-mailchimp_integration_api', 'os_mailchimp_api' );
    register_setting( 'os-mailchimp_integration_api', 'os_mailchimp_list' );
    register_setting( 'os-mailchimp_integration_api' ,'os_mailchimp_dg' );
    register_setting( 'os-mailchimp_integration_api' ,'os_id_ninja_form' );
    register_setting( 'os-mailchimp_integration_api' ,'os_id_ninja_field' );
}

function os_newsletter_options() {
    ?>
    <div class="wrap">
        <h1>Mailchimp Integration</h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'os-mailchimp_integration_api' ); ?>
            <?php do_settings_sections( 'os-mailchimp_integration_api' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Mailchimp API Key</th>
                    <td>
                        <input type="text" name="os_mailchimp_api" value="<?php echo esc_attr( get_option('os_mailchimp_api') ); ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Mailchimp list Key</th>
                    <td><input type="text" name="os_mailchimp_list" value="<?php echo esc_attr( get_option('os_mailchimp_list') ); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Mailchimp DG.</th>
                    <td><input type="text" name="os_mailchimp_dg" value="<?php echo esc_attr( get_option('os_mailchimp_dg') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">ID Ninja Form.</th>
                    <td><input type="text" name="os_id_ninja_form" value="<?php echo esc_attr( get_option('os_id_ninja_form') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">ID field email.</th>
                    <td><input type="text" name="os_id_ninja_field" value="<?php echo esc_attr( get_option('os_id_ninja_field') ); ?>" /></td>
                </tr>
            </table>

            <?php submit_button(); ?>

        </form>
    </div>
<?php }

add_filter( 'ninja_forms_submit_data', 'my_ninja_forms_submit_data' );

function my_ninja_forms_submit_data( $formData )
{

    $fileLogger = new FileLogger('mailchimp-sync',__DIR__.'/var/log/mailchimp-sync-'.date('m-Y').'.log');

    if($formData['id'] != esc_attr( get_option('os_id_ninja_form') )) {
        return $formData;
    }

    if(!isset($formData['fields'][esc_attr( get_option('os_id_ninja_field') )])) {
        return $formData;
    }

    $email = $formData['fields'][esc_attr( get_option('os_id_ninja_field') )]['value'];

    if(!is_email($email)) {
        return  $formData;
    }


    $mailChimpIntegration = new MailChimpIntegration(get_option('os_mailchimp_list'), get_option('os_mailchimp_dg'),get_option('os_mailchimp_api'));

    if($mailChimpIntegration->addSubscriber($email, $fileLogger)) {
        $fileLogger->addInfo('Email  '.$email.' enviado a mailchimp correctamente');
        return  $formData;
    }

    $fileLogger->addError('Email  '.$email.' no se ha enviado');

    return  $formData;
}

