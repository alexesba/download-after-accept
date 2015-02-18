<?php
/**
 * Plugin Name: Download Affter Agree
 * Plugin URI: http://github.com/alexesba/download-after-agree
 * Description: Insert a checkbox to accept or shows a popup dialog with terms and conditions
 * Version: 1.0.0
 * Author: Alejandro Espinoza
 * Author URI: http://github.com/alexesba
 */
// Register the shortcodes
//

/* Register our stylesheet. */
wp_register_style( 'datStyle', plugins_url('style.css', __FILE__), array(), filemtime( get_stylesheet_directory() . '/style.css' ));
wp_enqueue_style( 'datStyle' );
add_shortcode('dat_terms_container', 'shortcode_handler_dat_terms_container');
add_shortcode('dat_terms', 'shortcode_handler_dat_terms');

//Shortcode handler fucntion: inserts an container with the EULA agreement content
//

function shortcode_handler_dat_terms_container($atts){
   extract(shortcode_atts(array(
         'eula_page_id'  => 0,
         'height' => '200px',
         'padding' => '20px',
         'class' => 'entry',
         'margin_bottom' => '10px',
         'modal' => 0
      ), $atts));

   if($modal)
     return "";
   if (empty($eula_page_id))
      return "";
   else{
      $eula_content_page = parse_content_eula_page($eula_page_id);
     $output = <<<EndOfHeredoc
       <div class="eula-container {$class}" style='max-height:{$height} margin-bottom: {$margin_bottom}'>{$eula_content_page }</div>
EndOfHeredoc;

     return $output;
   }

}

//Returns the content of the EULA page based on the ID
//Allowed properties
// - eula_page_id -> ID of the EULA page displayed in the dialog [required]
function parse_content_eula_page($eula_page_id)
{
  $terms_page = get_post ($eula_page_id, "OBJECT", "display");
  // Get the terms page content
  $terms_page_content = $terms_page->post_content;
  // Convert double line breaks into paragraphs, replacing \n with <br /> to the string
  $terms_page_content = wpautop($terms_page_content);
  // Remove non-printable characters
  $terms_page_content = preg_replace('/[\x00-\x1F]/u', '', $terms_page_content);
  // HTML-encode double quotes because the string will be enclosed in double quotes
  $terms_page_content = str_replace ('"', "&quot;", $terms_page_content);
  return $terms_page_content;
}

//
// Shortcode handler function: inserts a div with the EULA agreement content
//
// Allowed properties:
// -  eula_page_id        -> ID of the EULA page displayed in the dialog [required]
// -  modalbox_title      -> The title of the dialog to be displayed [optional]
// -  class               -> CSS class of the div enclosing the dialog content [optional]
// -  padding             -> Padding between the dialog frame and the inner content [optional]
// -  width               -> Width of the dialog [optional]
// -  agree_button_text   -> Text for the OK button [optional]
// -  alert_agree_message -> Text for the alert message when the user doesn't  accept the terms using the checkbox
// -  modal               -> boolean value(0|1) to display the EULA in a modal box.
//

function shortcode_handler_dat_terms($atts)
{
   // Set up attribute defaults where nothing has been specified
   extract(shortcode_atts(array(
         'modalbox_title'      => 'Terms and Conditions',
         'class'               => 'entry',
         'padding'             => '20px',
         'width'               => '80%',
         'agree_button_text'   => 'I agree with the terms and conditions',
         'alert_agree_message' => 'Please agree with the terms and conditions',
         'eula_page_id'        => 0,
         'modal'               => 0
      ), $atts));

   // Get the libraries we need
    wp_enqueue_script('jquery');

   if($modal){#included if the modalbox is required
     wp_enqueue_script('jquery-ui-dialog');
     wp_enqueue_style('wp-jquery-ui-dialog');
   }

   // Get the terms page
   if (empty($eula_page_id))
      return "";
   else{
     if($modal) {#parse the content of the EULA page only if modal is required
       $terms_page = get_post($eula_page_id, "OBJECT", "display");
       // Get the terms page content
       $terms_page_content = $terms_page->post_content;
       // Convert double line breaks into paragraphs, replacing \n with <br /> to the string
       $terms_page_content = wpautop($terms_page_content);
       // Remove non-printable characters
       $terms_page_content = preg_replace('/[\x00-\x1F]/u', '', $terms_page_content);
       // HTML-encode double quotes because the string will be enclosed in double quotes
       $terms_page_content = str_replace ('"', "&quot;", $terms_page_content);
     }else{
       //Get the url of the EULA page
       $terms_page_url = get_permalink($eula_page_id);
     }
   }

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
      //Remove the attribute href and insert a data url attribute with the same value
      var convertHrefToData = function(elements){
         elements.data('url', elements.prop('href'));
         elements.removeAttr("href");
      }
      // Function to insert the checkbox using jquery
      var InsertChekbox = function(element){
        var container = element.find('section:last');
        var button = container.find('a.button');
        button.addClass('eula-button-disabled');
        var eula_container = jQuery('<div>', { class: 'eula-box-container' });
        var checkbox = jQuery('<input>', { type: 'checkbox', name: 'agree_eula' });
        var label = jQuery('<label>', { text: ' {$agree_button_text} ', for: 'agree_eula'});
        eula_container.append(checkbox).append(label)
        container.prepend(eula_container);

        checkbox.on('change', function(){
          if(!this.checked){
            button.addClass('eula-button-disabled');
          }else{
            button.removeClass('eula-button-disabled');
          }
        });
      }

      var downloadFile = function(url){
          window.location.href = url;
      }

      var showModalBox = function(url){
          var height = jQuery(window).height() * 0.8;
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
                     downloadFile(url);
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
         // Show the modalbox
         var downloadAgree = $('.agree_download a');
         convertHrefToData(downloadAgree);
         if(!$modal) InsertChekbox(downloadAgree.closest('div'));
         downloadAgree.click(function (e)
         {
            var url = $(this).data('url');
            if($modal){
              showModalBox(url);
            }else{
              if($('input[name="agree_eula"]').is(':checked')){
                  downloadFile(url);
                }else{
                 alert('{$alert_agree_message}');
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
