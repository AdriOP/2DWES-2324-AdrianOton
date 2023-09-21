
<!DOCTYPE html>
<html>
<head>
    <title>EJ1-Conversion IP Decimal a Binario</title>
	
</head>
<body>
    <?php
    $ip = "192.18.16.204";
    $num = explode(".", $ip);

    $binario = "";
    
    list($n1, $n2, $n3, $n4) = $num;
    
    $binario .= str_pad(decbin($n1), 8, "0", STR_PAD_LEFT) . ".";
    $binario .= str_pad(decbin($n2), 8, "0", STR_PAD_LEFT) . ".";
    $binario .= str_pad(decbin($n3), 8, "0", STR_PAD_LEFT) . ".";
    $binario .= str_pad(decbin($n4), 8, "0", STR_PAD_LEFT);
	  echo "La IP $ip en binario es   $binario <br/>";
    ?>
</body>
</html>
