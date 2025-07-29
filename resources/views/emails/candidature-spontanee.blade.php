<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">

<head>
<meta name="viewport" content="width=device-width"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Confirmation de votre candidature</title>
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
        background-color: #2ea44f;
        color: white;
        font-weight: bold;
        padding: 12px 24px;
        border-radius: 6px;
        font-size: 18px;
        font-family: monospace, monospace;
        letter-spacing: 2px;
    }
</style>
</head>

<body itemscope itemtype="http://schema.org/EmailMessage"
      style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f8fa; margin: 0;"
      bgcolor="#f6f8fa">

<table class="body-wrap"
       style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f8fa; margin: 0;"
       bgcolor="#f6f8fa">
    <tr>
        <td valign="top" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"></td>
        <td class="container" width="600"
            valign="top"
            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;">
            <div class="content"
                 style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                <table class="main" width="100%" cellpadding="0" cellspacing="0"
                       bgcolor="#fff"
                       style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;">
                    <!-- En-tête -->
                    <tr>
                        <td align="center"
                            bgcolor="#38414a"
                            valign="top"
                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #38414a; margin: 0; padding: 20px;">
                            <a href="{{ url('/') }}" style="text-decoration: none;">
                                <img src="https://i.pinimg.com/736x/2b/36/12/2b3612426dad8e23b17e6bfd56a6db91.jpg" height="24" alt="Logo de l'entreprise" style="border: 0;">
                            </a>
                            <br/>
                            <span style="margin-top: 10px; display: block; font-size: 16px;">Confirmation de candidature</span>
                        </td>
                    </tr>

                    <!-- Contenu principal -->
                    <tr>
                        <td class="content-padding"
                            valign="top"
                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 32px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                   style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 16px; font-size: 16px; line-height: 1.6; color: #24292e;">
                                            Bonjour {{ $candidature->candidat->prenoms }} {{ $candidature->candidat->nom }},
                                        </p>
                                        <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #24292e;">
                                            Nous avons bien reçu votre candidature et nous vous remercions pour l'intérêt que vous portez à notre entreprise. Vous pouvez suivre l'évolution de votre dossier en utilisant le code ci-dessous.
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <span class="code">{{ $candidature->uuid }}</span>
                                    </td>
                                </tr>

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

                <!-- Pied de page -->
                <div class="footer"
                     style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                    <table width="100%" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <tr>
                            <td align="center"
                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;"
                                valign="top">
                                © {{ date('Y') }}.cagecfi. Tous droits réservés.<br>
                                <a href="{{ url('/') }}" target="_blank" style="color: #0366d6; text-decoration: none;">Visitez notre site</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
        <td valign="top"
            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"></td>
    </tr>
</table>
</body>
</html>
