<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Widget for Mobilize America Events.
 */
class Widget_Mobilize_America_Events extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mobilize_america_events';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Mobilize America Events', 'mobilize-america-events' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-calendar'; // You can use any Elementor icon class.
	}

	/**
	 * Get widget categories.
	 *
	 * @return array List of categories the widget belongs to.
	 */
	public function get_categories() {
		return [ 'basic' ]; // Or any other category you prefer.
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array List of keywords the widget belongs to.
	 */
	public function get_keywords() {
		return [ 'mobilize', 'events', 'america', 'shortcode' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to customize the widget settings.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'mobilize-america-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'organization_id',
			[
				'label' => esc_html__( 'Organization ID', 'mobilize-america-events' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'required' => true,
				'description' => esc_html__( 'Enter your Mobilize America Organization ID.', 'mobilize-america-events' ),
			]
		);

		$this->add_control(
			'timeslot_start',
			[
				'label' => esc_html__( 'Timeslot Start', 'mobilize-america-events' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the start date/time for events (ISO8601 format).', 'mobilize-america-events' ),
			]
		);

		$this->add_control(
			'timeslot_end',
			[
				'label' => esc_html__( 'Timeslot End', 'mobilize-america-events' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the end date/time for events (ISO8601 format).', 'mobilize-america-events' ),
			]
		);

        $this->add_control(
			'event_type',
			[
				'label' => esc_html__( 'Event Type', 'mobilize-america-events' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the event type.', 'mobilize-america-events' ),
			]
		);

        $this->add_control(
			'zipcode',
			[
				'label' => esc_html__( 'Zipcode', 'mobilize-america-events' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the zipcode.', 'mobilize-america-events' ),
			]
		);

        $this->add_control(
			'radius',
			[
				'label' => esc_html__( 'Radius', 'mobilize-america-events' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the radius.', 'mobilize-america-events' ),
			]
		);

		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Limit', 'mobilize-america-events' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'description' => esc_html__( 'Maximum number of events to display.', 'mobilize-america-events' ),
			]
		);

		$this->add_control(
			'template',
			[
				'label' => esc_html__( 'Template', 'mobilize-america-events' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'mobilize-america-events' ),
					'card' => esc_html__( 'Card', 'mobilize-america-events' ),
				],
				'description' => esc_html__( 'Choose a template for displaying events.', 'mobilize-america-events' ),
			]
		);

        $this->add_control(
            'event_id',
            [
                'label' => esc_html__( 'Event ID', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__( 'Enter a specific Event ID to display only that event.', 'mobilize-america-events' ),
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label' => esc_html__( 'Show Description', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'your-text-domain' ),
                'label_off' => esc_html__( 'No', 'your-text-domain' ),
                'default' => 'true',
                'description' => esc_html__( 'Show the event description.', 'mobilize-america-events' ),
            ]
        );

        $this->add_control(
            'is_virtual',
            [
                'label' => esc_html__( 'Virtual Events', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'All Events', 'mobilize-america-events' ),
                    'true' => esc_html__( 'Virtual Only', 'mobilize-america-events' ),
                    'false' => esc_html__( 'In-Person Only', 'mobilize-america-events' ),
                ],
                'description' => esc_html__( 'Show only virtual, in-person, or all events.', 'mobilize-america-events' ),
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => esc_html__( '1', 'mobilize-america-events' ),
                    '2' => esc_html__( '2', 'mobilize-america-events' ),
                    '3' => esc_html__( '3', 'mobilize-america-events' ),
                    '4' => esc_html__( '4', 'mobilize-america-events' ),
                ],
                'description' => esc_html__( 'Number of columns for the card template.', 'mobilize-america-events' ),
                'condition' => [
                    'template' => 'card',
                ],
            ]
        );

		$this->end_controls_section();

        // Title Style
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__( 'Title Style', 'mobilize-america-events' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Title Typography', 'mobilize-america-events' ),
                'selector' => '{{WRAPPER}}  .event-title a',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .event-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

         $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_background',
                'label' => esc_html__( 'Title Background', 'mobilize-america-events' ),
                'types' => [ 'classic', 'gradient' ],
                'selectors' => [
                    '{{WRAPPER}} .event-title a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_padding',
            [
                'label' => esc_html__( 'Title Padding', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .event-title a' => 'padding: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'event_card_style_section',
            [
                'label' => esc_html__( 'Event Card Style', 'mobilize-america-events' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'template' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'label' => esc_html__( 'Card Background', 'mobilize-america-events' ),
                'types' => [ 'classic', 'gradient' ],
                'selectors' => [
                    '{{WRAPPER}} .event-card' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_padding',
            [
                'label' => esc_html__( 'Card Padding', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .event-card' => 'padding: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'event_item_style_section',
            [
                'label' => esc_html__( 'Event Item Style', 'mobilize-america-events' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'template' => 'default',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'label' => esc_html__( 'Item Background', 'mobilize-america-events' ),
                'types' => [ 'classic', 'gradient' ],
                'selectors' => [
                    '{{WRAPPER}} .event-item' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_padding',
            [
                'label' => esc_html__( 'Item Padding', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .event-item' => 'padding: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'text_style_section',
            [
                'label' => esc_html__( 'Text Style', 'mobilize-america-events' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => esc_html__( 'Date Color', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .event-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'location_color',
            [
                'label' => esc_html__( 'Location Color', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .event-location' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .event-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__( 'Link Color', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .event-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => esc_html__( 'Link Hover Color', 'mobilize-america-events' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .event-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @return string
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['organization_id'] ) ) {
			echo '<div class="mobilize-america-error">' . esc_html__( 'Error: Organization ID is required.', 'mobilize-america-events' ) . '</div>';
			return;
		}

		$shortcode_atts = [
			'organization_id' => $settings['organization_id'],
		];

        if (!empty($settings['timeslot_start'])) {
            $shortcode_atts['timeslot_start'] = $settings['timeslot_start'];
        }
        if (!empty($settings['timeslot_end'])) {
            $shortcode_atts['timeslot_end'] = $settings['timeslot_end'];
        }
        if (!empty($settings['event_type'])) {
            $shortcode_atts['event_type'] = $settings['event_type'];
        }
        if (!empty($settings['zipcode'])) {
            $shortcode_atts['zipcode'] = $settings['zipcode'];
        }
        if (!empty($settings['radius'])) {
            $shortcode_atts['radius'] = $settings['radius'];
        }
        if (!empty($settings['limit'])) {
             $shortcode_atts['limit'] = $settings['limit'];
        }

        if (!empty($settings['template'])) {
             $shortcode_atts['template'] = $settings['template'];
        }
        if (!empty($settings['event_id'])) {
             $shortcode_atts['event_id'] = $settings['event_id'];
        }
        if (!empty($settings['show_description'])) {
             $shortcode_atts['show_description'] = $settings['show_description'];
        }
        if (!empty($settings['is_virtual'])) {
             $shortcode_atts['is_virtual'] = $settings['is_virtual'];
        }
        if (!empty($settings['columns'])) {
            $shortcode_atts['columns'] = $settings['columns'];
        }

		try {
			$shortcode_str = '[mobilize_america_events ';
            $shortcode_str .= 'organization_id="' . $settings['organization_id'] . '" ';
            if (!empty($settings['timeslot_start'])) {
                $shortcode_str .= ' timeslot_start="' . $settings['timeslot_start'] . '" ';
            }
            if (!empty($settings['timeslot_end'])) {
                $shortcode_str .= ' timeslot_end="' . $settings['timeslot_end'] . '" ';
            }
             if (!empty($settings['template'])) {
                $shortcode_str .= ' template="' . $settings['template'] . '" ';
            }
            if (!empty($settings['show_description'])) {
                 $shortcode_str .= ' show_description="' . $settings['show_description'] . '" ';
            }
            if (!empty($settings['is_virtual'])) {
                 $shortcode_str .= ' is_virtual="' . $settings['is_virtual'] . '" ';
            }
            if (!empty($settings['columns'])) {
                 $shortcode_str .= ' columns="' . $settings['columns'] . '" ';
            }
            $shortcode_str .= ']';

			if ( ! is_string( $shortcode_str ) ) {
				$shortcode_str = '';
			}
			$output = do_shortcode( $shortcode_str );
            if (empty($output)) {
                echo '<div class="mobilize-america-error">Shortcode output is empty.  Please check your settings.</div>';
                 error_log("Elementor Widget: Shortcode output is empty.  Settings: " . print_r($shortcode_atts, true) . ". Shortcode String: " . $shortcode_str . ". Output: " . print_r($output, true));
            }
            else
            {
                echo $output;
            }

		} catch (Exception $e) {
			echo '<div class="mobilize-america-error">' . esc_html__( 'Error: Shortcode processing failed.', 'mobilize-america-events' ) . '</div>';
            error_log("Elementor Widget Render Error: " . $e->getMessage() . ".  Settings: " . print_r($shortcode_atts, true));
			return;
		}
	}
}

