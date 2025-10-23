<?php
/**
 * Plugin Name: Shortcode for MobilizeAmerica API
 * Description: Displays events from Mobilize America on your WordPress site.
 * Version:     1.0.16
 * Author:      South Florida Web Advisors
 * Author URI:  https://sflwa.net
 * License: GPLv2 or later
 * Requires at least: 6.7
 * Tested up to: 6.8
 * Stable tag: 1.0.16
 * Text Domain: shortcode-for-mobilizeamerica-api
 */

// Prevent direct access to the plugin file.
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once( plugin_dir_path( __FILE__ ) . 'sflwa-notice-handler.php' ); 

/**
 * Mobilize America API Class.
 * Handles communication with the Mobilize America API.
 */
class Mobilize_America_API {

    private $api_url = 'https://api.mobilize.us/v1/';
    private $organization_id = '';

    /**
     * Constructor.
     */
    public function __construct($organization_id) {
        $this->organization_id = $organization_id;
    }

    /**
     * Fetch events from the Mobilize America API.
     *
     * @param array $args Array of arguments for the API request.
     * See https://www.mobilize.us/api/docs/#events-list for available parameters.
     * @return array|WP_Error Array of events on success, WP_Error on failure.
     */
    public function scfmaapi_get_events( $args = array() ) {
        // Base URL always includes organization ID from constructor
        $url = $this->api_url . 'organizations/' . intval($this->organization_id) . '/events';

        // Initialize query_args with only the parameters that should be in the URL
        $query_args = array();

        // Check if 'organization_only' is set to 'yes' and add organization_id as a query parameter
        if ( isset( $args['organization_only'] ) && $args['organization_only'] === 'yes' ) {
            $query_args['organization_id'] = $this->organization_id; // Add organization_id as query param
        }

        // Add other relevant arguments to query_args, excluding those already handled or not API parameters
        foreach ( $args as $key => $value ) {
            if ( $key !== 'organization_only' && $key !== 'organization_id' ) { // Ensure organization_only and organization_id are not re-added
                $query_args[ $key ] = $value;
            }
        }

        // Add the filtered query arguments to the URL
        $url = add_query_arg( $query_args, $url );

        // Make the API request.
        $response = wp_remote_get(
            $url,
            array(
                'timeout' => 15, // Set a timeout.
            )
        );

        // Check for a successful response.
        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'api_error', __( 'Error: Unable to connect to the Mobilize America API.', 'shortcode-for-mobilizeamerica-api' ), $response->get_error_message() );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        // Check the response code.
        $response_code = wp_remote_retrieve_response_code( $response );
        if ( $response_code != 200 ) {
            if (isset($data['errors']) && is_array($data['errors'])) {
                $error_message = implode(", ", $data['errors']); // Combine errors into a single string
            } else {
        /* translators: 1: Error code returned from API. */    
                $error_message = sprintf( __( 'Mobilize America API returned an error: %d', 'shortcode-for-mobilizeamerica-api' ), $response_code );
            }
            return new WP_Error( 'api_error', $error_message, $data );
        }

        // Check if the 'data' key exists and is an array.
        if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
            return new WP_Error( 'api_error', __( 'Error: Invalid data received from the Mobilize America API.', 'shortcode-for-mobilizeamerica-api' ), $data );
        }

        return $data['data'];
    }

    /**
     * Get a single event by its ID.
     *
     * @param int $event_id The ID of the event to retrieve.
     * @return array|WP_Error The event data on success, WP_Error on failure.
     */
    public function get_event_by_id( $event_id ) {
        $url = $this->api_url . 'events/' . intval( $event_id ) . '/';

        $response = wp_remote_get( $url );

        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'api_error', __( 'Error: Unable to connect to the Mobilize America API.', 'shortcode-for-mobilizeamerica-api' ), $response->get_error_message() );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        $response_code = wp_remote_retrieve_response_code( $response );
        if ( $response_code != 200 ) {
             if (isset($data['errors']) && is_array($data['errors'])) {
                $error_message = implode(", ", $data['errors']); // Combine errors into a single string
            } else {
                /* translators: 1: Error code returned from API. */
        $error_message = sprintf( __( 'Mobilize America API returned an error: %d', 'shortcode-for-mobilizeamerica-api' ), $response_code );
            }
            return new WP_Error( 'api_error', $error_message, $data );
        }

        if ( ! is_array( $data ) ) {
            return new WP_Error( 'api_error', __( 'Error: Invalid data received from the Mobilize America API.', 'shortcode-for-mobilizeamerica-api' ), $data );
        }
        return $data;
    }
}

/**
 * Shortcode to display Mobilize America events.
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output for the events.
 */
function mobilize_america_events_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
        'organization_id'   => '', // Organization ID is now required.
        'timeslot_start'    => 'gte_now', // ISO8601 date-time string
        'timeslot_end'      => '', // ISO8601 date-time string
        'event_types'       => '', // event type(s)
        'zipcode'           => '', // zipcode
        'radius'            => '', // radius
        'limit'             => 15,    // Maximum number of events to display.
        'template'          => 'default', //Which template to use
        'event_id'          => '', //show a single event,
        'show_description'  => 'true', //show description
        'is_virtual'        => '', // Virtual Only - blank is all
        'columns'           => '3', // Number of columns for card template
        'tag_id'            => '', // Tag ID to filter events.
        'organization_only' => 'no', // 'yes' to include org ID as query param, 'no' to not
        ),
        $atts,
        'mobilize_america_events'
    );

    // Organization ID is now required.  Return an error if not provided.
    if ( empty( $atts['organization_id'] ) ) {
        return '<div class="mobilize-america-error">' . esc_html__( 'Error: organization_id attribute is required.', 'shortcode-for-mobilizeamerica-api' ) . '</div>';
    }

    $api = new Mobilize_America_API( $atts['organization_id'] ); // Pass org ID to API class.

    if( !empty( $atts['event_id'] ) ) {
        $event_data = $api->get_event_by_id( $atts['event_id'] );
         if ( is_wp_error( $event_data ) ) {
            return '<div class="mobilize-america-error">' . esc_html( $event_data->get_error_message() ) . '</div>';
        }
        $events = array($event_data); //wrap the single event in an array for consistent processing
    } else {
        
        // Check for the special 'INTEREST_FORM' case first
        if ( ! empty( $atts['event_types'] ) && strtoupper( $atts['event_types'] ) === 'INTEREST_FORM' ) {
            
            // If INTEREST_FORM, only include event_types and ignore all other filtering attributes
            $api_args = array(
                'event_types' => $atts['event_types'],
            );
            
        } else {
            // Build the arguments array for the API request for all other cases (standard events).
            $api_args = array(
                'timeslot_start'    => $atts['timeslot_start'],
                'timeslot_end'      => $atts['timeslot_end'],
                'limit'             => $atts['limit'],
                'organization_only' => $atts['organization_only'], // Pass organization_only to API class
            );

            if( !empty( $atts['event_types'] ) ) {
                $api_args['event_types'] = $atts['event_types'];
            }

            if( !empty( $atts['zipcode'] ) ) {
                $api_args['zipcode'] = $atts['zipcode'];
            }

            if( !empty( $atts['radius'] ) ) {
                $api_args['radius'] = $atts['radius'];
            }
            if( !empty( $atts['tag_id'] ) ) { 
                $api_args['tag_id'] = $atts['tag_id'];
            }
            if( !empty( $atts['is_virtual'] ) ) {
                $api_args['is_virtual'] = $atts['is_virtual'];
            }
        }
        
        // Remove empty arguments.
        $api_args = array_filter( $api_args );

        $events = $api->scfmaapi_get_events( $api_args );

        if ( is_wp_error( $events ) ) {
            return '<div class="mobilize-america-error">' . esc_html( $events->get_error_message() ) . '</div>'; 
        }
    }

    // Sort events by the start date of the first timeslot in ascending order (earliest first).
    if ( is_array( $events ) && ! empty( $events ) ) {
        usort( $events, function( $a, $b ) {
            // Safely get the start date timestamp. Assume a very distant future time (PHP_INT_MAX) 
            // if timeslots are missing to push events without a date to the end.
            $time_a = isset( $a['timeslots'][0]['start_date'] ) ? intval( $a['timeslots'][0]['start_date'] ) : PHP_INT_MAX;
            $time_b = isset( $b['timeslots'][0]['start_date'] ) ? intval( $b['timeslots'][0]['start_date'] ) : PHP_INT_MAX;
            
            if ( $time_a === $time_b ) {
                return 0;
            }
            // Ascending order: -1 means $a comes before $b.
            return ( $time_a < $time_b ) ? -1 : 1; 
        });
    }

    if ( empty( $events ) ) {
        return '<div class="mobilize-america-no-events">' . esc_html__( 'No events found.', 'shortcode-for-mobilizeamerica-api' ) . '</div>';
    }

    $output = '<div class="mobilize-america-events-wrapper columns-' . intval($atts['columns']) . '">';

    // Include CSS file.
   wp_enqueue_style( 'shortcode-for-mobilizeamerica-api-styles', plugin_dir_url( __FILE__ ) . 'css/shortcode-for-mobilizeamerica-api.css', array(), '1.0.0' );


    // Include template file
    $template_path = plugin_dir_path( __FILE__ ) . 'templates/event-templates.php';
    if ( file_exists( $template_path ) ) {
        include $template_path;
    } else {
        return '<div class="mobilize-america-error">' . esc_html__( 'Error: Template file missing.', 'shortcode-for-mobilizeamerica-api' ) . '</div>';
    }

    // MODIFIED: Pass event_types to the template function.
    $output .= mobilize_america_get_template( $events, $atts['template'], intval($atts['columns']), $atts['show_description'], $atts['event_types'] );
    $output .= '</div>'; // Close events-wrapper

    return $output;

}
add_shortcode( 'mobilize_america_events', 'mobilize_america_events_shortcode' );

// Check if Elementor is installed before hooking into it.
if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '1.0.0', '>=' ) ) {
    add_action( 'elementor/widgets/widgets_registered', 'scfmaapi_register_mobilize_america_widget' );
}

/**
 * Register the Mobilize America Events widget for Elementor.
 */
function scfmaapi_register_mobilize_america_widget() {
    // Include the widget class file.
    require_once( __DIR__ . '/includes/elementor-widget.php' );

    // Register the widget.
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \scfmaapi_Widget_Mobilize_America_Events() );
}
