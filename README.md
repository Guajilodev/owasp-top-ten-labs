# OWASP Top Ten Labs - Educational Cybersecurity Platform

[![License](https://img.shields.io/badge/license-CC%20BY--NC--ND%204.0-blue.svg)](LICENSE)
[![Docker](https://img.shields.io/badge/Docker-Supported-blue)](docker-compose.yml)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1)](https://www.mysql.com/)

Educational platform for learning and practicing OWASP Top 10 vulnerabilities in a controlled environment. Created by guajilodev for cybersecurity education and research purposes.

## 🎯 Project Overview

OWASP Top Ten Labs is an educational platform designed to help developers, security professionals, and students understand, identify, and mitigate common web application security vulnerabilities as defined by the [OWASP Top 10](https://owasp.org/www-project-top-ten/) standard.

This platform provides hands-on experience with real-world vulnerabilities in a safe, isolated environment. Each lab demonstrates specific attack techniques and corresponding mitigation strategies.

## 🏆 Features

- **Interactive Labs**: Real-time toggling between vulnerable and secure code
- **10 Complete Vulnerability Categories**: Full coverage of OWASP Top 10 (2021)
- **Realistic Scenarios**: Practical examples with real-world data
- **Educational Focus**: Detailed explanations and prevention strategies
- **Dockerized Environment**: Easy setup with consistent environment
- **Step-by-Step Guides**: Comprehensive exploitation instructions
- **Security Framework Mapping**: Links to MITRE ATT&CK and CWE classifications

## 🛡️ OWASP Top 10 Categories Covered

This platform includes labs for all 10 categories of the OWASP Top 10 (2021):

1. **[A01:2021 - Broken Access Control](src/a01_broken_access_control/)** (IDOR)
2. **[A02:2021 - Cryptographic Failures](src/a02_cryptographic_failures/)** 
3. **[A03:2021 - Injection (XSS)](src/a03_injection_xss/)** (SQL Injection, XSS)
4. **[A04:2021 - Insecure Design](src/a04_lfi/)** (LFI)
5. **[A05:2021 - Security Misconfiguration](src/a05_security_misconfiguration/)** (Directory listing, verbose errors)
6. **[A06:2021 - Vulnerable and Outdated Components](src/a06_vulnerable_components/)** (File upload bypass)
7. **[A07:2021 - Identification and Authentication Failures](src/a07_auth_failures/)** (Brute force)
8. **[A08:2021 - Software and Data Integrity Failures](src/a08_integrity_failures/)** (Insecure deserialization)
9. **[A09:2021 - Security Logging and Monitoring Failures](src/a09_logging_failures/)** (Insufficient logging)
10. **[A10:2021 - Server-Side Request Forgery](src/a10_ssrf/)** (SSRF)

## 🔧 Prerequisites

- Docker Engine (version 20.10 or higher)
- Docker Compose v2
- At least 2GB RAM
- 5GB free disk space

## 🚀 Setup and Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Guajilodev/owasp-top-ten-labs.git
   cd owasp-top-ten-labs
   ```

2. **Start the Docker containers:**
   ```bash
   docker-compose up -d
   ```

3. **Access the platform:**
   Open your browser and navigate to `http://localhost:8081`

4. **Wait for initialization:**
   The database will be automatically populated. This may take 1-2 minutes.

## 📚 Usage Guide

### Basic Navigation
- Browse through the different vulnerability categories from the main index
- Each lab includes both vulnerable and secure implementations
- Use the toggle button to switch between modes
- Follow the guided exercises in each section

### User Accounts (for labs that require authentication)
- `alice` / `password123`
- `bob` / `password123`
- `admin` / `admin`

### Switching Modes
- **Vulnerable Mode**: Demonstrates insecure code and allows exploitation
- **Secure Mode**: Shows properly secured implementation

## 🎯 Exploitation Guide

For comprehensive exploitation techniques using Kali Linux tools, see: [EXPLOTACION_KALI.md](EXPLOTACION_KALI.md)

This detailed guide covers:
- **Manual exploitation** techniques for each vulnerability
- **Automated tools** usage (Burp Suite, SQLMap, Hydra, etc.)
- **Advanced techniques** like log poisoning, reverse shells
- **Kali Linux tools** integration for each attack type
- **Evidence collection** and analysis methods

**Tools covered in the exploitation guide:**
- [Burp Suite](https://portswigger.net/burp) - Web vulnerability scanner and interceptor
- [SQLMap](http://sqlmap.org/) - Automatic SQL injection tool
- [Hydra](https://github.com/vanhauser-thc/thc-hydra) - Password cracker
- [Nikto](https://cirt.net/Nikto2) - Web server scanner
- [Dirb/Dirbuster](http://dirb.sourceforge.net/) - Web content scanner
- [Hashcat](https://hashcat.net/hashcat/) - Password recovery tool
- [John the Ripper](https://www.openwall.com/john/) - Password cracker
- [wfuzz](https://github.com/xmendez/wfuzz) - Web application fuzzer
- [cURL](https://curl.se/) - Command line data transfer tool
- [Metasploit Framework](https://www.metasploit.com/) - Penetration testing framework

## 🚨 Security Warning

> ⚠️ **WARNING**: This repository contains intentional security vulnerabilities for educational purposes. These vulnerabilities can be exploited to gain unauthorized access, compromise data, or otherwise damage systems. DO NOT:
> - Deploy this code in any production environment
> - Use this on systems without explicit authorization
> - Access this platform from public networks without proper isolation

The platform is designed to be accessed only locally for educational purposes.

## 🤝 Contributing

Contributions are welcome! Please read our contribution guidelines before submitting pull requests. Keep in mind that the purpose of this project is educational, so any contributions should maintain the educational value while improving the learning experience.

## 📄 License

This project is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International (CC BY-NC-ND 4.0) license. See the [LICENSE](LICENSE) file for details.

Commercial use is strictly prohibited. This project is available for educational and research purposes only.

## 🙏 Acknowledgments

- [OWASP Foundation](https://owasp.org/) for the Top 10 standard
- [MITRE Corporation](https://cwe.mitre.org/) for the CWE classification
- The cybersecurity community for continuous knowledge sharing
- Educational institutions that promote secure coding practices

## 📞 Support

For questions about this educational platform, please open an issue on the GitHub repository.