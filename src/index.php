<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OWASP Top 10 Vulnerable Lab</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">OWASP Top 10 Vulnerable Lab 🧪</h1>
        <p class="text-center text-muted">Un laboratorio para practicar pentesting en un entorno controlado.</p>

        <div class="list-group mt-4">
            <!-- Los laboratorios se añadirán aquí -->
            <a href="/a01_broken_access_control/" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">A01: Broken Access Control</h5>
                    <small class="text-success">Implementado</small>
                </div>
                <p class="mb-1">Laboratorio que demuestra vulnerabilidades de control de acceso, como Insecure Direct Object References (IDOR).</p>
            </a>
            <a href="/a02_cryptographic_failures/" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">A02: Cryptographic Failures</h5>
                    <small class="text-success">Implementado</small>
                </div>
                <p class="mb-1">Laboratorio que demuestra el uso de funciones de hash débiles (MD5) para el almacenamiento de contraseñas.</p>
            </a>
            <a href="/a03_injection_xss/" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">A03: Injection (XSS)</h5>
                    <small class="text-success">Implementado</small>
                </div>
                <p class="mb-1">Laboratorio que demuestra una vulnerabilidad de Cross-Site Scripting (XSS) Almacenado en un libro de visitas.</p>
            </a>
            <a href="/a04_lfi/" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">A04: Insecure Design (LFI)</h5>
                    <small class="text-success">Implementado</small>
                </div>
                <p class="mb-1">Laboratorio que demuestra una vulnerabilidad de Inclusión Local de Archivos (LFI) como ejemplo de un diseño inseguro.</p>
            </a>
            <a href="/a05_security_misconfiguration/" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">A05: Security Misconfiguration</h5>
                    <small class="text-success">Implementado</small>
                </div>
                <p class="mb-1">Demuestra varios fallos de configuración: errores detallados, listado de directorios y archivos sensibles expuestos.</p>
            </a>
            <a href="/a06_vulnerable_components/" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">A06: Vulnerable and Outdated Components</h5>
                    <small class="text-success">Implementado</small>
                </div>
                <p class="mb-1">Demuestra cómo un componente de subida de archivos vulnerable puede ser explotado para cargar archivos maliciosos.</p>
            </a>
            <a href="/a07_auth_failures/" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">A07: Identification and Authentication Failures</h5>
                    <small class="text-success">Implementado</small>
                </div>
                <p class="mb-1">Demuestra la falta de protección contra fuerza bruta y el uso de contraseñas débiles.</p>
            </a>
            <a href="/a08_integrity_failures/" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">A08: Software and Data Integrity Failures</h5>
                    <small class="text-success">Implementado</small>
                </div>
                <p class="mb-1">Demuestra la deserialización insegura de objetos PHP a través de cookies.</p>
            </a>
            <a href="/a09_logging_failures/" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">A09: Security Logging and Monitoring Failures</h5>
                    <small class="text-success">Implementado</small>
                </div>
                <p class="mb-1">Demuestra la falta de registro suficiente y la exposición de archivos de log.</p>
            </a>
            <a href="/a10_ssrf/" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">A10: Server-Side Request Forgery (SSRF)</h5>
                    <small class="text-success">Implementado</small>
                </div>
                <p class="mb-1">Demuestra cómo forzar al servidor a realizar peticiones a recursos internos o externos no deseados.</p>
            </a>
            <!-- ... más laboratorios pendientes ... -->
        </div>

        <footer class="text-center mt-5">
            <p class="text-muted">⚠️ Esta aplicación es deliberadamente vulnerable. No la uses en producción.</p>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
