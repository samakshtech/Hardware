# Hum Aura Decor Studio — Static site + Telegram booking webhook

Files created:
- `index.html` — main site
- `styles.css` — styles and responsive rules
- `script.js` — form handling and animations
- `send.php` — example server-side webhook to forward bookings to Telegram

Quick setup
1. Edit `send.php` and set `$BOT_TOKEN`, `$SECRET_KEY`, and `$CHAT_IDS`.
   - Put real chat IDs (from Telegram) in `$CHAT_IDS` as an array.
   - For security, move `send.php` outside public webroot or protect it.
2. In `index.html` replace the hidden `REPLACE_WITH_YOUR_SECRET` value with the same secret.
3. Host on a server supporting PHP (most shared hosts). For platforms that require other languages, implement the same POST logic on the server side.

How Telegram integration works
- The form sends a POST to `send.php` with booking details + `secret`.
- `send.php` validates the secret and sends messages to every chat ID in `$CHAT_IDS` via Telegram Bot API.

Security notes
- Never put your bot token in client-side code. Keep it on the server.
- Use a secret key and (optionally) IP restrictions to avoid abuse.
- Consider rate-limiting and captcha for public sites.

Running locally with Live Server (VS Code)
1. Install the `Live Server` extension in VS Code.
2. Right-click `index.html` → `Open with Live Server`.
Note: `send.php` will not work via Live Server (it serves static files). Use a local PHP server for testing:

```bash
php -S localhost:8000
# then open http://localhost:8000 in your browser
```

Deployment
- Upload all files to a PHP-capable host and ensure `send.php` contains the correct bot token and chat IDs.
