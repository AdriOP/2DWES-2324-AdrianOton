<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $operand1 = $_POST["operand1"];
        $operand2 = $_POST["operand2"];
        $operation = $_POST["operation"];

       
            if ($operation === "sum") {
                echo "<div>Resultado: " . ($operand1 + $operand2) . "</div>";
            } elseif ($operation === "rest") {
                echo "<div>Resultado: " . ($operand1 - $operand2) . "</div>";
            } elseif ($operation === "mult") {
                echo "<div>Resultado: " . ($operand1 * $operand2) . "</div>";
            } elseif ($operation === "div") {
                if ($operand2 == 0) {
                    echo "<div>No se puede dividir por cero.</div>";
                } else {
                    echo "<div>Resultado: " . ($operand1 / $operand2) . "</div>";
                }
            } else {
                echo "<div>error.</div>";
            }
        } else {
            echo "<div>Por favor, ingrese 2 números válidos.</div>";
        }
    
    ?>
</body>
</html>
