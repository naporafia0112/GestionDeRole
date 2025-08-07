<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">

<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Entretien annulé</title>
    <style type="text/css">
        @media screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                border-radius: 0 !important;
                border: 0 !important;
            }

            .content-padding {
                padding: 20px !important;
            }
        }

        .code {
            display: inline-block;
            background-color: #d9534f;
            color: white;
            font-weight: bold;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 18px;
            font-family: monospace, monospace;
            letter-spacing: 2px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #38414a;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body bgcolor="#f6f8fa"
      style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; background-color: #f6f8fa; margin: 0;">

<table class="body-wrap" bgcolor="#f6f8fa" style="width: 100%;">
    <tr>
        <td></td>
        <td class="container" width="600" style="margin: 0 auto;">
            <div class="content" style="padding: 20px; background: #ffffff; border: 1px solid #e9e9e9; border-radius: 6px;">
                <table width="100%">
                    <tr>
                        <td align="center" bgcolor="#38414a" style="padding: 20px; color: white; font-size: 18px; border-radius: 6px 6px 0 0;">
                            <img src="https://i.pinimg.com/736x/2b/36/12/2b3612426dad8e23b17e6bfd56a6db91.jpg" height="24" alt="Logo">
                            <br/>
                            Entretien annulé
                        </td>
                    </tr>
                    <tr>
                        <td class="content-padding" style="padding: 32px 40px;">
                            <p style="font-size: 16px; color: #24292e;">Bonjour {{ $candidat->prenoms }} {{ $candidat->nom }},</p>

                            <p style="font-size: 16px; color: #24292e;">
                                Nous vous informons que votre entretien prévu pour le poste <strong>{{ $entretien->offre->titre }}</strong> a été <strong style="color: red;">annulé</strong>.
                            </p>

                            <p style="font-size: 16px; color: #24292e;">
                                Nous vous contacterons bientôt pour fixer un nouveau créneau si nécessaire.
                            </p>

                            <div align="center">
                                <a href="{{ url('/') }}" class="btn">Accéder à la plateforme</a>
                            </div>

                            <p style="font-size: 16px; color: #24292e; margin-top: 30px;">
                                Merci pour votre compréhension,<br/>
                                L’équipe RH.
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="footer" style="text-align: center; color: #999; font-size: 12px; padding: 20px;">
                © {{ date('Y') }} .cagecfi. Tous droits réservés.
                <br/>
                <a href="{{ url('/') }}" style="color: #0366d6;">Visitez notre site</a>
            </div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>
