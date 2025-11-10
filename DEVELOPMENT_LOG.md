# 🚀 DEVELOPMENT_LOG.md - OWASP Top 10 Vulnerable Lab

Este archivo documenta el proceso de creación de la aplicación "OWASP Top 10 Vulnerable Lab".

## 🎯 Objetivo

Crear un laboratorio práctico y deliberadamente vulnerable basado en el OWASP Top 10 2021 para fines de entrenamiento en pentesting.

## 🛠️ Stack Tecnológico

* **Backend:** PHP 8.2 (Puro)
* **Base de Datos:** MySQL 8.0
* **Contenerización:** Docker Compose
* **Frontend:** HTML5, Bootstrap 5

---

## 📋 Plan de Implementación

### Fase 1: Configuración del Entorno (Completada)

1. **[✅] Crear `DEVELOPMENT_LOG.md`:** Documentar el proceso de desarrollo.
2. **[✅] Crear `docker-compose.yml`:** Definir los servicios `web` (PHP/Apache) y `db` (MySQL).
3. **[✅] Crear `php/Dockerfile`:** Configurar la imagen de Apache/PHP e instalar extensiones. Solucionado problema de contexto de build.
4. **[✅] Crear `mysql/init.sql`:** Definir el schema inicial de la BD (`owasp_labs`, `users`, `notes`).
5. **[✅] Crear `src/index.php`:** Menú principal para los laboratorios.
6. **[✅] Crear `src/config/db.php`:** Script para la conexión a la BD.
7. **[✅] Desplegar contenedores:** Solucionado error de puerto `8080` ocupado, movido a `8081`.

### Fase 2: Implementación de Laboratorios (En Progreso)

* **[✅] A01: Broken Access Control**
  * **Descripción:** Se ha creado un visor de notas donde un usuario (simulado como 'alice') puede ver notas de otros usuarios cambiando el `note_id` en la URL (vulnerabilidad IDOR).
  * **Archivos Creados:**
    * `src/a01_broken_access_control/index.php`
    * `src/a01_broken_access_control/view_note.php`

* **[✅] A02: Cryptographic Failures**
  * **Descripción:** Se ha creado un sistema de login y registro que utiliza `MD5` para hashear las contraseñas. Esto demuestra el riesgo de usar algoritmos de hash débiles y sin "salt".
  * **Archivos Creados:**
    * `src/a02_cryptographic_failures/index.php` (formularios de login/registro)
    * `src/a02_cryptographic_failures/login.php` (lógica de login vulnerable)
    * `src/a02_cryptographic_failures/register.php` (lógica de registro vulnerable)
  * **BD:** Se añadió la tabla `users_crypto` con contraseñas en MD5.

* **[✅] A03: Injection (XSS)**
  * **Descripción:** Se ha creado un libro de visitas que es vulnerable a Cross-Site Scripting (XSS) Almacenado. Los comentarios no se sanitizan antes de guardarse y mostrarse, permitiendo la ejecución de scripts en el navegador de otros usuarios.
  * **Archivos Creados:**
    * `src/a03_injection_xss/index.php` (formulario y visualización de comentarios)
    * `src/a03_injection_xss/add_comment.php` (lógica de guardado vulnerable)
  * **BD:** Se añadió la tabla `comments`.

* **[✅] A04: Insecure Design (LFI)**
  * **Descripción:** Se ha creado un visor de páginas que sufre de Inclusión Local de Archivos (LFI). Es un ejemplo de diseño inseguro, ya que la entrada del usuario se usa para construir una ruta de archivo sin validación, permitiendo el Path Traversal.
  * **Archivos Creados:**
    * `src/a04_lfi/index.php` (script vulnerable)
    * `src/a04_lfi/pages/home.php` (página de ejemplo)
    * `src/a04_lfi/pages/about.php` (página de ejemplo)

* **[✅] A05: Security Misconfiguration**
  * **Descripción:** Demuestra tres fallos de configuración: 1) Mensajes de error de BD detallados que filtran información. 2) Listado de directorios habilitado en una carpeta `/uploads`. 3) Archivos sensibles expuestos dentro de ese directorio.
  * **Archivos Creados:**
    * `src/a05_security_misconfiguration/index.php` (página principal del lab)
    * `src/a05_security_misconfiguration/db_error.php` (provoca el error de BD)
    * `src/a05_security_misconfiguration/uploads/` (directorio expuesto)

* **[✅] A06: Vulnerable and Outdated Components**
  * **Descripción:** Se ha implementado un componente de subida de archivos con una lógica de validación de extensiones basada en lista negra, fácilmente evitable. Esto demuestra cómo un componente vulnerable o desactualizado puede permitir la carga de archivos maliciosos (web shells).
  * **Archivos Creados:**
    * `src/a06_vulnerable_components/index.php` (formulario de subida y listado de archivos)
    * `src/a06_vulnerable_components/upload.php` (lógica de subida vulnerable)
    * `src/a06_vulnerable_components/uploads/` (directorio de archivos subidos)

* **[✅] A07: Identification and Authentication Failures**
  * **Descripción:** Se ha implementado un sistema de login sin protección contra ataques de fuerza bruta (sin rate limiting ni bloqueo de cuentas) y que utiliza contraseñas débiles (texto plano).
  * **Archivos Creados:**
    * `src/a07_auth_failures/index.php` (formulario de login)
    * `src/a07_auth_failures/login.php` (lógica de login vulnerable)
    * `src/a07_auth_failures/dashboard.php` (página de éxito)
    * `src/a07_auth_failures/logout.php` (cierre de sesión)
  * **BD:** Se añadió la tabla `users_auth` con contraseñas en texto plano.

* **[✅] A08: Software and Data Integrity Failures**
  * **Descripción:** Demuestra la deserialización insegura de objetos PHP. Un atacante puede manipular una cookie que contiene un objeto serializado para alterar propiedades del objeto (ej. `is_admin`).
  * **Archivos Creados:**
    * `src/a08_integrity_failures/UserPreference.php` (clase de ejemplo)
    * `src/a08_integrity_failures/index.php` (muestra/carga preferencias)
    * `src/a08_integrity_failures/save_preference.php` (serializa y guarda preferencias en cookie)

* **[✅] A09: Security Logging and Monitoring Failures**
  * **Descripción:** Demuestra la falta de registro suficiente para acciones críticas y la exposición de archivos de log que pueden ser leídos y modificados por un atacante.
  * **Archivos Creados:**
    * `src/a09_logging_failures/log.txt` (archivo de log vulnerable)
    * `src/a09_logging_failures/index.php` (página principal del lab)
    * `src/a09_logging_failures/perform_action.php` (simula acción con log insuficiente)
    * `src/a09_logging_failures/view_logs.php` (muestra el contenido del log)

* **[✅] A10: Server-Side Request Forgery (SSRF)**
  * **Descripción:** Demuestra cómo un atacante puede forzar al servidor a realizar peticiones HTTP a recursos internos o externos no deseados, explotando una función que obtiene contenido de una URL sin validación.
  * **Archivos Creados:**
    * `src/a10_ssrf/index.php` (formulario para introducir URL)
    * `src/a10_ssrf/fetch.php` (script vulnerable que obtiene la URL)

---

## 🚀 ¡Todos los laboratorios del OWASP Top 10 están implementados! 🚀

* **[ ] A03: Injection**
* **[ ] A04: Insecure Design**
* **[ ] A05: Security Misconfiguration**
* **[ ] A06: Vulnerable and Outdated Components**
* **[ ] A07: Identification and Authentication Failures**
* **[ ] A08: Software and Data Integrity Failures**
* **[ ] A09: Security Logging and Monitoring Failures**
* **[ ] A10: Server-Side Request Forgery (SSRF)**

---
