<?php

// HTTP authentication 
$url = "http://trade.aliexpress.com/orderList.htm"; 
$ch = curl_init();     
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($ch, CURLOPT_URL, $url);  
curl_setopt($ch, CURLOPT_USERPWD, "mszerg@gmail.com:Figvam08");  
$result = curl_exec($ch);  
curl_close($ch);  
echo $result; 

?> 