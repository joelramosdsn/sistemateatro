<?php
require "../includes/protect.php";
require "../includes/db.php";

// ---------- DELETE ----------
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    // Deleta todas as vendas relacionadas a este evento
    $pdo->prepare("DELETE FROM vendas WHERE evento_id = ?")->execute([$id]);

    // Agora deleta o evento
    $pdo->prepare("DELETE FROM eventos WHERE id = ?")->execute([$id]);

    header("Location: eventos.php");
    exit;
}

// ---------- ADD ----------
if(isset($_POST['add'])){
    $sql = $pdo->prepare("
        INSERT INTO eventos (titulo, data_evento, descricao, preco, capacidade)
        VALUES (?, ?, ?, ?, ?)
    ");
    $sql->execute([
        $_POST['titulo'],
        $_POST['data_evento'],
        $_POST['descricao'],
        $_POST['preco'],
        min($_POST['capacidade'], 200)
    ]);
}

// ---------- EDIT ----------
if(isset($_POST['edit'])){
    $sql = $pdo->prepare("
        UPDATE eventos SET titulo=?, data_evento=?, descricao=?, preco=?, capacidade=?
        WHERE id=?
    ");

    $sql->execute([
        $_POST['titulo'],
        $_POST['data_evento'],
        $_POST['descricao'],
        $_POST['preco'],
        min($_POST['capacidade'], 200),
        $_POST['id']
    ]);
}

// ---------- LIST ----------
$eventos = $pdo->query("SELECT * FROM eventos ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/eventos.css">
</head>
<body>
<div class="header">
<a href="../index.php"> <!-- Link para a página inicial -->
<img src="../icone_casa.png" alt="Home" class="icon-home">
</a>
<h1>Gerenciar Eventos</h1>
</div>


<!-- FORM ADD -->
<div class="form-box">
    <h2>Adicionar Evento</h2>
    <form method="POST">
        <input type="hidden" name="add" value="1">
        <input type="text" name="titulo" required placeholder="Título">
        <input type="date" name="data_evento" required>
        <textarea name="descricao" placeholder="Descrição"></textarea>
        <input type="number" step="0.01" name="preco" required placeholder="Preço">
        <input type="number" name="capacidade" required placeholder="Capacidade (máx 200)">
        <button type="submit" class="btn-add">Adicionar</button>
    </form>
</div>

<table>
<tr>
    <th>ID</th><th>Título</th><th>Data</th><th>Preço</th><th>Capacidade</th><th>Vendidos</th><th>Ações</th>
</tr>

<?php foreach($eventos as $e): ?>
<tr class="evento-cadastrado">
    <td><?= $e['id'] ?></td>
    <td><?= $e['titulo'] ?></td>
    <td><?= $e['data_evento'] ?></td>
    <td>R$ <?= $e['preco'] ?></td>
    <td><?= $e['capacidade'] ?></td>
    <td><?= $e['ingressos_vendidos'] ?></td>
    <td>
        <button onclick="editar(<?= $e['id'] ?>,'<?= $e['titulo'] ?>','<?= $e['data_evento'] ?>','<?= htmlspecialchars($e['descricao']) ?>','<?= $e['preco'] ?>','<?= $e['capacidade'] ?>')">Editar</button>
        <a class="delete" href="eventos.php?delete=<?= $e['id'] ?>">Excluir</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<!-- MODAL -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h2>Editar Evento</h2>
        <form method="POST">
            <input type="hidden" name="edit" value="1">
            <input type="hidden" name="id" id="e-id">

            <input type="text" id="e-titulo" name="titulo">
            <input type="date" id="e-data" name="data_evento">
            <textarea name="descricao" id="e-desc"></textarea>
            <input type="number" name="preco" id="e-preco">
            <input type="number" name="capacidade" id="e-cap">

            <button>Salvar</button>
            <button type="button" class="close" onclick="closeModal()">Cancelar</button>
        </form>
    </div>
</div>

<script>
function editar(id,titulo,data,desc,preco,cap){
    document.getElementById("modal").style.display = "block";
    document.getElementById("e-id").value = id;
    document.getElementById("e-titulo").value = titulo;
    document.getElementById("e-data").value = data;
    document.getElementById("e-desc").value = desc;
    document.getElementById("e-preco").value = preco;
    document.getElementById("e-cap").value = cap;
}
function closeModal(){
    document.getElementById("modal").style.display = "none";
}
</script>

</body>
</html>
