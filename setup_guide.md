# 🔴 PHISHING LAB — COMPLETE PRACTICAL GUIDE
### Windows WSL + PHP Server | CEH Demo | Educational Only

---

> ⚠️ **LEGAL DISCLAIMER:** Yeh lab sirf apne computer pe, apne browser mein test karne ke liye hai.
> Real users pe use karna **IT Act 2000, Section 66C/66D** ke under crime hai.
> Instructor demo only — controlled lab environment.

---

## 📦 STEP 1: WSL Setup (Agar pehle se nahi hai)

### Windows mein WSL install karo:

**PowerShell (Admin) mein run karo:**
```powershell
wsl --install
```
Restart karo. Ubuntu install ho jayega automatically.

**WSL open karo:**
```
Start Menu → Ubuntu → Open
```

---

## 🔧 STEP 2: PHP Install Karo

WSL terminal mein:

```bash
# Update packages
sudo apt update && sudo apt upgrade -y

# PHP install karo
sudo apt install php -y

# Verify karo
php --version
```

**Expected output:**
```
PHP 8.x.x (cli) ...
```

---

## 📁 STEP 3: Lab Files Setup Karo

```bash
# Desktop pe Lab folder banao
mkdir -p /mnt/c/Users/$USER/Desktop/phishing_lab
cd /mnt/c/Users/$USER/Desktop/phishing_lab

# Files download ki jagah — ye folder Windows Explorer mein bhi dikhega
ls
```

**Ab Windows File Explorer mein jaao:**
```
C:\Users\<YourName>\Desktop\phishing_lab\
```

Yahan teen files paste karo jo tumhare paas hain:
- `fake_login.html`
- `capture.php`
- `success.html`

---

## ▶️ STEP 4: PHP Server Chalao

```bash
# Phishing lab folder mein jao
cd /mnt/c/Users/$USER/Desktop/phishing_lab

# PHP built-in server start karo (port 8080)
php -S localhost:8080
```

**Expected output:**
```
PHP x.x Development Server (http://localhost:8080) started
```

> 💡 Server chalte rehna chahiye. Terminal band mat karo!

---

## 🌐 STEP 5: Browser Mein Test Karo

Browser open karo aur jaao:

```
http://localhost:8080/fake_login.html
```

**Kya dikhega:**
- SBI jaise dikhne wala fake login page
- Username + Password fields
- Login karo kisi bhi test credentials se (e.g., `testuser` / `pass123`)

**Kya hoga:**
- Popup aayega — "Credentials Captured!"
- Username aur password display hoga

---

## 📋 STEP 6: Captured Credentials Dekho

PHP wala version use karo to log file mein credentials save honge:

### fake_login.html ko modify karo PHP ke liye:

HTML form tag dhundo:
```html
<form id="loginForm" onsubmit="captureCredentials(event)">
```

Isse replace karo:
```html
<form id="loginForm" method="POST" action="capture.php">
```

Aur `<script>` block remove kar do.

### Ab test karo:
1. `http://localhost:8080/fake_login.html` pe jao
2. Fake credentials dalo
3. Submit karo
4. `success.html` dikhega (redirect)

### Log file dekho:
```bash
cat /mnt/c/Users/$USER/Desktop/phishing_lab/captured_creds.txt
```

**Output dikhega kuch aisa:**
```
=============================================
[CAPTURED] 2024-03-15 14:32:07
---------------------------------------------
  Username  : testuser@gmail.com
  Password  : mypassword123
  IP Address: 127.0.0.1
  Browser   : Mozilla/5.0 ...
=============================================
```

---

## 🔍 STEP 7: URL Difference Demonstrate Karo (Key Teaching Point!)

**Real SBI URL:**
```
https://www.onlinesbi.sbi/
```

**Tumhara fake URL:**
```
http://localhost:8080/fake_login.html
```

**Class mein point out karo:**

| Feature | Real Site | Fake Site |
|---------|-----------|-----------|
| Protocol | `https://` ✅ | `http://` ❌ |
| Domain | `onlinesbi.sbi` ✅ | `localhost:8080` ❌ |
| SSL Lock | 🔒 Green lock ✅ | No lock ❌ |
| URL bar | Clearly SBI ✅ | Random domain ❌ |

> 👉 **Yahi sabse bada difference hai — aur 97% log URL nahi dekhte!**

---

## 📧 BONUS: Email Header Analysis Demo

Gmail mein kisi bhi email pe:
1. **Three dots (⋮)** click karo
2. **"Show original"** click karo
3. Ye dekho:

```
Received-SPF: FAIL  ← ❌ Red Flag!
DKIM Signature: FAIL ← ❌ Red Flag!
From: "SBI Bank" <noreply@sbi-secure-verify.net>
                              ↑
                    Fake domain! Real hai: onlinesbi.sbi
```

---

## 🛡️ STEP 8: Protection Demo

### Test karo Password Manager se:

1. **Bitwarden** extension install karo Chrome mein
2. Real SBI ka URL save karo: `https://www.onlinesbi.sbi/`
3. Ab fake page `localhost:8080` pe jao
4. Dekho — Bitwarden **auto-fill nahi karega!**

> 👉 **Yahi reason hai password manager use karna chahiye!**

---

## 🎯 Live Demo Flow (Class Ke Liye)

```
1. [2 min]  Terminal mein server chalao — php -S localhost:8080
2. [2 min]  Fake page dikhao browser mein — bilkul SBI jaisi
3. [3 min]  Khud test karo — credentials dalo, popup dikhao
4. [3 min]  captured_creds.txt open karo — sab log dekh ke shock honge!
5. [2 min]  URL difference point out karo
6. [3 min]  Password manager demo — auto-fill nahi hua!
```

**Total demo time: ~15 minutes 🔥**

---

## ❓ Common Issues & Fix

| Problem | Fix |
|---------|-----|
| `php: command not found` | `sudo apt install php -y` |
| Port 8080 already in use | `php -S localhost:9090` use karo |
| File not found | Check karo file same folder mein hai |
| Permission denied | `chmod 755 capture.php` |
| capture.php kaam nahi kar raha | Form action check karo — `action="capture.php"` |

---

## 📚 Key Learning Points (Exam Ke Liye)

1. **Phishing** = Social engineering + Fake web page
2. **Tools real world mein:** GoPhish, SET Toolkit, Evilginx2
3. **Protection:** URL verify, HTTPS check, Password Manager, 2FA
4. **Legal:** IT Act 2000 Section 66C (Identity Theft) — 3 year jail
5. **CEH Domain:** Social Engineering (Domain 9)
6. **OWASP:** A07 - Identification & Authentication Failures

---

> ✅ **Lab Complete!**
> Yeh practical sirf controlled, isolated environment (localhost) mein hai.
> Real networks pe kabhi use mat karo.

---
*Prepared for CEH Trainer Demo | Educational Purpose Only*
