<?php

session_start();
require '../db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer includes
require_once '../PHPMailer-master/src/Exception.php';
require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';
require_once 'email_system.php';

// Connexion à la base
$conn = new mysqli("localhost", "root", "", "users");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    if (isset($_POST["signup"])) {
        // INSCRIPTION
        $name = $_POST["name"] ?? '';
        $annee_scolaire = $_POST["annee_scolaire"] ?? '';
        $filiere = $_POST["filiere"] ?? '';
        $niveau = $_POST["niveau"] ?? '';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Vérifier doublon email
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "❌ Email déjà utilisé. Veuillez vous connecter.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, annee_scolaire, filiere, niveau) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $hashedPassword, $annee_scolaire, $filiere, $niveau);
            if ($stmt->execute()) {
                sendWelcomeEmail($email, $name, $annee_scolaire, $filiere, $niveau);
                $message = "✅ Inscription réussie ! Un email de bienvenue vous a été envoyé.";
            } else {
                $message = "❌ Erreur lors de l'inscription.";
            }
            $stmt->close();
        }
        $check->close();

    } elseif (isset($_POST["signin"])) {
        // CONNEXION
        if ($email === "admin@gmail.com" && $password === "admin1234") {
            header("Location: /stagepro/espaceadmin/index.php");
            exit();
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION['user_email'] = $user['email'];
                header("Location: ../accueil/accueil.php");
                exit();
            } else {
                $message = "❌ Mot de passe incorrect.";
            }
        } else {
            $message = "❌ Email non trouvé.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modern Login Page</title>
    <link rel="stylesheet" href="login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>

<body>
    <?php if (isset($message)) echo "<p style='text-align:center;color:red;'>$message</p>"; ?>
    <?php if (isset($_GET['message']) && $_GET['message'] == 'logged_out'): ?>
        <div style="text-align:center;color:green;background-color:#d4edda;padding:10px;margin:10px;border-radius:5px;border:1px solid #c3e6cb;">
            ✅ Vous avez été déconnecté avec succès !
        </div>
    <?php endif; ?>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="annee_scolaire" placeholder="Année scolaire" required>
                <input type="text" name="filiere" placeholder="Filière" required>
                <input type="text" name="niveau" placeholder="Niveau" required>
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email password</span>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
               
                <button type="submit" name="signin">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="login.js"></script>
</body>

</html>
