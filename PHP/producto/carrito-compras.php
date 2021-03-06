<?php
        session_start();
        
        include "../../baseDeDatos/conexion.php";
        $codigo = $_POST["codigo-producto-form"];
        $consulta1 = "SELECT * FROM productos WHERE codigo = '$codigo'";
        $resultado = $conn->query($consulta1);
        $row = $resultado->fetch_assoc();
        $imagenProducto = $row["imagen"];
        // validamos los datos 
        // si los datos se encuentran correctos entonces se envian
        // debemos validar el nombre cantidad y todos los campos para enviarlos al carrito
        if(($row["cantidad"]>=$_POST["cantidad-productos-form"]) && ($row["codigo"] == $_POST["codigo-producto-form"]) 
        && ($row["modelo"] == $_POST["modelo-form"]) && ($row["descripcion"] == $_POST["descripcion-form"]) && ($row["precio"] == $_POST["precio-form"])
        && ($row["descuento"] == $_POST["descuento-form"])){
            // debemos calcular el precio total = (cantidad*precio) - ((cantidad*precio)*descuento);
            $precioSinDescuento = (floatval($_POST["precio-form"]) * floatval($_POST["cantidad-productos-form"]));
            $descuentoTotal = ($_POST["precio-form"] * $_POST["cantidad-productos-form"] * ($_POST["descuento-form"]/100)); 
            $precioFinal = $precioSinDescuento - $descuentoTotal;

            $_SESSION["datos"] = array();
            $_SESSION["datos"][0] = $_POST["codigo-producto-form"];
            $_SESSION["datos"][1] = $_POST["cantidad-productos-form"];
            $_SESSION["datos"][2] = $_POST["modelo-form"];
            $_SESSION["datos"][3] = $imagenProducto;
            $_SESSION["datos"][4] = $_POST["precio-form"];
            $_SESSION["datos"][5] = $_POST["descuento-form"];
            $_SESSION["datos"][6] = $descuentoTotal;
            $_SESSION["datos"][7] = $precioSinDescuento;
            $_SESSION["datos"][8] = $precioFinal;

            // agregamos el producto al carrito
            if(isset($_SESSION["carrito"])){
                $cantProductos = count($_SESSION["carrito"]); 
                $validar = FALSE;
                
                $codigoActual = $_SESSION["datos"][0];
                $cantidadActual = $_SESSION["datos"][1];
                $descuentoTotalActual = $_SESSION["datos"][6];
                $precioSinDescuentoActual = $_SESSION["datos"][7];
                $precioFinalActual = $_SESSION["datos"][8];
                // tambien debemos modificar la descuento total, precio sin descuento y el precio final
                for ($i=0; $i < count($_SESSION["carrito"]); $i++) { 
                    if($codigoActual == $_SESSION["carrito"][$i][0]){
                        // agregamos producto con el mismo codigo
                        // sumamos las cantidades
                        $_SESSION["carrito"][$i][1] = $_SESSION["carrito"][$i][1] + $cantidadActual;
                        $_SESSION["carrito"][$i][6] = $_SESSION["carrito"][$i][6] + $descuentoTotalActual;
                        $_SESSION["carrito"][$i][7] = $_SESSION["carrito"][$i][7] + $precioSinDescuentoActual;
                        $_SESSION["carrito"][$i][8] = $_SESSION["carrito"][$i][8] + $precioFinalActual;
                        $validar = TRUE;
                    }
                }
                if($validar == FALSE){
                    $_SESSION["carrito"][$cantProductos] = $_SESSION["datos"];
                }
            }else{
                $_SESSION["carrito"] = array();
                $_SESSION["carrito"][0] = $_SESSION["datos"];
            };
            echo "<div class='mensaje-carrito'>El producto a sido agregado correctamente <i class='fas fa-check-circle'></i> </div>";
        }else{
            echo "<div class='mensaje-carrito'>El producto no a sido a??adido por algunos errores. <i class='fas fa-check-circle'></i> </div>";
        }
        
?>