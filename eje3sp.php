<<HTML>
<HEAD><TITLE> EJ2-Direccion Red – Broadcast y Rango</TITLE></HEAD>
<BODY>
<?php
$ip1 = "192.168.16.100/16";

list($ip, $Mask) = explode('/', $ip1);

list($o1, $o2, $o3, $o4) = explode('.', $ip);

$ipBin = str_pad(decbin($o1), 8, '0', STR_PAD_LEFT) .
             str_pad(decbin($o2), 8, '0', STR_PAD_LEFT) .
             str_pad(decbin($o3), 8, '0', STR_PAD_LEFT) .
             str_pad(decbin($o4), 8, '0', STR_PAD_LEFT);

$MaskBin= str_repeat('1', $Mask) . str_repeat('0', 32 - $Mask);
$netBin = $ipBin & $MaskBin;
$broadcastBin = $ipBin | ~$MaskBin;

$firstIpBin = substr($netBin, 0, -1) . '1';
$lastIpBin = substr($broadcastBin, 0, -1) . '0';

$network = implode('.', str_split($netBin, 8));
$broadcast = implode('.', str_split($broadcastBin, 8));
$firstIp = implode('.', str_split($firstIpBin, 8));
$lastIp = implode('.', str_split($lastIpBin, 8));

$network = bindec(substr($netBin, 0, 8)) . "." . bindec(substr($netBin, 8, 8)) . "." . bindec(substr($netBin, 16, 8)) . "." . bindec(substr($netBin, 24, 8));
$broadcast = bindec(substr($broadcastBin, 0, 8)) . "." . bindec(substr($broadcastBin, 8, 8)) . "." . bindec(substr($broadcastBin, 16, 8)) . "." . bindec(substr($broadcastBin, 24, 8));
$firstIp = bindec(substr($firstIpBin, 0, 8)) . "." . bindec(substr($firstIpBin, 8, 8)) . "." . bindec(substr($firstIpBin, 16, 8)) . "." . bindec(substr($firstIpBin, 24, 8));
$lastIp = bindec(substr($lastIpBin, 0, 8)) . "." . bindec(substr($lastIpBin, 8, 8)) . "." . bindec(substr($lastIpBin, 16, 8)) . "." . bindec(substr($lastIpBin, 24, 8));
echo "IP $ip1\n";
echo "Mascara $Mask\n";
echo "Direccion Red: $network\n";
echo "Direccion Broadcast: $broadcast\n";
echo "Rango: $firstIp a $lastIp\n";
?>
</BODY>
</HTML>
