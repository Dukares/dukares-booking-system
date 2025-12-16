<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Accesso bloccato</title>
    <style>
        body {
            font-family: sans-serif;
            background: #0f172a;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .box {
            background: #1e293b;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>🚫 Accesso bloccato</h2>
        <p>{{ $reason ?? 'Tentativo sospetto rilevato.' }}</p>
        <p>IP registrato.</p>
    </div>
</body>
</html>
