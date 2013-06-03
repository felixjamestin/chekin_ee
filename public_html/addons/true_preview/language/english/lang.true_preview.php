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

$lang = array(

//----------------------------------------
// Required for MODULES page
//----------------------------------------

'true_preview_module_name' => TRUE_PREVIEW_NAME,
'true_preview_module_description' => TRUE_PREVIEW_DESC,

//----------------------------------------
// Edit Entry page
//----------------------------------------

'preview' => 'Preview',
'channel' => 'channel',

//----------------------------------------
// Module Settings page
//----------------------------------------

'yes' => 'Yes',
'no' => 'No',
'settings' => 'Settings',
'documentation' => 'Documentation',
'display_channel_title_question' => 'Display channel title on Edit page?',
'channel_heading' => 'Channel',
'template_heading' => 'Template group/name',
'preferences_update_success_message' => 'Settings have been successfuly updated.',
'url_template_value_error_message' => 'URL template for the channel {channel_title} does not contain slash delimited template group name and template name.',

/* END */
'' => ''
);
?>