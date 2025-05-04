=== MobilizeAmerica Shortcode ===
Contributors: sflwa
Tags: shortcode, api
Requires at least: 6.7
Tested up to: 6.8
Stable tag: 1.0.5
Requires PHP: 8.0
License: GPLv2 or later
WordPress Plugin to display events from Mobilize.us with a shortcode. This plugin was generated using Google Gemini.


The Mobilize America Events widget will display a list of events from Mobilize America, styled according to your chosen template (either the default or card template) and customized with the style settings you've applied in the Elementor editor.
Here's a breakdown of what you'll see:
Event List: The widget will generate a list of events, with each event typically including the following information:
Title: The title of the event, which will also be a link to the event page on Mobilize America.
Date and Time: The date and time of the event, formatted according to your WordPress site's settings.
Location: The venue name, city, and region of the event.
Description: (Optional) The event description, if you've enabled this option in the widget settings.
Sign Up Link: A link to the Mobilize America event page where users can sign up.
Featured Image: (Optional) If the event has a featured image, it will be displayed.
Styling: The widget's output will be styled according to the settings you configure in the Elementor editor. This includes:
Title Color
Date Color
Location Color
Description Color
Link Color
Link Hover Color
Templates: The widget offers two templates for displaying events:
Default Template: This template displays events in a list format, with the title, date, location, and description (if enabled) displayed in a vertical arrangement.
Card Template: This template displays events as individual cards, with the title, date, featured image (if available), description (if enabled), location, and sign-up link contained within a styled card.
Example Output
Here's an example of what the widget output might look like, depending on the chosen template and style settings:
Default Template Example
Image of Mobilize America Events Widget output in Elementor using the default template
Card Template Example
Image of Mobilize America Events Widget output in Elementor using the card template
Important Notes:
The actual appearance of the widget will depend on your specific Elementor settings and the styling of your WordPress theme.
If no events are found, the widget will display the message "No events found."
If there is an error connecting to the Mobilize America API, the widget will display an error message.












## Shortcode Attributes
* is_virtual
  * True = Only show virtual events
  * False = Do not show virtual events
  * Blank / Not set = Show all events   

## To Dos
* Adjust CSS for Default Template vs Card Template
* Add / Fix Timezone offset
* Truncate Description for Card Template
* Document Shortcode Varibles & Reference options
* Fix Elementor Widget Style settings 


## Change Log

2025-05-03 v1.0.5
* Moved Template Code to separate file
* Added column feature to card template

2025-05-03 v1.0.4
* Added Elementor Widget

2025-05-01 v1.0.3
* WordPress Plugin Header Standards

2025-05-01 v1.0.2
* Update name & files to match WordPress Standards

2025-05-01 v1.0.1
* Added "is_virutal" to Shortcode

2025-04-28 - v1.0.0 - Intial Release
