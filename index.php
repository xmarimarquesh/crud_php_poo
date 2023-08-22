<?php
require_once('classes/Crud.php');
require_once('conexao/conexao.php');

$database=new Database();
$db = $database->getConnection();
$crud=new Crud($db);

if(isset($_GET['action'])){
    switch($_GET['action']){
        case 'create':
            $crud->create($_POST);
            $rows = $crud->read();
            break;
        case 'read':
            $rows = $crud->read();
            break;
        
        case 'update':
            if(isset($_POST['id'])){
                $rows=$crud->update($_POST);
            }

            $rows=$crud->read();
            break;

        case 'delete':
            $crud->delete($_GET['id']);
            $rows = $crud->read();
            break;
            
        
        default:
        $rows = $crud->read();
        break;
    }
}else{
    $rows = $crud->read();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <style>
        form{
            max-width: 500px;
            margin: 0 auto;
        }

        label{
            display: flex;
            margin-top: 10px;
        }

        input[type=text]{
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit]{
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
    
        }

        input[type=submit]:hover{
            background-color: #45a049;
        }
        table{
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        th, td{
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        th{
            background-color: #f2f2f2;
            font-weight: bold;
        }

        a{
            display: inline-block;
            padding: 4px 8px;
            background-color: #007bff;
            color: #fff;
        }

        a:hover{
            background-color: #0069d9;
        }
        
        a.delete{
            background-color: #dc3545;
        }

        a.delete:hover{
            background-color: #c82333;
        }

    </style>
</head>
<body>

        <?php

            if(isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])){
                $id = $_GET['id'];
                $result = $crud->readOne($id);

                if(!$result){
                    echo "Registro não encontrado.";
                    exit();
                }

                $modelo = $result['modelo'];
                $marca = $result['marca'];
                $placa = $result['placa'];
                $cor = $result['cor'];
                $ano = $result['ano'];
            
        ?>

        <form action="?action=update" method="POST">
            <input type="hidden" name="id" value="<?php echo $id ?>">

            <label for="modelo">Modelo</label><input type="text" name="modelo" value="<?php echo $modelo ?>">
            <label for="marca">Marca</label><input type="text" name="marca" value="<?php echo $marca ?>">
            <label for="placa">Placa</label><input type="text" name="placa" value="<?php echo $placa ?>">
            <label for="cor">Cor</label><input type="text" name="cor" value="<?php echo $cor ?>">
            <label for="ano">Ano</label><input type="text" name="ano" value="<?php echo $ano ?>">

            <input type="submit" value="Atualizar" name="enviar" onclick="return confirm('Certeza que deseja atualizar?')">
        </form>

        <?php
        }else{
        ?>



    <form action="?action=create" method="POST">
        <label for="">Modelo</label><input type="text" name="modelo">
        <label for="">Marca</label><input type="text" name="marca">
        <label for="">Placa</label><input type="text" name="placa">
        <label for="">Cor</label><input type="text" name="cor">
        <label for="">Ano</label><input type="text" name="ano">

        <input type="submit" value="Cadastrar" name="enviar">
    </form>

    <?php
        }
    ?>

    <table>
        <tr>
            <td>ID</td>
            <td>Modelo</td>
            <td>Marca</td>
            <td>Placa</td>
            <td>Cor</td>
            <td>Ano</td>
            <td>Ações</td>
        </tr>

        <?php
            if($rows->rowCount() == 0){
                echo "<tr>";
                echo "<td colspan='7'>Nenhum dado encontrado</td>";
                echo "</tr>";
            } else {
                while($row = $rows->fetch(PDO::FETCH_ASSOC)){
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['modelo'] . "</td>";
                    echo "<td>" . $row['marca'] . "</td>";
                    echo "<td>" . $row['placa'] . "</td>";
                    echo "<td>" . $row['cor'] . "</td>";
                    echo "<td>" . $row['ano'] . "</td>";
                    echo "<td>";
                    echo "<a href='?action=update&id=" . $row['id'] . "'>Atualizar</a>";
                    echo "<a href='?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"Tem certeza que quer apagar esse registro?\")' class='delete'>Deletar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
        ?>
    </table>
</body>
</html>