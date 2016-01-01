<?php
header('Content-type: text/xml; charset=utf-8');

$url = "https://api.privatbank.ua/p24api/pay_pb";

require_once 'login.php';

//$p24_merid = "";
//$p24_card = "";
//$p24_card = "";
//$id = "";
//$p24_password = "";


/*$data  = '<oper>cmt</oper>';
$data .= '<wait>0</wait>';
$data .= '<test>0</test>';
$data .= '<payment id="">';
$data .= '<prop name="sd" value="01.01.2015" />';
$data .= '<prop name="ed" value="30.04.2015" />';
$data .= '<prop name="p24_card" value="' . $p24_card . '" />';
$data .= '</payment>';*/


$data  =  '<oper>cmt</oper><wait>0</wait><test>0</test><payment id=""><prop name="b_card_or_acc" value="5168755320902857" /><prop name="amt" value="1" /><prop name="ccy" value="UAH" /><prop name="details" value="test" /></payment>';

//echo $data;



//$sign=sha1(md5($data.$p24_password));

//echo "sign = " . calcSignature($data,$p24_password) . "<br/>";

$postdata = '<?xml version="1.0" encoding="UTF-8"?><request version="1.0"><merchant>';
$postdata .= '<id>' . $p24_merid .'</id>';
$postdata .= '<signature>'.calcSignature($data,$p24_password).'</signature>';
$postdata .= '</merchant><data>'.$data.'</data></request>';

//echo $postdata;

$uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

$header = array();
$header[] = "Content-Type: text/xml";
$header[] = "\r\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
$rez = curl_exec($ch);

echo $rez;
//echo curl_errno($ch)."<br/>";
//echo curl_error($ch)."<br/>";
//echo curl_getinfo($ch)."<br/>";
curl_close($ch);


	function calcSignature($data,$p24_password) { // расчёт сигнатуры
		return sha1(md5($data.$p24_password));
	}

?>