<?php
/**
 * Plugin Name: Council Controller
 * Description: A Must-Use WordPress plugin for managing council information and serving it via shortcodes.
 * Version: 1.2.0
 * Author: Council Controller
 * Text Domain: council-controller
 * License: MIT
 * Requires at least: 5.0
 * Requires PHP: 7.0
 *
 * This plugin follows Semantic Versioning (https://semver.org/)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Council Controller Class
 */
class Council_Controller {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Settings option name
     */
    const OPTION_NAME = 'council_controller_settings';
    
    /**
     * Shortcode documentation registry
     */
    private $shortcode_docs = array();
    
    /**
     * Get instance of this class
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Initialize shortcode documentation first
        $this->init_shortcode_docs();
        
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'init', array( $this, 'register_shortcodes' ) );
    }
    
    /**
     * Initialize shortcode documentation registry
     * 
     * This method defines all shortcode documentation in one place.
     * When adding new shortcodes, add their documentation here to automatically
     * update the admin interface documentation section.
     */
    private function init_shortcode_docs() {
        $this->shortcode_docs = array(
            'council_name' => array(
                'tag'         => 'council_name',
                'description' => __( 'Displays the council name.', 'council-controller' ),
                'attributes'  => array(
                    array(
                        'name'        => 'tag',
                        'description' => __( 'HTML tag to wrap the name: h1, h2, h3, h4, h5, h6, p, span, or div (default: span)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'class',
                        'description' => __( 'Optional CSS class to add to the wrapper element', 'council-controller' ),
                    ),
                ),
                'examples'    => array(
                    '[council_name]',
                    '[council_name tag="h1"]',
                    '[council_name tag="h2" class="my-council-name"]',
                    '[council_name tag="p"]',
                ),
            ),
            'council_logo' => array(
                'tag'         => 'council_logo',
                'description' => __( 'Displays the council logo.', 'council-controller' ),
                'attributes'  => array(
                    array(
                        'name'        => 'size',
                        'description' => __( 'Image size: thumbnail, medium, large, or full (default: full)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'class',
                        'description' => __( 'Optional CSS class to add to the image', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'link',
                        'description' => __( 'Whether to link to the home page: yes or no (default: no)', 'council-controller' ),
                    ),
                ),
                'examples'    => array(
                    '[council_logo]',
                    '[council_logo size="medium"]',
                    '[council_logo size="large" class="header-logo" link="yes"]',
                ),
            ),
            'council_info' => array(
                'tag'         => 'council_info',
                'description' => __( 'Displays both council name and logo together in a formatted block.', 'council-controller' ),
                'attributes'  => array(
                    array(
                        'name'        => 'logo_size',
                        'description' => __( 'Logo image size: thumbnail, medium, large, or full (default: medium)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'show_name',
                        'description' => __( 'Show the name: yes or no (default: yes)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'show_logo',
                        'description' => __( 'Show the logo: yes or no (default: yes)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'name_tag',
                        'description' => __( 'HTML tag to wrap the name: h1, h2, h3, h4, h5, h6, p, span, or div (default: h2)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'class',
                        'description' => __( 'Optional CSS class to add to the wrapper div', 'council-controller' ),
                    ),
                ),
                'examples'    => array(
                    '[council_info]',
                    '[council_info logo_size="large"]',
                    '[council_info show_logo="no"]',
                    '[council_info name_tag="h1"]',
                    '[council_info logo_size="thumbnail" class="sidebar-council"]',
                    '[council_info name_tag="h3" logo_size="medium"]',
                ),
            ),
        );
        
        /**
         * Allow plugins and themes to add their own shortcode documentation
         * 
         * @param array $shortcode_docs Array of shortcode documentation
         */
        $this->shortcode_docs = apply_filters( 'council_controller_shortcode_docs', $this->shortcode_docs );
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'Council Settings', 'council-controller' ),
            __( 'Council Settings', 'council-controller' ),
            'manage_options',
            'council-settings',
            array( $this, 'render_settings_page' ),
            'dashicons-admin-generic',
            30
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'council_controller_settings_group',
            self::OPTION_NAME,
            array( $this, 'sanitize_settings' )
        );
        
        add_settings_section(
            'council_controller_main_section',
            __( 'Council Information', 'council-controller' ),
            array( $this, 'render_section_description' ),
            'council-settings'
        );
        
        add_settings_field(
            'council_name',
            __( 'Council Name', 'council-controller' ),
            array( $this, 'render_council_name_field' ),
            'council-settings',
            'council_controller_main_section'
        );
        
        add_settings_field(
            'council_logo',
            __( 'Council Logo', 'council-controller' ),
            array( $this, 'render_council_logo_field' ),
            'council-settings',
            'council_controller_main_section'
        );
        
        add_settings_section(
            'council_controller_shortcodes_section',
            __( 'Available Shortcodes', 'council-controller' ),
            array( $this, 'render_shortcodes_section' ),
            'council-settings'
        );
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings( $input ) {
        $sanitized = array();
        
        if ( isset( $input['council_name'] ) ) {
            $sanitized['council_name'] = sanitize_text_field( $input['council_name'] );
        }
        
        if ( isset( $input['council_logo'] ) ) {
            $sanitized['council_logo'] = absint( $input['council_logo'] );
        }
        
        return $sanitized;
    }
    
    /**
     * Render section description
     */
    public function render_section_description() {
        echo '<p>' . esc_html__( 'Configure your council information below.', 'council-controller' ) . '</p>';
    }
    
    /**
     * Render shortcodes section
     * 
     * Dynamically renders shortcode documentation from the registry.
     * This ensures the documentation stays in sync with available shortcodes.
     */
    public function render_shortcodes_section() {
        ?>
        <p><?php esc_html_e( 'Use these shortcodes to display council information on your website:', 'council-controller' ); ?></p>
        
        <div class="council-shortcodes-reference">
            <?php foreach ( $this->shortcode_docs as $shortcode_tag => $doc ) : ?>
                <div class="shortcode-item">
                    <h3 class="shortcode-title">
                        <code>[<?php echo esc_html( $doc['tag'] ); ?>]</code>
                    </h3>
                    
                    <p class="shortcode-description">
                        <?php echo esc_html( $doc['description'] ); ?>
                    </p>
                    
                    <?php if ( ! empty( $doc['attributes'] ) ) : ?>
                        <div class="shortcode-attributes">
                            <strong><?php esc_html_e( 'Attributes:', 'council-controller' ); ?></strong>
                            <ul>
                                <?php foreach ( $doc['attributes'] as $attr ) : ?>
                                    <li>
                                        <code><?php echo esc_html( $attr['name'] ); ?></code> - 
                                        <?php echo esc_html( $attr['description'] ); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $doc['examples'] ) ) : ?>
                        <div class="shortcode-examples">
                            <strong><?php esc_html_e( 'Examples:', 'council-controller' ); ?></strong>
                            <ul>
                                <?php foreach ( $doc['examples'] as $example ) : ?>
                                    <li><code><?php echo esc_html( $example ); ?></code></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
    
    /**
     * Render council name field
     */
    public function render_council_name_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $council_name = isset( $options['council_name'] ) ? $options['council_name'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[council_name]" 
               id="council_name" 
               value="<?php echo esc_attr( $council_name ); ?>" 
               class="regular-text" />
        <p class="description">
            <?php esc_html_e( 'Enter the name of your council.', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render council logo field
     */
    public function render_council_logo_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $logo_id = isset( $options['council_logo'] ) ? $options['council_logo'] : '';
        $logo_url = '';
        
        if ( $logo_id ) {
            $logo_url = wp_get_attachment_url( $logo_id );
            // Handle case where attachment doesn't exist
            if ( false === $logo_url ) {
                $logo_url = '';
            }
        }
        ?>
        <div class="council-logo-upload">
            <input type="hidden" 
                   name="<?php echo esc_attr( self::OPTION_NAME ); ?>[council_logo]" 
                   id="council_logo_id" 
                   value="<?php echo esc_attr( $logo_id ); ?>" />
            
            <div class="council-logo-preview" style="margin-bottom: 10px;">
                <?php if ( $logo_url ) : ?>
                    <img src="<?php echo esc_url( $logo_url ); ?>" 
                         alt="<?php esc_attr_e( 'Council Logo', 'council-controller' ); ?>" 
                         style="max-width: 200px; height: auto; display: block;" />
                <?php else : ?>
                    <img src="" 
                         alt="<?php esc_attr_e( 'Council Logo', 'council-controller' ); ?>" 
                         style="max-width: 200px; height: auto; display: none;" />
                <?php endif; ?>
            </div>
            
            <button type="button" 
                    class="button council-upload-logo-button">
                <?php esc_html_e( 'Choose Logo', 'council-controller' ); ?>
            </button>
            
            <?php if ( $logo_id ) : ?>
                <button type="button" 
                        class="button council-remove-logo-button">
                    <?php esc_html_e( 'Remove Logo', 'council-controller' ); ?>
                </button>
            <?php else : ?>
                <button type="button" 
                        class="button council-remove-logo-button" 
                        style="display: none;">
                    <?php esc_html_e( 'Remove Logo', 'council-controller' ); ?>
                </button>
            <?php endif; ?>
            
            <p class="description">
                <?php esc_html_e( 'Upload or select a logo for your council from the media library.', 'council-controller' ); ?>
            </p>
        </div>
        <?php
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts( $hook ) {
        // Only load on our settings page
        if ( 'toplevel_page_council-settings' !== $hook ) {
            return;
        }
        
        // Enqueue WordPress media scripts
        wp_enqueue_media();
        
        // Enqueue our custom script
        wp_enqueue_script(
            'council-controller-admin',
            plugins_url( 'assets/js/admin.js', __FILE__ ),
            array( 'jquery' ),
            '1.2.0',
            true
        );
        
        // Localize script for translations
        wp_localize_script(
            'council-controller-admin',
            'councilControllerL10n',
            array(
                'mediaTitle'  => __( 'Choose Council Logo', 'council-controller' ),
                'mediaButton' => __( 'Use this logo', 'council-controller' ),
            )
        );
        
        // Enqueue custom styles
        wp_enqueue_style(
            'council-controller-admin',
            plugins_url( 'assets/css/admin.css', __FILE__ ),
            array(),
            '1.2.0'
        );
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        // Show error/update messages
        settings_errors( self::OPTION_NAME );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'council_controller_settings_group' );
                do_settings_sections( 'council-settings' );
                submit_button( __( 'Save Settings', 'council-controller' ) );
                ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Get council name
     */
    public static function get_council_name() {
        $options = get_option( self::OPTION_NAME, array() );
        return isset( $options['council_name'] ) ? $options['council_name'] : '';
    }
    
    /**
     * Get council logo URL
     */
    public static function get_council_logo_url() {
        $options = get_option( self::OPTION_NAME, array() );
        $logo_id = isset( $options['council_logo'] ) ? $options['council_logo'] : '';
        
        if ( $logo_id ) {
            $logo_url = wp_get_attachment_url( $logo_id );
            // Return empty string if attachment doesn't exist
            return ( false === $logo_url ) ? '' : $logo_url;
        }
        
        return '';
    }
    
    /**
     * Get council logo ID
     */
    public static function get_council_logo_id() {
        $options = get_option( self::OPTION_NAME, array() );
        return isset( $options['council_logo'] ) ? $options['council_logo'] : '';
    }
    
    /**
     * Register shortcodes
     */
    public function register_shortcodes() {
        add_shortcode( 'council_name', array( $this, 'shortcode_council_name' ) );
        add_shortcode( 'council_logo', array( $this, 'shortcode_council_logo' ) );
        add_shortcode( 'council_info', array( $this, 'shortcode_council_info' ) );
    }
    
    /**
     * Shortcode: [council_name]
     * Displays the council name
     * 
     * Attributes:
     * - tag: HTML tag to wrap the name (default: span)
     * - class: CSS class to add to the wrapper
     */
    public function shortcode_council_name( $atts ) {
        $atts = shortcode_atts(
            array(
                'tag'   => 'span',
                'class' => '',
            ),
            $atts,
            'council_name'
        );
        
        $council_name = self::get_council_name();
        
        if ( empty( $council_name ) ) {
            return '';
        }
        
        // Validate and sanitize the tag parameter
        $allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div' );
        $tag = strtolower( trim( $atts['tag'] ) );
        if ( ! in_array( $tag, $allowed_tags, true ) ) {
            $tag = 'span'; // Default fallback
        }
        
        $class_attr = ! empty( $atts['class'] ) ? ' class="' . esc_attr( $atts['class'] ) . '"' : '';
        
        return '<' . $tag . $class_attr . '>' . esc_html( $council_name ) . '</' . $tag . '>';
    }
    
    /**
     * Shortcode: [council_logo]
     * Displays the council logo
     * 
     * Attributes:
     * - size: thumbnail, medium, large, full (default: full)
     * - class: CSS class to add to the image
     * - link: yes/no - whether to link to the home page (default: no)
     */
    public function shortcode_council_logo( $atts ) {
        $atts = shortcode_atts(
            array(
                'size'  => 'full',
                'class' => '',
                'link'  => 'no',
            ),
            $atts,
            'council_logo'
        );
        
        $logo_id = self::get_council_logo_id();
        
        if ( empty( $logo_id ) ) {
            return '';
        }
        
        // Get the image at the specified size
        $image = wp_get_attachment_image(
            $logo_id,
            $atts['size'],
            false,
            array(
                'class' => $atts['class'],
                'alt'   => esc_attr( self::get_council_name() ),
            )
        );
        
        if ( empty( $image ) ) {
            return '';
        }
        
        // Optionally wrap in a link to home page
        if ( 'yes' === strtolower( $atts['link'] ) ) {
            $image = '<a href="' . esc_url( home_url( '/' ) ) . '">' . $image . '</a>';
        }
        
        return $image;
    }
    
    /**
     * Shortcode: [council_info]
     * Displays council name and logo together
     * 
     * Attributes:
     * - logo_size: thumbnail, medium, large, full (default: medium)
     * - show_name: yes/no - whether to show the name (default: yes)
     * - show_logo: yes/no - whether to show the logo (default: yes)
     * - name_tag: HTML tag to wrap the name (default: h2)
     * - class: CSS class to add to the wrapper div
     */
    public function shortcode_council_info( $atts ) {
        $atts = shortcode_atts(
            array(
                'logo_size' => 'medium',
                'show_name' => 'yes',
                'show_logo' => 'yes',
                'name_tag'  => 'h2',
                'class'     => '',
            ),
            $atts,
            'council_info'
        );
        
        $output = '';
        $council_name = self::get_council_name();
        $logo_id = self::get_council_logo_id();
        
        // Return empty if nothing to display
        if ( ( 'no' === strtolower( $atts['show_name'] ) || empty( $council_name ) ) &&
             ( 'no' === strtolower( $atts['show_logo'] ) || empty( $logo_id ) ) ) {
            return '';
        }
        
        // Validate and sanitize the name_tag parameter
        $allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div' );
        $name_tag = strtolower( trim( $atts['name_tag'] ) );
        if ( ! in_array( $name_tag, $allowed_tags, true ) ) {
            $name_tag = 'h2'; // Default fallback
        }
        
        $class_attr = ! empty( $atts['class'] ) ? ' class="council-info ' . esc_attr( $atts['class'] ) . '"' : ' class="council-info"';
        
        $output .= '<div' . $class_attr . '>';
        
        // Show logo if enabled and available
        if ( 'yes' === strtolower( $atts['show_logo'] ) && ! empty( $logo_id ) ) {
            $logo = wp_get_attachment_image(
                $logo_id,
                $atts['logo_size'],
                false,
                array(
                    'class' => 'council-logo',
                    'alt'   => esc_attr( $council_name ),
                )
            );
            if ( ! empty( $logo ) ) {
                $output .= '<div class="council-logo-wrapper">' . $logo . '</div>';
            }
        }
        
        // Show name if enabled and available
        if ( 'yes' === strtolower( $atts['show_name'] ) && ! empty( $council_name ) ) {
            $output .= '<' . $name_tag . ' class="council-name">' . esc_html( $council_name ) . '</' . $name_tag . '>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
}

// Initialize the plugin
add_action( 'plugins_loaded', array( 'Council_Controller', 'get_instance' ) );
