<?php
include_once "encabezado.php";
date_default_timezone_set('America/Santiago');
require './base/vendor/autoload.php';

$uri = "mongodb://localhost";
$client = new MongoDB\Client($uri);
$collection = $client->tics->pruebas->find();
$simulaciones = array();

?>

<title>Pruebas Anteriores</title>
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

<?php
  foreach($collection as $entry){
    $ID_simulacion = $entry["0"]['ID_simulacion'];
    $Horas_diarias_atencion = $entry["0"]['Horas_diarias_atencion'];
    $Num_periodos_tiempo = $entry["0"]['Num_periodos_tiempo'];
    $Numero_total_clientes_Xdia = $entry["0"]['Numero_total_clientes_Xdia'];
    $Cantidad_min_productos_Xclientes = $entry["0"]['Cantidad_min_productos_Xclientes'];
    $Cantidad_max_productos_Xclientes = $entry["0"]['Cantidad_max_productos_Xclientes'];
    $Tiempo_promedio_seleccion_Xproducto = $entry["0"]['Tiempo_promedio_seleccion_Xproducto'];
    $Tiempo_promedio_despacho_Xproducto = $entry["0"]['Tiempo_promedio_despacho_Xproducto'];
    $Tiempo_promedio_pago = $entry["0"]['Tiempo_promedio_pago'];
?>


<button type="submit" class="btn-results" id="<?php echo $ID_simulacion ?>" value="<?php echo $entry["_id"] ?>" onclick="mostrarContenido(this)">
    Mostrar <?php echo $ID_simulacion ?>
  </button>
  

  <div id="<?php echo $entry["_id"] ?>" style="display: none">
    <center>
    <legend>Descripción caso de simulación <?php echo $ID_simulacion ?></legend>
      <table border="2" style="width:90%;text-align:center;">
          <tr>
              <th>Horas diarias atencion</th>
              <th>Número de periodos de tiempo</th>
              <th>Número total clientes esperados</th>
              <th>Mínimo de productos por cliente</th>
              <th>Máximo de productos por cliente</th>
          </tr>
          <tr>
              <td>
                  <?php echo $Horas_diarias_atencion ?>
              </td>
              <td>
                  <?php echo $Num_periodos_tiempo ?>
              </td>
              <td>
                  <?php echo $Numero_total_clientes_Xdia ?>
              </td>
              <td>
                  <?php echo $Cantidad_min_productos_Xclientes ?>
              </td>
              <td>
                  <?php echo $Cantidad_max_productos_Xclientes ?>
              </td>
          </tr>
      </table>
      <br>
      <table border="2" style="width:90%;text-align:center;">
        <tr>
            <th>Tiempo promedio de selección por producto</th>
            <th>Tiempo promedio de despacho por producto</th>
            <th>Tiempo promedio de pago en la caja</th>
        </tr>
        <tr>
          <td>
            <?php echo $Tiempo_promedio_seleccion_Xproducto ?>
          </td>
          <td>
            <?php echo $Tiempo_promedio_despacho_Xproducto ?>
          </td>
          <td>
            <?php echo $Tiempo_promedio_pago ?>
          </td>
        </tr>
      </table>
      <br>
      <legend>Resultados de simulación <?php echo $ID_simulacion ?></legend>
      <?php
        for($i = 1; $i <= $Num_periodos_tiempo; $i++){
      ?>
        <div class="intervalos"><b>Intervalo número <?php echo $i ?></b><br>
          <span class="intervalos-item">Tiempo total simulado: <?php echo $entry["${i}"]["Tiempo_total_simulado"] ?></span>
          <span class="intervalos-item">Cantidad de clientes ingresados: <?php echo $entry["${i}"]["Cant_clientes_ingresados"] ?></span>
          <span class="intervalos-item">Cantidad de clientes despachados: <?php echo $entry["${i}"]["Cant_clientes_despachados"] ?></span>
          <span class="intervalos-item">Promedio de productos despachados: <?php echo $entry["${i}"]["Promedio_productos_despachados"] ?></span>
          <span class="intervalos-item">Promedio de clientes esperando en colas de cajas: <?php echo $entry["${i}"]["Promedio_clientes_esperando_en_colas_de_cajas"] ?></span>
          <span class="intervalos-item">Longitud máxima cola de espera: <?php echo $entry["${i}"]["Longitud_max_cola_de_espera"] ?></span>
          <hr>
      </div>
    <?php
      }
    ?>
    </center><br><br><br>
    <a href="http://ec2-54-157-167-58.compute-1.amazonaws.com/index.php?again=1&id=<?php echo $entry["_id"] ?>" type="submit">Nueva simulación cambiando valores</a>
  </div>

<?php
  }
?>
<footer>
<script>

  function mostrarContenido(btnResults){
    let contenidoDiv = document.getElementById(btnResults.value);
    contenidoDiv.style.display = "block";
    btnResults.innerHTML = "Ocultar " + btnResults.id;
    btnResults.setAttribute("onclick", "ocultarContenido(this)");
  }

  function ocultarContenido(btnResults){
    let contenidoDiv = document.getElementById(btnResults.value);
    contenidoDiv.style.display = "none";

    btnResults.innerHTML = "Mostrar" + btnResults.id;
    btnResults.setAttribute("onclick", "mostrarContenido(this)");
  }
</script>
<?php
include_once "pie.php"
?>
