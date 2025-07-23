<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Invitation à un entretien</title>
    <style type="text/css">
        /* Reset des styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f4f4f4; }

        /* Styles pour les clients qui supportent les media queries (mobile) */
        @media screen and (max-width: 600px) {
            .wrapper {
                width: 100% !important;
                max-width: 100% !important;
            }
            .content {
                padding: 20px !important;
            }
            .header {
                padding: 20px 0 !important;
            }
        }
    </style>
</head>
<body style="margin: 0 !important; padding: 0 !important; background-color: #f4f4f4;">

    <!-- HIDDEN PREHEADER TEXT -->
    <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        Vous avez un entretien planifié avec nous. Voici tous les détails.
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="background-color: #f4f4f4;">
                <!--[if (gte mso 9)|(IE)]>
                <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                <tr>
                <td align="center" valign="top" width="600">
                <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" class="wrapper">
                    <!-- LOGO -->
                    <tr>
                        <td align="center" valign="top" style="padding: 40px 20px 20px 20px;" class="header">
                            <a href="#" target="_blank">
                                <img alt="Logo de l'entreprise" src="https://i.pinimg.com/736x/2b/36/12/2b3612426dad8e23b17e6bfd56a6db91.jpg" width="180" style="display: block; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333333; font-size: 16px;" border="0">
                            </a>
                        </td>
                    </tr>
                    <!-- CORPS DE L'EMAIL -->
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                <tr>
                                    <td align="left" style="padding: 40px 30px 20px 30px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
                                        <h1 style="font-size: 24px; font-weight: 600; margin: 0 0 20px 0; color: #1a202c;">Bonjour {{ $candidat->firstname }},</h1>
                                        <p style="margin: 0 0 25px 0;">Suite à votre candidature pour le poste, nous avons le plaisir de vous inviter à un entretien pour échanger plus en détail.</p>

                                        <!-- CARTE D'INFORMATION -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background-color: #f8fafc;">
                                            <tr>
                                                <td align="left" valign="top">
                                                    <p style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                                        <strong>Date :</strong> {{ \Carbon\Carbon::parse($entretien->date)->translatedFormat('l d F Y') }}
                                                    </p>
                                                    <p style="margin: 12px 0 0 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                                        <strong>Heure :</strong> {{ \Carbon\Carbon::parse($entretien->heure)->format('H:i') }} (Heure de Paris)
                                                    </p>
                                                    <p style="margin: 12px 0 0 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                                        <strong>Lieu / Plateforme :</strong> {{ $entretien->lieu ?? 'À préciser' }}
                                                    </p>
                                                     <p style="margin: 12px 0 0 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                                         interviewing_face   <strong>Type :</strong> {{ $entretien->type }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="margin: 25px 0 35px 0;">Merci de bien vouloir vous préparer en conséquence. N'hésitez pas à consulter notre site web pour en apprendre davantage sur notre culture et nos valeurs.</p>

                                    </td>
                                </tr>
                                 <tr>
                                    <td align="left" style="padding: 0px 30px 40px 30px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; color: #555555;">
                                        <p style="margin: 0;">Nous nous réjouissons de cet échange.</p>
                                        <p style="margin: 20px 0 0 0;">Cordialement,<br>L'équipe du RH</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- PIED DE PAGE -->
                    <tr>
                        <td align="center" style="padding: 20px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; line-height: 18px; color: #888888;">
                            <p style="margin: 0;">© {{ date('Y') }}.cagecfi. All Rights Reserved</p>
                           <p style="margin: 4px 0 0; font-size: 12px;">
                               <a href="http://127.0.0.1:8000" target="_blank" style="color: #0366d6; text-decoration: none;">Visitez notre site</a>
                            </p>
                        </td>
                    </tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
    </table>

</body>
</html>
