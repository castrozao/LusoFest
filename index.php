<?php
include 'db.php';
$ip_address = $_SERVER['REMOTE_ADDR'];
$current_date = date('Y-m-d');

$sql_check_visit = "SELECT * FROM log_visitas WHERE ip_address = '$ip_address' AND data_visita = '$current_date'";
$result_check_visit = $conn->query($sql_check_visit);

if ($result_check_visit->num_rows == 0) {
    $sql_insert_visit = "INSERT INTO log_visitas (ip_address, data_visita) VALUES ('$ip_address', '$current_date')";
    $conn->query($sql_insert_visit);
}

$current_date = date('Y-m-d');

$sql_get_next_event = "SELECT * FROM eventos WHERE data_evento >= '$current_date' ORDER BY data_evento ASC LIMIT 1";
$result_next_event = $conn->query($sql_get_next_event);

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $_SESSION['comprarevento'] = $_POST['id'];
    ob_clean(); // Limpar o buffer de saída
    header("Location: ../comprar-bilhete");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lusofunk</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .fundo {
            background: url('banner.jpg');
        }

        .banner {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3.5vw;
            font-weight: bold;
            text-align: center;
            padding: 0 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .banner2 {
            min-height: 550px;
            color: white;
            font-size: 20px;
            padding: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .evento-recente {
            padding: 50px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .panelultimo {
            margin-left: 20px;
        }

        .cartazultimo {
            height: 400px;
            width: 300px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .botaocomprarevento button {
            font-size: 20px;
            width: 300px;
            border-radius: 5px;
            background-color: black;
            color: white;
            padding: 20px;
            border: none;
            cursor: pointer;
                    transform: translateY(2px);
        }

        .botaocomprarevento button:hover {
            font-size: 20px;
            width: 300px;
            border-radius: 5px;
            background-color: white;
            color: black;
            padding: 20px;
            border: none;
            cursor: pointer;
            box-shadow: inset 0 0 0 4px black;
            transform: translateY(2px);
        }

        .panelultimo p,
        .panelultimo img {
            vertical-align: middle;
        }
    .no-events-message {
        text-align: center;
        margin-top: 20px;
    }
    </style>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome CDN link for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="fundo">
         <?php include 'cabecalho.php'; ?>
    <div class="banner">
        <br><br><br>
        <h2>Transformamos as tuas noites em festas inesquecíveis!</h2>
    </div>
    <div class="banner2">
        <a id="proximoevento"></a>
        <?php
        if ($result_next_event->num_rows > 0) {
            $row = $result_next_event->fetch_assoc();
        ?>
        <table style="margin: 0 auto;">
            <tr>
                <td>
                    <?php echo '<img src="/admin/inserir-eventos/' . $row['capa_evento'] . '" class="cartazultimo">'; ?>
                </td>
                <td>
                    <div class="panelultimo">
                        <h1><?php echo $row['nome_evento']; ?></h1>
<p><img src='/festas/date.png' width='20' height='20'> <?php echo $row['data_evento']; ?> Ás <?php echo date('H:i', strtotime($row['hora_evento'])); ?></p>
                        <p><img src='/festas/loc.png' width='20' height='20'> <?php echo $row['local_evento']; ?></p>
                        <p><img src='/festas/bilhe.png' width='20' height='20'> <?php echo $row['ofertas']; ?></p>
                        <form method='post' action=''>
                            <div class="botaocomprarevento">
                                <input type='hidden' name='id' value='<?php echo $row['id']; ?>'>
                                <button type='submit'>Comprar <?php echo $row['preco']; ?>€</button>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
        </table>
    </div>
        <?php
} else {
    echo "<div style='text-align: center;'><h3>Ainda não existem eventos disponíveis.</h3></div>";
}
        ?>
    </div>
    <?php include 'rodape.php'; ?>
</body>
</html>