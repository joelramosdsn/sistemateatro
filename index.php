<?php
require "includes/db.php";
$eventos = $pdo->query("SELECT * FROM eventos ORDER BY data_evento ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/geral.css">
</head>
<body>

<div class="header">
<h1>Eventos Disponíveis</h1>
<a href="login.php" class="icon-admin">
    <img src="icone_admin.png" alt="Login Admin">
</a>
</div>

<div class="lista-eventos">

<?php foreach($eventos as $e): ?>

<div class="evento">
    <h2><?= $e['titulo'] ?></h2>
    <p><?= $e['descricao'] ?></p>
    <p><b>Data:</b> <?= $e['data_evento'] ?></p>
    <p><b>Preço:</b> R$ <?= $e['preco'] ?></p>
    <p><b>Disponíveis:</b> <?= $e['capacidade'] - $e['ingressos_vendidos'] ?></p>

    <a class="botao" href="comprar.php?id=<?= $e['id'] ?>">Comprar ingresso</a>
</div>

<?php endforeach; ?>

</div>

</body>
</html>
