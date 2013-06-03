<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Currency Converter Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Percipio.Me Ltd
 * @link		http://addons.percipio.me
 * @copyright   Copyright (c) 2012 Percipio.Me Ltd
 * @license		http://addons.percipio.me/licence	
 */

$plugin_info = array(
	'pi_name'		=> 'Currency Converter',
	'pi_version'	=> '1.0.1',
	'pi_author'		=> 'Percipio.Me Ltd',
	'pi_author_url'	=> 'http://addons.percipio.me',
	'pi_description'=> 'Converts currencies through Google calculator',
	'pi_usage'		=> Currency_converter::usage()
);


class Currency_converter {

	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>
------------------
Base Tag:
------------------

{exp:currency_converter:convert_currency amount="100" from="USD" to="EUR" round="up" round_to="99" decimal="2"}

------------------
PARAMETERS:
------------------

amount="100"	 -	Required. Value to convert.	

from="USD" -	Required. Currency to convert FROM	

to="EUR"	 -	Required. Currency to convert TO	

round="up|down" - Facility to round result up or down to next full value.

round_to="00"	 -	Use in conjunction with round parameter - useful for rounding product prices to nearest 99 / 00.

decimal="2"	 -	Number of decimal places to use. 

For a full list of currency codes, visit http://www.google.com/finance/converter.

------------------
Example:
------------------

{exp:currency_converter:convert_currency amount="12.99" from="USD" to="GBP"} Output = 8.4305 (at exchange rate on 18th Jan)

{exp:currency_converter:convert_currency amount="12.99" from="USD" to="GBP" round_to="00" } = 8

{exp:currency_converter:convert_currency amount="12.99" from="USD" to="GBP" round="up" round_to="00"} = 9.00

{exp:currency_converter:convert_currency amount="12.99" from="USD" to="GBP" round="up" round_to="99"} = 8.99

{exp:currency_converter:convert_currency amount="12.99" from="USD" to="GBP" round="down" round_to="99"} = 7.99

{exp:currency_converter:convert_currency amount="12.99" from="USD" to="GBP" round="down" round_to="00"} = 8.00

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	
	public function convert_currency()
	{
		$this->EE->load->library(array('currency'));
		
		$amount = $this->EE->TMPL->fetch_param('amount');
		$from 	= $this->EE->TMPL->fetch_param('from');
		$to 	= $this->EE->TMPL->fetch_param('to');
	
		$round_to 	= $this->EE->TMPL->fetch_param('round_to');
		$round 		= $this->EE->TMPL->fetch_param('round');
		$decimal 	= $this->EE->TMPL->fetch_param('decimal');
		
		$res = $from != $to ? $this->EE->currency->convert_currency($amount, $from, $to) : $amount;

		if ($round == 'up') 
			$res = ceil($res);			
		elseif ($round == 'down')
			$res = floor($res);

		if ($round_to) 
		{
			$strlen = strlen( $round_to );
			$res = floor($res) + $round_to / pow(10, strlen( $round_to ));
			$ratio = (int)$round_to ? 1 : 0;
			$res = sprintf("%.0{$strlen}f", $res-$ratio);
		}
		if ($decimal)
		{ 
			$res = number_format(round($res, 0));
			//$res = sprintf("%.0{$decimal}f", $res);
		}
		
		return $res;
	}	
}
/* End of file pi.currency_converter.php */
/* Location: /system/expressionengine/third_party/currency_converter/pi.currency_converter.php */