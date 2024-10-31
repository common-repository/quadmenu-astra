<?php
/**
 * Plugin Name: QuadMenu - Astra Mega Menu
 * Plugin URI: https://quadmenu.com
 * Description: Integrates QuadMenu with the Woocommerce Astra theme.
 * Version: 1.1.5
 * Author: QuadMenu
 * Author URI: https://quadmenu.com
* License: GPLv3
 */
if (!defined('ABSPATH')) {
    die('-1');
}

if (!class_exists('QuadMenu_Astra')) :

    final class QuadMenu_Astra {

        function __construct() {

            add_action('admin_notices', array($this, 'notices'));

            add_action('admin_enqueue_scripts', array($this, 'dequeue'), 999);
            add_action('customize_controls_enqueue_scripts', array($this, 'dequeue'), 999);
            add_filter('quadmenu_developer_options', array($this, 'options'), 10);
            add_filter('quadmenu_default_themes', array($this, 'themes'), 10);
            add_filter('quadmenu_default_options', array($this, 'general'), 10);
            add_filter('quadmenu_default_options_social', array($this, 'social'), 10);
            add_filter('quadmenu_default_options_theme_astra_light', array($this, 'light'), 10);
            add_filter('quadmenu_default_options_location_primary', array($this, 'primary'), 10);
        }
        
        function notices() {

            $screen = get_current_screen();

            if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
                return;
            }

            $plugin = 'quadmenu/quadmenu.php';

            if (is_plugin_active($plugin)) {
                return;
            }

            if (is_quadmenu_installed()) {

                if (!current_user_can('activate_plugins')) {
                    return;
                }
                ?>
                <div class="error">
                    <p>
                        <a href="<?php echo wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1', 'activate-plugin_' . $plugin); ?>" class='button button-secondary'><?php _e('Activate QuadMenu', 'quadmenu'); ?></a>
                        <?php esc_html_e('QuadMenu Astra not working because you need to activate the QuadMenu plugin.', 'quadmenu'); ?>   
                    </p>
                </div>
                <?php
            } else {

                if (!current_user_can('install_plugins')) {
                    return;
                }
                ?>
                <div class="error">
                    <p>
                        <a href="<?php echo wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=quadmenu'), 'install-plugin_quadmenu'); ?>" class='button button-secondary'><?php _e('Install QuadMenu', 'quadmenu'); ?></a>
                        <?php esc_html_e('QuadMenu Astra not working because you need to install the QuadMenu plugin.', 'quadmenu'); ?>
                    </p>
                </div>
                <?php
            }
        }        

        function dequeue() {
            wp_dequeue_script('astra-color-alpha');
        }

        function themes($themes) {

            $themes['astra_light'] = 'Astra Light';

            return $themes;
        }

        function general($defaults) {

            $defaults['viewport'] = 1;
            $defaults['styles'] = 1;
            $defaults['styles_normalize'] = 1;
            $defaults['styles_widgets'] = 1;
            $defaults['styles_icons'] = 'fontawesome';
            $defaults['styles_pscrollbar'] = 1;
            $defaults['gutter'] = 36;

            return $defaults;
        }

        function primary($defaults) {

            $defaults['theme'] = 'astra_light';
            $defaults['integration'] = 1;

            return $defaults;
        }

        function options($options) {

            if (!function_exists('astra_get_option')) {
                return $options;
            }

            if (astra_get_option('header-layouts') == 'header-main-layout-3') {
                $align = 'left';
            } elseif (astra_get_option('header-layouts') == 'header-main-layout-2') {
                $align = 'center';
            } else {
                $align = 'right';
            }

            // Locations
            // -----------------------------------------------------------------
            $options['primary_unwrap'] = 0;

            // Themes
            // -----------------------------------------------------------------

            $options['astra_light_mobile_shadow'] = 'hide';

            $options['astra_light_logo'] = false;
            $options['astra_light_navbar_logo'] = array();
            $options['astra_light_navbar_logo_height'] = '60';

            $options['astra_light_navbar_background'] = 'color';
            $options['astra_light_navbar_background_color'] = 'transparent';
            $options['astra_light_navbar_background_to'] = 'transparent';

            $options['astra_light_layout_width'] = 0;
            $options['astra_light_layout_width_inner'] = '';
            $options['astra_light_theme_title'] = 'Astra Light';
            $options['astra_light_layout_width_selector'] = '';
            $options['astra_light_layout_align'] = $align;
            $options['astra_light_layout'] = 'embed';
            $options['astra_light_layout_sticky_divider'] = '';
            $options['astra_light_layout_sticky'] = 0;
            $options['astra_light_layout_sticky_offset'] = '90';
            //$options['astra_light_layout_divider'] = 'hide';
            $options['astra_light_layout_current'] = 0;
            $options['astra_light_layout_offcanvas_float'] = 'right';
            //$options['astra_light_layout_hover_effect'] = '';
            $options['astra_light_layout_breakpoint'] = astra_get_option('mobile-header-breakpoint', 920);

            $options['astra_light_layout_classes'] = 'ast-flex-grow-1 main-header-bar-navigation';

            $options['astra_light_navbar_logo_bg'] = 'transparent';

            $options['astra_light_sticky'] = '';
            $options['astra_light_sticky_height'] = '60';
            $options['astra_light_sticky_background'] = 'transparent';
            $options['astra_light_sticky_logo_height'] = '25';

            $options['css'] = '
                    body {
                        overflow-x: hidden;
                    }
                    #quadmenu.quadmenu-astra_light .embed {
                        overflow-y: auto;
                        display: block;
                        visibility: visible;
                    }
                    #quadmenu.quadmenu-astra_light,
                    #quadmenu.quadmenu-astra_light .quadmenu-container {
                        position: initial;
                    }                    
                    #quadmenu.main-header-bar-navigation.toggle-on {
                        width: 100%;
                        padding-bottom: 1em;
                    }

            ';

            return $options;
        }

        function light($defaults) {

            // Layout
            // -----------------------------------------------------------------
            $defaults['layout'] = 'embed';
            $defaults['layout_offcanvas_float'] = 'left';
            $defaults['layout_caret'] = 'show';
            $defaults['layout_trigger'] = 'hoverintent';
            $defaults['layout_breakpoint'] = '768';
            $defaults['layout_hover_effect'] = '';

            // Fonts
            // -----------------------------------------------------------------
            $defaults['navbar_font'] = $defaults['dropdown_font'] = array(
                'font-family' => 'Roboto Slab',
                'google' => true,
                'font-size' => 15,
                'font-style' => 'normal',
                'font-weight' => '600',
            );

            $defaults['font'] = array(
                'font-family' => 'Roboto Slab',
                'google' => true,
                'font-size' => 14,
                'font-style' => 'normal',
                'font-weight' => '600'
            );

            // Navbar
            // -----------------------------------------------------------------

            $defaults['navbar_logo'] = array();
            $defaults['navbar_height'] = '60';
            $defaults['navbar_width'] = '260';
            $defaults['navbar_toggle_open'] = '#4f96ce';
            $defaults['navbar_toggle_close'] = '#4f96ce';
            $defaults['navbar_mobile_border'] = 'transparent';
            $defaults['navbar_background'] = 'color';
            $defaults['navbar_background_color'] = 'transparent';
            $defaults['navbar_background_to'] = 'transparent';

            $defaults['navbar_background_deg'] = '17';

            $defaults['navbar_sharp'] = 'transparent';
            $defaults['navbar_text'] = '#9aa0a7';

            $defaults['navbar_logo_height'] = '60';
            $defaults['navbar_link'] = '#000000';
            $defaults['navbar_link_hover'] = '#4f96ce';
            $defaults['navbar_link_bg'] = 'transparent';
            $defaults['navbar_link_bg_hover'] = 'transparent';
            $defaults['navbar_link_hover_effect'] = 'transparent';
            $defaults['navbar_link_margin'] = array('border-top' => '0', 'border-right' => '0', 'border-left' => '0', 'border-bottom' => '0');
            $defaults['navbar_link_radius'] = array('border-top' => '0', 'border-right' => '0', 'border-left' => '0', 'border-bottom' => '0');
            $defaults['navbar_link_transform'] = '';
            $defaults['navbar_link_icon'] = '#4f96ce';
            $defaults['navbar_link_icon_hover'] = '#000000';
            $defaults['navbar_link_subtitle'] = '#9aa0a7';
            $defaults['navbar_link_subtitle_hover'] = '#9aa0a7';
            $defaults['navbar_button'] = '#ffffff';
            $defaults['navbar_button_hover'] = '#ffffff';
            $defaults['navbar_button_bg'] = '#4f96ce';
            $defaults['navbar_button_bg_hover'] = '#000000';
            $defaults['navbar_badge'] = '#4f96ce';
            $defaults['navbar_badge_color'] = '#ffffff';
            $defaults['navbar_scrollbar'] = '#4f96ce';
            $defaults['navbar_scrollbar_rail'] = '#ffffff';

            // Dropdown
            // -------------------------------------------------------------------------
            $defaults['dropdown_margin'] = 15;
            $defaults['dropdown_radius'] = 0;
            $defaults['dropdown_shadow'] = 'hide';
            $defaults['dropdown_border'] = array('border-all' => '1px', 'border-top' => '0', 'border-color' => '#eaeaea');
            $defaults['dropdown_background'] = '#f3f3f7';
            $defaults['dropdown_scrollbar'] = '#222222';
            $defaults['dropdown_scrollbar_rail'] = '#eeeeee';
            $defaults['dropdown_title'] = '#1d1e24';
            $defaults['dropdown_title_border'] = array('border-all' => '2', 'border-top' => '2', 'border-color' => '#4f96ce', 'border-style' => 'solid');
            $defaults['dropdown_link'] = '#152428';
            $defaults['dropdown_link_hover'] = '#4f96ce';
            $defaults['dropdown_link_bg_hover'] = '#eaeff3';
            $defaults['dropdown_link_border'] = array('border-all' => '0', 'border-top' => '0', 'border-color' => '#eee', 'border-style' => 'solid');
            $defaults['dropdown_link_transform'] = '';
            $defaults['dropdown_tab_bg'] = 'rgba(0,0,0,0.02)';
            $defaults['dropdown_tab_bg_hover'] = '#ffffff';
            $defaults['dropdown_button'] = '#ffffff';
            $defaults['dropdown_button_bg'] = '#4f96ce';
            $defaults['dropdown_button_hover'] = '#ffffff';
            $defaults['dropdown_button_bg_hover'] = '#000000';
            $defaults['dropdown_link_icon'] = '#4f96ce';
            $defaults['dropdown_link_icon_hover'] = '#4f96ce';
            $defaults['dropdown_link_subtitle'] = '#6d6d6d';
            $defaults['dropdown_link_subtitle_hover'] = '#6d6d6d';

            return $defaults;
        }

        function social($social) {

            return array(
                array(
                    'title' => 'Facebook',
                    'icon' => 'fa fa-facebook ',
                    'url' => 'http://codecanyon.net/user/quadlayers/portfolio?ref=quadlayers',
                ),
                array(
                    'title' => 'Twitter',
                    'icon' => 'fa fa-twitter',
                    'url' => 'http://codecanyon.net/user/quadlayers/portfolio?ref=quadlayers',
                ),
                array(
                    'title' => 'Google',
                    'icon' => 'fa fa-google-plus',
                    'url' => 'http://codecanyon.net/user/quadlayers/portfolio?ref=quadlayers',
                ),
                array(
                    'title' => 'RSS',
                    'icon' => 'fa fa-rss',
                    'url' => 'http://codecanyon.net/user/quadlayers/portfolio?ref=quadlayers',
                ),
            );
        }

        static function activation() {

            update_option('_quadmenu_compiler', true);

            if (class_exists('QuadMenu')) {

                QuadMenu_Redux::add_notification('blue', esc_html__('Thanks for install QuadMenu Astra. We have to create the stylesheets. Please wait.', 'quadmenu'));

                QuadMenu_Activation::activation();
            }
        }

    }

    endif; // End if class_exists check

new QuadMenu_Astra();

if (!function_exists('is_quadmenu_installed')) {

    function is_quadmenu_installed() {

        $file_path = 'quadmenu/quadmenu.php';

        $installed_plugins = get_plugins();

        return isset($installed_plugins[$file_path]);
    }

}

register_activation_hook(__FILE__, array('QuadMenu_Astra', 'activation'));
