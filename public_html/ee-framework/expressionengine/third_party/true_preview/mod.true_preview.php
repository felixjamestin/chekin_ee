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

class True_preview {

  // -------------------------
 	//	Constructor
 	// -------------------------
  
  function True_preview()
  {
    $this->EE =& get_instance();
  }
  // END FUNCTION
  
  function url()
  {
    
    //var_dump($_POST);
    $conds = array();
    
    $site_id = $this->EE->session->cache[TRUE_PREVIEW_ID]['site_id'];
    $entry_id = $this->EE->session->cache[TRUE_PREVIEW_ID]['entry_id'];
    $channel_id = $this->EE->session->cache[TRUE_PREVIEW_ID]['channel_id'];
    
    $conds[TRUE_PREVIEW_ID.'_site_id'] = $site_id;
    $conds[TRUE_PREVIEW_ID.'_entry_id'] = $entry_id;
    $conds[TRUE_PREVIEW_ID.'_channel_id'] = $channel_id;
    
    $conds[TRUE_PREVIEW_ID.'_channel_name'] = '';
    $conds[TRUE_PREVIEW_ID.'_url_title'] = '';
    
    // fetch tagdata
    $tagdata = $this->EE->TMPL->tagdata;
    
    if ($site_id AND $entry_id AND $channel_id)
    {
      $sql_entry_data = "SELECT exp_channels.channel_name, exp_channel_titles.url_title
                         FROM exp_channels, exp_channel_titles
                         WHERE exp_channels.channel_id = exp_channel_titles.channel_id AND exp_channel_titles.channel_id = '".$channel_id."' AND exp_channel_titles.entry_id = '".$entry_id."' AND exp_channels.site_id = '".$site_id."' 
                         LIMIT 1";
      $query_entry_data = $this->EE->db->query($sql_entry_data);
      
      if ($query_entry_data->num_rows() == 1)
      {
        $entry_data_row_array = $query_entry_data->row_array();
        $channel_name = $entry_data_row_array['channel_name'];
        $url_title = $entry_data_row_array['url_title'];
        $conds[TRUE_PREVIEW_ID.'_channel_name'] = $channel_name;
        $conds[TRUE_PREVIEW_ID.'_url_title'] = $url_title;
      }
    }
    
    // prepare conditionals
    $tagdata = $this->EE->functions->prep_conditionals($tagdata, $conds);
    // output variables
    foreach ($conds as $key => $val)
    {
      $tagdata = $this->EE->TMPL->swap_var_single($key, $val, $tagdata);
    }
    //echo '$tagdata: '.$tagdata;
    
    return $tagdata;
  }
  // END FUNCTION
}
// END CLASS
?>