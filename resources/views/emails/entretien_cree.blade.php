<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">

<head>
<meta name="viewport" content="width=device-width"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Invitation à un entretien</title>
<style type="text/css">
  /* Responsive mobile styles */
  @media screen and (max-width: 600px) {
    .container {
      width: 100% !important;
      border-radius: 0 !important;
      border: 0 !important;
    }
    .content-padding {
      padding: 20px !important;
    }
    .header-padding {
      padding: 20px 0 !important;
    }
  }
</style>
</head>

<body itemscope itemtype="http://schema.org/EmailMessage"
      style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; 
             -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; 
             line-height: 1.6em; background-color: #f4f4f4; margin: 0;"
      bgcolor="#f4f4f4">

<table class="body-wrap"
       style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; 
              width: 100%; background-color: #f4f4f4; margin: 0;"
       bgcolor="#f4f4f4">
  <tr>
    <td valign="top" 
        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;">
    </td>
    <td class="container" width="600"
        valign="top"
        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; 
               vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;">
      <div class="content"
           style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; 
                  max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
        <table class="main" width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff"
               style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; 
                      border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;">
          <!-- En-tête -->
          <tr>
            <td align="center" class="header-padding" 
                style="padding: 40px 20px 20px 20px; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 16px; 
                       line-height: 1.6; color: #333333; border-radius: 8px 8px 0 0; background-color: #ffffff;">
              <a href="{{ url('/') }}" target="_blank" style="text-decoration: none;">
                <img alt="Logo de l'entreprise" src="https://i.pinimg.com/736x/2b/36/12/2b3612426dad8e23b17e6bfd56a6db91.jpg" 
                     width="180" style="display: block; border: 0;"/>
              </a>
            </td>
          </tr>

          <!-- Corps du mail -->
          <tr>
            <td align="left" class="content-padding"
                style="padding: 40px 30px 20px 30px; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; 
                       font-size: 16px; line-height: 24px; color: #333333;">
              <h1 style="font-size: 24px; font-weight: 600; margin: 0 0 20px 0; color: #1a202c;">
                Bonjour {{ $candidat->firstname }},
              </h1>
              <p style="margin: 0 0 25px 0;">
                Suite à votre candidature pour le poste, nous avons le plaisir de vous inviter à un entretien pour échanger plus en détail.
              </p>

              <!-- Carte d'information -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%" 
                     style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background-color: #f8fafc; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;">
                <tr>
                  <td align="left" valign="top" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;">
                    <p style="margin: 0;">
                      <strong>Date :</strong> {{ \Carbon\Carbon::parse($entretien->date)->translatedFormat('l d F Y') }}
                    </p>
                    <p style="margin: 12px 0 0 0;">
                      <strong>Heure :</strong> {{ \Carbon\Carbon::parse($entretien->heure)->format('H:i') }} (Heure de Paris)
                    </p>
                    <p style="margin: 12px 0 0 0;">
                      <strong>Lieu / Plateforme :</strong> {{ $entretien->lieu ?? 'À préciser' }}
                    </p>
                    <p style="margin: 12px 0 0 0;">
                      <strong>Type :</strong> {{ $entretien->type }}
                    </p>
                  </td>
                </tr>
              </table>

              <p style="margin: 25px 0 35px 0;">
                Merci de bien vouloir vous préparer en conséquence. N'hésitez pas à consulter notre site web pour en apprendre davantage sur notre culture et nos valeurs.
              </p>
            </td>
          </tr>

          <tr>
            <td align="left" style="padding: 0 30px 40px 30px; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 16px; line-height: 24px; color: #555555;">
              <p style="margin: 0;">Nous nous réjouissons de cet échange.</p>
              <p style="margin: 20px 0 0 0;">Cordialement,<br>L'équipe du RH</p>
            </td>
          </tr>
        </table>

        <!-- Pied de page -->
        <div class="footer" 
             style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; 
                    width: 100%; clear: both; color: #888888; margin: 0; padding: 20px; text-align:center;">
          <p style="margin: 0;">© {{ date('Y') }}.cagecfi. All Rights Reserved</p>
          <p style="margin: 4px 0 0; font-size: 12px;">
            <a href="{{ url('/') }}" target="_blank" style="color: #0366d6; text-decoration: none;">Visitez notre site</a>
          </p>
        </div>
      </div>
    </td>
    <td valign="top" 
        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;">
    </td>
  </tr>
</table>

</body>
</html>
