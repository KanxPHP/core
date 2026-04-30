# KanxPHP Core

**The "Fortress in a Box" for Rapid API Development.**

KanxPHP is a security-first, high-performance PHP framework designed for one thing: **launching profitable, stateless micro-app APIs in seconds.** 

Stop writing boilerplate for headers, security, and JSON handling. KanxPHP provides the hardened infrastructure (`SafeCurl`, `SqlGuard`, `SafeJSON`) so you can focus on building the logic that generates passive income on marketplaces like RapidAPI.

---

## ⚡ RAD (Rapid Application Development) in Action
**From Concept to Market in 60 Seconds.**

KanxPHP enables "Kit-based" development. Instead of building one API at a time, you scaffold entire professional suites.

### Example: The "Web Scan Kit" 🚀
This kit was built using KanxPHP RAD principles and is ready for production deployment. It bundles four high-value tools into a single endpoint:

1.  **Auditor Controller:** Security header & SEO health analysis.
2.  **SSL Checker:** Real-time certificate expiry & issuer verification.
3.  **Robots Parser:** Directives analysis for SEO and AI-scraper protection.
4.  **DNS Lookup:** High-speed resolution for A, MX, TXT, and CNAME records.

**How it was built:**
```bash
# 1. Scaffold the entire niche infrastructure
php kanx-cli build:kit web-scan

# 2. Generate the specialized logic controllers
php kanx-cli make:controller Auditor
php kanx-cli make:controller SslCheck
php kanx-cli make:controller Robots
php kanx-cli make:controller Dns

# 3. Deploy to RapidAPI
# Done.
```

---

## 🗳️ Sponsor-Driven Roadmap
We build what the market demands. Sponsors get to vote on which **Kanx-Kit** or specific micro-app we develop next. 

### Current Voting Niche: **Security & Validation ("Kanx-Scan")**
*Sponsors are currently prioritizing these upcoming tools:*

*   **JWT Debugger:** Decodes and validates JWT tokens for integrity.
*   **Password Pwned Checker:** Compares hashes against known data breach APIs.
*   **VAT Number Validator:** Checks EU VAT IDs via SOAP/CURL.
*   **IBAN Validator:** Validates bank account numbers via Modulo 97.
*   **Disposable Email Filter:** Detects 5,000+ burner email domains.
*   **XSS Deep-Cleaner:** Strips malicious scripts using `DOMDocument`.
*   **SQLi Threat Scorer:** Analyzes strings for injection patterns via `SqlGuard`.
*   **IPv6/IPv4 Range Checker:** Determines CIDR block membership.
*   **Password Entropy Meter:** Calculates true bit-strength.
*   **HoneyPot Link Generator:** Traps and identifies malicious scrapers.

---

## 🛠️ Core Infrastructure
*   **SafeCurl:** SSRF-protected external requests with private IP blacklisting.
*   **SafeJSON:** Type-enforced JSON parsing with `IntegrityException` handling.
*   **SafeString:** Multi-byte/Emoji-safe truncation and secure entropy generation.
*   **Unified Router:** Single-entry point logic for multi-tool kits.

---

## 🚀 Get Started
Become a contributor or sponsor to gain access to the **Kanx-CLI** and start scaffolding your own API empire.

**Are you ready to launch?** Check out the `/examples/web-scan-kit` directory to see the full implementation of our latest production-ready suite.
