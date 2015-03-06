<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Email Trigger
*
* @package Email Trigger
* @author  Srikanth Aetukuru
*/

$plugin_info = array(
	'pi_name'			=> 'Email Trigger',
	'pi_version'		=> '1.0',
	'pi_author'			=> 'Srikanth Aetukuru',
	'pi_author_url'		=> '',
	'pi_description'	=> '',
	'pi_usage'			=> email_trigger::usage(),
);


$__parent_folder = rtrim(realpath(rtrim(dirname(__FILE__), "/") . '/../'), '/') . '/freeform/';

class email_trigger extends Freeform
{
	public $return_data = "";
	
	public function __construct ()
	{
		parent::__construct();
        }
        
        public function send() {
                ee()->load->add_package_path(PATH_THIRD.'freeform/');
                ee()->load->library('freeform_notifications');
                
                $entry_id = (ee()->TMPL->fetch_param('freeform_entry_id'))? ee()->TMPL->fetch_param('freeform_entry_id') : "" ;
		$form_id = (ee()->TMPL->fetch_param('freeform_form_id'))? ee()->TMPL->fetch_param('freeform_form_id') : "" ;
		
                $data = ee()->freeform_entry_model->get_entry_data($entry_id, $form_id);
                print_r($data);
                
                if($data['form_field_34'] == "no") {
                    $send_date = $data['form_field_31'];
                    $send_date_format = date('Ymd',strtotime($send_date));
                    $today = date('Ymd',time());
                    if($send_date_format == $today) {
                        
                        $field_input_data = array('entry_id' => $entry_id);
                        
                        ee()->freeform_notifications->send_notification(array(
                                'form_id'			=> $form_id,
                                'entry_id'			=> $entry_id,
                                'notification_type'	=> true,
                                'form_input_data'	=> $field_input_data,
                                'recipients'		=> $data['form_field_24'],
                                'template'			=> 'gift_purchase'
                        ));
                    }
                }

		if ($data === FALSE)
		{
			$this->return_data = lang('invalid_date');
		}
		else
		{
			$this->return_data = $data;
		}
	}
	

// ----------------------------------------
//  Plugin Usage
// ----------------------------------------

// This function describes how the plugin is used.

function usage()
{
ob_start(); 
?>



<?php
$buffer = ob_get_contents();
	
ob_end_clean(); 

return $buffer;
}
/* END */

} 
// END Email Trigger Class

/* End of file  pi.email_trigger.php */
/* Location: ./system/expressionengine/third_party/email_trigger/pi.email_trigger.php */