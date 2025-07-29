<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Affectation de stage</title>
</head>

<body itemscope itemtype="http://schema.org/EmailMessage"
      style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;
             -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%;
             line-height: 1.6em; background-color: #f6f6f6; margin: 0;"
      bgcolor="#f6f6f6">

<table class="body-wrap" bgcolor="#f6f6f6" style="width: 100%; margin: 0;">
    <tr>
        <td></td>
        <td class="container" width="600" style="display: block !important; max-width: 600px !important; margin: 0 auto; clear: both !important;">
            <div class="content" style="max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                <table class="main" width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff"
                       style="border-radius: 3px; background-color: #fff; border: 1px solid #e9e9e9;">
                    <tr>
                        <td align="center" bgcolor="#38414a" style="font-size: 16px; color: #fff; font-weight: 500; text-align: center; padding: 20px; border-radius: 3px 3px 0 0;">
                            <a href="{{ url('/') }}">
                                <img src="https://i.pinimg.com/736x/2b/36/12/2b3612426dad8e23b17e6bfd56a6db91.jpg" height="24" alt="Logo de l'entreprise" style="border: none;">
                            </a>
                            <br/>
                            <span style="margin-top: 10px; display: block; font-size: 16px;">Affectation de stage</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="content-wrap" style="padding: 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="font-size: 14px; line-height: 1.6; color: #333;">
                                        <p>Bonjour {{ $tuteur->nom }},</p>

                                        <p>Vous avez été désigné comme <strong>tuteur</strong> pour le stage du candidat suivant :</p>

                                        <ul style="padding-left: 20px;">
                                            <li><strong>Nom du stagiaire :</strong> {{ $candidature->candidat->nom }} {{ $candidature->candidat->prenoms }}</li>
                                            <li><strong>Email :</strong> {{ $candidature->candidat->email }}</li>
                                            <li><strong>Téléphone :</strong> {{ $candidature->candidat->telephone }}</li>
                                            <li><strong>Type de stage :</strong> {{ $candidature->candidat->type_depot }}</li>
                                        </ul>

                                        <p>Merci de bien vouloir prendre contact avec le stagiaire dès que possible.</p>

                                        <p style="margin-top: 30px;">Cordialement,<br>L’équipe RH</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <div class="footer" style="width: 100%; clear: both; color: #999; text-align: center; padding: 20px;">
                    <p style="margin: 0;">© {{ date('Y') }} Cagecfi. Tous droits réservés.</p>
                    <p style="margin-top: 5px; font-size: 12px;">
                        <a href="{{ url('/') }}" target="_blank" style="color: #999; text-decoration: underline;">Visitez notre site</a>
                    </p>
                </div>
            </div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>
