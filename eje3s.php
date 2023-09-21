<HTML>
<HEAD><TITLE> EJ2-Direccion Red – Broadcast y Rango</TITLE></HEAD>
<BODY>
<?php
$ip1="192.168.16.100/16";
$num1 = explode(".", $ip1);
$mask1= explode ("/", $ip1); 
$bin1="";

$ip2="192.168.16.100/21";
$num2 = explode(".", $ip2);
$mask2= explode ("/", $ip2); 
$bin2="";

$ip3="10.33.15.100/8";
$num3 = explode(".", $ip3);
$mask3= explode ("/", $ip3); 
$bin3="";


 list($n11, $n12, $n13, $n14) = $num1;
    
    $bin1 .= str_pad(decbin($n11), 8, "0", STR_PAD_LEFT) . ".";
    $bin1 .= str_pad(decbin($n12), 8, "0", STR_PAD_LEFT) . ".";
    $bin1 .= str_pad(decbin($n13), 8, "0", STR_PAD_LEFT) . ".";
    $bin1 .= str_pad(decbin($n14), 8, "0", STR_PAD_LEFT);
	
	
 list($n21, $n22, $n23, $n24) = $num2;
    
    $bin2 .= str_pad(decbin($n21), 8, "0", STR_PAD_LEFT) . ".";
    $bin2 .= str_pad(decbin($n22), 8, "0", STR_PAD_LEFT) . ".";
    $bin2 .= str_pad(decbin($n23), 8, "0", STR_PAD_LEFT) . ".";
    $bin2 .= str_pad(decbin($n24), 8, "0", STR_PAD_LEFT);
	
 list($n31, $n32, $n33, $n34) = $num3;
    
    $bin3 .= str_pad(decbin($n31), 8, "0", STR_PAD_LEFT) . ".";
    $bin3 .= str_pad(decbin($n32), 8, "0", STR_PAD_LEFT) . ".";
    $bin3 .= str_pad(decbin($n33), 8, "0", STR_PAD_LEFT) . ".";
    $bin3 .= str_pad(decbin($n34), 8, "0", STR_PAD_LEFT);
	
 echo "La IP $ip1 en binario es   $bin1   <br/>";
  echo "La IP $ip2 en binario es   $bin2   <br/>";
   echo "La IP $ip3 en binario es   $bin3   <br/>";
?>
</BODY>
</HTML>
