<?php
session_start();
require "includes/db.php";

if(isset($_POST['email'])){
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=?");
    $sql->execute([$email]);
    $user = $sql->fetch();

    if($user && $senha == $user['senha']){

        $_SESSION['user'] = $user['id'];
        header("Location: admin/usuarios.php");
        exit;
    } else {
        $erro = "Login inválido!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<form method="POST" class="login-box">
    <h2>Login</h2>
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="senha" required placeholder="Senha">
    <button type="submit">Entrar</button>
    <?php if(isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
</form>

</body>
</html>
