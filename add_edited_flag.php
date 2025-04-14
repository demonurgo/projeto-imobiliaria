<?php
// Script para adicionar a coluna editado_manualmente na tabela de notas

// Configurações de conexão com o banco de dados
$host = 'localhost';
$username = 'root'; // Usuário padrão do XAMPP
$password = ''; // Senha padrão do XAMPP (vazia)
$database = 'imobiliaria_fiscal'; // Nome do banco de dados

// Conectar ao banco de dados
try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar se a coluna já existe
    $stmt = $conn->query("SHOW COLUMNS FROM `notas` LIKE 'editado_manualmente'");
    $column_exists = $stmt->fetchColumn();
    
    if (!$column_exists) {
        // A coluna não existe, adicioná-la
        $sql = "ALTER TABLE `notas` ADD COLUMN `editado_manualmente` TINYINT(1) NOT NULL DEFAULT 0;";
        $conn->exec($sql);
        echo "Coluna 'editado_manualmente' adicionada com sucesso!";
    } else {
        echo "Coluna 'editado_manualmente' já existe na tabela.";
    }
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

// Fechar conexão
$conn = null;
