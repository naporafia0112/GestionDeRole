<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre candidature</title>
    <style type="text/css">
        /* Styles responsives pour mobile */
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
    </style>
</head>
<body style="margin: 0; padding: 0; width: 100%; background-color: #f6f8fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji';">

    <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f6f8fa;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Conteneur principal - Largeur fixe sur desktop, pleine largeur sur mobile -->
                <table class="container" width="600" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width: 600px; background-color: #ffffff; border: 1px solid #e1e4e8; border-radius: 6px;">

                    <!-- Section En-tête avec Logo -->
                    <tr>
                        <td align="center" style="padding: 24px; border-bottom: 1px solid #e1e4e8;">
                            <!-- Remplacez par l'URL absolue de votre logo (idéalement en SVG ou PNG transparent) -->
                            <a href="http://127.0.0.1:8000" target="_blank" style="text-decoration: none;">
                                <img src="https://i.pinimg.com/736x/2b/36/12/2b3612426dad8e23b17e6bfd56a6db91.jpg" alt="Logo de l'entreprise" width="48" style="display: block; border: 0;">
                            </a>
                        </td>
                    </tr>

                    <!-- Section Contenu principal -->
                    <tr>
                        <td class="content-padding" style="padding: 32px 40px;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">

                                <!-- Message de salutation et corps du texte -->
                                <tr>
                                    <td>
                                        <h1 style="margin: 0 0 16px; font-size: 24px; font-weight: 600; color: #24292e;">
                                            Confirmation de candidature
                                        </h1>
                                        <p style="margin: 0 0 16px; font-size: 16px; line-height: 1.6; color: #24292e;">
                                            Bonjour {{ $candidature->candidat->prenoms }} {{ $candidature->candidat->nom }},
                                        </p>
                                        <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #24292e;">
                                            Nous avons bien reçu votre candidature et nous vous remercions pour l'intérêt que vous portez à notre entreprise. Vous pouvez suivre l'évolution de votre dossier en utilisant le lien ci-dessous.
                                        </p>
                                    </td>
                                </tr>

                                <!-- Bouton d'action (CTA) -->
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td align="center" bgcolor="#2ea44f" style="border-radius: 6px;">
                                                    <a href="{{ route('candidatures.suivi', $candidature->uuid) }}" target="_blank" style="font-size: 16px; font-weight: 600; text-decoration: none; color: #ffffff; background-color: #2ea44f; border-radius: 6px; padding: 12px 24px; display: inline-block;">
                                                        Suivre ma candidature
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Message de conclusion -->
                                <tr>
                                    <td>
                                        <p style="margin: 0; font-size: 16px; line-height: 1.6; color: #24292e;">
                                            Merci et bonne chance !<br>
                                            L'équipe du RH
                                        </p>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Section Pied de page (Footer) -->
                <table width="600" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width: 600px;">
                    <tr>
                        <td align="center" style="padding: 24px;">
                            <p style="margin: 0; font-size: 12px; color: #6a737d;">
                                © {{ date('Y') }} .cagecfi. All Rights Reserved
                            </p>
                            <p style="margin: 4px 0 0; font-size: 12px;">
                               <a href="http://127.0.0.1:8000" target="_blank" style="color: #0366d6; text-decoration: none;">Visitez notre site</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
