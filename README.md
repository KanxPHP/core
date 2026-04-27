# 🛡️ KanxPHP Core

![Tests](https://github.com)
![License](https://shields.io/badge/Synchronised-2026-02-01-blue?style=for-the-badge)
![PHP Version](https://shields.io/badge/Synchronised-2026-02-01-blue?style=for-the-badge)

**Future-proofed PHP security wrappers and data integrity tools.**

PHP's native functions are powerful but often fail silently or use insecure defaults. **KanxPHP Core** provides a high-integrity abstraction layer that forces secure algorithms, handles errors via exceptions, and ensures your application remains compatible across PHP 7.4 through 8.3+.

---

## 🚀 Installation

Install the core suite via [Composer](https://getcomposer.org):

```bash
composer require kanxphp/core
```

---

## 🛠️ Usage

### Safe String Truncation
Native `substr` or `mb_substr` can be tricky with encoding. `SafeString::limit` handles multi-byte characters and "magic" widths safely.

```php
use KanxPHP\Core\SafeString;

// Returns "This is a..."
echo SafeString::limit("This is a very long string", 10); 
```

### High-Integrity Hashing
Stop worrying about which algorithm is currently "best." `SafeString::hash` defaults to **Argon2id** (the industry gold standard) and throws an exception if the server fails to generate a secure result.

```php
use KanxPHP\Core\SafeString;
use KanxPHP\Core\Exceptions\IntegrityException;

try {
    $hash = SafeString::hash('user-password-123');
} catch (IntegrityException $e) {
    // Handle security failure (e.g., log it and alert admin)
}
```

### High-Integrity Hashing & Verification
Stop worrying about algorithm drift. `SafeString::hash` defaults to **Argon2id** (the industry gold standard) and throws an exception on failure. Pair it with `verify()` for a consistent security API.

```php
use KanxPHP\Core\SafeString;
use KanxPHP\Core\Exceptions\IntegrityException;

// 1. Hashing a password (e.g., during registration)
try {
    $hashedPassword = SafeString::hash('user-secret-password');
} catch (IntegrityException $e) {
    // Handle secure generation failure
}

// 2. Verifying a password (e.g., during login)
$userInput = 'user-secret-password';

if (SafeString::verify($userInput, $hashedPassword)) {
    // Login successful
} else {
    // Invalid credentials
}
```

### Strict JSON Parsing
Native `json_decode` returns `null` on failure. `SafeJSON` ensures you never proceed with corrupted data by throwing a strict `IntegrityException`.

```php
use KanxPHP\Core\SafeJSON;

// Returns an associative array or throws IntegrityException
$data = SafeJSON::parse($jsonString); 

// Encodes data safely with forced UTF-8 and Unescaped Slashes
$json = SafeJSON::encode(['status' => 'secure', 'link' => 'https://kanxphp.com']);
```

---

## 🗳️ Roadmap Democracy

KanxPHP is a community-driven project. We prioritize development based on **Sponsor Votes**. 

**Current Modules under development:**
* [ ] **PII Scrubber**: Automated masking of sensitive data in logs.
* [ ] **Webhook Guard**: Unified signature verification for Stripe/GitHub.
* [ ] **SQL Last-Stand**: Outbound query monitoring for injection patterns.

[**Cast your vote on the Roadmap Discussions →**](https://github.com)

---

## 💖 Support the Mission (Passive Income)

Maintaining security tools requires constant updates to bridge new PHP versions and patch emerging vulnerabilities. By becoming a sponsor, you fund the development of the "Advanced Security Modules."

### Sponsorship Tiers:
* **🛡️ Defender ($10/mo):** Access to Sponsor-only polls and 5x voting weight on the roadmap.
* **🚀 Startup ($49/mo):** Your logo on the README + early access to private security modules.
* **🏛️ Enterprise ($199/mo):** Direct priority support and custom security wrapper requests.

[**Become a Sponsor on GitHub**](https://github.com)

---

## 📝 License

The KanxPHP Core is open-sourced software licensed under the [MIT license](LICENSE).
