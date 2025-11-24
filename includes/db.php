<?php
$host = "sql305.byethost7.com";
$dbname = "b7_40492608_bancoteatro";
$user = "b7_40492608";
$pass = "teatro1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e){
    die("Erro ao conectar ao banco: " . $e->getMessage());
}
