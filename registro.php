<?php
require_once("db/conection.php");
$db = new Database();
$con = $db->conectar();
require 'vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorPNG;

$usua = $con->prepare("SELECT * FROM automovil");
        $usua->execute();
        $asigna = $usua->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
if((isset($_POST["registro"])) && ($_POST["registro"]== "formu")){
    $placa = $_POST['placa'];
    $marca = $_POST['marca'];
    $dueño = $_POST['dueño'];
    $codigo_barras = uniqid() .rand(1000,9999);
    $generator = new BarcodeGeneratorPNG();
    $codigo_barras_imagen = $generator->getBarcode($codigo_barras, $generator::TYPE_CODE_128);
    file_put_contents(__DIR__.'/images/' . $codigo_barras . '.png', $codigo_barras_imagen);

    $sql=$con -> prepare ("SELECT*FROM automovil where placa = '$placa'");
    $sql -> execute();
    $fila = $sql -> fetchALL(PDO::FETCH_ASSOC);
    
    if ($placa=="" || $marca=="" || $dueño=="" )
    {
    echo '<script>alert("EXISTEN DATOS VACIOS"); </script>';
   
    }
    else if($fila){
    echo '<script>alert("Placa ya registrada");</script>';
   
    }
    else {
    $insertSQL = $con->prepare("INSERT INTO automovil(placa,cod_bar,marca,dueño) VALUES (?,?,?,?)");
    $insertSQL->execute([$placa, $codigo_barras,$marca, $dueño]);
    echo '<script>alert("Registro exitoso"); </script>';
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Document</title>
</head>
<body>
    <main class="contenedor sombra">
        <div class="container mt-5 col-md-4 mx-auto">
            <h2> Crear herramientas </h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Placa:</label>
                    <input type="text" class="form-control" id="placa" name="placa" required>
                </div>

                <div class="form-group">
                    <label for="">Marca:</label>
                    <input type="text" class="form-control" id="marca" name="marca" required>
                </div>
                <div class="form-group">
                    <label for="">Dueño:</label>
                    <input type="text" class="form-control" id="dueño" name="dueño" required>
                </div>
                <br>
                <input type="submit" class="btn btn-success" value="Registrate">
                <input type="hidden" name="registro" value="formu">
            </form>
        </div>

        <div class="container mt-3">
            <table class="table table-striped table-bordered table-hover border-primary">
                <thead class="thead-dark">
                    <tr style="text-transform: uppercase;">
                        <th>PLACA</th>
                        <th>MARCA</th>
                        <th>DUEÑO</th>
                        <th>CODIGO DE BARRAS</th>
                    </tr>
                </thead>
<tbody>

            <?php foreach ($asigna as $usua) { ?>
                    <tr>
                        <td><?= $usua["placa"] ?></td>
                        <td><?= $usua["marca"] ?></td>
                        <td><?= $usua["dueño"] ?></td>
                        <td><img src="images/<?=$usua["cod_bar"] ?>.png" style="max-width: 400px;"></td>
                </tr>
        <?php }
        ?>

            </div>

        </div>
        </tbody>
        </table>
            <div>
    </main>
</body>
</html>