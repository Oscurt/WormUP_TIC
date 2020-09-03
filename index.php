<?php
    include_once "encabezado.php";
    session_start();
?>
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
            alert('Algunas casillas están vacias, le pedimos ingresar todos los datos.');
           return false;
        }else{
            $('#Form').attr('action', '/ingresodatos.php');
        }
    });
});
});
</script>
    <form name="Form" id="Form" method="post" style="text-align: center;">
        <label for="Periodos">Ingrese periodos de tiempo</label>
        <td><input type="number" min="10" max="50" step="1" id="periodos" name="periodos" pattern="[0-9]"><br>
            <p id="minMessage">Tienes que escribir algo bro</p>
        </td><br><br>
        <input type="submit" value="Siguiente" name="enviar" id="enviar">
    </form>

<?php
    include_once "pie.php";
?>