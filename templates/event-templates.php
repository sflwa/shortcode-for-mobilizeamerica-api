<?php
/**
 * Mobilize America Events - Template Functions
 *
 * This file contains the functions that generate the HTML for displaying events
 * in different formats.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get the HTML for displaying events based on the specified template.
 *
 * @param array  $events  Array of event data.
 * @param string $template The template to use (default or card).
 * @param int    $columns Number of columns for card template.
 * @return string HTML output for the events.
 */
function mobilize_america_get_template( $events, $template = 'default', $columns = 3 ) {
	$output = '';
    $columns_class = 'columns-' . intval( $columns ); //sanitize

	if ( $template == 'card' ) {
		//$output .= '<div class="mobilize-america-events-wrapper ' . esc_attr( $columns_class ) . '">'; // Added the column class to the wrapper
		$output .= mobilize_america_get_card_template( $events, $columns ); // Pass $columns to the card template function
		$output .= '</div>';
	} else {
		$output .= '<div class="mobilize-america-events-wrapper">';
		$output .= mobilize_america_get_default_template( $events );
		$output .= '</div>';
	}

	return $output;
}

/**
 * Get the HTML for displaying events using the default template.
 *
 * @param array $events Array of event data.
 * @return string HTML output for the events.
 */
function mobilize_america_get_default_template( $events ) {
	$output = '';
	foreach ( $events as $event ) {
		// Format the timeslot start date and time.
        $start_time = isset($event['timeslots'][0]['start_date']) ? strtotime( $event['timeslots'][0]['start_date'] ) : false;
        $formatted_date = $start_time ? date_i18n( get_option( 'date_format' ), $start_time ) : '';
        $formatted_time = $start_time ? date_i18n( get_option( 'time_format' ), $start_time ) : '';
        $event_url = isset($event['browser_url']) ? esc_url( $event['browser_url'] ) : '#';

		$output .= '<div class="event-item">';
		$output .= '<h3 class="event-title"><a href="' . $event_url . '" target="_blank" rel="noopener noreferrer">' . esc_html( $event['title'] ) . '</a></h3>';
		$output .= '<p class="event-date">' . ($start_time ? esc_html( $formatted_date ) . ' ' . esc_html( $formatted_time ) : '') . '</p>';
		$output .= '<p class="event-location">' . esc_html( $event['location']['venue_name'] ) . ', ' . esc_html( $event['location']['locality'] ) . ', ' . esc_html( $event['location']['region'] ) . '</p>';
		if ( isset( $event['description'] ) ) {
			$output .= '<div class="event-description">' . wp_kses_post( $event['description'] ) . '</div>';
		}
		$output .= '<a href="' . $event_url . '" class="event-link" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Sign Up', 'mobilize-america-events' ) . '</a>';
		$output .= '</div>';
	}
	return $output;
}

/**
 * Get the HTML for displaying events using the card template.
 *
 * @param array $events Array of event data.
 * @param int $columns Number of columns
 * @return string HTML output for the events.
 */
function mobilize_america_get_card_template( $events, $columns ) {
	$output = '';
	foreach ( $events as $event ) {
            // Format the timeslot start date and time.
            $start_time = $event['timeslots'][0]['start_date'] ; //get first timeslot
            $formatted_date = gmdate( "D, F j, Y", $start_time  );
            $formatted_time = gmdate( "g:i a", $start_time );

            $event_url = isset($event['browser_url']) ? esc_url( $event['browser_url'] ) : '#';

            $output .= '<div class="event-card">';
            $output .= '<h3 class="event-title"><a href="' . $event_url . '" target="_blank" rel="noopener noreferrer">' . esc_html( $event['title'] ) . '</a></h3>';
            $output .= '<p class="event-date">' . esc_html( $formatted_date ) . '</p>';
            #$output .= '<p class="event-date">' . esc_html( $formatted_date ) . ' ' . esc_html( $formatted_time ) . '</p>';  
            

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
	return $output;
}
