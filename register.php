<?php
header('Content-Type: application/json');
session_start();

$host = 'localhost';
$db   = 'fanta_prova';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// $username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
// $league = trim($data['league'] ?? '');

if ( !$email || !$password ) {
    echo json_encode(['success' => false, 'message' => 'Tutti i campi sono obbligatori']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email non valida']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'La password deve contenere almeno 6 caratteri']);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Verifica che l'email non sia già registrata
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email già registrata']);
    exit;
}

// Inserisce l'utente
$stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
$stmt->execute([$email, $hashed_password]);

echo json_encode(['success' => true]);