# wordpress-datepicker

## Intro

This plugin is based on a plugin available from [Kyle G](http://www.komprihensiv.com/adding-the-jquery-ui-datepicker-to-a-wordpress-metabox/).

The major changes are making it a freestanding configurable class object that can be called multiple times in your theme.

In theory you don't have to use this as a plugin, as it could be reworked as a standalone class in your theme distribution.

## Usage

<pre>
// always check that the class exists before you call it!
if(class_exists('metabox_datepicker') { 
  $myDatePicker = new metabox_datepicker($post_type, $custom_meta_name, $label_for_textbox, $label_for_admin_metabox);
}

// EXAMPLE: date picker fields for post type 'event'
if (class_exists('metabox_datepicker')){
  $event_start_date = new metabox_datepicker('event', 'event_start_date', 'Event start date', "Start Date");
  $event_end_date = new metabox_datepicker('event', 'event_end_date', 'Event end date', "End Date");
}

</pre>
