<?php
/**
 * Plugin Name: Council Controller
 * Description: A Must-Use WordPress plugin for managing council information and serving it via shortcodes.
 * Version: 1.0.0
 * Author: Council Controller
 * Text Domain: council-controller
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
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
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
            '1.0.0',
            true
        );
        
        // Enqueue custom styles
        wp_enqueue_style(
            'council-controller-admin',
            plugins_url( 'assets/css/admin.css', __FILE__ ),
            array(),
            '1.0.0'
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
            return wp_get_attachment_url( $logo_id );
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
}

// Initialize the plugin
add_action( 'plugins_loaded', array( 'Council_Controller', 'get_instance' ) );
