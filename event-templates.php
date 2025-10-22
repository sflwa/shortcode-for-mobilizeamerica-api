<?php
/**
 * Shortcode for Mobilize America API - Template Functions
 *
 * This file contains the functions that generate the HTML for displaying events
 * in different formats.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'mobilize_america_get_template' ) ) {
	/**
	 * Get the HTML for displaying events based on the specified template.
	 *
	 * @param array  $events  Array of event data.
	 * @param string $template The template to use (default or card).
	 * @param int    $columns Number of columns for card template.
	 * @param bool   $show_description
	 * @return string HTML output for the events.
	 */
	function mobilize_america_get_template( $events, $template = 'default', $columns = 3, $show_description = true ) {
		$output = '';
		$columns_class = 'columns-' . intval( $columns ); //sanitize

		if ( $template == 'card' ) {
			$output .= mobilize_america_get_card_template( $events, $columns, $show_description ); // Pass $columns to the card template function
		
		} else {
			$output .= '<div class="mobilize-america-events-wrapper">';
			$output .= mobilize_america_get_default_template( $events, $show_description );
			$output .= '</div>';
		}
			

		return $output;
	}
}

if ( ! function_exists( 'mobilize_america_get_default_template' ) ) {
	/**
	 * Get the HTML for displaying events using the default template.
	 *
	 * @param array $events Array of event data.
	 * @param bool $show_description
	 * @return string HTML output for the events.
	 */
function mobilize_america_get_default_template( $events, $show_description ) {
	$output = '';
	foreach ( $events as $event ) {
		// Format the timeslot start date and time.
        $event_start_datetime = New DateTime("@".$event['timeslots'][0]['start_date']);
		$event_start_datetime->setTimeZone(wp_timezone());
	    $event_end_datetime = New DateTime("@".$event['timeslots'][0]['end_date']);
		$event_end_datetime->setTimeZone(wp_timezone());
		$event_duration = strtotime($event_end_datetime->format('m/d/Y g:i A')) - strtotime($event_start_datetime->format('m/d/Y g:i A'));


		$event_url = isset($event['browser_url']) ? esc_url( $event['browser_url'] ) : '#';

		$output .= '<div class="event-item">';
		$output .= '<h3 class="event-title"><a href="' . $event_url . '" target="_blank" rel="noopener noreferrer">' . esc_html( $event['title'] ) . '</a></h3>';

  if ($event_duration == 86340) {$output .= '<p class="event-date">All Day Event</p>';}
     else{

		$output .= '<p class="event-date">' . $event_start_datetime->format('m/d/Y g:i A') . ' - '.$event_end_datetime->format('g:i A')  . '</p>';
		}
      
		$output .= '<p class="event-location">';
            if ($event['is_virtual']) {
                $output .= esc_html__( 'Online Event', 'shortcode-for-mobilizeamerica-api' );
            } else {
                 $output .= esc_html( $event['location']['venue'] ) .  '<br />' . esc_html( $event['location']['address_lines'][0] ) . '<br />' .   esc_html( $event['location']['locality'] ) . ', ' . esc_html( $event['location']['region'] );
            }
		$output .='</p>';
		
		
		
		
		#$output .= '<p class="event-location">' . esc_html( $event['location']['venue'] ) . '<br />' .  esc_html( $event['location']['address_lines'][0] ) . '<br />' .  esc_html( $event['location']['locality'] ) . ', ' . esc_html( $event['location']['region'] ) . '</p>';
		  if ( $show_description === true && isset( $event['description'] ) ) {
			$output .= '<div class="event-description">' . wp_kses_post( $event['description'] ) . '</div>';
		}
		$output .= '<a href="' . $event_url . '" class="event-link" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Sign Up', 'shortcode-for-mobilizeamerica-api' ) . '</a>';
		$output .= '</div>';
	}
	return $output;
}
}

if ( ! function_exists( 'mobilize_america_get_card_template' ) ) {
	/**
	 * Get the HTML for displaying events using the card template.
	 *
	 * @param array $events Array of event data.
	 * @param int $columns Number of columns
	 * @param bool $show_description
	 * @return string HTML output for the events.
	 */
function mobilize_america_get_card_template( $events, $columns, $show_description ) {
	$output = '';
	foreach ( $events as $event ) {
            // Format the timeslot start date and time.
            $event_start_datetime = New DateTime("@".$event['timeslots'][0]['start_date']);
		$event_start_datetime->setTimeZone(wp_timezone());
	    $event_end_datetime = New DateTime("@".$event['timeslots'][0]['end_date']);
		$event_end_datetime->setTimeZone(wp_timezone());
		$event_duration = strtotime($event_end_datetime->format('m/d/Y g:i A')) - strtotime($event_start_datetime->format('m/d/Y g:i A'));


            $event_url = isset($event['browser_url']) ? esc_url( $event['browser_url'] ) : '#';

            $output .= '<div class="event-card">';
            $output .= '<h3 class="event-title"><a href="' . $event_url . '" target="_blank" rel="noopener noreferrer">' . esc_html( $event['title'] ) . '</a></h3>';
           if ($event_duration == 86340) {$output .= '<p class="event-date">All Day Event</p>';}
     else{

		$output .= '<p class="event-date">' . $event_start_datetime->format('m/d/Y g:i A') . ' - '.$event_end_datetime->format('g:i A')  . '</p>';
		}

            if (isset($event['featured_image_url']) && !empty($event['featured_image_url'])) {
                 $output .= '<div class="event-image">';
                 $output .= '<img src="' . esc_url( $event['featured_image_url'] ) . '" alt="' . esc_attr( $event['title'] ) . '">';
                 $output .= '</div>';
            }

         if ( $show_description === true && isset( $event['description'] ) ) {
				$output .= '<div class="event-description">' . wp_kses_post( $event['description'] ) . '</div>';
			}


           $output .= '<p class="event-location">';
            if ($event['is_virtual']) {
                $output .= esc_html__( 'Online Event', 'shortcode-for-mobilizeamerica-api' );
            } else {
                 $output .= esc_html( $event['location']['venue'] ) .  '<br />' . esc_html( $event['location']['address_lines'][0] ) . '<br />'  . esc_html( $event['location']['locality'] ) . ', ' . esc_html( $event['location']['region'] );
            }
             $output .='</p>';

            $output .= '<p><strong>Sponsor:</strong> <a href="' . $event['sponsor']['event_feed_url'] . '" target="_blank" style="text-decoration:none;">' . $event['sponsor']['name'] . '</a></p>';

          
            $output .= '<a href="' . $event_url . '" class="event-link" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Click here for more information', 'shortcode-for-mobilizeamerica-api' ) . '</a>';
            
			
			
			
			
			$output .= '</div>'; // Close event-card
        }
	return $output;
}
}