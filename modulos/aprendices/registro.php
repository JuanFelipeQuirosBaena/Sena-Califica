<?php
// ...existing code...

function normalize_to_gmail(string $input): ?string {
    // limpiar y bajar a minúsculas
    $s = trim(mb_strtolower($input));

    // si contiene '@' tomar la parte local antes del primer '@'
    if (strpos($s, '@') !== false) {
        $local = explode('@', $s, 2)[0];
    } else {
        // si no tiene '@', quitar sufijos tipo "gmail", "gmail.com", "gmail.com.ar", "gmail.com.com", etc.
        $local = preg_replace('/(?:@)?gmail(?:\.[a-z0-9.-]+)*$/i', '', $s);
    }

    // por si queda algún '@' o parte de dominio, eliminar lo que siga a un '@'
    $local = preg_replace('/@.*$/', '', $local);

    // permitir sólo caracteres válidos en la parte local
    $local = preg_replace('/[^a-z0-9._-]+/', '', $local);

    // normalizar puntos
    $local = preg_replace('/\.{2,}/', '.', $local);
    $local = trim($local, '.');

    // validar longitud
    if ($local === '' || strlen($local) > 64) {
        return null;
    }

    $email = $local . '@gmail.com';

    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
}

// Uso (antes de guardar en la BD):
$raw = $_POST['email'] ?? '';
$email = normalize_to_gmail($raw);
if (!$email) {
    die('Correo inválido. Proporcione un nombre de usuario válido para Gmail.');
}
// ahora usar $email en el INSERT
// ...existing code...