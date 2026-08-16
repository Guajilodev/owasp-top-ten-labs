# OWASP Top Ten Labs

Plataforma educativa para comprender y practicar vulnerabilidades web del **OWASP Top 10 (2021)** en un entorno local y controlado. Cada laboratorio presenta una categoría, un escenario intencionalmente vulnerable y material para estudiar su identificación y mitigación.

**Demo en vivo:** [https://labot10.guajilodev.com/](https://labot10.guajilodev.com/)

> [!WARNING]
> **Uso exclusivo para formación, investigación autorizada y práctica local.** Este repositorio contiene vulnerabilidades deliberadas. No lo despliegues en producción, no lo expongas a redes públicas y no lo uses contra sistemas, datos o cuentas sin autorización explícita. Quien lo utilice es responsable de aislar el entorno y cumplir las leyes, políticas y autorizaciones aplicables.

## Inicio rápido

1. Crea tu archivo de configuración local:

   ```bash
   cp .env.example .env
   ```

2. Revisa y reemplaza los valores de ejemplo en `.env`.

3. Construye e inicia los servicios:

   ```bash
   docker compose up -d --build
   ```

4. Abre [http://localhost:8081](http://localhost:8081).

Para detener el entorno:

```bash
docker compose down
```

## Qué incluye

| Área | Contenido |
| --- | --- |
| Estándar | Las diez categorías de OWASP Top 10 (2021) |
| Aplicación | PHP 8.2 con Apache |
| Base de datos | MariaDB 10.11 |
| Ejecución | Servicios definidos con Docker Compose |
| Acceso local | La aplicación se publica en `127.0.0.1:8081` |

## Laboratorios OWASP Top 10 (2021)

| Categoría | Escenario principal |
| --- | --- |
| [A01: Control de acceso roto](src/a01_broken_access_control/) | IDOR (referencia directa insegura a objetos) |
| [A02: Fallos criptográficos](src/a02_cryptographic_failures/) | Contraseñas con hash MD5 sin sal |
| [A03: Inyección](src/a03_injection/) | Inyección SQL por concatenación directa de consultas |
| [A04: Diseño inseguro](src/a04_insecure_design/) | Manipulación de precios en el cliente |
| [A05: Configuración de seguridad incorrecta](src/a05_security_misconfiguration/) | Errores detallados, listado de directorios y evasión de listas negras de carga |
| [A06: Componentes vulnerables y desactualizados](src/a06_vulnerable_components/) | Biblioteca sanitizadora HTML desactualizada con evasiones conocidas |
| [A07: Fallos de identificación y autenticación](src/a07_auth_failures/) | Fuerza bruta sin limitación de intentos |
| [A08: Fallos de integridad de software y datos](src/a08_integrity_failures/) | Deserialización insegura mediante cookies |
| [A09: Fallos de registro y monitoreo de seguridad](src/a09_logging_failures/) | Registro insuficiente y archivos de registro expuestos |
| [A10: Falsificación de solicitudes del lado del servidor](src/a10_ssrf/) | Obtención de URL sin validación |

## Requisitos

- Docker Engine con Docker Compose disponible como `docker compose`.
- Un navegador para acceder a la aplicación local.

## Configuración del entorno

El archivo [`.env.example`](.env.example) define las variables requeridas por la base de datos:

```dotenv
DB_NAME=owasp_labs
DB_USER=owasp_user
DB_PASSWORD=CHANGE_ME
DB_ROOT_PASSWORD=CHANGE_ME_ROOT
```

No publiques el archivo `.env` ni reutilices sus credenciales fuera de este entorno educativo.

## Material de práctica

- Navega desde la página principal hacia la categoría que quieras estudiar.
- Lee el escenario y realiza los ejercicios únicamente dentro de esta instancia local.
- Consulta [EXPLOTACION_KALI.md](EXPLOTACION_KALI.md) para técnicas de explotación y herramientas de Kali Linux asociadas a los laboratorios.

## Límites de seguridad

Los controles de Docker buscan reducir la exposición del entorno: la aplicación se enlaza solo a `127.0.0.1`, la red de base de datos es interna y los contenedores tienen restricciones de privilegios. Estos controles **no convierten los laboratorios en software seguro para producción**; las vulnerabilidades son parte intencional del material didáctico.

## Licencia

Este proyecto se distribuye bajo la licencia [Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International (CC BY-NC-ND 4.0)](LICENSE). El uso comercial está prohibido y la distribución pública de versiones modificadas requiere autorización previa, conforme a los términos del archivo de licencia.

## Disclaimer legal

Este software se proporciona "tal cual", sin garantías de ningún tipo. El autor no es responsable de:

- Uso indebido del conocimiento adquirido con estos laboratorios.
- Daños causados por ejecutar el software sin las protecciones y el aislamiento adecuados.
- Consecuencias legales de atacar sistemas sin autorización.

**Atacar sistemas sin autorización explícita es ilegal.** Estos laboratorios existen para practicar en un entorno controlado, autorizado y legal.

## Agradecimientos

El proyecto fue desarrollado con asistencia de flujo de trabajo y herramientas de [Gentleman Programming](https://github.com/Gentleman-Programming) y [Gentle AI](https://github.com/Gentleman-Programming/gentle-ai). Este reconocimiento se limita a dicha asistencia y no implica patrocinio, respaldo, afiliación ni propiedad del proyecto por parte de dichas organizaciones.

- [OWASP Foundation](https://owasp.org/) por el estándar OWASP Top 10.
- [MITRE Corporation](https://cwe.mitre.org/) por la clasificación CWE.
- La comunidad de ciberseguridad y las instituciones educativas que promueven el desarrollo seguro.
