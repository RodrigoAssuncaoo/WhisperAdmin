<!doctype html>
<html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>

<head>
    <title>Confirmação de Email | Whisper</title>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <meta name='viewport' content='width=device-width,initial-scale=1'>
    <link href='https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;700&display=swap' rel='stylesheet'>

    <style type='text/css'>
        body {
            margin: 0;
            padding: 0;
            background-color: #d2edf7;
            font-family: 'Ubuntu', Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        img {
            border: 0;
            display: block;
            width: 100%;
            height: auto;
        }

        .main-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .content {
            padding: 30px 20px;
            text-align: center;
        }

        .content h1 {
            color: #1a1a1a;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .content p {
            color: #333333;
            font-size: 16px;
            line-height: 1.5;
        }

        .highlight {
            color: #489BDA;
            font-weight: bold;
        }

        .cta-button {
            display: inline-block;
            background-color: #8bb420;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 12px 25px;
            margin-top: 25px;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .cta-button:hover {
            background-color: #77a11d;
        }

        .footer {
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        @media only screen and (max-width: 480px) {
            .content {
                padding: 20px 15px;
            }

            .content h1 {
                font-size: 20px;
            }

            .cta-button {
                width: 100%;
                padding: 14px 0;
            }
        }
    </style>
</head>

<body>
    <div class='main-container'>
        <img src='https://rodrigoassuncaoo.github.io/WhisperSite/assets/img/logo/logo%20em%20grande/logo_corrigido.png' alt='Whisper Logo'>

        <div class='content'>
            <h1>Olá, {{FirstName}} 👋</h1>
            <p>Obrigado por juntares-te à <span class="highlight">Whisper</span>!</p>
            <p>Para começares a explorar os nossos roteiros turísticos, confirma o teu email clicando no botão abaixo.</p>

            <a href='{{ReferralLink}}' class='cta-button' target='_blank'>Validar Email</a>

            <p style='margin-top: 30px;'>Recebe <strong>15% de desconto</strong> na próxima compra!<br><span style='font-size: 14px;'>Partilha o código <strong>{{ReferalCode}}</strong> com os teus amigos.</span></p>
        </div>

        <div class='footer'>
            Com os melhores cumprimentos,<br> Equipa {{CompanyName}}
        </div>
    </div>
</body>

</html>