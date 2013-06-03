<?php

/*
=====================================================
 This ExpressionEngine add-on was created by Laisvunas
 - http://devot-ee.com/developers/laisvunas
=====================================================
 Copyright (c) Laisvunas
=====================================================
 This is commercial Software.
 One purchased license permits the use this Software on the SINGLE website.
 Unless you have been granted prior, written consent from Laisvunas, you may not:
 * Reproduce, distribute, or transfer the Software, or portions thereof, to any third party
 * Sell, rent, lease, assign, or sublet the Software or portions thereof
 * Grant rights to any other person
=====================================================
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once PATH_THIRD.'true_preview/config.php';

class True_preview_mcp {

  // -------------------------
 	//	Constructor
 	// -------------------------
  
  function True_preview_mcp($switch = TRUE)
  {
    $this->EE =& get_instance();
  }
  // END FUNCTION
  
  // -------------------------
 	//	Module homepage - settings
 	// -------------------------
  
  function index()
  {
    // Contents of title tag and last breadcrumb
    $this->EE->cp->set_variable('cp_page_title', TRUE_PREVIEW_NAME.' '.TRUE_PREVIEW_VER.' '.$this->EE->lang->line('settings'));
    
    // Documentation link
    $this->EE->cp->set_right_nav(array(
				  'documentation'		=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.TRUE_PREVIEW_ID.AMP.'method=docs'
			 )); 
    
    $sql_weblog_order_table_exists = "SHOW TABLES LIKE 'exp_weblog_order'";
    $query_weblog_order_table_exists = $this->EE->db->query($sql_weblog_order_table_exists);
    //print_r($query_weblog_order_table_exists->result_array()); echo '<hr>';
    
    if ($query_weblog_order_table_exists->num_rows() != 1)
    {
      $channels = $this->EE->db->query("SELECT exp_channels.channel_id, exp_channels.channel_title, exp_true_preview_prefs.template, exp_true_preview_prefs.display_channel_title
                                       FROM 
                                         exp_channels
                                           LEFT OUTER JOIN
                                         exp_true_preview_prefs
                                           ON
                                         exp_channels.channel_id=exp_true_preview_prefs.channel_id  
                                       WHERE exp_channels.site_id='".$this->EE->config->item('site_id')."'
                                       ORDER BY exp_channels.channel_title");
    }
    else
    {
      $channels = $this->EE->db->query("SELECT exp_channels.channel_id, exp_channels.channel_title, exp_true_preview_prefs.template, exp_true_preview_prefs.display_channel_title
                                       FROM 
                                         exp_channels
                                           LEFT OUTER JOIN
                                         (SELECT exp_weblog_order.weblog_id, exp_weblog_order.order_num FROM exp_weblog_order WHERE exp_weblog_order.site_id='".$this->EE->config->item('site_id')."') as weblog_order
                                           ON
                                         exp_channels.channel_id=weblog_order.weblog_id 
                                           LEFT OUTER JOIN
                                         exp_true_preview_prefs
                                           ON
                                         exp_channels.channel_id=exp_true_preview_prefs.weblog_id
                                       WHERE exp_channels.site_id='".$this->EE->config->item('site_id')."'
                                       ORDER BY weblog_order.order_num, exp_channels.channel_title"); 
    }
    //print_r($weblogs->result_array());
    
    $channels_result_array = $channels->result_array();
    
    $vars = array();
    $vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.TRUE_PREVIEW_ID.AMP.'method=save_settings'; 
    $vars['display_channel_title'] = count($channels_result_array) > 0 ? $channels_result_array[0]['display_channel_title'] : 'y';
    $vars['channels'] = $channels_result_array;
    
    return $this->EE->load->view('index', $vars, TRUE);
  }
  // END FUNCTION
  
  // -------------------------
 	//	Saving settings
 	// -------------------------
  
  function save_settings()
  {
    // Saving settings
    if ($this->EE->input->post(TRUE_PREVIEW_ID.'_update_prefs') == 'y')
    {
      $this->EE->lang->loadfile(TRUE_PREVIEW_ID);
      //var_dump($_POST);
      // Form SQL query to update settings
      $channel_ids_having_incorrect_template = array(); 
      $prefs_array = array();
      $i = 0;
      $display_cannel_title = (isset($_POST['display_channel_title']) AND ($_POST['display_channel_title'] == 'y' OR $_POST['display_channel_title'] == 'n')) ? $_POST['display_channel_title'] : 'y';
      $length = strlen('template_group_and_name_channel_');
      foreach ($_POST as $name => $val)
      {
        $channel_id = substr($name, $length);
        $name_part = substr($name, 0, $length);
        if ($name_part == 'template_group_and_name_channel_' AND is_numeric($channel_id) === TRUE)
        {
          $val = trim($val);
          $prefs_array[$i]['channel_id'] = $channel_id;
          $prefs_array[$i]['template'] = $val;
          if ($val AND strpos($val, '/') == FALSE) // slash must be present and cannot be the first symbol
          {
            array_push($channel_ids_having_incorrect_template, $channel_id);
          }
          $prefs_array[$i]['display_channel_title'] = $display_cannel_title;
        }
        $i++;
      }
      //var_dump($prefs_array); echo '<hr>'.PHP_EOL;
      
      $values = '';
      $site_id = $this->EE->config->item('site_id'); // proceed here ++++++++++++++++++++++++++++++++
      foreach ($prefs_array as $pref)
      {
        $values .= PHP_EOL."('".$site_id."', '".$pref['channel_id']."', '".$pref['template']."', '".$pref['display_channel_title']."'),";
      }
      $values = rtrim($values, ',');
      
      $sql_update_prefs = "INSERT INTO exp_true_preview_prefs
                           (
                             site_id,
                             channel_id,
                             template,
                             display_channel_title
                           )
                           VALUES "
                           .$values.
                           " ON DUPLICATE KEY UPDATE template = VALUES(template),  display_channel_title = VALUES(display_channel_title) ";
      $this->EE->db->query($sql_update_prefs);
      
      // Success or error message
      if (count($channel_ids_having_incorrect_template) > 0) // error mag
      {
        // find channel titles
        $sql_channel_titles = "SELECT channel_id, channel_title FROM exp_channels WHERE site_id = '".$site_id."' AND channel_id IN ('".implode("','", $channel_ids_having_incorrect_template)."') ";
        $query_channel_titles = $this->EE->db->query($sql_channel_titles);
        $channel_titles_result_array = $query_channel_titles->result_array();
        $error_msg_array = array();
        foreach ($channel_titles_result_array as $row)
        {
           array_push($error_msg_array, str_replace('{channel_title}', $row['channel_title'], $this->EE->lang->line('url_template_value_error_message')));
        }
        $error_msg = implode('<br>', $error_msg_array);
        $this->EE->session->set_flashdata(array(
          'message_error' => $error_msg
        ));
      }
      else // success msg
      {
        $this->EE->session->set_flashdata(array(
          'message_success' => $this->EE->lang->line('preferences_update_success_message')
        ));
      }
      
      $this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.TRUE_PREVIEW_ID);
    }
  }
  // END FUNCTION
  
  // -------------------------
 	//	Documentation
 	// -------------------------
  
  function docs()
  {
    // Contents of title tag and last breadcrumb
    $this->EE->cp->set_variable('cp_page_title', TRUE_PREVIEW_NAME.' '.TRUE_PREVIEW_VER.' '.$this->EE->lang->line('documentation'));
    
    // Settings page link
    $this->EE->cp->set_right_nav(array(
				  'settings'		=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.TRUE_PREVIEW_ID
			 ));
    
    // CSS
    $this->EE->cp->add_to_head('
    <style type="text/css">
    tr.even, tr.odd {
      vertical-align: top;
    }
    </style>
    ');
    
    $vars = array();
    $vars['author'] = TRUE_PREVIEW_AUTHOR;
    $vars['author_url'] = TRUE_PREVIEW_DOCS;
    $vars['description'] = TRUE_PREVIEW_DESC;
    $vars['usage'] = $this->usage();
    
    return $this->EE->load->view('docs', $vars, TRUE);
  }
  // END FUNCTION
  
  function usage()
  {
    ob_start(); 
?>

[b]INSTALLATION FOR EE2.0+[/b]

1) Unzip downloaded files.

2) Upload the directory "<?= @TRUE_PREVIEW_ID ?>" to /system/expressionengine/third_party on the server.

3) Go CP Home > Add-ons > Modules, find "<?= @TRUE_PREVIEW_NAME ?>" in modules list and click "Install".
Then select module, extension and accessory and click "Install".

4) Go to <?= @TRUE_PREVIEW_NAME ?> module's settings page and set settings:

a) You can set if channel's title should be displayed in Edit Entry page's breadcrumbs. Default value is "yes".

b) For each channel you can define slash delimited template group and template name which will output URL for 
the entries of the relevant channel. E.g. for the channel news you can define template this way: "entry_url/news", i.e.
URLs for the entries of the News weblog will be outputted by the template "news" located in "entry_url" template group.

5) Then go to template manager and enter the code for those templates which were defined in the step 4b). Most probably
you will need to wrap all the code of each of such templates with the tag pair exp:true_preview:url. In URL template you
can use any relevant ExpressionEngine's tag; tags can be nested as usual. Conditionals and global variables are also supported.

[b]THE TAG PAIR exp:true_preview:url[/b]

The tag pair exp:true_preview:url has several single variables useful for creating entry's URL:

1) true_preview_site_id - outputs ID of the site.

2) true_preview_entry_id - outputs entry ID.

3) true_preview_url_title - outputs url_title of the entry.

4) true_preview_channel_id - outputs channel ID of the channel entry belongs to.

5) true_preview_channel_name - outputs channel short name of the channel entry belongs to.

SIMPLE EXAMPLE OF URL TEMPLATE CODE

Here only global variable "homepage" and the tag pair exp:true_preview:url has been used.

[code]
{exp:true_preview:url parse="inward"}
{homepage}/site/news/{true_preview_url_title}/
{/exp:true_preview:url}
[/code]

COMPLEX EXAMPLE OF URL TEMPLATE CODE

Here global variable "homepage", the tag pair exp:true_preview:url, the tag exp:entry_cats of Entry Categories plugin (available at http://devot-ee.com/add-ons/entry-categories), 
conditionals and tag nesting has been used.

[code]
{exp:true_preview:url parse="inward"}
{exp:entry_cats entry_id="{true_preview_entry_id}" site="{true_preview_site_id}"}
{homepage}/ebook/analysis_page/{level0_category0_cat_url_title}{if level1_category0_cat_id}-{level1_category0_cat_url_title}{/if}/{true_preview_url_title}/basic_analysis/
{/exp:entry_cats}
{/exp:true_preview:url}
[/code]

<?php
    $buffer = ob_get_contents();
    ob_end_clean();
    
    $this->EE->load->library('typography');
    $this->EE->typography->initialize();
    
    $prefs = array(
      'text_format' => 'br', 
      'highlight_code' => TRUE
    ); 
    $buffer = $this->EE->typography->parse_type(trim($buffer), $prefs); 
    return $buffer;  
  }
  // END FUNCTION
}
// END CLASS
?>