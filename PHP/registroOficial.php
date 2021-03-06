<?php 
    include "head.php";
    include "header.php";
    include "registro.php";
?>
    <div class="contenedor-formulario">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="formulario">
            <div class="mensaje-php">
            <?php
                // necesitmo agregar un contador de errores
                $NumErrores = 0;
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    $nombre = $apellidoP = $fechaNacimiento = $dni = $celular = $correo = $password = $password2 = "";
                    // nombre
                    if(empty($_POST["nombre"])){
                        $NumErrores++;
                    }else{
                        $nombre = validarDato($_POST["nombre"]);
                    }
                
                    // apellido
                    if(empty($_POST["apellidoP"])){
                        $NumErrores++;
                    }else{
                        $apellidoP = validarDato($_POST["apellidoP"]);
                    }

                    // fecha de nacimiento
                    if(empty($_POST["fecha-nacimiento"])){
                        $NumErrores++;
                    }else{
                        $fechaNacimiento = validarDato($_POST["fecha-nacimiento"]);
                    }

                    // dni
                    if(empty($_POST["dni"])){
                        $NumErrores++;
                    }else{
                        $dni = validarDato($_POST["dni"]);
                    }
                    
                    // celular
                    if(empty($_POST["celular"])){
                        $NumErrores++;
                    }else{
                        $celular = validarDato($_POST["celular"]);
                    }

                    // correo
                    if(empty($_POST["correo"])){
                        $NumErrores++;
                    }else{
                        $correo = validarDato($_POST["correo"]);
                    }

                    // password
                    if(empty($_POST["pass"])){
                        $NumErrores++;

                    }else{
                        $pass = $_POST["pass"];
                    }
                    
                    // repeatPassword
                    if(empty($_POST["repeatPassword"])){
                        $NumErrores++;
                    }else{
                        $password2 = validarDato($_POST["repeatPassword"]);
                    }

                    // si se encuentran errores entonces
                    if($NumErrores != 0){
                        // el error se muestra cuando se presiona el boton de enviar los datos
                        $mensajeError = "Los datos de alg??n campo se encuentran inv??lidos...";
                        echo $mensajeError;
                    }else{
                        include "../baseDeDatos/conexion.php";
                        // necesitamos saber que el correo, celular y dni no se repitan
                        $buscar = "SELECT * FROM usuarios WHERE celular = '$celular' || dni = '$dni' || correo = '$correo'";
                        $buscando = $conn->query($buscar);
                        if($buscando->num_rows > 0){
                            // se encuentran datos repetidos
                            $mensajeDatosRepetidos = "El correo, celular o dni ya se encuentran registrados";
                            echo $mensajeDatosRepetidos;
                        }
                        else{
                            $enviar = "INSERT INTO usuarios (nombre,apellidoP,fechaNacimiento,dni,celular,correo,pass) VALUES ('$nombre','$apellidoP','$fechaNacimiento','$dni','$celular','$correo','$pass')";
                            $enviando = $conn->query($enviar);
                            if($enviando === TRUE){
                                // los datos se enviaron
                                echo "Te registraste correctamente";
                            }
                            else{
                                // los datos no se enviaron por cualquier error
                                echo "Error: " . $enviar. "<br>" . $conn->error;
                            }
                        }

                        // cerramos la conexion a la base de datos
                        $conn->close();

                        // sino se encontraron errores entonces se envian los datos
                    }
                }
                // validamos los datos
                function validarDato($dato){
                    $dato = trim($dato);
                    $dato = stripslashes($dato);
                    $dato = htmlspecialchars($dato);
                    return $dato;
                }
            ?>
            </div>    
            <div class="titulo-principal"><p>Crear una cuenta de usuario</p></div>
            
            <div class="subtitulo"><strong>Informaci??n Personal</strong></div>
            
            <div class="contenedor-input" id="nombre">
                <p>Nombre</p>
                <input type="text" name="nombre">
                <span class="info-error">Por favor, use solo letras A-Z/a-z en este campo.</span>
            </div>
            <div class="contenedor-input" id="apellidoP">
                <p>Apellido paterno</p>
                <input type="text" name="apellidoP">
                <span class="info-error">Por favor, use solo letras A-Z/a-z en este campo.</span>
            </div>
            <div class="contenedor-input" id="fecha-nacimiento">
                <p>Fecha de nacimiento</p>
                <input type="text" name="fecha-nacimiento" placeholder="D??a / Mes / A??o">
                <span class="info-error">Por favor, ingrese correctamente su fecha de nacimiento.</span>
            </div>
            <div class="contenedor-input" id="dni">
                <p>DNI</p>
                <input type="text" name="dni">
                <span class="info-error">Por favor, ingrese solo 8 d??gitos y valores v??lidos.</span>
            </div>
            <div class="contenedor-input" id="celular">
                <p>Celular</p>
                <p><strong>Per??</strong> (+51)</p>
                <input type="text" name="celular">
                <span class="info-error">Por favor, ingrese solo 9 d??gitos y valores v??lidos.</span>
            </div>

            <div class="subtitulo"><strong>Informaci??n de inicio de sesi??n</strong></div>
            
            <div class="contenedor-input" id="correo">
                <p>Email</p>
                <input type="email" name="correo">
                <span class="info-error">Por favor, ingrese correctamente su correo electr??nico.</span>
            </div>
            <div class="contenedor-input" id="password">
                <p>Contrase??a</p>
                <input type="password" name="pass">
                <span class="info-error">Por favor, la contrase??a debe contar con min??sculas, may??sculas y n??mero para que est?? correcto.</span>
            </div>
            <div class="contenedor-input" id="repeatPassword">
                <p>Confirmar contrase??a</p>
                <input type="password" name="repeatPassword">
                <span class="info-error">Las contrase??as no coinciden.</span>
            </div>
            <div class="contenedor-checkbox">
                <input type="checkbox">
                <span>Acepto todos los t??rminos y condiciones</span>
            </div>
            <div class="contenedor-boton boton-enviar">
                <button type="submit" name="registrar">CREAR CUENTA</button>
            </div>
        </form>
    </div>
    <script src="http://localhost:8080/PROYECTO-OFICIAL-FINAL\JS\codigo-registro.js"></script>
<!-- agregamos el footer -->
<?php include "footer.php" ?>