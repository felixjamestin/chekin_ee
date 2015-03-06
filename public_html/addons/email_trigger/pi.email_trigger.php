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
                $status = false;
                
                if($data['form_field_34'] == "no" && $data['form_field_21'] == "Transaction Successful" && $data['form_field_33'] != "") {
                    
                    $send_date = $data['form_field_31'];
                    $send_date_format = date('Ymd',strtotime($send_date));
                    $today = date('Ymd',time());
                    //only on same day
                    if($send_date_format == $today) {
                        
                        $field_input_data = array('entry_id' => $entry_id);
                       
                        $email = array(
                                'form_id'			=> $form_id,
                                'entry_id'			=> $entry_id,
                                'notification_type'	=> true,
                                'form_input_data'	=> $field_input_data,
                                'recipients'		=> $data['form_field_24'],
                                'template'			=> 'gift_purchase'
                        );
                        $field_input_data = array();
                        if(ee()->freeform_notifications->send_notification($email)) {
                           $field_input_data['email_sent'] = "yes";
                            ee()->freeform_forms->update_entry(
				$form_id,
				$entry_id,
				$field_input_data
                            ); 
                            $status = true;
                        }
                        
                    }
                }
                 return $status;		
	}
	

// ----------------------------------------
//  Plugin Usage
// ----------------------------------------

// This function describes how the plugin is used.

function usage()
{

}
/* END */

} 
// END Email Trigger Class

/* End of file  pi.email_trigger.php */
/* Location: ./system/expressionengine/third_party/email_trigger/pi.email_trigger.php */