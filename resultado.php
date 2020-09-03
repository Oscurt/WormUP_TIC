<?php
require './base/vendor/autoload.php';
date_default_timezone_set('UTC');
include_once "encabezado.php";
date_default_timezone_set('America/Santiago');

if(empty($_POST) || !isset($_POST)){
    header('Location: ./index.php');
}

echo "<center><legend> Resultados de la simulación </legend><center><br>";

class Cliente
{
    public $Num_productos;
    public $Tiempo_en_caja;
    public $Tiempo_en_supermercado;
    public $next = null;

    public function __construct($Cantidad_min_productos_Xclientes, $Cantidad_max_productos_Xclientes)
    {
        $this->Num_productos = rand($Cantidad_min_productos_Xclientes, $Cantidad_max_productos_Xclientes);
        $this->Tiempo_en_caja = 0;
        $this->Tiempo_en_supermercado = -1;
    }
}

class Fila
{
    public $primero;
    public $ultimo;
    public $Cant_clientes_despachados;
    public $Cant_productos_despachados;

    public function __construct()
    {
        $this->primero = null;
        $this->ultimo = null;
        $this->Cant_clientes_despachados = 0;
    }

    public function addLast($X)
    {
        if ($this->primero == null) {
            $this->primero = $X;
            $this->ultimo = $X;
        } else {
            $this->ultimo->next = $X;
            $this->ultimo = $X;
        }
    }

    public function size()
    {
        $contador = 1;
        if ($this->primero == null) {
            return 0;
        } else {
            $current = $this->primero;
            while ($current->next != null) {
                $current = $current->next;
                $contador++;
            }
        }
        return $contador;
    }

    public function Remover_primero()
    {
        if ($this->primero != null) {
            $this->primero = $this->primero->next;
        }
    }

    public function Print()
    {
        if ($this->primero != null) {
            $current = $this->primero;
            while ($current->next != null) {
                echo $current->Num_productos;
                $current = $current->next;
            }
            echo $current->Num_productos;
        }
    }
}

/* Funciones */



function validar_clientes($Supermercado, $Cajas, $Cantidad_min_productos_Xclientes)
{
    for ($i = 0; $i < sizeof($Supermercado); $i++) {
        if (
            $Supermercado[$i]->Tiempo_en_supermercado == 0
            && $Supermercado[$i]->Num_productos >= $Cantidad_min_productos_Xclientes
        ) {

            Encolar($Supermercado[$i], $Cajas);

            // Ahora se remueven del supermercado porque pasaron a caja.
            unset($Supermercado[$i]);
            $Supermercado = array_values($Supermercado);
            $i--;
        }
    }
}

function Encolar($X, $Cajas)
{
    $caja = 0;
    $menor_fila_existe = 0;
    $min = 999999;

    for ($i = 0; $i < sizeof($Cajas); $i++) {
        if ($Cajas[$i]->size() < $min) { // Se busca la caja que tenga la menor fila.
            $min = $Cajas[$i]->size();
            $caja = $i;
            $menor_fila_existe = 1;
        }
    }

    if ($menor_fila_existe == 1) { // Si encuentra la caja, el cliente ingresa ahí.
        $Cajas[$caja]->addLast($X);
    } else {
        $c = rand(0, sizeof($Cajas) - 1);
        $Cajas[$c]->addLast($X); // Sino, ingresa en una caja aleatoria.
    }
}

function Desencolar($Cajas)
{
    for ($i = 0; $i < sizeof($Cajas); $i++) {
        if ($Cajas[$i]->size() != 0) {
            if ($Cajas[$i]->primero->Tiempo_en_caja == 0) {
                $Cajas[$i]->Cant_productos_despachados += $Cajas[$i]->primero->Num_productos;
                $Cajas[$i]->Remover_primero();
                $Cajas[$i]->Cant_clientes_despachados++;
            }
        }
    }
}

function Disminuir_tiempo_en_caja($Cajas)
{
    for ($i = 0; $i < sizeof($Cajas); $i++) {
        if ($Cajas[$i]->primero != null) {
            $Cajas[$i]->primero->Tiempo_en_caja--;
        }
    }
}

function Disminuir_tiempo_en_supermercado($Supermercado)
{
    for ($i = 0; $i < sizeof($Supermercado); $i++) {
        $Supermercado[$i]->Tiempo_en_supermercado--;
    }
}

/* TEST */

// $Horas_diarias_atencion = 10;
// $Num_periodos_tiempo = 10;
// $Numero_total_clientes_Xdia = 500;

// $Distribucion_porcentual_en_intervalos = array(5, 7, 9, 11, 14, 14, 9, 7, 11, 13);
// $Cajas_abiertas_en_intervalos = array(5, 5, 5, 10, 10, 10, 15, 15, 10, 10);

// $Cantidad_min_productos_Xclientes = 1;
// $Cantidad_max_productos_Xclientes = 10;

// $Tiempo_promedio_seleccion_Xproducto = 2;
// $Tiempo_promedio_despacho_Xproducto = 3;
// $Tiempo_promedio_pago = 4;

/* Parametros principales */
$Horas_diarias_atencion = $_POST['Horas_diarias_atencion'];
$Num_periodos_tiempo = $_POST['Num_periodos_tiempo'];
$Numero_total_clientes_Xdia = $_POST['Numero_total_clientes_Xdia'];

$Distribucion_porcentual_en_intervalos = array();
$Cajas_abiertas_en_intervalos = array();

for ($i = 1; $i <= $_POST['Num_periodos_tiempo']; $i++) {
    $Distribucion_porcentual_en_intervalos[$i - 1] = $_POST['distribucion' . strval($i)];
    $Cajas_abiertas_en_intervalos[$i - 1] = $_POST['cajas' . strval($i)];
}

$Cantidad_min_productos_Xclientes = $_POST['Cantidad_min_productos_Xclientes'];
$Cantidad_max_productos_Xclientes = $_POST['Cantidad_max_productos_Xclientes'];

$Tiempo_promedio_seleccion_Xproducto = $_POST['Tiempo_promedio_seleccion_Xproducto'];
$Tiempo_promedio_despacho_Xproducto = $_POST['Tiempo_promedio_despacho_Xproducto'];
$Tiempo_promedio_pago = $_POST['Tiempo_promedio_pago'];


/* COMIENZO DE LA SIMULACION */
$simulacion = array();
$Tiempo_total_simulado = ($Horas_diarias_atencion * 60) / $Num_periodos_tiempo;

array_push($simulacion, array(
    'Distribucion_porcentual_en_intervalos' => $Distribucion_porcentual_en_intervalos,
    'Cajas_abiertas_en_intervalos' => $Cajas_abiertas_en_intervalos,
    'ID_simulacion' => $_POST['ID_simulacion'],
    'Horas_diarias_atencion' => $_POST['Horas_diarias_atencion'],
    'Num_periodos_tiempo' => $_POST['Num_periodos_tiempo'],
    'Numero_total_clientes_Xdia' => $_POST['Numero_total_clientes_Xdia'],
    'Cantidad_min_productos_Xclientes' => $_POST['Cantidad_min_productos_Xclientes'],
    'Cantidad_max_productos_Xclientes' => $_POST['Cantidad_max_productos_Xclientes'],
    'Tiempo_promedio_seleccion_Xproducto' => $_POST['Tiempo_promedio_seleccion_Xproducto'],
    'Tiempo_promedio_despacho_Xproducto' => $_POST['Tiempo_promedio_despacho_Xproducto'],
    'Tiempo_promedio_pago' => $_POST['Tiempo_promedio_pago'],
    'Tiempo_total_simulado' => $Tiempo_total_simulado
));

echo "<b>Tiempo total simulado: ".$Tiempo_total_simulado." minutos.</b></br>";

for ($intervalo = 0; $intervalo < $Num_periodos_tiempo; $intervalo++) {

    /* Parametros a calcular */

    $Cant_clientes_ingresados = $Distribucion_porcentual_en_intervalos[$intervalo] * ($Numero_total_clientes_Xdia / 100);
    $Cant_clientes_despachados = 0;

    $Promedio_productos_despachados = 0;

    $Promedio_clientes_esperando_en_colas_de_cajas = 0;
    $Longitud_max__cola_de_espera = 0;

    /*---------------------------------*/

    /* Simulacion en el intervalo */

    $Cajas_abiertas = $Cajas_abiertas_en_intervalos[$intervalo];
    $Supermercado = array();
    $Cajas = array();
    for ($i = 0; $i < $Cajas_abiertas; $i++) {
        array_push($Cajas, new Fila);
    }

    /* Ingreso del cliente al Supermercado */

    for ($i = 0; $i < $Cant_clientes_ingresados; $i++) {

        $X = new Cliente($Cantidad_min_productos_Xclientes, $Cantidad_max_productos_Xclientes);

        $X->Tiempo_en_supermercado = $X->Num_productos * $Tiempo_promedio_seleccion_Xproducto;
        $X->Tiempo_en_caja = $X->Num_productos * $Tiempo_promedio_despacho_Xproducto + $Tiempo_promedio_pago;

        //Disminuir tiempo:
        Disminuir_tiempo_en_supermercado($Supermercado);
        Disminuir_tiempo_en_caja($Cajas);

        array_push($Supermercado, $X);

        // Encolar:
        validar_clientes($Supermercado, $Cajas, $Cantidad_min_productos_Xclientes);

        // Desencolar:
        Desencolar($Cajas);
    }

    

    /* Calculo de Resultados: */

    for ($i = 0; $i < sizeof($Cajas); $i++) {
        $Cant_clientes_despachados += $Cajas[$i]->Cant_clientes_despachados;
    }
    // ------------------------------------------------------------

    for ($i = 0; $i < sizeof($Cajas); $i++) {
        $Promedio_productos_despachados += $Cajas[$i]->Cant_productos_despachados;
    }

    if ($Cant_clientes_despachados > 0) {
        $Promedio_productos_despachados /= $Cant_clientes_despachados;
    } else {
        $Promedio_productos_despachados = 0;
    }

    // ------------------------------------------------------------

    for ($i = 0; $i < sizeof($Cajas); $i++) {
        $Promedio_clientes_esperando_en_colas_de_cajas += $Cajas[$i]->size();
    }

    $Promedio_clientes_esperando_en_colas_de_cajas /= sizeof($Cajas);

    // ------------------------------------------------------------

    $maxima_longitud = -999999;

    for ($i = 0; $i < sizeof($Cajas); $i++) {
        if ($Cajas[$i]->size() > $maxima_longitud) {
            $maxima_longitud = $Cajas[$i]->size();
        }
    }

    $Longitud_max_cola_de_espera = $maxima_longitud;

    // ------------------------------------------------------------

    /* Imprime resultados: */

    // Transforma a variable entera:
    $Promedio_productos_despachados = (int)  $Promedio_productos_despachados;
    $Promedio_clientes_esperando_en_colas_de_cajas = (int) $Promedio_clientes_esperando_en_colas_de_cajas;
    echo "<div class=\"intervalos\"><b> Intervalo número ", ($intervalo + 1), ":", "</b><br>";
    echo "<span class=\"intervalos-item\"> Cantidad de clientes ingresados : ", $Cant_clientes_ingresados, "</span>";
    echo "<span class=\"intervalos-item\"> Cantidad de clientes despachados : ", $Cant_clientes_despachados, "</span>";
    echo "<span class=\"intervalos-item\"> Promedio de productos despachados : ", $Promedio_productos_despachados, "</span>";
    echo "<span class=\"intervalos-item\"> Promedio clientes esperando en colas de cajas : ", $Promedio_clientes_esperando_en_colas_de_cajas, "</span>";
    echo "<span class=\"intervalos-item\"> Longitud max cola de espera : ", $Longitud_max_cola_de_espera, "</span> ";
    echo "</div><hr>";

    /* Traspaso de información a Mongodb: */
    array_push($simulacion, array(
        "Intervalo" => $intervalo + 1,
        "Cant_clientes_ingresados" => $Cant_clientes_ingresados,
        "Cant_clientes_despachados" => $Cant_clientes_despachados,
        "Promedio_productos_despachados" => $Promedio_productos_despachados,
        "Promedio_clientes_esperando_en_colas_de_cajas" => $Promedio_clientes_esperando_en_colas_de_cajas,
        "Longitud_max_cola_de_espera" => $Longitud_max_cola_de_espera
    ));
}
$date =  date("Y-m-d H:i:s");
$simulacion['fecha'] = $date;
$uri = 'mongodb://localhost';
$client = new MongoDB\Client($uri);
$db = $client->tics->pruebas;
$db->insertOne($simulacion);
?>

<title>Resultados</title>
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

        .error {
            outline: 1px solid red;
        }
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>


    <form style="text-align:center;" method="POST" id="Form">
        <fieldset>
            <legend>Probar nuevamente</legend>
            <input type="hidden" id="fecha" name="fecha" value="<?php echo date("d/m/Y H:i:s"); ?>"></input>
            <center>
                <table border="2" style="width:90%;text-align:center;">
                    <tr>

                        <th><label for="detalle">Detalle del caso</label><br>
                            <h6>(Responsable, situacion que se simula y numero o escenario de simulación)<br>Maximo 60 caracteres</h6>
                        </th>
                        <th><label for="horas de prueba">Horas diaras de atención</label></th>
                        <th><label for="periodos de tiempo">Periodos de tiempo</label></th>
                        <th>
                            <laber for="clientes esperados">Total clientes esperados</label>
                        </th>

                    </tr>
                    <tr>

                        <td><input type="text" id="texto" name="ID_simulacion" maxlength="60" value="<?php echo $_POST['ID_simulacion'] ?>"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="0.1" max="24" id="horas" name="Horas_diarias_atencion" step="0.1" value="<?php echo $Horas_diarias_atencion ?>"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="10" step="1" id="periodos" name="Num_periodos_tiempo" pattern="[0-9]" value="<?php echo $Num_periodos_tiempo ?>"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="1" step="1" id="clientesesp" max="10000" name="Numero_total_clientes_Xdia" pattern="[0-9]" value="<?php echo $Numero_total_clientes_Xdia ?>"><br>
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

                        <td><input type="number" min="0" id="minimos" name="Cantidad_min_productos_Xclientes" step="1" pattern="[0-9]" value="<?php echo $Cantidad_min_productos_Xclientes ?>"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="1" max="1000" id="maximos" name="Cantidad_max_productos_Xclientes" step="1" pattern="[0-9]" value="<?php echo $Cantidad_max_productos_Xclientes ?>"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="0.1" max="3600" id="productos" name="Tiempo_promedio_seleccion_Xproducto" step="0.1" value="<?php echo $Tiempo_promedio_seleccion_Xproducto ?>"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>
                        <td><input type="number" min="0.1" max="3600" id="marcado" name="Tiempo_promedio_despacho_Xproducto" step="0.1" value="<?php echo $Tiempo_promedio_despacho_Xproducto ?>"><br>
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

                        <td><input type="number" min="0.1" max="3600" id="pago" name="Tiempo_promedio_pago" step="0.1" value="<?php echo $Tiempo_promedio_pago ?>"><br>
                            <p id="minMessage">Tienes que escribir algo bro</p>
                        </td>

                    </tr>
                </table>
            </center><br>

            <center>
                <table border="2" id="tabla_intervalos" style="width:67.5%;text-align:center;">
                    <tr>
                        <th><label for="Intervalo">Numero intervalo</label></th>
                        <th><label for="distribución porcentual">Distribución porcentual de clientes (total 100%)</label></th>
                        <th><label for="cajas abiertas">Cajas abiertas</label></th>

                    </tr>
                    <?php

                    for ($i = 1; $i <= $Num_periodos_tiempo; $i++) {
                    ?>
                        <tr id="tr_inputs<?php echo $i ?>">

                            <td>
                                <h5><?php echo $i; ?></h5>
                            </td>
                            <td><input type="number" min="0" max="100" id="distribucion<?php echo $i; ?>" name="distribucion<?php echo $i; ?>" step="0.1" value="<?php echo $Distribucion_porcentual_en_intervalos[$i - 1] ?>"><br>
                                <p id="minMessage">Tienes que escribir algo bro</p>
                            </td>
                            <td><input type="number" min="1" max="100" id="cajas<?php echo $i; ?>" name="cajas<?php echo $i; ?>" step="1" pattern="[0-9]" value="<?php echo $Cajas_abiertas_en_intervalos[$i - 1] ?>"><br>
                                <p id="minMessage">Tienes que escribir algo bro</p>
                            </td>

                        </tr>
                    <?php
                    }
                    ?>

                    <tr id="tr_modificar_intervalos">

                        <td>
                            <input type="button" id="agregar_intervalo" value="Agregar Intervalo" style="background-color:green; color:white;" onclick="agregarIntervalo()">
                            <br>
                            <input type="button" id="eliminar_intervalo" value="Eliminar Intervalo" style="background-color:red; color:white;" onclick="eliminarIntervalo()">
                        </td>
                        <td></td>
                    </tr>

                </table>
            </center><br><br>

            <input type="submit" id="enviar" style="background-color:green; color:white;" value="Probar">
        </fieldset>
    </form>

    <script>
        let contadorIntervalos = parseInt(document.getElementById("periodos").value);

        function agregarIntervalo() {
            contadorIntervalos++;

            let tablaIntervalos = document.getElementById("tabla_intervalos");
            let trModificarIntervalos = document.getElementById("tr_modificar_intervalos");

            let newH5 = document.createElement("h5");
            let newTr = document.createElement("tr");
            newTr.id = "tr_inputs" + contadorIntervalos;

            let newTd1 = document.createElement("td");
            let newTd2 = document.createElement("td");
            let newTd3 = document.createElement("td");


            let textNode = document.createTextNode(contadorIntervalos);
            newH5.appendChild(textNode);

            let newInput1 = document.createElement("input");
            newInput1.type = "number";
            newInput1.min = "0";
            newInput1.max = "100";
            newInput1.id = "distribucion" + contadorIntervalos;
            newInput1.name = "distribucion" + contadorIntervalos;
            newInput1.step = "0.1";

            let newInput2 = document.createElement("input");
            newInput2.type = "number";
            newInput2.min = "1";
            newInput1.max = "100";
            newInput2.id = "cajas" + contadorIntervalos;
            newInput2.name = "cajas" + contadorIntervalos;
            newInput2.step = "1";
            newInput2.pattern = "[0-9]";



            newTd1.appendChild(newH5);
            newTd2.appendChild(newInput1);
            newTd3.appendChild(newInput2)

            newTr.appendChild(newTd1);
            newTr.appendChild(newTd2);
            newTr.appendChild(newTd3);

            tablaIntervalos.appendChild(newTr);
            tablaIntervalos.appendChild(trModificarIntervalos);

        }

        function eliminarIntervalo() {
            if (contadorIntervalos > 10) {
                let tablaIntervalos = document.getElementById("tabla_intervalos");
                let auxName = "tr_inputs" + contadorIntervalos;
                document.getElementById(auxName).remove();
                contadorIntervalos--;
            }
        }

        $(document).ready(function() {
            $('#enviar').click(function() {
                let periodos = document.getElementById("periodos");
                let sumaPorcentajes = 0;
                let maximos = document.getElementById("maximos");
                let minimos = document.getElementById("minimos");

                for (let i = 1; i <= contadorIntervalos; i++) {
                    let distribucion = document.getElementById(`distribucion${i}`);
                    sumaPorcentajes += parseInt(distribucion.value);
                }


                if (sumaPorcentajes != 100) {
                    alert("El total de la distribución porcentual de clientes debe ser 100%.");
                    return false;
                } else if (periodos.value != contadorIntervalos) {
                    alert("El número de periodos de tiempo debe ser igual a la cantidad de intervalos agregados.");
                    return false;
                } else if (parseInt(maximos.value) <= parseInt(minimos.value)) {
                    alert("El máximo de productos debe ser mayor al mínimo. ");
                    return false;
                }

                $('input').each(function() {
                    if (!$(this).val()) {
                        alert('Algunas casillas están vacias, le pedimos ingresar todos los datos.');
                        return false;
                    } else {
                        $('#Form').attr('action', './resultado.php');
                    }
                });

                return true;

            });
        });
    </script>

</body>
</footer>
<?php


/*echo "<SCRIPT>
                               alert('La sesion se encuentra iniciada en otro computador o comuniquese con un administrativo')
                               window.location.replace('./login.php');
                          </SCRIPT>";*/


include_once "pie.php"
?>