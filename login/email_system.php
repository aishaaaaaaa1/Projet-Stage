<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure les fichiers PHPMailer
require_once '../PHPMailer-master/src/Exception.php';
require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';

/**
 * Système complet d'email de bienvenue pour CampusOne
 */

/**
 * Fonction pour envoyer un email de bienvenue
 * @param string $userEmail - Email de l'utilisateur
 * @param string $userName - Nom de l'utilisateur
 * @param string $annee - Année scolaire (optionnel)
 * @param string $filiere - Filière de l'étudiant (optionnel)
 * @param string $niveau - Niveau de l'étudiant (optionnel)
 * @return bool - True si l'email a été envoyé avec succès
 */
function sendWelcomeEmail($userEmail, $userName, $annee = '', $filiere = '', $niveau = '') {
    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'aichaoutajer2@gmail.com';
        $mail->Password = 'bufs bbgc vdph uoxi'; // Mot de passe d'application Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Expéditeur et destinataire
        $mail->setFrom('aichaoutajer2@gmail.com', 'CampusOne');
        $mail->addAddress($userEmail, $userName);

        // Sujet et contenu
        $mail->Subject = 'Bienvenue sur CampusOne ! 🎓';
        
        // Contenu HTML de l'email
        $mail->isHTML(true);
        $mail->Body = createWelcomeEmailHTML($userName, $annee, $filiere, $niveau);
        
        // Version texte simple (fallback)
        $mail->AltBody = createWelcomeEmailText($userName, $annee, $filiere, $niveau);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur d'envoi d'email: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Créer le contenu HTML de l'email de bienvenue
 */
function createWelcomeEmailHTML($userName, $annee = '', $filiere = '', $niveau = '') {
    $userInfoHTML = '';
    if ($annee || $filiere || $niveau) {
        $userInfoHTML = '
        <div class="user-info">
            <h3>📋 Vos informations d\'inscription :</h3>';
        
        if ($annee) {
            $userInfoHTML .= '
            <div class="info-row">
                <span class="info-label">Année scolaire :</span>
                <span>' . htmlspecialchars($annee) . '</span>
            </div>';
        }
        
        if ($filiere) {
            $userInfoHTML .= '
            <div class="info-row">
                <span class="info-label">Filière :</span>
                <span>' . htmlspecialchars($filiere) . '</span>
            </div>';
        }
        
        if ($niveau) {
            $userInfoHTML .= '
            <div class="info-row">
                <span class="info-label">Niveau :</span>
                <span>' . htmlspecialchars($niveau) . '</span>
            </div>';
        }
        
        $userInfoHTML .= '</div>';
    }

    return '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bienvenue sur CampusOne</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f4f4f4;
            }
            .email-container {
                background-color: #ffffff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .header {
                text-align: center;
                background: linear-gradient(135deg, #071739 0%, #a68868 100%);
                color: white;
                padding: 20px;
                border-radius: 10px 10px 0 0;
                margin: -30px -30px 30px -30px;
            }
            .welcome-title {
                font-size: 28px;
                margin: 0;
                font-weight: bold;
            }
            .subtitle {
                font-size: 16px;
                margin: 10px 0 0 0;
                opacity: 0.9;
            }
            .content {
                padding: 20px 0;
            }
            .user-info {
                background-color: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                margin: 20px 0;
                border-left: 4px solid #071739;
            }
            .info-row {
                display: flex;
                justify-content: space-between;
                margin: 10px 0;
                padding: 8px 0;
                border-bottom: 1px solid #eee;
            }
            .info-label {
                font-weight: bold;
                color: #071739;
            }
            .features {
                margin: 30px 0;
            }
            .feature-item {
                display: flex;
                align-items: center;
                margin: 15px 0;
                padding: 10px;
                background-color: #f8f9fa;
                border-radius: 5px;
            }
            .feature-icon {
                font-size: 20px;
                margin-right: 15px;
                color: #071739;
            }
            .footer {
                text-align: center;
                margin-top: 30px;
                padding-top: 20px;
                border-top: 2px solid #eee;
                color: #666;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="header">
                <h1 class="welcome-title">🎓 Bienvenue sur CampusOne !</h1>
                <p class="subtitle">Votre plateforme étudiante complète</p>
            </div>
            
            <div class="content">
                <p>Bonjour <strong>' . htmlspecialchars($userName) . '</strong>,</p>
                
                <p>Nous sommes ravis de vous accueillir sur <strong>CampusOne</strong>, votre nouvelle plateforme étudiante ! Votre inscription a été validée avec succès.</p>
                
                ' . $userInfoHTML . '
                
                <div class="features">
                    <h3>🚀 Découvrez les fonctionnalités de CampusOne :</h3>
                    
                    <div class="feature-item">
                        <span class="feature-icon">📚</span>
                        <div>
                            <strong>Gestion des cours</strong><br>
                            Accédez à vos cours, ressources et supports de formation
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <span class="feature-icon">📅</span>
                        <div>
                            <strong>Calendrier et planning</strong><br>
                            Organisez votre emploi du temps et vos événements
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <span class="feature-icon">💼</span>
                        <div>
                            <strong>Espace personnel</strong><br>
                            Gérez vos absences, factures et documents
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <span class="feature-icon">🏢</span>
                        <div>
                            <strong>Réservation d\'espaces</strong><br>
                            Réservez salles de cours, bibliothèques et espaces de travail
                        </div>
                    </div>
                </div>
                
                <p><strong>Prochaines étapes :</strong></p>
                <ul>
                    <li>Connectez-vous à votre espace personnel</li>
                    <li>Consultez votre emploi du temps</li>
                    <li>Explorez les ressources disponibles</li>
                    <li>Prenez contact avec vos enseignants</li>
                </ul>
                
                <p>Si vous avez des questions ou besoin d\'aide, n\'hésitez pas à nous contacter à <strong>campusone11@gmail.com</strong></p>
            </div>
            
            <div class="footer">
                <p>© 2024 CampusOne - Tous droits réservés</p>
                <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            </div>
        </div>
    </body>
    </html>';
}

/**
 * Créer la version texte de l'email de bienvenue
 */
function createWelcomeEmailText($userName, $annee = '', $filiere = '', $niveau = '') {
    $userInfo = '';
    if ($annee || $filiere || $niveau) {
        $userInfo = "\n\nVos informations d'inscription :\n";
        if ($annee) $userInfo .= "- Année scolaire : $annee\n";
        if ($filiere) $userInfo .= "- Filière : $filiere\n";
        if ($niveau) $userInfo .= "- Niveau : $niveau\n";
    }

    return "Bienvenue sur CampusOne !

Bonjour $userName,

Nous sommes ravis de vous accueillir sur CampusOne, votre nouvelle plateforme étudiante ! Votre inscription a été validée avec succès.$userInfo

Fonctionnalités disponibles :
- Gestion des cours et ressources
- Calendrier et planning
- Espace personnel (absences, factures)
- Réservation d'espaces

Prochaines étapes :
1. Connectez-vous à votre espace personnel
2. Consultez votre emploi du temps
3. Explorez les ressources disponibles
4. Prenez contact avec vos enseignants

Pour toute question : campusone11@gmail.com

© 2024 CampusOne - Tous droits réservés";
}

/**
 * Fonction pour envoyer des emails en masse aux utilisateurs existants
 * @param mysqli $db - Connexion à la base de données
 * @return array - Statistiques d'envoi
 */
function sendBulkWelcomeEmails($db) {
    $success_count = 0;
    $error_count = 0;
    
    // Récupérer tous les utilisateurs de la table users
    $sql = "SELECT * FROM users WHERE email != 'admin@gmail.com'";
    $result = $db->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Extraire les informations disponibles
            $email = $row['email'] ?? '';
            $name = $row['name'] ?? '';
            $annee = $row['annee_scolaire'] ?? '';
            $filiere = $row['filiere'] ?? '';
            $niveau = $row['niveau'] ?? '';
            
            if ($email && $name) {
                if (sendWelcomeEmail($email, $name, $annee, $filiere, $niveau)) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            } else {
                $error_count++;
            }
        }
    }
    
    return [
        'success' => $success_count,
        'error' => $error_count,
        'total' => $success_count + $error_count
    ];
}

/**
 * Interface d'administration pour l'envoi en masse
 */
function showBulkEmailInterface() {
    // Connexion à la base de données
    $conn = new mysqli("localhost", "root", "", "users");
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }
    
    $message = "";
    $stats = ['success' => 0, 'error' => 0, 'total' => 0];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_emails'])) {
        $stats = sendBulkWelcomeEmails($conn);
        $message = "Envoi terminé : {$stats['success']} emails envoyés avec succès, {$stats['error']} échecs.";
    }
    
    // Récupérer la liste des utilisateurs pour affichage
    $users_sql = "SELECT * FROM users WHERE email != 'admin@gmail.com' ORDER BY id";
    $users_result = $conn->query($users_sql);
    
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Envoi d'Emails de Bienvenue - CampusOne</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f4f4f4;
            }
            .container {
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                margin-bottom: 20px;
            }
            .header {
                background: linear-gradient(135deg, #071739 0%, #a68868 100%);
                color: white;
                padding: 20px;
                border-radius: 10px;
                margin: -30px -30px 30px -30px;
                text-align: center;
            }
            .warning {
                background-color: #fff3cd;
                color: #856404;
                padding: 15px;
                border-radius: 5px;
                margin: 20px 0;
                border-left: 4px solid #ffc107;
            }
            .success {
                background-color: #d4edda;
                color: #155724;
                padding: 15px;
                border-radius: 5px;
                margin: 20px 0;
            }
            .error {
                background-color: #f8d7da;
                color:rgb(202, 1, 41);
                padding: 15px;
                border-radius: 5px;
                margin: 20px 0;
            }
            .btn {
                background: linear-gradient(135deg, #071739 0%, #a68868 100%);
                color: white;
                padding: 12px 25px;
                border: none;
                border-radius: 25px;
                cursor: pointer;
                font-size: 16px;
                font-weight: bold;
                margin: 10px 5px;
            }
            .btn:hover {
                opacity: 0.9;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            th {
                background-color: #f8f9fa;
                font-weight: bold;
                color: #071739;
            }
            tr:hover {
                background-color: #f5f5f5;
            }
            .stats {
                display: flex;
                justify-content: space-around;
                margin: 20px 0;
            }
            .stat-card {
                background: white;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                flex: 1;
                margin: 0 10px;
            }
            .stat-number {
                font-size: 2em;
                font-weight: bold;
                color: #071739;
            }
            .stat-label {
                color: #666;
                margin-top: 5px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>📧 Envoi d'Emails de Bienvenue</h1>
                <p>Administration - CampusOne</p>
            </div>
            
            <div class="warning">
                <strong>⚠️ Attention :</strong> Cette action enverra un email de bienvenue à tous les utilisateurs inscrits (sauf l'administrateur). 
                Assurez-vous que la configuration SMTP est correcte avant de procéder.
            </div>
            
            <?php if ($message): ?>
                <div class="<?= strpos($message, 'succès') !== false ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($users_result && $users_result->num_rows > 0): ?>
                <div class="stats">
                    <div class="stat-card">
                        <div class="stat-number"><?= $users_result->num_rows ?></div>
                        <div class="stat-label">Utilisateurs</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['success'] ?></div>
                        <div class="stat-label">Emails Envoyés</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['error'] ?></div>
                        <div class="stat-label">Échecs</div>
                    </div>
                </div>
                
                <h3>👥 Liste des utilisateurs qui recevront l'email :</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Année</th>
                            <th>Filière</th>
                            <th>Niveau</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $users_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?? 'N/A' ?></td>
                                <td><?= htmlspecialchars($row['name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['email'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['annee_scolaire'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['filiere'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['niveau'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                
                <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir envoyer des emails de bienvenue à tous les utilisateurs ?');">
                    <button type="submit" name="send_emails" class="btn">
                        📧 Envoyer les Emails de Bienvenue
                    </button>
                </form>
            <?php else: ?>
                <div class="error">
                    Aucun utilisateur trouvé dans la base de données.
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 30px;">
                <a href="espaceadmin/index.php" class="btn">← Retour à l'Administration</a>
                <a href="email_test.php" class="btn">🧪 Test Email</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    $conn->close();
}

/**
 * Page de test pour l'email
 */
function showEmailTestPage() {
    $message = "";
    
    if (isset($_POST['test_email'])) {
        $email = $_POST['email'] ?? 'test@example.com';
        $name = $_POST['name'] ?? 'Test User';
        $annee = $_POST['annee'] ?? '';
        $filiere = $_POST['filiere'] ?? '';
        $niveau = $_POST['niveau'] ?? '';
        
        if (sendWelcomeEmail($email, $name, $annee, $filiere, $niveau)) {
            $message = "✅ Email de bienvenue envoyé avec succès à $email !";
        } else {
            $message = "❌ Erreur lors de l'envoi de l'email. Vérifiez la configuration SMTP.";
        }
    }
    
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Test Email de Bienvenue - CampusOne</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                background-color: #f4f4f4;
            }
            .container {
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .form-group {
                margin-bottom: 15px;
            }
            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            input[type="text"], input[type="email"] {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 16px;
            }
            button {
                background: linear-gradient(135deg, #071739 0%, #a68868 100%);
                color: white;
                padding: 12px 25px;
                border: none;
                border-radius: 25px;
                cursor: pointer;
                font-size: 16px;
                font-weight: bold;
            }
            button:hover {
                opacity: 0.9;
            }
            .success {
                background-color: #d4edda;
                color: #155724;
                padding: 15px;
                border-radius: 5px;
                margin-top: 20px;
            }
            .error {
                background-color: #f8d7da;
                color: #721c24;
                padding: 15px;
                border-radius: 5px;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🧪 Test Email de Bienvenue</h1>
            <p>Utilisez ce formulaire pour tester l'envoi d'emails de bienvenue aux nouveaux utilisateurs.</p>
            
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email de test :</label>
                    <input type="email" id="email" name="email" value="test@example.com" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Nom de l'utilisateur :</label>
                    <input type="text" id="name" name="name" value="Test User" required>
                </div>
                
                <div class="form-group">
                    <label for="annee">Année scolaire (optionnel) :</label>
                    <input type="text" id="annee" name="annee" value="">
                </div>
                
                <div class="form-group">
                    <label for="filiere">Filière (optionnel) :</label>
                    <input type="text" id="filiere" name="filiere" value="">
                </div>
                
                <div class="form-group">
                    <label for="niveau">Niveau (optionnel) :</label>
                    <input type="text" id="niveau" name="niveau" value="">
                </div>
                
                <button type="submit" name="test_email">📧 Envoyer Email de Test</button>
            </form>
            
            <?php if ($message): ?>
                <div class="<?= strpos($message, 'succès') !== false ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
        </div>
    </body>
    </html>
    <?php
}

// Gestion des pages - seulement si appelé directement
if (basename($_SERVER['SCRIPT_NAME']) === 'email_system.php') {
    if (isset($_GET['page'])) {
        switch ($_GET['page']) {
            case 'bulk':
                showBulkEmailInterface();
                break;
            case 'test':
                showEmailTestPage();
                break;
            default:
                showEmailTestPage();
        }
    } else {
        showEmailTestPage();
    }
}
?> 