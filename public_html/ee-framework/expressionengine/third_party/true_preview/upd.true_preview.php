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

class True_preview_upd {

  var $version = TRUE_PREVIEW_VER;
  
  // ----------------------------------------
 	//	Constructor
 	// ----------------------------------------
  
  function True_preview_upd()
  {
    $this->EE =& get_instance();
  }
  // END FUNCTION
  
  // ----------------------------------------
 	//	Module installer
 	// ----------------------------------------
  
  function install()
  {
    $data = array(
  			'module_name' 	 => TRUE_PREVIEW_CLASS,
  			'module_version' => TRUE_PREVIEW_VER,
  			'has_cp_backend' => 'y'
  		);
    
    $this->EE->db->insert('modules', $data);
    
    $sql[] = "CREATE TABLE IF NOT EXISTS exp_true_preview_prefs
              (
                site_id INT(5) UNSIGNED NOT NULL,
                channel_id INT(5) UNSIGNED NOT NULL,
                template VARCHAR(120) NULL,
                display_channel_title VARCHAR(1) NOT NULL DEFAULT 'y',
                PRIMARY KEY (site_id, channel_id)
              )";
    
    foreach ($sql as $query)
  		{
  			$this->EE->db->query($query);
  		}
    
    return TRUE;
  }
  // END FUNCTION
  
  function uninstall()
  {
    $query = $this->EE->db->query("SELECT module_id
  							 FROM exp_modules 
  							 WHERE module_name = '".TRUE_PREVIEW_CLASS."'");
  
    $sql[] = "DELETE FROM exp_module_member_groups 
  				  WHERE module_id = '".$query->row('module_id')."'";
        
    $sql[] = "DELETE FROM exp_modules 
  				  WHERE module_name = '".TRUE_PREVIEW_CLASS."'";
  
    foreach ($sql as $query)
  		{
  			$this->EE->db->query($query);
  		}
    
    return TRUE;
  }
  // END FUNCTION
  
  // ----------------------------------------
 	//	Module updater
 	// ----------------------------------------
  
  function update($current = '')
 	{
 		 // update version number
  		$sql[] = "UPDATE exp_modules SET module_version = '".TRUE_PREVIEW_VER."' WHERE module_name = '".TRUE_PREVIEW_CLASS."' ";
    
    foreach ($sql as $query)
  		{
  			$this->EE->db->query($query);
  		}
    
    return TRUE;
 	}
  // END FUNCTION
}
// END CLASS
?>