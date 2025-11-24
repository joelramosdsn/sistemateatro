<?php
require "../includes/protect.php";
require "../includes/db.php";

// ---------- DELETE ----------
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);
    header("Location: usuarios.php");
    exit;
}

// ---------- ADD USER ----------
if (isset($_POST['add'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $sql->execute([$nome, $email, $senha]);
}

// ---------- EDIT USER ----------
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $sql = $pdo->prepare("UPDATE usuarios SET nome=?, email=?, senha=? WHERE id=?");
        $sql->execute([$nome, $email, $senha, $id]);
    } else {
        $sql = $pdo->prepare("UPDATE usuarios SET nome=?, email=? WHERE id=?");
        $sql->execute([$nome, $email, $id]);
    }
}

// ---------- LOAD USERS ----------
$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/usuarios.css">
    <title>Usuários</title>
</head>
<body>
<div class="header">
<a href="../index.php"> <!-- Link para a página inicial -->
<img src="../icone_casa.png" alt="Home" class="icon-home">
</a>
<h1>Gerenciar Usuários</h1>

<a href="../admin/eventos.php" class="admin-btn">
<img src="../icone_eventos.png" alt="Eventos">
</a>
</div>



<!-- FORM ADD -->
<div class="form-box">
    <h2>Adicionar Usuário</h2>
    <form method="POST">
        <input type="hidden" name="add" value="1">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit" class="btn-add">Adicionar</button>
    </form>
</div>

<!-- LISTA -->
<div class="usuarios-cadastrados">
<table>
    <tr>
        <th>ID</th><th>Nome</th><th>Email</th><th>Ações</th>
    </tr>

    <?php foreach($usuarios as $u): ?>
        <tr class="usuario-cadastrado">
            <td><?= $u['id'] ?></td>
            <td><?= $u['nome'] ?></td>
            <td><?= $u['email'] ?></td>
            <td>
                <button onclick="editar(<?= $u['id'] ?>, '<?= $u['nome'] ?>', '<?= $u['email'] ?>')">Editar</button>
                <a class="delete" href="usuarios.php?delete=<?= $u['id'] ?>" onclick="return confirm('Excluir?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</div>

<!-- MODAL EDITAR -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h2>Editar Usuário</h2>

        <form method="POST">
            <input type="hidden" name="edit" value="1">
            <input type="hidden" id="edit-id" name="id">

            <input type="text" id="edit-nome" name="nome" required>
            <input type="email" id="edit-email" name="email" required>
            <input type="password" name="senha" placeholder="Nova senha (opcional)">
            <button type="submit">Salvar</button>
            <button type="button" class="close" onclick="closeModal()">Cancelar</button>
        </form>
    </div>
</div>

<script>
function editar(id, nome, email){
    document.getElementById("modal").style.display = "block";
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-nome").value = nome;
    document.getElementById("edit-email").value = email;
}
function closeModal(){
    document.getElementById("modal").style.display = "none";
}
</script>

</body>
</html>
