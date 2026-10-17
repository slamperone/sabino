<?php
/**
 * Puenter Agency - Contact Form Mailer
 * -------------------------------------
 * Script PHP seguro y optimizado para procesar el formulario de contacto.
 * Soporta peticiones AJAX (JSON/FormData) y envíos estándar HTML POST.
 */

// -------------------------------------------------------------
// 1. CONFIGURACIÓN
// -------------------------------------------------------------
// Dirección de correo donde recibirás los mensajes:
$DESTINATARIO_EMAIL = 'hey@puenter.agency';
$DESTINATARIO_NOMBRE = 'Puenter Agency';

// Asunto del correo:
$ASUNTO_BASE = 'New message from sabinoweb 2.0';

// Remitente del sistema (usar un dominio del servidor para evitar filtros de spam):
$server_domain = !empty($_SERVER['SERVER_NAME']) ? preg_replace('/^www\./i', '', $_SERVER['SERVER_NAME']) : 'puenter.agency';
$SENDER_EMAIL = 'noreply@' . $server_domain;
$SENDER_NOMBRE = 'Puenter Agency Web';

// -------------------------------------------------------------
// 2. ENCABEZADOS Y CONTROL DE ORIGEN (CORS)
// -------------------------------------------------------------
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

// Manejo de preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header('Access-Control-Allow-Methods: POST, OPTIONS');
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    exit(0);
}

// Configurar respuesta JSON por defecto
header('Content-Type: application/json; charset=UTF-8');

// -------------------------------------------------------------
// 3. VALIDACIÓN DEL MÉTODO HTTP
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido. Solo se aceptan peticiones POST.'
    ]);
    exit;
}

// -------------------------------------------------------------
// 4. LECTURA Y SANITIZACIÓN DE DATOS (POST / JSON)
// -------------------------------------------------------------
$raw_input = file_get_contents('php://input');
$json_data = json_decode($raw_input, true);

// Soporta nombres de campo de Webflow ('name-2', 'Enter-your-e-mail', 'field-2')
// y nombres convencionales ('name', 'email', 'message')
$name = trim($_POST['name-2'] ?? $_POST['name'] ?? $json_data['name-2'] ?? $json_data['name'] ?? '');
$email = trim($_POST['Enter-your-e-mail'] ?? $_POST['email'] ?? $json_data['Enter-your-e-mail'] ?? $json_data['email'] ?? '');
$message = trim($_POST['field-2'] ?? $_POST['message'] ?? $json_data['field-2'] ?? $json_data['message'] ?? '');
$honeypot = trim($_POST['_hp_website'] ?? $json_data['_hp_website'] ?? '');

// Protección Anti-Spam: Si el campo trampa (honeypot) fue rellenado, es un bot
if (!empty($honeypot)) {
    // Retornamos éxito simulado para engañar al bot sin saturar el buzón
    echo json_encode([
        'success' => true,
        'message' => 'Thanks for make contact, you will ear bout us soon.'
    ]);
    exit;
}

// -------------------------------------------------------------
// 5. VALIDACIONES DE ENTRADA
// -------------------------------------------------------------
$errores = [];

if (empty($name) || mb_strlen($name) < 2) {
    $errores[] = 'We need to know your name.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'We need a valid e-mail address.';
}

if (!empty($errores)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => implode(' ', $errores)
    ]);
    exit;
}

// -------------------------------------------------------------
// 6. PROTECCIÓN CONTRA HEADER INJECTION
// -------------------------------------------------------------
$clean_name = str_replace(["\r", "\n", "%0a", "%0d"], '', $name);
$clean_email = str_replace(["\r", "\n", "%0a", "%0d"], '', $email);

// Metadatos adicionales para el remitente
$client_ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'Desconocida';
$fecha_envio = date('Y-m-d H:i:s (T)');
$asunto = $ASUNTO_BASE . ' - ' . $clean_name;

// -------------------------------------------------------------
// 7. CONSTRUCCIÓN DE LA PLANTILLA HTML DEL EMAIL
// -------------------------------------------------------------
$html_name = htmlspecialchars($clean_name, ENT_QUOTES, 'UTF-8');
$html_email = htmlspecialchars($clean_email, ENT_QUOTES, 'UTF-8');
$html_message = !empty($message) ? nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) : '<em>(Sin mensaje)</em>';

$cuerpo_html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>{$asunto}</title>
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0c0c0c; margin: 0; padding: 30px 15px; color: #f4f4f4; }
    .container { max-width: 600px; margin: 0 auto; background: #171717; border-radius: 12px; border: 1px solid #282828; overflow: hidden; }
    .header { background: #111111; padding: 25px 30px; border-bottom: 1px solid #282828; }
    .header h1 { margin: 0; font-size: 20px; font-weight: 600; color: #ffffff; letter-spacing: -0.5px; }
    .header p { margin: 6px 0 0; font-size: 13px; color: #888888; text-transform: uppercase; letter-spacing: 1px; }
    .content { padding: 30px; }
    .field { margin-bottom: 20px; }
    .field-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #999999; margin-bottom: 6px; font-weight: 600; }
    .field-value { font-size: 15px; color: #f4f4f4; background: #202020; padding: 12px 16px; border-radius: 8px; border: 1px solid #2f2f2f; }
    .message-box { font-size: 15px; line-height: 1.6; color: #f4f4f4; background: #202020; padding: 16px; border-radius: 8px; border: 1px solid #2f2f2f; min-height: 80px; }
    .footer { padding: 20px 30px; background: #111111; border-top: 1px solid #282828; font-size: 12px; color: #666666; display: flex; justify-content: space-between; align-items: center; }
    .btn { display: inline-block; background: #c8ff00; color: #000000; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 13px; margin-top: 15px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <p>Puenter Agency</p>
      <h1>New Contact Message from website</h1>
    </div>
    <div class="content">
      <div class="field">
        <div class="field-label">Name</div>
        <div class="field-value">{$html_name}</div>
      </div>
      <div class="field">
        <div class="field-label">E-mail</div>
        <div class="field-value"><a href="mailto:{$html_email}" style="color: #c8ff00; text-decoration: none;">{$html_email}</a></div>
      </div>
      <div class="field">
        <div class="field-label">Message</div>
        <div class="message-box">{$html_message}</div>
      </div>
      <a href="mailto:{$html_email}?subject=Re:%20Contacto%20Puenter%20Agency" class="btn">Responder al cliente</a>
    </div>
    <div class="footer">
      <span>IP: {$client_ip}</span>
      <span>Fecha: {$fecha_envio}</span>
    </div>
  </div>
</body>
</html>
HTML;

// -------------------------------------------------------------
// 8. ENCABEZADOS DEL CORREO
// -------------------------------------------------------------
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=UTF-8';
$headers[] = 'From: ' . $SENDER_NOMBRE . ' <' . $SENDER_EMAIL . '>';
$headers[] = 'Reply-To: ' . $clean_name . ' <' . $clean_email . '>';
$headers[] = 'X-Mailer: PHP/' . phpversion();

// -------------------------------------------------------------
// 9. ENVÍO DEL CORREO
// -------------------------------------------------------------
$enviado = @mail(
    $DESTINATARIO_EMAIL,
    $asunto,
    $cuerpo_html,
    implode("\r\n", $headers)
);

// -------------------------------------------------------------
// 10. RESPUESTA AL CLIENTE
// -------------------------------------------------------------
if ($enviado) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Thanks for make contact, you will ear bout us soon.'
    ]);
} else {
    // Si la función mail() falla en el servidor
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'We are having some issues with our form, please send an e-mail to ' . $DESTINATARIO_EMAIL
    ]);
}
