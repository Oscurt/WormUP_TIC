<?php
include_once "encabezado.php";
date_default_timezone_set('America/Santiago');
?>

<title>Probar</title>
</header>

<body>

    <style>
        #minMessage {
            color: #f00;
            display: none;
        }

        #enviar:disabled {
            background: #666;
        }
        .error{
        outline: 1px solid red;
    }    
    </style>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
$(document).ready(function(){
    $('#enviar').click(function(){
    $('input').each(function() {
        if(!$(this).val()){
            alert('Algunas casillas están vacias, le pedimos ingresar todos los datos');
           $('#Form').attr('action', '/index.php');
           return false;
        }else{
            $('#Form').attr('action', '/calculo.php');
        }
    });
});
});
</script>

    <form style="text-align:center;" method="post" id="Form" >
        <fieldset>
            <legend>Datos para prueba</legend>
            <input type="hidden" id="fecha" name="fecha" value="<?php echo date("d/m/Y H:i:s"); ?>"></input>
            <center>
                <table border="2" style="width:90%;text-align:center;">
                    <tr>

                        <th><label for="detalle">Detalle del caso</label><br>
                            <h6>(Responsable, situacion que se simula y numero o escenario de simulación)<br>Maximo 60 caracteres</h6>
                        </th>
                        <th><label for="horas de prueba">Horas de prueba</label></th>
                        <th>
                            <laber for="clientes esperados">Clientes esperados</label>
                        </th>

                    </tr>
                    <tr>

                        <td><input type="text" id="texto" name="texto" maxlength="60"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="0.1" max="24" id="horas" name="horas" step="0.1"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        
                        <td><input type="number" min="1" step="1" id="clientesesp" name="clientesesp" pattern="[0-9]"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>

                    </tr>
                </table>
            </center><br>

            <center>
                <table border="2" style="width:90%;text-align:center;">
                    <tr>
                        <th><label for="productos minimos">Productos minimo por cliente</label></th>
                        <th><label for="productos maximos">Productos maximo por cliente</label></th>
                        <th><label for="tiempo seleccion de productos">Tiempo selección de productos (en segundos)</label></th>
                        <th><label for="marcado por producto">Tiempo de marcado por producto (segundos)</label></th>

                    </tr>
                    <tr>

                        <td><input type="number" min="1" id="minimos" name="minimos" step="1" pattern="[0-9]"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="1" id="maximos" name="maximos" step="1" pattern="[0-9]"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="0.1" id="productos" name="productos" step="0.1"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="0.1" id="marcado" name="marcado" step="0.1"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>

                    </tr>
                </table>
            </center><br>

            <center>
                <table border="2" style="width:22.5%;text-align:center;">
                    <tr>
                        <th><label for="marcado por producto">Tiempo de pago por cliente (en segundos)</label></th>

                    </tr>
                    <tr>

                        <td><input type="number" min="0.1" id="pago" name="pago" step="0.1"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>

                    </tr>
                </table>
            </center><br>

            <center>
                <table border="2" style="width:67.5%;text-align:center;">
                    <tr>
                        <th><label for="Intervalo">Numero intervalo</label></th>
                        <th><label for="distribución porcentual">Distribución procentual de clientes (total 100%)</label></th>
                        <th><label for="cajas abiertas">Cajas abiertas</label></th>
                    </tr>
                    <?php
                    for ($i = 1; $i <= $_POST["periodos"]; $i++) {
                    ?>
                        <tr>

                            <td>
                                <h5><?php echo $i; ?></h5>
                            </td>
                            <td><input type="number" min="0.1" id="distribucion<?php echo $i; ?>" name="distribucion" step="0.1"><br>
                                <p id="minMessage">Tienes que escribir algo bro</p>
                            </td>
                            <td><input type="number" min="1" id="cajas<?php echo $i; ?>" name="cajas" step="1" pattern="[0-9]"><br>
                                <p id="minMessage">Tienes que escribir algo bro</p>
                            </td>

                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </center><br><br>

            <input type="submit" id="enviar" onclick="return validateForm()" style="background-color:green; color:white;" value="Probar">
        </fieldset>
    </form>

</body>
</footer>
<?php
include_once "pie.php"
?>
