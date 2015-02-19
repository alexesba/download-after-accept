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
wp_register_script( 'datScript', plugins_url('jquery.cookie.js', __FILE__), array('jquery'));
wp_enqueue_style( 'datStyle' );
wp_enqueue_script( 'datScript');
add_shortcode('dat_terms_container', 'shortcode_handler_dat_terms_container');
add_shortcode('dat_terms', 'shortcode_handler_dat_terms');


function download_after_agree_menu(){
  add_options_page('Download After Agree Options',
    'Download After Agree',
    'manage_options',
    'download-after-agree-plugin-menu',
    'download_after_agree_options');
}

function download_after_agree_options(){
  include('admin/download-after-agree-admin.php');
}
add_action('admin_menu','download_after_agree_menu');


//Register all the input fields
function dat_register_settings() {
	register_setting( 'dat_download_after_agree_options', 'dat_protected_page_id', 'intval');
	register_setting( 'dat_download_after_agree_options', 'eula_redirect_to_accept_page_id', 'intval');
	register_setting( 'dat_download_after_agree_options', 'dat_eula_page_id', 'intval');
	register_setting( 'dat_download_after_agree_options', 'dat_modalbox_title');
	register_setting( 'dat_download_after_agree_options', 'dat_accept_text');
	register_setting( 'dat_download_after_agree_options', 'dat_modalbox_active');
	register_setting( 'dat_download_after_agree_options', 'dat_terms_of_use_page_id');
	register_setting( 'dat_download_after_agree_options', 'dat_privacy_policy_page_id');
	register_setting( 'dat_download_after_agree_options', 'dat_eula_page_text');
	register_setting( 'dat_download_after_agree_options', 'dat_terms_of_use_page_text');
	register_setting( 'dat_download_after_agree_options', 'dat_privacy_policy_page_text');
 }
add_action( 'admin_init', 'dat_register_settings' );

function cookiebasedredirect() {
  session_start();
  $cookie_name = 'eula_accepted';
  $protected_slug_page = basename(get_permalink(get_option('dat_protected_page_id')));
  $accept_terms_slug_page = get_permalink(get_option('eula_redirect_to_accept_page_id'));
  // WHEN YOU HAVE FOUND YOUR COOKIE
  if ( !isset($_COOKIE[$cookie_name])) {

    // GRABS THE CURRENT PAGE NAME - THIS IS ALSO KNOWS AS THE PAGE/POST SLUG
    $pagename = get_query_var('pagename');

    // PAGE CHECK SO THAT YOU ARE NOT IN AN INFINITE LOOP
    if( $pagename == $protected_slug_page) {
       wp_redirect( $accept_terms_slug_page, 301 );
       exit;
    }
  }
}

add_action("template_redirect", "cookiebasedredirect");

// Shortcode handler fucntion: inserts an container with the EULA agreement content
function shortcode_handler_dat_terms_container($atts){
   extract(shortcode_atts(array(
         'eula_page_id'  => get_option('dat_eula_page_id'),
         'height' => '200px;',
         'padding' => '20px;',
         'class' => 'entry',
         'margin_bottom' => '10px;',
         'modal' => get_option('dat_modalbox_active')
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
         'modalbox_title'           => get_option('dat_modalbox_title'),
         'class'                    => 'entry',
         'padding'                  => '20px',
         'width'                    => '80%',
         'agree_button_text'        => 'I agree with the terms and conditions',
         'alert_agree_message'      => 'Please agree with the terms and conditions',
         'eula_page_id'             => get_option('dat_eula_page_id'),
         'eula_terms_of_use_id'     => get_option('dat_terms_of_use_page_id'),
         'eula_privacy_policy_id'   => get_option('dat_privacy_policy_page_id'),
         'modal'                    => get_option('dat_modalbox_active'),
         'eula_page_text'           => get_option('dat_eula_page_text'),
         'terms_of_use_page_text'   => get_option('dat_terms_of_use_page_text'),
         'privacy_policy_page_text' => get_option('dat_privacy_policy_page_text')
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
       $eula_page = get_permalink($eula_page_id);
     }
   }
   //Dashware terms url
   $terms_of_use_page = get_permalink($eula_terms_of_use_id);
   $privacy_policy_page = get_permalink($eula_privacy_policy_id);

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

      var buildLink = function(eula_container, url, name){
        var checkbox = jQuery('<input>', { type: 'checkbox', class: 'dat_checkbox',  name: 'dat_'+ name });
        var label = jQuery('<label>', { text: '', for: name});
        var link = jQuery("<a>", {class: 'dat_link', href: url,  target:'_blank', text: name });
        label.append(link);
        eula_container.append(checkbox).append(label).append(jQuery('<br>'))
        return eula_container;
      }
      // Function to insert the checkbox using jquery
      var hasAcceptedTerms = function(){
          return (jQuery('.dat_checkbox:checked').length == jQuery('.dat_checkbox').length);
      }
      var InsertChekbox = function(element){
        var container = element.find('section:last');
        var button = container.find('a.button');
        button.addClass('eula-button-disabled');
        var eula_container = jQuery('<div>', { class: 'eula-box-container' });
        buildLink(eula_container, '{$eula_page}', '{$eula_page_text}');
        buildLink(eula_container, '{$terms_of_use_page}', '{$terms_of_use_page_text}');
        buildLink(eula_container, '{$privacy_policy_page}', '{$privacy_policy_page_text}');
        container.prepend(eula_container);

        container.find('.dat_checkbox').on('change', function(){
          if(!hasAcceptedTerms()) {
            button.addClass('eula-button-disabled');
            jQuery.removeCookie('eula_accepted', { path: '/' });
          }else{
            jQuery.cookie('eula_accepted', true, { path: '/' });
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
              if(hasAcceptedTerms()){
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
