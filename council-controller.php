<?php
/**
 * Plugin Name: Council Controller
 * Description: A Must-Use WordPress plugin for managing council information and serving it via shortcodes.
 * Version: 1.10.0
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
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_styles' ) );
        
        // Add custom fields to all pages and posts for page builder integration
        // Use template_redirect which fires after wp but before template rendering
        add_action( 'template_redirect', array( $this, 'add_custom_fields_to_posts' ), 1 );
        
        // Also hook into get_post_metadata to provide custom fields dynamically
        add_filter( 'get_post_metadata', array( $this, 'filter_post_metadata' ), 10, 4 );
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
                    array(
                        'name'        => 'prepend',
                        'description' => __( 'Text to add before the council name', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'append',
                        'description' => __( 'Text to add after the council name', 'council-controller' ),
                    ),
                ),
                'examples'    => array(
                    '[council_name]',
                    '[council_name tag="h1"]',
                    '[council_name tag="h2" class="my-council-name"]',
                    '[council_name tag="h1" prepend="Welcome to"]',
                    '[council_name prepend="Official Site of" tag="h2"]',
                    '[council_name tag="p" append="- Official Website"]',
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
                    array(
                        'name'        => 'aria_label',
                        'description' => __( 'ARIA label for accessibility. If not provided, uses the council name', 'council-controller' ),
                    ),
                ),
                'examples'    => array(
                    '[council_logo]',
                    '[council_logo size="medium"]',
                    '[council_logo size="large" class="header-logo" link="yes"]',
                    '[council_logo aria_label="City Council Logo"]',
                    '[council_logo size="medium" aria_label="Official Council Emblem"]',
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
                    array(
                        'name'        => 'prepend',
                        'description' => __( 'Text to add before the council name', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'append',
                        'description' => __( 'Text to add after the council name', 'council-controller' ),
                    ),
                ),
                'examples'    => array(
                    '[council_info]',
                    '[council_info logo_size="large"]',
                    '[council_info show_logo="no"]',
                    '[council_info name_tag="h1"]',
                    '[council_info logo_size="thumbnail" class="sidebar-council"]',
                    '[council_info name_tag="h1" prepend="Welcome to"]',
                    '[council_info prepend="Official Site of" logo_size="medium"]',
                ),
            ),
            'council_hero_image' => array(
                'tag'         => 'council_hero_image',
                'description' => __( 'Returns the URL of the hero image. Outputs only the URL with no HTML markup, perfect for use in CSS background-image properties or PHP background styles.', 'council-controller' ),
                'attributes'  => array(
                    array(
                        'name'        => 'size',
                        'description' => __( 'Image size: thumbnail, medium, large, or full (default: full)', 'council-controller' ),
                    ),
                ),
                'examples'    => array(
                    '[council_hero_image]',
                    '[council_hero_image size="large"]',
                    '[council_hero_image size="full"]',
                ),
            ),
            'council_hero_background' => array(
                'tag'         => 'council_hero_background',
                'description' => __( 'Wraps content with a div that has the hero image as a full-width background. Perfect for use with page builder shortcode wrappers like Breakdance.', 'council-controller' ),
                'attributes'  => array(
                    array(
                        'name'        => 'size',
                        'description' => __( 'Image size: thumbnail, medium, large, or full (default: full)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'bg_size',
                        'description' => __( 'CSS background-size: cover, contain, auto, or specific size like "100% 100%" (default: cover)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'bg_repeat',
                        'description' => __( 'CSS background-repeat: no-repeat, repeat, repeat-x, repeat-y (default: no-repeat)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'bg_position',
                        'description' => __( 'CSS background-position: center, top, bottom, left, right, or specific like "50% 50%" (default: center)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'bg_attachment',
                        'description' => __( 'CSS background-attachment: scroll, fixed, local (default: scroll)', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'class',
                        'description' => __( 'Optional CSS class to add to the wrapper div', 'council-controller' ),
                    ),
                    array(
                        'name'        => 'min_height',
                        'description' => __( 'Minimum height of the background section (e.g., "400px", "50vh"). Default: none', 'council-controller' ),
                    ),
                ),
                'examples'    => array(
                    '[council_hero_background]Your content here[/council_hero_background]',
                    '[council_hero_background bg_size="cover" bg_position="center"]Content[/council_hero_background]',
                    '[council_hero_background min_height="500px" bg_attachment="fixed"]Content[/council_hero_background]',
                    '[council_hero_background class="hero-section" bg_size="cover" bg_position="top center"]Content[/council_hero_background]',
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
        
        add_settings_field(
            'hero_image',
            __( 'Hero Image', 'council-controller' ),
            array( $this, 'render_hero_image_field' ),
            'council-settings',
            'council_controller_main_section'
        );
        
        // Color Management Section
        add_settings_section(
            'council_controller_colors_section',
            __( 'Color Management', 'council-controller' ),
            array( $this, 'render_colors_section_description' ),
            'council-settings'
        );
        
        add_settings_field(
            'primary_color',
            __( 'Primary Color', 'council-controller' ),
            array( $this, 'render_primary_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'secondary_color',
            __( 'Secondary Color', 'council-controller' ),
            array( $this, 'render_secondary_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'tertiary_color',
            __( 'Tertiary Color', 'council-controller' ),
            array( $this, 'render_tertiary_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'h1_color',
            __( 'H1 Color', 'council-controller' ),
            array( $this, 'render_h1_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'h2_color',
            __( 'H2 Color', 'council-controller' ),
            array( $this, 'render_h2_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'h3_color',
            __( 'H3 Color', 'council-controller' ),
            array( $this, 'render_h3_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'h4_color',
            __( 'H4 Color', 'council-controller' ),
            array( $this, 'render_h4_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'h5_color',
            __( 'H5 Color', 'council-controller' ),
            array( $this, 'render_h5_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'h6_color',
            __( 'H6 Color', 'council-controller' ),
            array( $this, 'render_h6_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'link_color',
            __( 'Link Color', 'council-controller' ),
            array( $this, 'render_link_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'menu_link_color',
            __( 'Menu Link Color', 'council-controller' ),
            array( $this, 'render_menu_link_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'body_color',
            __( 'Body Text Color', 'council-controller' ),
            array( $this, 'render_body_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'button_color',
            __( 'Button Color', 'council-controller' ),
            array( $this, 'render_button_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'button_text_color',
            __( 'Button Text Color', 'council-controller' ),
            array( $this, 'render_button_text_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'button_hover_color',
            __( 'Button Hover Color', 'council-controller' ),
            array( $this, 'render_button_hover_color_field' ),
            'council-settings',
            'council_controller_colors_section'
        );
        
        add_settings_field(
            'button_text_hover_color',
            __( 'Button Text Hover Color', 'council-controller' ),
            array( $this, 'render_button_text_hover_color_field' ),
            'council-settings',
            'council_controller_colors_section'
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
        
        if ( isset( $input['hero_image'] ) ) {
            $sanitized['hero_image'] = absint( $input['hero_image'] );
        }
        
        // Sanitize color fields
        $color_fields = array( 'primary_color', 'secondary_color', 'tertiary_color', 'h1_color', 'h2_color', 'h3_color', 'h4_color', 'h5_color', 'h6_color', 'link_color', 'menu_link_color', 'body_color', 'button_color', 'button_text_color', 'button_hover_color', 'button_text_hover_color' );
        foreach ( $color_fields as $field ) {
            if ( isset( $input[ $field ] ) ) {
                $sanitized[ $field ] = sanitize_hex_color( $input[ $field ] );
            }
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
     * Render hero image field
     */
    public function render_hero_image_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $hero_id = isset( $options['hero_image'] ) ? $options['hero_image'] : '';
        $hero_url = '';
        
        if ( $hero_id ) {
            $hero_url = wp_get_attachment_url( $hero_id );
            // Handle case where attachment doesn't exist
            if ( false === $hero_url ) {
                $hero_url = '';
            }
        }
        ?>
        <div class="council-hero-upload">
            <input type="hidden" 
                   name="<?php echo esc_attr( self::OPTION_NAME ); ?>[hero_image]" 
                   id="hero_image_id" 
                   value="<?php echo esc_attr( $hero_id ); ?>" />
            
            <div class="council-hero-preview" style="margin-bottom: 10px;">
                <?php if ( $hero_url ) : ?>
                    <img src="<?php echo esc_url( $hero_url ); ?>" 
                         alt="<?php esc_attr_e( 'Hero Image', 'council-controller' ); ?>" 
                         style="max-width: 400px; height: auto; display: block;" />
                <?php else : ?>
                    <img src="" 
                         alt="<?php esc_attr_e( 'Hero Image', 'council-controller' ); ?>" 
                         style="max-width: 400px; height: auto; display: none;" />
                <?php endif; ?>
            </div>
            
            <button type="button" 
                    class="button council-upload-hero-button">
                <?php esc_html_e( 'Choose Hero Image', 'council-controller' ); ?>
            </button>
            
            <?php if ( $hero_id ) : ?>
                <button type="button" 
                        class="button council-remove-hero-button">
                    <?php esc_html_e( 'Remove Hero Image', 'council-controller' ); ?>
                </button>
            <?php else : ?>
                <button type="button" 
                        class="button council-remove-hero-button" 
                        style="display: none;">
                    <?php esc_html_e( 'Remove Hero Image', 'council-controller' ); ?>
                </button>
            <?php endif; ?>
            
            <p class="description">
                <?php esc_html_e( 'Upload or select a hero image from the media library. Use the [council_hero_image] shortcode to get the image URL for backgrounds.', 'council-controller' ); ?>
            </p>
        </div>
        <?php
    }
    
    /**
     * Render colors section description
     */
    public function render_colors_section_description() {
        echo '<p>' . esc_html__( 'Configure color scheme for your website. These colors will be available as CSS variables that page builders and themes can use.', 'council-controller' ) . '</p>';
    }
    
    /**
     * Render primary color field
     */
    public function render_primary_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $primary_color = isset( $options['primary_color'] ) ? $options['primary_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[primary_color]" 
               id="primary_color" 
               value="<?php echo esc_attr( $primary_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'Primary brand color. Available as CSS variable: --council-primary', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render secondary color field
     */
    public function render_secondary_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $secondary_color = isset( $options['secondary_color'] ) ? $options['secondary_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[secondary_color]" 
               id="secondary_color" 
               value="<?php echo esc_attr( $secondary_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'Secondary brand color. Available as CSS variable: --council-secondary', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render tertiary color field
     */
    public function render_tertiary_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $tertiary_color = isset( $options['tertiary_color'] ) ? $options['tertiary_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[tertiary_color]" 
               id="tertiary_color" 
               value="<?php echo esc_attr( $tertiary_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'Tertiary brand color. Available as CSS variable: --council-tertiary', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render h1 color field
     */
    public function render_h1_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $h1_color = isset( $options['h1_color'] ) ? $options['h1_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[h1_color]" 
               id="h1_color" 
               value="<?php echo esc_attr( $h1_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'H1 heading color. Available as CSS variable: --council-h1', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render h2 color field
     */
    public function render_h2_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $h2_color = isset( $options['h2_color'] ) ? $options['h2_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[h2_color]" 
               id="h2_color" 
               value="<?php echo esc_attr( $h2_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'H2 heading color. Available as CSS variable: --council-h2', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render h3 color field
     */
    public function render_h3_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $h3_color = isset( $options['h3_color'] ) ? $options['h3_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[h3_color]" 
               id="h3_color" 
               value="<?php echo esc_attr( $h3_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'H3 heading color. Available as CSS variable: --council-h3', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render h4 color field
     */
    public function render_h4_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $h4_color = isset( $options['h4_color'] ) ? $options['h4_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[h4_color]" 
               id="h4_color" 
               value="<?php echo esc_attr( $h4_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'H4 heading color. Available as CSS variable: --council-h4', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render h5 color field
     */
    public function render_h5_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $h5_color = isset( $options['h5_color'] ) ? $options['h5_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[h5_color]" 
               id="h5_color" 
               value="<?php echo esc_attr( $h5_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'H5 heading color. Available as CSS variable: --council-h5', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render h6 color field
     */
    public function render_h6_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $h6_color = isset( $options['h6_color'] ) ? $options['h6_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[h6_color]" 
               id="h6_color" 
               value="<?php echo esc_attr( $h6_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'H6 heading color. Available as CSS variable: --council-h6', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render link color field
     */
    public function render_link_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $link_color = isset( $options['link_color'] ) ? $options['link_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[link_color]" 
               id="link_color" 
               value="<?php echo esc_attr( $link_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'Link color. Available as CSS variable: --council-link', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render menu link color field
     */
    public function render_menu_link_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $menu_link_color = isset( $options['menu_link_color'] ) ? $options['menu_link_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[menu_link_color]" 
               id="menu_link_color" 
               value="<?php echo esc_attr( $menu_link_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'Menu link color. Available as CSS variable: --council-menu-link', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render body color field
     */
    public function render_body_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $body_color = isset( $options['body_color'] ) ? $options['body_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[body_color]" 
               id="body_color" 
               value="<?php echo esc_attr( $body_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'Default body text color. Available as CSS variable: --council-body-text', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render button color field
     */
    public function render_button_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $button_color = isset( $options['button_color'] ) ? $options['button_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[button_color]" 
               id="button_color" 
               value="<?php echo esc_attr( $button_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'Button background color. Available as CSS variable: --council-button', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render button text color field
     */
    public function render_button_text_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $button_text_color = isset( $options['button_text_color'] ) ? $options['button_text_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[button_text_color]" 
               id="button_text_color" 
               value="<?php echo esc_attr( $button_text_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'Button text color. Available as CSS variable: --council-button-text', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render button hover color field
     */
    public function render_button_hover_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $button_hover_color = isset( $options['button_hover_color'] ) ? $options['button_hover_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[button_hover_color]" 
               id="button_hover_color" 
               value="<?php echo esc_attr( $button_hover_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'Button hover background color. Available as CSS variable: --council-button-hover', 'council-controller' ); ?>
        </p>
        <?php
    }
    
    /**
     * Render button text hover color field
     */
    public function render_button_text_hover_color_field() {
        $options = get_option( self::OPTION_NAME, array() );
        $button_text_hover_color = isset( $options['button_text_hover_color'] ) ? $options['button_text_hover_color'] : '';
        ?>
        <input type="text" 
               name="<?php echo esc_attr( self::OPTION_NAME ); ?>[button_text_hover_color]" 
               id="button_text_hover_color" 
               value="<?php echo esc_attr( $button_text_hover_color ); ?>" 
               class="council-color-picker" 
               data-default-color="" />
        <p class="description">
            <?php esc_html_e( 'Button text hover color. Available as CSS variable: --council-button-text-hover', 'council-controller' ); ?>
        </p>
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
        
        // Enqueue WordPress color picker
        wp_enqueue_style( 'wp-color-picker' );
        
        // Enqueue our custom script
        wp_enqueue_script(
            'council-controller-admin',
            plugins_url( 'assets/js/admin.js', __FILE__ ),
            array( 'jquery', 'wp-color-picker', 'media-upload', 'media-views' ),
            '1.8.1',
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
            '1.8.1'
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
     * Enqueue frontend styles with CSS variables
     */
    public function enqueue_frontend_styles() {
        $options = get_option( self::OPTION_NAME, array() );
        
        // Build CSS variables
        $css_vars = array();
        
        if ( ! empty( $options['primary_color'] ) ) {
            $css_vars[] = '--council-primary: ' . esc_attr( $options['primary_color'] );
        }
        
        if ( ! empty( $options['secondary_color'] ) ) {
            $css_vars[] = '--council-secondary: ' . esc_attr( $options['secondary_color'] );
        }
        
        if ( ! empty( $options['tertiary_color'] ) ) {
            $css_vars[] = '--council-tertiary: ' . esc_attr( $options['tertiary_color'] );
        }
        
        if ( ! empty( $options['h1_color'] ) ) {
            $css_vars[] = '--council-h1: ' . esc_attr( $options['h1_color'] );
        }
        
        if ( ! empty( $options['h2_color'] ) ) {
            $css_vars[] = '--council-h2: ' . esc_attr( $options['h2_color'] );
        }
        
        if ( ! empty( $options['h3_color'] ) ) {
            $css_vars[] = '--council-h3: ' . esc_attr( $options['h3_color'] );
        }
        
        if ( ! empty( $options['h4_color'] ) ) {
            $css_vars[] = '--council-h4: ' . esc_attr( $options['h4_color'] );
        }
        
        if ( ! empty( $options['h5_color'] ) ) {
            $css_vars[] = '--council-h5: ' . esc_attr( $options['h5_color'] );
        }
        
        if ( ! empty( $options['h6_color'] ) ) {
            $css_vars[] = '--council-h6: ' . esc_attr( $options['h6_color'] );
        }
        
        if ( ! empty( $options['link_color'] ) ) {
            $css_vars[] = '--council-link: ' . esc_attr( $options['link_color'] );
        }
        
        if ( ! empty( $options['menu_link_color'] ) ) {
            $css_vars[] = '--council-menu-link: ' . esc_attr( $options['menu_link_color'] );
        }
        
        if ( ! empty( $options['body_color'] ) ) {
            $css_vars[] = '--council-body-text: ' . esc_attr( $options['body_color'] );
        }
        
        if ( ! empty( $options['button_color'] ) ) {
            $css_vars[] = '--council-button: ' . esc_attr( $options['button_color'] );
        }
        
        if ( ! empty( $options['button_text_color'] ) ) {
            $css_vars[] = '--council-button-text: ' . esc_attr( $options['button_text_color'] );
        }
        
        if ( ! empty( $options['button_hover_color'] ) ) {
            $css_vars[] = '--council-button-hover: ' . esc_attr( $options['button_hover_color'] );
        }
        
        if ( ! empty( $options['button_text_hover_color'] ) ) {
            $css_vars[] = '--council-button-text-hover: ' . esc_attr( $options['button_text_hover_color'] );
        }
        
        // Only output if we have colors defined
        if ( ! empty( $css_vars ) ) {
            $custom_css = ':root { ' . implode( '; ', $css_vars ) . '; }';
            wp_add_inline_style( 'wp-block-library', $custom_css );
        }
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
     * Get hero image URL
     * 
     * @param string $size Image size (thumbnail, medium, large, full). Default: full
     * @return string Hero image URL or empty string
     */
    public static function get_hero_image_url( $size = 'full' ) {
        $options = get_option( self::OPTION_NAME, array() );
        $hero_id = isset( $options['hero_image'] ) ? $options['hero_image'] : '';
        
        if ( $hero_id ) {
            $hero_url = wp_get_attachment_image_url( $hero_id, $size );
            // Return empty string if attachment doesn't exist
            return ( false === $hero_url ) ? '' : $hero_url;
        }
        
        return '';
    }
    
    /**
     * Get hero image ID
     * 
     * @return int|string Hero image attachment ID or empty string
     */
    public static function get_hero_image_id() {
        $options = get_option( self::OPTION_NAME, array() );
        return isset( $options['hero_image'] ) ? $options['hero_image'] : '';
    }
    
    /**
     * Register shortcodes
     */
    public function register_shortcodes() {
        add_shortcode( 'council_name', array( $this, 'shortcode_council_name' ) );
        add_shortcode( 'council_logo', array( $this, 'shortcode_council_logo' ) );
        add_shortcode( 'council_info', array( $this, 'shortcode_council_info' ) );
        add_shortcode( 'council_hero_image', array( $this, 'shortcode_hero_image' ) );
        add_shortcode( 'council_hero_background', array( $this, 'shortcode_hero_background' ) );
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
                'tag'     => 'span',
                'class'   => '',
                'prepend' => '',
                'append'  => '',
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
        
        // Build the content with prepend and append
        $content = '';
        if ( ! empty( $atts['prepend'] ) ) {
            $content .= esc_html( $atts['prepend'] ) . ' ';
        }
        $content .= esc_html( $council_name );
        if ( ! empty( $atts['append'] ) ) {
            $content .= ' ' . esc_html( $atts['append'] );
        }
        
        return '<' . $tag . $class_attr . '>' . $content . '</' . $tag . '>';
    }
    
    /**
     * Shortcode: [council_logo]
     * Displays the council logo
     * 
     * Attributes:
     * - size: thumbnail, medium, large, full (default: full)
     * - class: CSS class to add to the image
     * - link: yes/no - whether to link to the home page (default: no)
     * - aria_label: ARIA label for accessibility (default: uses council name)
     */
    public function shortcode_council_logo( $atts ) {
        $atts = shortcode_atts(
            array(
                'size'       => 'full',
                'class'      => '',
                'link'       => 'no',
                'aria_label' => '',
            ),
            $atts,
            'council_logo'
        );
        
        $logo_id = self::get_council_logo_id();
        
        if ( empty( $logo_id ) ) {
            return '';
        }
        
        // Determine aria-label: use provided value or default to council name
        $aria_label = ! empty( $atts['aria_label'] ) ? $atts['aria_label'] : self::get_council_name();
        
        // Build image attributes
        $image_attrs = array(
            'class' => $atts['class'],
            'alt'   => esc_attr( self::get_council_name() ),
        );
        
        // Add aria-label if available
        if ( ! empty( $aria_label ) ) {
            $image_attrs['aria-label'] = esc_attr( $aria_label );
        }
        
        // Get the image at the specified size
        $image = wp_get_attachment_image(
            $logo_id,
            $atts['size'],
            false,
            $image_attrs
        );
        
        if ( empty( $image ) ) {
            return '';
        }
        
        // Optionally wrap in a link to home page
        if ( 'yes' === strtolower( $atts['link'] ) ) {
            $link_attrs = '';
            // Add aria-label to the link if provided
            if ( ! empty( $aria_label ) ) {
                $link_attrs = ' aria-label="' . esc_attr( $aria_label ) . '"';
            }
            $image = '<a href="' . esc_url( home_url( '/' ) ) . '"' . $link_attrs . '>' . $image . '</a>';
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
                'prepend'   => '',
                'append'    => '',
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
            // Build the content with prepend and append
            $name_content = '';
            if ( ! empty( $atts['prepend'] ) ) {
                $name_content .= esc_html( $atts['prepend'] ) . ' ';
            }
            $name_content .= esc_html( $council_name );
            if ( ! empty( $atts['append'] ) ) {
                $name_content .= ' ' . esc_html( $atts['append'] );
            }
            
            $output .= '<' . $name_tag . ' class="council-name">' . $name_content . '</' . $name_tag . '>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode: [council_hero_image]
     * Returns the URL of the hero image (no HTML markup)
     * 
     * This shortcode outputs only the image URL, making it perfect for use
     * in CSS background-image properties or PHP background styles.
     * 
     * Attributes:
     * - size: thumbnail, medium, large, full (default: full)
     * 
     * @param array $atts Shortcode attributes
     * @return string Hero image URL or empty string
     */
    public function shortcode_hero_image( $atts ) {
        $atts = shortcode_atts(
            array(
                'size' => 'full',
            ),
            $atts,
            'council_hero_image'
        );
        
        // Get the hero image URL
        $hero_url = self::get_hero_image_url( $atts['size'] );
        
        // Return empty string if no hero image is set
        if ( empty( $hero_url ) ) {
            return '';
        }
        
        // Return just the URL (no HTML markup)
        return esc_url( $hero_url );
    }
    
    /**
     * Shortcode: [council_hero_background]
     * Wraps content with a div that has the hero image as a background
     * Perfect for page builder shortcode wrappers like Breakdance
     * 
     * Attributes:
     * - size: Image size (default: full)
     * - bg_size: CSS background-size (default: cover)
     * - bg_repeat: CSS background-repeat (default: no-repeat)
     * - bg_position: CSS background-position (default: center)
     * - bg_attachment: CSS background-attachment (default: scroll)
     * - class: CSS class to add to wrapper
     * - min_height: Minimum height of the section (optional)
     * 
     * @since 1.10.0
     */
    public function shortcode_hero_background( $atts, $content = null ) {
        $atts = shortcode_atts(
            array(
                'size'          => 'full',
                'bg_size'       => 'cover',
                'bg_repeat'     => 'no-repeat',
                'bg_position'   => 'center',
                'bg_attachment' => 'scroll',
                'class'         => '',
                'min_height'    => '',
            ),
            $atts,
            'council_hero_background'
        );
        
        // Get the hero image URL
        $hero_url = self::get_hero_image_url( $atts['size'] );
        
        // Return empty string if no hero image is set
        if ( empty( $hero_url ) ) {
            return '';
        }
        
        // Sanitize and validate CSS properties
        $bg_size = esc_attr( trim( $atts['bg_size'] ) );
        $bg_repeat = esc_attr( trim( $atts['bg_repeat'] ) );
        $bg_position = esc_attr( trim( $atts['bg_position'] ) );
        $bg_attachment = esc_attr( trim( $atts['bg_attachment'] ) );
        $min_height = ! empty( $atts['min_height'] ) ? esc_attr( trim( $atts['min_height'] ) ) : '';
        
        // Build inline styles
        $styles = array(
            'background-image: url(' . esc_url( $hero_url ) . ')',
            'background-size: ' . $bg_size,
            'background-repeat: ' . $bg_repeat,
            'background-position: ' . $bg_position,
            'background-attachment: ' . $bg_attachment,
            'width: 100%',
        );
        
        // Add min-height if specified
        if ( ! empty( $min_height ) ) {
            $styles[] = 'min-height: ' . $min_height;
        }
        
        $style_attr = 'style="' . implode( '; ', $styles ) . ';"';
        $class_attr = ! empty( $atts['class'] ) ? ' class="' . esc_attr( $atts['class'] ) . '"' : '';
        
        // Process the content (support for nested shortcodes)
        $content = do_shortcode( $content );
        
        // Return the wrapped content
        return '<div' . $class_attr . ' ' . $style_attr . '>' . $content . '</div>';
    }
    
    /**
     * Add custom fields to all pages and posts for page builder integration
     * 
     * This method automatically adds council image URLs as custom fields to every page and post,
     * making them accessible to page builders through WordPress custom fields.
     * 
     * Custom fields added:
     * - council_hero_image_url: URL of the hero image (full size)
     * - council_logo_url: URL of the council logo (full size)
     * 
     * Page builders can access these fields using their custom field/dynamic data features:
     * - Elementor: Dynamic Tags > Post > Custom Field
     * - Beaver Builder: Field Connections > Custom Field
     * - Divi: Dynamic Content > Post Custom Field
     * - Gutenberg: Block bindings or custom field blocks
     * 
     * @since 1.9.0
     * @return void
     */
    public function add_custom_fields_to_posts() {
        // Only run on singular posts and pages (not archives)
        if ( ! is_singular() ) {
            return;
        }
        
        global $post;
        
        // Ensure we have a valid post object
        if ( ! $post || ! isset( $post->ID ) ) {
            return;
        }
        
        // Get hero image URL (full size)
        $hero_url = self::get_hero_image_url( 'full' );
        if ( ! empty( $hero_url ) ) {
            update_post_meta( $post->ID, 'council_hero_image_url', $hero_url );
        } else {
            // Delete the custom field if no hero image is set
            delete_post_meta( $post->ID, 'council_hero_image_url' );
        }
        
        // Get logo URL (full size)
        $logo_url = self::get_council_logo_url( 'full' );
        if ( ! empty( $logo_url ) ) {
            update_post_meta( $post->ID, 'council_logo_url', $logo_url );
        } else {
            // Delete the custom field if no logo is set
            delete_post_meta( $post->ID, 'council_logo_url' );
        }
    }
    
    /**
     * Filter post metadata to provide custom fields dynamically
     * 
     * This filter intercepts requests for our custom field meta keys and provides
     * the values dynamically, ensuring they're always available even if not yet
     * written to the database. This solves timing issues with page builders that
     * may request custom fields before the add_custom_fields_to_posts() method runs.
     * 
     * @since 1.9.1
     * 
     * @param mixed  $value     The value to return. Default null.
     * @param int    $object_id Post ID.
     * @param string $meta_key  Meta key being requested.
     * @param bool   $single    Whether to return a single value.
     * @return mixed The filtered meta value.
     */
    public function filter_post_metadata( $value, $object_id, $meta_key, $single ) {
        // Only intercept our specific custom fields
        if ( 'council_hero_image_url' !== $meta_key && 'council_logo_url' !== $meta_key ) {
            return $value;
        }
        
        // If value is already set (from database), return it
        if ( null !== $value ) {
            return $value;
        }
        
        // Dynamically generate the value
        if ( 'council_hero_image_url' === $meta_key ) {
            $generated_value = self::get_hero_image_url( 'full' );
        } else {
            $generated_value = self::get_council_logo_url( 'full' );
        }
        
        // Return empty string if no value, not null
        if ( empty( $generated_value ) ) {
            return $single ? '' : array( '' );
        }
        
        // Return the value in the format WordPress expects
        if ( $single ) {
            return $generated_value;
        } else {
            return array( $generated_value );
        }
    }
}

// Initialize the plugin
add_action( 'plugins_loaded', array( 'Council_Controller', 'get_instance' ) );
