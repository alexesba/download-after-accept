<?php
/**
 * Plugin Name: Terms After Download
 * Plugin URI: http://github.com/alexesba/dowload-after-download/
 * Description: Shows a popup dialog with terms and conditions
 * Version: 1.0.0
 * Author: Alejandro Espinoza
 * Author URI: http://github.com/alexesba
 * License: GPL2
 */


// Register the shortcodes
// add_shortcode('dat_link', 'shortcode_handler_dat_link');
add_shortcode('dat_terms', 'shortcode_handler_dat_terms');

//
// Shortcode handler function: inserts a div with the EULA agreement content
//
// Allowed properties:
// -  eula_page_id     ->    ID of the EULA page displayed in the dialog [required]
// -  modalbox_title    ->    The title of the dialog to be displayed [optional]
// -  class             ->    CSS class of the div enclosing the dialog content [optional]
// -  padding           ->    Padding between the dialog frame and the inner content [optional]
// -  width             ->    Width of the dialog [optional]
// -  accept_button_text    ->    Text for the OK button [optional]
//
function shortcode_handler_dat_terms($atts)
{
   // Set up attribute defaults where nothing has been specified
   extract(shortcode_atts(array(
         'modalbox_title'    => 'Terms and Conditions',
         'class'           => 'entry',
         'padding'         => '20px',
         'width'           => '80%',
         'agree_button_text'  => 'I agree to the terms',
         'eula_page_id'   => 0,
         'modal' => 1
      ), $atts));

   // Get the libraries we need
    wp_enqueue_script('jquery');
   if($modal){
     wp_enqueue_script('jquery-ui-dialog');
     wp_enqueue_style('wp-jquery-ui-dialog');
   }

   // Get the terms page
   if (empty($eula_page_id))
      return "";
   else
      $terms_page = get_post($eula_page_id, "OBJECT", "display");

   // Get the terms page content
   $terms_page_content = $terms_page->post_content;
   // Convert double line breaks into paragraphs, replacing \n with <br /> to the string
   $terms_page_content = wpautop($terms_page_content);
   // Remove non-printable characters
   $terms_page_content = preg_replace('/[\x00-\x1F]/u', '', $terms_page_content);
   // HTML-encode double quotes because the string will be enclosed in double quotes
   $terms_page_content = str_replace ('"', "&quot;", $terms_page_content);

   // Build the output string
   $output = <<<EndOfHeredoc

   <script type="text/javascript">
      jQuery(document).ready(function ($)
      {
         if ($('#dat_terms').length == 0)
            $('body').append("<div id='dat_terms' title='{$modalbox_title}' class='{$class}' style='display:none; padding: {$padding};'>{$terms_page_content}</div>");
      });
   </script>

   <script type="text/javascript">

      var convertHrefToData = function(elements){
         elements.data('url', elements.prop('href'));
         elements.removeAttr("href");
      }

      var InsertChekbox = function(element){
        var checbox = jQuery('<input>', { type: 'checkbox', name: 'agree_eula' });
        var label = jQuery('<label>', { text: 'I agree with the terms and conditions', for: 'agree_eula'});
        element.append(label);
        element.append(checbox);
      }

      var showModalBox = function(height, url){
          jQuery("#dat_terms").dialog(
            {
               dialogClass: 'wp-dialog',
               resizable: false,
               draggable: false,
               modal: true,
               width: "{$width}",
               maxHeight: height,
               buttons:
               {
                  "{$agree_button_text}": function()
                  {
                     jQuery(this).dialog("close");
                     window.location.href = url;
                  },
                  Cancel: function()
                  {
                     jQuery(this).dialog("close");
                  }
               }
            });
         // Make the dialog stay in place when the user scrolls
         if($modal){
             jQuery(window).scroll(function()
             {
                jQuery('#dat_terms').dialog('option','position','center');
             });
          }
      }
      jQuery(document).ready(function ($)
      {
         // Show the dialog
         var downloadAgree = $('.agree_download a');
         convertHrefToData(downloadAgree);
         if(!$modal) InsertChekbox(downloadAgree.closest('div'));
         downloadAgree.click(function (e)
         {
            e.preventDefault();
            console.log('Display Dialog');
            var url = $(this).data('url');
            var height = $(window).height() * 0.8;
            if($modal){
              showModalBox(height, url);
            }else{
              if($('input[name="agree_eula"]').is(':checked')){
                window.location.href = url;
                }else{
                 alert('Please accept the terms and conditions before donwnlad');
                }
            }
            e.preventDefault();
            e.stopPropagation();
         });

      });
   </script>

EndOfHeredoc;
   return $output;
}


