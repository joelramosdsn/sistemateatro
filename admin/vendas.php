<?php
require "../includes/protect.php";
require "../includes/db.php";

if(isset($_GET['delete'])){
    $pdo->prepare("DELETE FROM vendas WHERE id=?")->execute([$_GET['delete']]);
    header("Location: vendas.php");
}

$vendas = $pdo->query("
    SELECT v.*, e.titulo 
    FROM vendas v
    JOIN eventos e ON v.evento_id = e.id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/vendas.css">
</head>
<body>

<?php require "../includes/header.php"; ?>

<h1>Relatório de Vendas</h1>

<table>
<tr>
    <th>ID</th><th>Evento</th><th>Cliente</th><th>Email</th><th>Qtd</th><th>Ação</th>
</tr>

<?php foreach($vendas as $v): ?>
<tr>
    <td><?= $v['id'] ?></td>
    <td><?= $v['titulo'] ?></td>
    <td><?= $v['nome_cliente'] ?></td>
    <td><?= $v['email_cliente'] ?></td>
    <td><?= $v['quantidade'] ?></td>
    <td><a class="delete" href="vendas.php?delete=<?= $v['id'] ?>">Excluir</a></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
