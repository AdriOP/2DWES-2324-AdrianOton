<HTML> 
<HEAD> <TITLE> calculadora </TITLE>
</HEAD>
<BODY>

    <h1>Calculadora</h1>
    <form name='formcalc' action='' method='post'>

        <label for="operand1">Primer Operando:</label>
        <input type="number" id="operand1" name="operand1" required>
        <br>
        <label for="operand2">Segundo Operando:</label>
        <input type="number" id="operand2" name="operand2" required>
        <br>
        
       
            <input type=radio name=operation value="sum">Suma</input>
            <input type=radio name=operation value="rest">Resta</input>
            <input type=radio name=operation value="mult">Multiplicacion</input>
            <input type=radio name=operation value="div">Division</input>

        <br>
        <button type="submit">Calcular</button>
        <button type="button" onclick="resetForm()">Borrar</button>
    </form>

    <div id="result">
       
    </div>

 
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

</BODY>
</HTML>
