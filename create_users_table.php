<?php
// Conexão com o banco de dados
$conn = mysqli_connect('127.0.0.1', 'root', '', 'imobiliaria_fiscal');

// Verificar conexão
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// SQL para verificar se a tabela existe
$check_table = "SHOW TABLES LIKE 'users'";
$result = mysqli_query($conn, $check_table);

if (mysqli_num_rows($result) == 0) {
    // Tabela não existe, vamos criá-la
    $sql = "CREATE TABLE `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL,
        `password` varchar(255) NOT NULL,
        `email` varchar(100) NOT NULL,
        `name` varchar(100) DEFAULT NULL,
        `role` enum('admin','user') NOT NULL DEFAULT 'user',
        `active` tinyint(1) NOT NULL DEFAULT 1,
        `last_login` datetime DEFAULT NULL,
        `created_at` datetime NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `username` (`username`),
        UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

    if (mysqli_query($conn, $sql)) {
        echo "Tabela users criada com sucesso!<br>";
        
        // Criar usuário admin
        $username = 'admin';
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $email = 'admin@example.com';
        $name = 'Administrador';
        $role = '1';
        
        $sql = "INSERT INTO users (username, password, email, name, is_admin) 
                VALUES ('$username', '$password', '$email', '$name', '$role')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Usuário admin criado com sucesso!<br>";
            echo "Username: admin<br>";
            echo "Senha: admin123<br>";
        } else {
            echo "Erro ao criar usuário admin: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "Erro ao criar tabela: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "A tabela users já existe no banco de dados.<br>";
    
    // Verificar se existe usuário admin
    $sql = "SELECT * FROM users WHERE username = 'admin'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        // Criar usuário admin
        $username = 'admin';
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $email = 'admin@example.com';
        $name = 'Administrador';
        $role = '1';
        
        $sql = "INSERT INTO users (username, password, email, name, is_admin) 
                VALUES ('$username', '$password', '$email', '$name', '$role')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Usuário admin criado com sucesso!<br>";
            echo "Username: admin<br>";
            echo "Senha: admin123<br>";
        } else {
            echo "Erro ao criar usuário admin: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "O usuário admin já existe.<br>";
    }
}

// Fechar conexão
mysqli_close($conn);

echo "<br>Você deve acessar o sistema em: <a href='http://localhost:8060/projeto-imobiliaria/'>http://localhost:8060/projeto-imobiliaria/</a>";
echo "<br>Depois que acessar o sistema, exclua este arquivo de criação de tabela por segurança.";
