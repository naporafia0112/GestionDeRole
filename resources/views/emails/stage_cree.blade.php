<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">

<head>
<meta name="viewport" content="width=device-width"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Validation de votre candidature</title>
</head>

<body itemscope itemtype="http://schema.org/EmailMessage"
      style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;
             -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%;
             line-height: 1.6em; background-color: #f6f6f6; margin: 0;"
      bgcolor="#f6f6f6">

<table class="body-wrap"
       style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;"
       bgcolor="#f6f6f6">
<tr>
  <td valign="top" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"></td>

  <td class="container" width="600"
      valign="top"
      style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;
             vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;">

    <div class="content"
         style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;
                max-width: 600px; display: block; margin: 0 auto; padding: 20px;">

      <table class="main" width="100%" cellpadding="0" cellspacing="0"
             style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;"
             bgcolor="#fff">
        <!-- En-tête avec fond sombre -->
        <tr>
          <td align="center"
              style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px;
                     vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0;
                     background-color: #38414a; margin: 0; padding: 20px;"
              bgcolor="#38414a" valign="top">
            <a href="{{ url('/') }}" style="color: #fff; text-decoration: none;">
              <img src="https://i.pinimg.com/736x/2b/36/12/2b3612426dad8e23b17e6bfd56a6db91.jpg" alt="Logo" height="40" style="border:0;"/>
            </a>
            <br/>
            <span style="margin-top: 10px; display: block; font-size: 16px; font-weight: 600;">Validation de votre candidature</span>
          </td>
        </tr>

        <!-- Contenu principal -->
        <tr>
          <td class="content-wrap"
              style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 40px 40px 20px 40px;"
              valign="top">

            <h1 style="font-size: 24px; font-weight: 600; margin: 0 0 20px 0; color: #333333;">
              Bonjour {{ $candidat->nom }},
            </h1>

            <p style="font-size: 16px; line-height: 24px; color: #333333; margin: 0 0 24px 0;">
              Votre candidature a été validée avec succès.
            </p>

            <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 16px; color: #333333;">Détails du stage :</h2>

            <ul style="margin: 0 0 24px 20px; padding: 0; color: #333333; font-size: 16px; line-height: 1.6;">
              <li><strong>Sujet :</strong> {{ $stage->sujet }}</li>
              <li><strong>Date de début :</strong> {{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}</li>
              <li><strong>Date de fin :</strong> {{ \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') }}</li>
              <li><strong>Lieu :</strong> {{ $stage->lieu }}</li>
            </ul>

            <p style="font-size: 16px; line-height: 24px; color: #333333; margin: 0 0 24px 0;">
              Merci de rester attentif à vos prochaines communications.
            </p>

            <p style="font-size: 16px; line-height: 24px; color: #333333; margin: 0;">
              Cordialement,<br/>
              L'équipe RH
            </p>

          </td>
        </tr>
      </table>

      <!-- Pied de page -->
      <div class="footer"
           style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;
                  width: 100%; clear: both; color: #999999; margin: 0; padding: 20px; text-align: center;">
        <p style="margin: 0;">© {{ date('Y') }} cagecfi. All Rights Reserved</p>
        <p style="margin: 4px 0 0; font-size: 12px;">
          <a href="{{ url('/') }}" target="_blank" style="color: #0366d6; text-decoration: none;">Visitez notre site</a>
        </p>
      </div>

    </div>
  </td>
  <td valign="top" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"></td>
</tr>
</table>
</body>
</html>
