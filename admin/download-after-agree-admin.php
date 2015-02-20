<div class="wrap">
  <h3>Download After Agree Settings</h3>
<hr>
  <form method="post" action="options.php">
    <?php settings_fields( 'dat_download_after_agree_options' ); ?>
    <fieldset>
      <legend><h3> Modal Box Options </h3></legend>
      <table class="form-table">
        <tr valign="top">
          <td>
            <label for="dat_modalbox_active">Display as checkbox: </label>
            <input name="dat_modalbox_active" value='0' type="radio"  <?php checked( 0, (int)get_option( 'dat_modalbox_active' ) ); ?> />
            <label for="dat_modalbox_active">Display as a Modalbox : </label>
            <input name="dat_modalbox_active" value='1' type="radio"  <?php checked( 1, (int)get_option( 'dat_modalbox_active' ) ); ?> />
          </td>
        </tr>
        <tr>
          <td>
            <label for="dat_modalbox_title">Modal box title:</label>
            <input type="text" name="dat_modalbox_title" value="<?php echo get_option( 'dat_modalbox_title' ); ?>"/>
          </td>
        </tr>
        <tr>
          <td>
            <label for="dat_text_above_checkboxes">Text above the checkboxes:</label>
            <input type="text" name="dat_text_above_checkboxes" value="<?php echo get_option( 'dat_text_above_checkboxes' ); ?>"/>
          </td>
        </tr>
        <tr>
          <td>
            <label for="dat_accept_text">Accept Terms Button/Link text</label>
            <input type="text" name="dat_accept_text" value="<?php echo get_option( 'dat_accept_text' ); ?>"/>
          </td>
        </tr>
      </table>
    </fieldset>
    <hr>
    <fieldset>
        <legend><h3>Terms and conditionspages:</h3></legend>
      <table class="form-table">
        <tr>
          <td>
            <label for="dat_eula_page_id">EULA Page</label>
            <?php
            wp_dropdown_pages(array(
            'echo' => 1,
            'show_option_none' => __( '&mdash; Select &mdash;' ),
            'name' => 'dat_eula_page_id',
            'option_none_value' => '0',
            'selected' =>get_option('dat_eula_page_id')
            ));?>
            <label for="dat_eula_page_text">Display name:</label>
            <input type="text" name="dat_eula_page_text" value="<?php echo get_option( 'dat_eula_page_text' ); ?>"/>
          </td>
        </tr>
        <tr>
          <td>
          <label for="eula_redirect_to_accept_page_id">Accept terms url:</label>
            <?php
            wp_dropdown_pages(array(
            'echo' => 1,
            'show_option_none' => __( '&mdash; Select &mdash;' ),
            'name' => 'eula_redirect_to_accept_page_id',
            'option_none_value' => '0',
            'selected' =>get_option('eula_redirect_to_accept_page_id')
          ));?>
            </td>
        </tr>
        <tr>
          <td>
          <label for="dat_protected_page_id">Protected download page:</label>
            <?php
            wp_dropdown_pages(array(
            'echo' => 1,
            'show_option_none' => __( '&mdash; Select &mdash;' ),
            'name' => 'dat_protected_page_id',
            'option_none_value' => '0',
            'selected' =>get_option('dat_protected_page_id')
            ));?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="dat_eula_terms_of_use_page_id">Terms of Use:</label>
            <?php
            wp_dropdown_pages(array(
            'echo' => 1,
            'show_option_none' => __( '&mdash; Select &mdash;' ),
            'name' => 'dat_terms_of_use_page_id',
            'option_none_value' => '0',
            'selected' =>get_option('dat_terms_of_use_page_id')
            ));?>
            <label for="dat_eula_page_text">Display name:</label>
            <input type="text" name="dat_terms_of_use_page_text" value="<?php echo get_option( 'dat_terms_of_use_page_text' ); ?>"/>
          </td>
        </tr>
        <tr>
          <td>
            <label for="dat_eula_terms_of_use_page_id">Privacy Policy:</label>
            <?php
            wp_dropdown_pages(array(
            'echo' => 1,
            'show_option_none' => __( '&mdash; Select &mdash;' ),
            'name' => 'dat_privacy_policy_page_id',
            'option_none_value' => '0',
            'selected' =>get_option('dat_privacy_policy_page_id')
            ));?>
            <label for="dat_eula_page_text">Display name:</label>
            <input type="text" name="dat_privacy_policy_page_text" value="<?php echo get_option( 'dat_privacy_policy_page_text' ); ?>"/>
          </td>
        </tr>
      </table>
    </fieldset>
    <?php submit_button(); ?>
  </form>
</div>
