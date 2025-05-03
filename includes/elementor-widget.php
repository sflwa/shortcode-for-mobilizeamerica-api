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
		return 'mobilizeamerica_shortcode';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Mobilize America Events', 'mobilizeamerica-shortcode' );
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
				'label' => esc_html__( 'Content', 'mobilizeamerica-shortcode' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'organization_id',
			[
				'label' => esc_html__( 'Organization ID', 'mobilizeamerica-shortcode' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'required' => true,
				'description' => esc_html__( 'Enter your Mobilize America Organization ID.', 'mobilizeamerica-shortcode' ),
			]
		);

		$this->add_control(
			'timeslot_start',
			[
				'label' => esc_html__( 'Timeslot Start', 'mobilizeamerica-shortcode' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the start date/time for events (ISO8601 format).', 'mobilizeamerica-shortcode' ),
			]
		);

		$this->add_control(
			'timeslot_end',
			[
				'label' => esc_html__( 'Timeslot End', 'mobilizeamerica-shortcode' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the end date/time for events (ISO8601 format).', 'mobilizeamerica-shortcode' ),
			]
		);

        $this->add_control(
			'event_type',
			[
				'label' => esc_html__( 'Event Type', 'mobilizeamerica-shortcode' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the event type.', 'mobilizeamerica-shortcode' ),
			]
		);

        $this->add_control(
			'zipcode',
			[
				'label' => esc_html__( 'Zipcode', 'mobilizeamerica-shortcode' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the zipcode.', 'mobilizeamerica-shortcode' ),
			]
		);

        $this->add_control(
			'radius',
			[
				'label' => esc_html__( 'Radius', 'mobilizeamerica-shortcode' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter the radius.', 'mobilizeamerica-shortcode' ),
			]
		);

		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Limit', 'mobilizeamerica-shortcode' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'description' => esc_html__( 'Maximum number of events to display.', 'mobilizeamerica-shortcode' ),
			]
		);

		$this->add_control(
			'template',
			[
				'label' => esc_html__( 'Template', 'mobilizeamerica-shortcode' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'mobilizeamerica-shortcode' ),
					'card' => esc_html__( 'Card', 'mobilizeamerica-shortcode' ),
				],
				'description' => esc_html__( 'Choose a template for displaying events.', 'mobilizeamerica-shortcode' ),
			]
		);

        $this->add_control(
            'event_id',
            [
                'label' => esc_html__( 'Event ID', 'mobilizeamerica-shortcode' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__( 'Enter a specific Event ID to display only that event.', 'mobilizeamerica-shortcode' ),
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label' => esc_html__( 'Show Description', 'mobilizeamerica-shortcode' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'your-text-domain' ),
                'label_off' => esc_html__( 'No', 'your-text-domain' ),
                'default' => 'true',
                'description' => esc_html__( 'Show the event description.', 'mobilizeamerica-shortcode' ),
            ]
        );

		$this->end_controls_section();

        // Style Tab
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Style', 'mobilizeamerica-shortcode' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'mobilizeamerica-shortcode' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .event-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => esc_html__( 'Date Color', 'mobilizeamerica-shortcode' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .event-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'location_color',
            [
                'label' => esc_html__( 'Location Color', 'mobilizeamerica-shortcode' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .event-location' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'mobilizeamerica-shortcode' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .event-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__( 'Link Color', 'mobilizeamerica-shortcode' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .event-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => esc_html__( 'Link Hover Color', 'mobilizeamerica-shortcode' ),
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

		$shortcode_atts = [
			'organization_id' => $settings['organization_id'],
			'timeslot_start'  => $settings['timeslot_start'],
			'timeslot_end'    => $settings['timeslot_end'],
            'event_type'      => $settings['event_type'],
            'zipcode'         => $settings['zipcode'],
            'radius'          => $settings['radius'],
			'limit'           => $settings['limit'],
			'template'        => $settings['template'],
            'event_id'        => $settings['event_id'],
            'show_description' => $settings['show_description']
		];

		$shortcode = shortcode_atts( 'mobilize_america_events', $shortcode_atts, 'mobilize_america_events' );

		echo do_shortcode( $shortcode );
	}
}
