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

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once PATH_THIRD.'true_preview/config.php';

class True_preview_acc {

  var $name	= TRUE_PREVIEW_NAME;
 	var $id	= TRUE_PREVIEW_ID;
 	var $version	= TRUE_PREVIEW_VER;
 	var $description	= TRUE_PREVIEW_DESC;
 	var $sections	= array();
  
  // -------------------------------
 	// Constructor
 	// -------------------------------
  
  function True_preview_acc()
 	{
 		 $this->EE =& get_instance();
 	}
  // END FUNCTION
  
  // -------------------------------
 	// Custom Stuff
 	// -------------------------------
  
  function set_sections()
  {       
    // render accessory tab only on Publish/Edit page (it will be hidden by extension)
    if($this->EE->input->get('D') == 'cp' AND $this->EE->input->get('C') == 'addons_accessories')
    {
      $this->EE->db->where('class', TRUE_PREVIEW_CLASS.'_acc');
      $this->EE->db->update('accessories', array('controllers' => 'content_publish'));
    }
    
    $channel_id = $this->EE->input->get('channel_id');
    $entry_id = $this->EE->input->get('entry_id');
    $site_id = $this->EE->config->item('site_id');

    if ($this->EE->input->get('C') == 'content_publish' AND $this->EE->input->get('M') == 'entry_form' AND is_numeric($channel_id) AND is_numeric($entry_id))
    {
      $this->EE->lang->loadfile('true_preview');
      $channel_title = $this->_fetch_channel_title($site_id, $channel_id);
      //echo '$channel_title: '.$channel_title;
      $crumb_text = $this->EE->lang->line('channel');
      $button_text = $this->EE->lang->line('preview');
      
      // create URL of the entry being edited
      $entry_url = $this->_create_entry_url($site_id, $channel_id, $entry_id);
      //echo '$entry_url: [['.$entry_url.']]';
       
      ob_start(); 
?>

<script type="text/javascript">

$(document).ready(function() {
  if ('<?= @$channel_title ?>') {
    $('#breadCrumb ol li.last').append(' (<?= @$channel_title ?> <?= @$crumb_text ?>)');
  }
  if ('<?= @$entry_url ?>') {
    var right_nav_div;
    
    right_nav_div = $('#mainContent div.rightNav');
    //alert('right_nav_div.length: ' + right_nav_div.length);
    if (right_nav_div.length == 1) {
      $('#mainContent div.rightNav div').prepend('<span class="button"><a class="submit" target="_blank" href="<?= @$entry_url ?>" title="<?= @$button_text ?>"><?= @$button_text ?></a></span>');
    }
    else {
      $('#mainContent').prepend('<div class="rightNav"><div style="float: left; width: 100%;"><span class="button"><a class="submit" target="_blank" href="<?= @$entry_url ?>" title="<?= @$button_text ?>"><?= @$button_text ?></a></span></div></div>');
    }
  }
});

</script>

<?php
      $js = ob_get_contents();
      ob_end_clean();
      
      $this->sections[] = $js;
    }
  }
  // END FUNCTION
  
  function _fetch_channel_title($site_id, $channel_id)
  {
    $channel_title = '';
    // fetch from module's settings if channel title should be inserted into breadcrumb 
    $sql_display_channel_title = "SELECT display_channel_title FROM exp_true_preview_prefs WHERE site_id = '".$site_id."' AND channel_id = '".$channel_id."' LIMIT 1";
    $query_display_channel_title = $this->EE->db->query($sql_display_channel_title);
    if ($query_display_channel_title->num_rows() == 1)
    {
      $display_channel_title_row_array = $query_display_channel_title->row_array();
      $display_channel_title = $display_channel_title_row_array['display_channel_title'];
    }
    else
    {
      $display_channel_title = 'y';
    }
    
    if ($display_channel_title == 'y')
    {
      // find channel title
      $sql_channel_title = "SELECT channel_title FROM exp_channels WHERE site_id = '".$site_id."' AND channel_id = '".$channel_id."' LIMIT 1";
      $query_channel_title = $this->EE->db->query($sql_channel_title);
      if ($query_channel_title->num_rows() == 1)
      {
        $row_channel_title = $query_channel_title->row_array();
        $channel_title = $row_channel_title['channel_title'];
        //echo '$channel_title: '.$channel_title;
      }
    }

    return $channel_title;
  }
  // END FUNCTION
  
  function _create_entry_url($site_id, $channel_id, $entry_id)
  {
    $entry_url = '';
    
    // fetch from module's settings template
    $sql_template = "SELECT template FROM exp_true_preview_prefs WHERE site_id = '".$site_id."' AND channel_id = '".$channel_id."' LIMIT 1";
    $query_template = $this->EE->db->query($sql_template);
    
    if ($query_template->num_rows() == 1)
    {
      $this->EE->session->cache[TRUE_PREVIEW_ID]['site_id'] = $site_id;
      $this->EE->session->cache[TRUE_PREVIEW_ID]['entry_id'] = $entry_id;
      $this->EE->session->cache[TRUE_PREVIEW_ID]['channel_id'] = $channel_id;
      
      $template_row_array = $query_template->row_array();
      //var_dump($template_row_array);
      $template = trim($template_row_array['template']);
      //echo '$template: [['.$template.']]';
      
      if ($template)
      {
        $template_group_name = current(explode('/', $template));
        $template_name = end(explode('/', $template));
  
        $this->EE->load->library('Template', NULL, 'TMPL');
        $this->EE->TMPL->fetch_and_parse($template_group_name, $template_name);
        $entry_url = $this->EE->TMPL->parse_globals($this->EE->TMPL->final_template);
        
        // trim URL and remove linebreaks
        $entry_url = trim($entry_url);
        $entry_url = str_replace(array('\r\n', '\n', '\r'), '', $entry_url);
      }
    }   

    return $entry_url;
  }
  // END FUNCTION
}
// END CLASS
?>