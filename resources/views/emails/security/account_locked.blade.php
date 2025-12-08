@component('mail::message')

# 🔒 Account Temporaneamente Bloccato

Ciao,  
abbiamo rilevato un'attività sospetta sul tuo account **DukaRes** e per motivi di sicurezza l’accesso è stato momentaneamente bloccato.

---

### ⚠️ **Dettagli dell'attività sospetta**
- **IP:** {{ $ip }}
- **Browser:** {{ $ua }}
- **Motivo del blocco:**
@foreach($reasons as $reason)
- {{ ucfirst(str_replace('_', ' ', $reason)) }}
@endforeach

---

### 🔓 Se sei tu, puoi sbloccare l’account
Clicca il pulsante qui sotto:

@component('mail::button', ['url' => $unlockUrl])
Sblocca Account
@endcomponent

---

### 🛡️ Consigli di sicurezza
- Assicurati di usare una connessione affidabile  
- Evita VPN / Proxy durante l'accesso  
- Controlla che nessuno stia cercando di usare le tue credenziali  

Grazie,  
**DukaRes Security System**

@endcomponent
