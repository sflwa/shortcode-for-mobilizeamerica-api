<?php
/**
 * Plugin Name: MobilizeAmerica Shortcode
 * Plugin URI:  https://github.com/sflwa/mobilizeamerica-shortcode/
 * Description: Displays events from Mobilize America on your WordPress site.
 * Version:     1.0.4
 * Author:      South Florida Web Advisors
 * Author URI:  https://sflwa.net
 * License: GPLv2 or later
 * Requires at least: 6.7
 * Tested up to: 6.8.1
 * Stable tag: 1.0.4
 * Text Domain: mobilizeamerica-shortcode

 */

// Prevent direct access to the plugin file.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Load plugin text domain for internationalization.
function mobilize_america_load_textdomain() {
	load_plugin_textdomain(
		'mobilizeamerica-shortcode',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'init', 'mobilize_america_load_textdomain' );

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
	public function get_events( $args = array() ) {
        $url = $this->api_url . 'organizations/' . $this->organization_id . '/events'; // Include org ID in URL

		// Build the query string from the arguments.
		$query_args = array();
        if (!empty($args)) {
            $query_args = $args;
        }

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
			return new WP_Error( 'api_error', __( 'Error: Unable to connect to the Mobilize America API.', 'mobilizeamerica-shortcode' ), $response->get_error_message() );
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
                $error_message = sprintf( __( 'Mobilize America API returned an error: %d', 'mobilizeamerica-shortcode' ), $response_code );
            }
			return new WP_Error( 'api_error', $error_message, $data );
		}

		// Check if the 'data' key exists and is an array.
		if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
			return new WP_Error( 'api_error', __( 'Error: Invalid data received from the Mobilize America API.', 'mobilizeamerica-shortcode' ), $data );
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
			return new WP_Error( 'api_error', __( 'Error: Unable to connect to the Mobilize America API.', 'mobilizeamerica-shortcode' ), $response->get_error_message() );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		$response_code = wp_remote_retrieve_response_code( $response );
		if ( $response_code != 200 ) {
             if (isset($data['errors']) && is_array($data['errors'])) {
                $error_message = implode(", ", $data['errors']); // Combine errors into a single string
            } else {
                /* translators: 1: Error code returned from API. */
		$error_message = sprintf( __( 'Mobilize America API returned an error: %d', 'mobilizeamerica-shortcode' ), $response_code );
            }
			return new WP_Error( 'api_error', $error_message, $data );
		}

		if ( ! is_array( $data ) ) {
			return new WP_Error( 'api_error', __( 'Error: Invalid data received from the Mobilize America API.', 'mobilizeamerica-shortcode' ), $data );
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
		'organization_id' => '', // Organization ID is now required.
		'timeslot_start'  => '', // ISO8601 date-time string
		'timeslot_end'    => '', // ISO8601 date-time string
        	'event_type'      => '', // event type
            	'zipcode'         => '', // zipcode
            	'radius'          => '', // radius
		'limit'           => 15,    // Maximum number of events to display.
		'template'        => 'default', //Which template to use
            	'event_id'        => '', //show a single event,
            	'show_description' => 'true', //show description
		'is_virtual' 	  => '', // Virtual Only - blank is all
		),
		$atts,
		'mobilize_america_events'
	);

    // Organization ID is now required.  Return an error if not provided.
    if ( empty( $atts['organization_id'] ) ) {
        return '<div class="mobilize-america-error">' . esc_html__( 'Error: organization_id attribute is required.', 'mobilizeamerica-shortcode' ) . '</div>';
    }

	$api = new Mobilize_America_API( $atts['organization_id'] ); // Pass org ID to API class.

    if( !empty( $atts['event_id'] ) ) {
        $event_data = $api->get_event_by_id( $atts['event_id'] );
         if ( is_wp_error( $event_data ) ) {
            return '<div class="mobilize-america-error">' . esc_html( $event_data->get_error_message() ) . '</div>';
        }
        $events = array($event_data); //wrap the single event in an array for consistent processing
    } else {

        // Build the arguments array for the API request.
        $api_args = array(
            //'organization_id' => $atts['organization_id'], // No longer needed in args.
            'timeslot_start'  => $atts['timeslot_start'],
            'timeslot_end'    => $atts['timeslot_end'],
            'limit'           => $atts['limit'],
        );

        if( !empty( $atts['event_type'] ) ) {
            $api_args['event_type'] = $atts['event_type'];
        }

        if( !empty( $atts['zipcode'] ) ) {
             $api_args['zipcode'] = $atts['zipcode'];
        }

        if( !empty( $atts['radius'] ) ) {
             $api_args['radius'] = $atts['radius'];
        }

        if( !empty( $atts['is_virtual'] ) ) {
             $api_args['is_virtual'] = $atts['is_virtual'];
        }    

	    
        // Remove empty arguments.
        $api_args = array_filter( $api_args );

        $events = $api->get_events( $api_args );

        if ( is_wp_error( $events ) ) {
            return '<div class="mobilize-america-error">' . esc_html( $events->get_error_message() ) . '</div>'; 
        }
    }


	if ( empty( $events ) ) {
		return '<div class="mobilize-america-no-events">' . esc_html__( 'No events found.', 'mobilizeamerica-shortcode' ) . '</div>';
	}

	$output = '<div class="mobilizeamerica-shortcode-wrapper">';

    // Include CSS file.
    wp_enqueue_style( 'mobilizeamerica-shortcode-styles', plugin_dir_url( __FILE__ ) . 'css/mobilizeamerica-shortcode.css', array(), '1.0.0' );


    if ($atts['template'] == 'card') {
        foreach ( $events as $event ) {
            // Format the timeslot start date and time.
            $start_time = $event['timeslots'][0]['start_date'] ; //get first timeslot
            $formatted_date = gmdate( "D, F j, Y", $start_time  );
            $formatted_time = gmdate( "g:i a", $start_time );

            $event_url = isset($event['browser_url']) ? esc_url( $event['browser_url'] ) : '#';

            $output .= '<div class="event-card">';
            $output .= '<h3 class="event-title"><a href="' . $event_url . '" target="_blank" rel="noopener noreferrer">' . esc_html( $event['title'] ) . '</a></h3>';
            $output .= '<p class="event-date">' . esc_html( $formatted_date ) . '</p>';
         // $output .= '<p class="event-date">' . esc_html( $formatted_date ) . ' ' . esc_html( $formatted_time ) . '</p>';  
            

            if (isset($event['featured_image_url']) && !empty($event['featured_image_url'])) {
                 $output .= '<div class="event-image">';
                 $output .= '<img src="' . esc_url( $event['featured_image_url'] ) . '" alt="' . esc_attr( $event['title'] ) . '">';
                 $output .= '</div>';
            }

            if($atts['show_description'] == 'true'){
                $output .= '<div class="event-description">' . wp_kses_post( $event['description'] ) . '</div>';
            }

          //  $output .= '<p class="event-location">' . esc_html( $event['location']['venue_name'] ) . ', ' . esc_html( $event['location']['locality'] ) . ', ' . esc_html( $event['location']['region'] ) . '</p>';
            $output .= '<a href="' . $event_url . '" class="event-link" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Click here for more information', 'mobilizeamerica-shortcode' ) . '</a>';
            $output .= '</div>'; // Close event-card
        }
    }
    else { //default template
        foreach ( $events as $event ) {
            // Format the timeslot start date and time.
            $start_time = strtotime( $event['timeslots'][0]['start_date'] );  //get first timeslot.
            $formatted_date = date_i18n( get_option( 'date_format' ), $start_time );
            $formatted_time = date_i18n( get_option( 'time_format' ), $start_time );

             $event_url = isset($event['browser_url']) ? esc_url( $event['browser_url'] ) : '#';

            $output .= '<div class="event-item">';
            $output .= '<h3 class="event-title"><a href="' . $event_url . '" target="_blank" rel="noopener noreferrer">' . esc_html( $event['title'] ) . '</a></h3>';
            $output .= '<p class="event-date">' . esc_html( $formatted_date ) . '</p>';
         // $output .= '<p class="event-date">' . esc_html( $formatted_date ) . ' ' . esc_html( $formatted_time ) . '</p>';  
            
            $output .= '<p class="event-location">' . esc_html( $event['location']['venue_name'] ) . ', ' . esc_html( $event['locality'] ) . ', ' . esc_html( $event['location']['region'] ) . '</p>';
            if($atts['show_description'] == 'true'){
                 $output .= '<div class="event-description">' . wp_kses_post( $event['description'] ) . '</div>';
            }
            $output .= '<a href="' . $event_url . '" class="event-link" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Sign Up', 'mobilizeamerica-shortcode' ) . '</a>';
            $output .= '</div>';
        }
    }
	$output .= '</div>'; // Close events-wrapper

	return $output;
}
add_shortcode( 'mobilize_america_events', 'mobilize_america_events_shortcode' );


// Check if Elementor is installed before hooking into it.
if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '1.0.0', '>=' ) ) {
	add_action( 'elementor/init', 'register_mobilizeamerica_shortcode_widget' );
}

/**
 * Register the Mobilize America Events widget for Elementor.
 */
function register_mobilize_america_widget() {
	// Include the widget class file.
	require_once( __DIR__ . '/includes/elementor-widget.php' );

	// Register the widget.
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_MobilizeAmerica_Shortcode() );
}

