<?php
require "includes/db.php";

$id = $_GET['id'] ?? 0;

$evento = $pdo->query("SELECT * FROM eventos WHERE id=$id")->fetch(PDO::FETCH_ASSOC);

if(!$evento){
    die("Evento não encontrado!");
}

$disponivel = $evento['capacidade'] - $evento['ingressos_vendidos'];

if(isset($_POST['nome'])){
    $qtd = $_POST['quantidade'];

    if($qtd <= 0 || $qtd > $disponivel){
        $erro = "Quantidade indisponível!";
    } else {

        $pdo->prepare("
            INSERT INTO vendas (evento_id, nome_cliente, email_cliente, quantidade)
            VALUES (?, ?, ?, ?)
        ")->execute([$id, $_POST['nome'], $_POST['email'], $qtd]);

        $pdo->prepare("
            UPDATE eventos SET ingressos_vendidos = ingressos_vendidos + ?
            WHERE id=?
        ")->execute([$qtd, $id]);

        header("Location: sucesso.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/comprar.css">
</head>
<body>
<div class="header">
<a href="index.php"> <!-- Link para a página inicial -->
<img src="icone_casa.png" alt="Home" class="icon-home">
</a>
<h1>Comprar ingresso - <?= $evento['titulo'] ?></h1>
</div>

<p class="disponiveis"><b>Disponíveis:</b> <?= $disponivel ?></p>

<div class="form-box">
<form method="POST">
    <input type="text" name="nome" required placeholder="Seu nome">
    <input type="email" name="email" required placeholder="Seu email">
    <input type="number" name="quantidade" required placeholder="Quantidade">

    <button type="submit">Finalizar compra</button>
</form>
</div>

<?php if(isset($erro)) echo "<p class='erro'>$erro</p>"; ?>

</body>
</html>
