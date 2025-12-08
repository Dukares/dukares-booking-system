<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>

<body style="font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px;">

        <h2 style="color: #d9534f;">⚠️ Nuovo accesso al tuo account DukaRes</h2>

        <p>Ciao,</p>

        <p>Abbiamo rilevato un <strong>accesso da un nuovo dispositivo</strong> sul tuo account DukaRes.</p>

        <p><strong>Dettagli del dispositivo:</strong></p>

        <ul>
            <li><strong>IP:</strong> {{ $ip }}</li>
            <li><strong>Browser:</strong> {{ $browser }}</li>
            <li><strong>Sistema operativo:</strong> {{ $os }}</li>
            <li><strong>Tipo dispositivo:</strong> {{ $deviceType }}</li>
            <li><strong>Data e ora:</strong> {{ now() }}</li>
        </ul>

        <p>
            Se non riconosci questo accesso, ti consigliamo di cambiare immediatamente la tua password
            e di contattare l'assistenza.
        </p>

        <br>

        <p style="color: #777;">DukaRes Security System</p>

    </div>

</body>
</html>
