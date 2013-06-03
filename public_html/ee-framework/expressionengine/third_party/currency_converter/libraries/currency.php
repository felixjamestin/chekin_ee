<?php
class Currency {
	public function convert_currency($amount = '100', $from = '', $to = '')
	{
		$amount = urlencode($amount);
		$from 	= urlencode($from);
		$to 	= urlencode($to);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://www.google.com/finance/converter?a={$amount}&from={$from}&to={$to}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)');
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

		$result = curl_exec($ch);
		curl_close($ch);

		preg_match("!<.*?>(\S*)[^<>]*?{$to}<!", $result, $result) ;

		return(trim(@$result[1]));
	}
}

/* End of file libraries/currency.php */