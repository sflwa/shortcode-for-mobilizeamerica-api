=== Shortcode for MobilizeAmerica API ===
Contributors: sflwa
Tags: shortcode, api
Requires at least: 6.7
Tested up to: 6.8
Stable tag: 1.0.9
Requires PHP: 8.0
License: GPLv2 or later
A shortcode to display events from Mobilize.us with and an Elementor Widget.

== External Services ==
This plugin connects to MobilizeAmerica's API to pull in events for a specific organization.
The shortcode / elementor widget passes a specificed organization id, a start date and a variable about virtual events.
This service is provided by MobilizeAmerica - https://api.mobilize.us
API Documentation - https://github.com/mobilizeamerica/api
Terms of Use (https://join.mobilize.us/terms-of-service/)
Privacy Policy (https://join.mobilize.us/privacy-policy/)

## Description 
The Mobilize America Events shortcode / widget will display a list of events from Mobilize America.
There are 2 layouts - Card & Default (List).
The elementor widget allows you to override the default styles.

## Instructions for Shortcode for MobilizeAmerica API
This shortcode allows you to display events from Mobilize America on your WordPress site.
The Element widget outputs the code for the shortcode so you do not have to build it manually

Shortcode: [mobilize_america_events]

Attributes
The shortcode accepts the following attributes:
* organization_id (required): The ID of the organization whose events you want to display. You can find this ID on Mobilize America. https://www.mobilize.us/dashboard/YOUR-ORGANIZATION/settings/
* timeslot_start: (optional):  Suggested: gte_now which is greater than now - Unix timestamp to filter events.  Only shows events withtimeslots starting after this date.
* timeslot_end: (optional): Unix timestamp to filter events. Only shows events with timeslots starting before this date.
Note: For timeslot please use comparison text -  The comparison operators are ≥ gte, > gt, ≤ lte, < lt
* event_type: (optional):  Filters events by event type.
* zipcode: (optional):  Filters events by zipcode.
* radius: (optional):  Filters events by a radius around the zipcode.
* limit: (optional): The maximum number of events to display. Default is 10.
* template: (optional): The template to use for displaying events.  Available options are:
** default:  Displays events in a list format.
** card: Displays events in a card format.
* show_description: (optional):  Show or hide the event description.  Default is true. Options are:
** true: Show the description.
** false: Hide the description.
* is_virtual: (optional): Filters events by their virtual status. Options are:
** "":  Show all events (default).
** "true": Show only virtual events.
** "false": Show only in-person events.
* columns: (optional, only applies to the card template):  The number of columns to use for the card layout.  Options are 1, 2, 3, or 4. Default is 3.

## Examples
Display 10 events for organization ID 1234:

[mobilize_america_events organization_id="1234"]

Display 5 card formatted events for organization 1234, starting after 2024-01-01:

[mobilize_america_events organization_id="1234" template="card" timeslot_start="gte_1704137620" limit="5"]


Display virtual events in 2 columns:

[mobilize_america_events organization_id="1234" template="card" is_virtual="true" columns="2"]


## Planed Features
* Truncate Description 
* Fix Elementor Widget Style settings 
* Map Link for Address
* Remove Default & Card Layout for single layout - future feature of custom layout

## Future Feature Ideas
* Block to output shortcode content
* Single Event Display
* Custom Layout in uploads directory 
* UI for custom layout inside wordpress
* Map Layout of events
* Events stored locally in database to allow for better search / filtering 


== Screenshots ==
1. Sample Output of 3 Column Card Template without Descriptions



## Change Log
2025-05-19 v1.0.9
* Added Location to output 
* Added time to output - adjusted output to local time based on WordPress timezone
* Added Sponsor Name / Link


2025-05-11 v1.0.8
* Added API Information to Readme
* Adjusted prefix per WordPress standards

2025-05-10 v1.0.7
* UPDATE Slug / Name based on WordPress Requirements
* FIX PHP Warning on Card Template

2025-05-04 v1.0.6
* FIX: PHP Function issue in Template

2025-05-03 v1.0.5
* Moved Template Code to separate file
* Added column feature to card template
* Checked Plugin to WordPress Standards

2025-05-03 v1.0.4
* Added Elementor Widget

2025-05-01 v1.0.3
* WordPress Plugin Header Standards

2025-05-01 v1.0.2
* Update name & files to match WordPress Standards

2025-05-01 v1.0.1
* Added "is_virutal" to Shortcode

2025-04-28 - v1.0.0 - Initial Release
