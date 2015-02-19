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
          <label for="dat_protected_url">Protected download page:</label>
            <?php
            wp_dropdown_pages(array(
            'echo' => 1,
            'show_option_none' => __( '&mdash; Select &mdash;' ),
            'name' => 'dat_protected_url',
            'option_none_value' => '0',
            'selected' =>get_option('dat_protected_url')
            ));?>
            <label for="dat_eula_dashboard_url">EULA Dashware page:</label>
            <?php
            wp_dropdown_pages(array(
            'echo' => 1,
            'show_option_none' => __( '&mdash; Select &mdash;' ),
            'name' => 'dat_eula_dashboard_url',
            'option_none_value' => '0',
            'selected' =>get_option('dat_eula_dashboard_url')
            ));?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="dat_eula_gopro_url">EULA Gopro page</label>
            <?php
            wp_dropdown_pages(array(
            'echo' => 1,
            'show_option_none' => __( '&mdash; Select &mdash;' ),
            'name' => 'dat_eula_gopro_url',
            'option_none_value' => '0',
            'selected' =>get_option('dat_eula_gopro_url')
            ));?>
          </td>
        </tr>
      </table>
    </fieldset>
    <?php submit_button(); ?>
  </form>
</div>
