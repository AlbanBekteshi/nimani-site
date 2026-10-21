<?php
// Reçoit le formulaire de rendez-vous et l'envoie par e-mail au garage choisi.
// Fonctionne sur l'hébergement OVH (fonction mail() de PHP), pas sur GitHub Pages.

// ===== À CONFIGURER =====
// Adresse d'expédition : doit appartenir au domaine du site pour ne pas finir en spam.
const FROM_EMAIL = 'noreply@nimanigroup.be';
// Destinataire selon le garage. Le visiteur ne peut choisir qu'une de ces clés.
const GARAGES = [
    'Lennik' => 'lennik@nimanigroup.be',
    'Anderlecht' => 'anderlecht@nimanigroup.be',
];
// ========================

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function respond(int $status, array $body): void
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}

// Champ texte sur une ligne : sans retours à la ligne (évite l'injection d'en-têtes) et de longueur bornée.
function field(string $key, int $max = 200): string
{
    $value = isset($_POST[$key]) ? (string) $_POST[$key] : '';
    $value = trim(preg_replace('/[\r\n\t]+/', ' ', $value));
    return mb_substr($value, 0, $max, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, ['ok' => false, 'error' => 'method']);
}

// Piège à robots : ce champ est caché aux visiteurs, seuls les robots le remplissent.
if (!empty($_POST['website'])) {
    respond(200, ['ok' => true]);
}

$garage = field('garage', 40);
$name = field('name', 120);
$phone = field('phone', 40);
$email = field('email', 160);
$service = field('service', 120);
$car = field('car', 120);
$plate = field('plate', 20);
$date = field('date', 20);
$slot = field('slot', 40);
$lang = field('lang', 2) === 'nl' ? 'nl' : 'fr';
$message = isset($_POST['msg']) ? mb_substr(trim((string) $_POST['msg']), 0, 3000, 'UTF-8') : '';

if (!isset(GARAGES[$garage]) || $name === '' || $phone === '' || $service === '' || $car === ''
    || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    respond(422, ['ok' => false, 'error' => 'fields']);
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(422, ['ok' => false, 'error' => 'email']);
}

$to = GARAGES[$garage];
$subject = 'Demande de rendez-vous – ' . $name . ' (' . $car . ')';

$lines = [
    'Nouvelle demande de rendez-vous depuis le site.',
    '',
    'Garage : ' . $garage,
    'Nom : ' . $name,
    'Téléphone : ' . $phone,
    'E-mail : ' . ($email !== '' ? $email : '-'),
    'Service : ' . $service,
    'Véhicule : ' . $car,
    'Plaque : ' . ($plate !== '' ? $plate : '-'),
    'Date souhaitée : ' . $date . ($slot !== '' ? ' (' . $slot . ')' : ''),
    'Langue du visiteur : ' . strtoupper($lang),
];
if ($message !== '') {
    $lines[] = '';
    $lines[] = 'Message :';
    $lines[] = $message;
}
$body = implode("\r\n", $lines) . "\r\n";

$headers = [
    'From: ' . mb_encode_mimeheader('Site Nimani Group', 'UTF-8') . ' <' . FROM_EMAIL . '>',
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
];
if ($email !== '') {
    $headers[] = 'Reply-To: ' . $email;
}

$sent = mail($to, mb_encode_mimeheader($subject, 'UTF-8'), $body, implode("\r\n", $headers), '-f' . FROM_EMAIL);

if (!$sent) {
    respond(500, ['ok' => false, 'error' => 'send']);
}
respond(200, ['ok' => true, 'to' => $to]);
