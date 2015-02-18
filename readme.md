# Download After agree
Contributors: alexesba
Tags: download,terms,eula,license
Requires at least: 3.5
Tested up to: 4.0
Stable tag: trunk
License: GPL2

Shows a popup dialog with terms and conditions (EULA) that must be accepted before a file can be downloaded

###Description
Terms Before Download adds a shortcode that can be used instead of HTML anchors to link to downloadable files. If such a link is clicked a popup dialog shows terms and conditions (EULA) which must be accepted for the download to start.

The terms and conditions are read from a Wordpress page. That way there is only a single place to maintain the terms and they can easily be displayed independently of the plugin.

The plugin supports Google Analytics to keep track of the number of downloads.

An example of the plugin in action can be found here: http://helgeklein.com/download/

###Usage

```
[dat_terms  eula_page_id=20 ok_button_text='Custom agree text' modalbox_title='Custom agree modalbox title' eula_ink_text='I agree with the' eula_link_url_text='terms and conditions' modal='0']
```



###The following properties can be used with the shortcode *dat_terms*:

*  eula_page_id        -> ID of the EULA page displayed in the dialog [required]
*  modalbox_title      -> The title of the dialog to be displayed [optional if the modal value is not set]
*  class               -> CSS class of the div enclosing the dialog content [optional]
*  padding             -> Padding between the dialog frame and the inner content [optional]
*  width               -> Width of the dialog [optional]
*  agree_button_text   -> Text for the agree button [optional]
*  eula_link_text      -> Text to be placed before the link to the terms and  conditions page
*  eula_link_url_text  -> Text to be  wrapped between the link to the terms and condition page
*  alert_agree_message -> Text for the alert message when the user doesn't  accept the terms using the checkbox
*  modal               -> boolean value(0|1) to display the EULA in a modal box.

##Default Values
```
         'modalbox_title'      => 'Terms and Conditions'
         'class'               => 'entry'
         'padding'             => '20px'
         'width'               => '80%'
         'agree_button_text'   => 'I agree to the terms'
         'eula_link_url_text'  => 'terms & conditions'
         'eula_ink_text'       => 'I agree to the'
         'alert_agree_message' => 'Please aggre with the terms and conditions'
         'eula_page_id'        => 0
         'modal'               => 0
```
