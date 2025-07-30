<?php
// Script pour créer un utilisateur admin dans la base de données
include_once "Connect.php";

try {
    // Vérifier si l'admin existe déjà
    $check_sql = "SELECT id FROM users WHERE email = 'admin@gmail.com'";
    $result = $conn->query($check_sql);
    
    if ($result->num_rows > 0) {
        echo "✅ L'utilisateur admin existe déjà dans la base de données.<br>";
        echo "Email: admin@gmail.com<br>";
        echo "Mot de passe: admin1234<br>";
    } else {
        // Créer l'utilisateur admin
        $name = "Administrateur";
        $email = "admin@gmail.com";
        $password = "admin1234";
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $annee = "2024";
        $filiere = "Administration";
        $niveau = "Admin";
        
        $insert_sql = "INSERT INTO users (name, email, password, annee_scolaire, filiere, niveau) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssssss", $name, $email, $password_hashed, $annee, $filiere, $niveau);
        
        if ($stmt->execute()) {
            echo "✅ Utilisateur admin créé avec succès !<br>";
            echo "Email: admin@gmail.com<br>";
            echo "Mot de passe: admin1234<br>";
        } else {
            echo "❌ Erreur lors de la création de l'utilisateur admin.<br>";
        }
    }
    
   
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?> 