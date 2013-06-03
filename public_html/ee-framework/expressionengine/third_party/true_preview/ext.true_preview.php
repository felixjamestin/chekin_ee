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

class True_preview_ext {

  var $settings = array();
  var $name = TRUE_PREVIEW_NAME;
  var $version = TRUE_PREVIEW_VER;
  var $description = TRUE_PREVIEW_DESC;
  var $settings_exist = 'n';
  var $docs_url = TRUE_PREVIEW_DOCS;
  
  var $author = TRUE_PREVIEW_AUTHOR;
  var $site_id = 1;
  
  // -------------------------------
 	// Constructor
 	// -------------------------------
  
  function True_preview_ext($settings = array())
  {
    $this->EE =& get_instance();
    
    return TRUE;
  }
  // END FUNCTION
  
  // --------------------------------
 	//  Activate Extension
 	// --------------------------------
  
  function activate_extension($settings = array())
  {    
    $data = array(
      'class'		=> __CLASS__,
      'method'	=> 'cp_css_end',
      'hook'		=> 'cp_css_end',
      'priority'	=> 10,
      'version'	=> $this->version,
      'enabled'	=> 'y',
      'settings'	=> serialize($settings)
    );
    $this->EE->db->insert('exp_extensions', $data);
    
    $data = array(
      'class'		=> __CLASS__,
      'method'	=> 'cp_js_end',
      'hook'		=> 'cp_js_end',
      'priority'	=> 10,
      'version'	=> $this->version,
      'enabled'	=> 'y',
      'settings'	=> serialize($settings)
    );
    $this->EE->db->insert('exp_extensions', $data);
    
    $data = array(
      'class'		=> __CLASS__,
      'method'	=> 'entry_submission_redirect',
      'hook'		=> 'entry_submission_redirect',
      'priority'	=> 1,
      'version'	=> $this->version,
      'enabled'	=> 'y',
      'settings'	=> serialize($settings)
    );
    $this->EE->db->insert('exp_extensions', $data);
    
    return TRUE;
  }
  // END FUNCTION
  
  // --------------------------------
 	//  Update Extension
 	// --------------------------------
  
  function update_extension($current = '')
  {
    if ($current == '' OR $current == $this->version)
  		{
  			return FALSE;
  		}
    
    if ($current < $this->version)
    {
      $this->EE->db->query("UPDATE exp_extensions 
  					SET version = '".$this->version."' 
  					WHERE class = '".get_class($this)."'");
    }
    
    return TRUE;
  }
  // END FUNCTION
  
  // --------------------------------
 	//  Disable Extension
 	// --------------------------------
  
  function disable_extension()
  {
    $this->EE->db->query("DELETE FROM exp_extensions WHERE class = '".__CLASS__."'");
    
    return TRUE;
  }
  // END FUNCTION
  
  function cp_css_end()
  {
    $css = " #accessoryTabs ul li a.".TRUE_PREVIEW_ID." { display:none; } ";
    
    // check in module's settings if channel's name should be added ++++++++++++++++++
    $css .= " #breadCrumb ol { display:none; } ";
    
    return $this->EE->extensions->last_call.$css;
  }
  
  function cp_js_end()
  {
    $js = " $('#accessoryTabs ul li:has(a.".TRUE_PREVIEW_ID.")').hide(); $('#breadCrumb ol').show(); ";
    
    return $this->EE->extensions->last_call.$js;
  }
  // END FUNCTION
  
  function entry_submission_redirect($entry_id, $meta, $data, $cp_call, $orig_loc)
  {
    //var_dump($entry_id);
    //var_dump($meta);
    //var_dump($data);
    //var_dump($cp_call);
    //var_dump($orig_loc);
    $edit_form_uri = str_replace('view_entry', 'entry_form', $orig_loc);
    
    return $edit_form_uri;
  }
  // END FUNCTION
  
  function _current_uri()
  {
    if (!isset($_SERVER['REQUEST_URI'])) {
        $serverrequri = $_SERVER['PHP_SELF'];
    } else {
        $serverrequri = $_SERVER['REQUEST_URI'];
    }
    /**
     * server request uri depends upon server OS version
     */
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    /**
     * get protocal if SSL enabled.
     */
    $serverprotocol = current(explode('/', strtolower($_SERVER["SERVER_PROTOCOL"]))) . $s;
    $serverport = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
    /**
     * Page might be run with IP and Port also like 127.0.0.1:8080
     */
    return $serverprotocol . "://" . $_SERVER['SERVER_NAME'] . $serverport . $serverrequri;
  }
  // END FUNCTION
}
// END CLASS
?>