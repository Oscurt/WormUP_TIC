<?php
include_once "encabezado.php";
date_default_timezone_set('America/Santiago');
session_start();
$_SESSION["periodos"]=$_POST["periodos"];


?>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
$(document).ready(function(){
    $('#enviar').click(function(){
    $('input').each(function() {
        if(!$(this).val()){
            alert('Algunas casillas están vacias, le pedimos ingresar todos los datos.');
           $('#Form').attr('action', '/ingresodatos.php');
           return false;
        }else{
            $('#Form').attr('action', '/resultados.php');
        }
    });
});
});
</script>
<form style="text-align:center;" method="POST" id="Form">
        <fieldset>
            <legend>Datos para prueba</legend>
            <input type="hidden" id="fecha" name="fecha" value="<?php echo date("d/m/Y H:i:s"); ?>"></input>
            <center>
                <table border="2" style="width:90%;text-align:center;">
                    <tr>

                        <th><label for="detalle">Detalle del caso</label><br>
                            <h6>(Responsable, situacion que se simula y numero o escenario de simulación)<br>Maximo 60 caracteres</h6>
                        </th>
                        <th><label for="horas de prueba">Horas diaras de atención</label></th>
                        <th>
                            <laber for="clientes esperados">Total clientes esperados</label>
                        </th>

                    </tr>
                    <tr>

                        <td><input type="text" id="texto" name="ID_simulacion" maxlength="60"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="0.1" max="24" id="horas" name="Horas_diarias_atencion" step="0.1"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="1" step="1" id="clientesesp" name="Numero_total_clientes_Xdia" pattern="[0-9]"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>

                    </tr>
                </table>
            </center><br>

            <center>
                <table border="2" style="width:90%;text-align:center;">
                    <tr>
                        <th><label for="productos minimos">Mínimo de productos por cliente</label></th>
                        <th><label for="productos maximos">Máximo de productos por cliente</label></th>
                        <th><label for="tiempo seleccion de productos">Tiempo selección de productos (en segundos)</label></th>
                        <th><label for="marcado por producto">Tiempo de marcado por producto (segundos)</label></th>

                    </tr>
                    <tr>

                        <td><input type="number" min="1" id="minimos" name="Cantidad_min_productos_Xclientes" step="1" pattern="[0-9]"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="1" id="maximos" name="Cantidad_max_productos_Xclientes" step="1" pattern="[0-9]"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="0.1" id="productos" name="Tiempo_promedio_seleccion_Xproducto" step="0.1"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="0.1" id="marcado" name="Tiempo_promedio_despacho_Xproducto" step="0.1"><br>
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

                        <td><input type="number" min="0.1" id="pago" name="Tiempo_promedio_pago" step="0.1"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>

                    </tr>
                </table>
            </center><br>

            <center>
                <table border="2" id="tabla_intervalos" style="width:67.5%;text-align:center;">
                    <tr>
                        <th><label for="Intervalo">Numero intervalo</label></th>
                        <th><label for="distribución porcentual">Distribución procentual de clientes (total 100%)</label></th>
                        <th><label for="cajas abiertas">Cajas abiertas</label></th>

                    </tr>
                    <?php

                    for ($i = 1; $i <= $_SESSION["periodos"]; $i++) {
                    ?>
                        <tr id="tr_inputs<?php echo $i?>">

                            <td>
                                <h5><?php echo $i; ?></h5>
                            </td>
                            <td><input type="number" min="0.1" id="distribucion<?php echo $i; ?>" name="distribucion<?php echo $i; ?>" step="0.1"><br>
                                <p id="minMessage">Tienes que escribir algo bro</p>
                            </td>
                            <td><input type="number" min="1" id="cajas<?php echo $i; ?>" name="cajas<?php echo $i; ?>" step="1" pattern="[0-9]"><br>
                                <p id="minMessage">Tienes que escribir algo bro</p>
                            </td>


                        </tr>
                    <?php
                    }
                    ?>

                </table>
            </center><br><br>

            <input type="submit" id="enviar" style="background-color:green; color:white;" value="Probar">
        </fieldset>
    </form>


<script>
        let contadorIntervalos = 10;

    $(document).ready(function(){
        $('#enviar').click(function(){
            let periodos = document.getElementById("periodos");
            let sumaPorcentajes = 0;
            let maximos = document.getElementById("maximos");
            let minimos = document.getElementById("minimos");

            
            for(let i = 1; i <= contadorIntervalos; i++){
                let distribucion = document.getElementById(`distribucion${i}`);
                sumaPorcentajes += parseFloat(distribucion.value);
            }


            if(sumaPorcentajes !== 100){
                    alert("El total de la distribución porcentual de clientes debe ser 100%.");
                    return false;
            }else if(periodos.value != contadorIntervalos){
                alert("El número de periodos de tiempo debe ser igual a la cantidad de intervalos agregados.");
                return false;
            }else if(parseInt(maximos.value) <= parseInt(minimos.value)){
                alert("El máximo de productos debe ser mayor al mínimo. ");
                return false;
            }
            
        });
    });

    </script>

</body>


<?php
/*echo "<SCRIPT>
                               alert('La sesion se encuentra iniciada en otro computador o comuniquese con un administrativo')
                               window.location.replace('./login.php');
                          </SCRIPT>";*/


include_once "pie.php"
?>
