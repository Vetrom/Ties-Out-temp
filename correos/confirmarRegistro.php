<?php

    //require('phpmailer/class.phpmailer.php');
    require 'phpmailer/PHPMailerAutoload.php';
    $mailer = new PHPMailer();
    $mailer->IsSMTP();
    $mailer->SMTPAuth = true;
    $mailer->SMTPSecure = 'tls';
    $mailer->Host = "mx1.hostinger.mx";
    $mailer->Port = 587;

    $mailer->Username   = "no-reply@ties-out.com";
    $mailer->Password   = "Nyd263UyvVCQ";
    $mailer->SetFrom("no-reply@ties-out.com", "TIESOUT, no responder este correo.");

    $mailer->AddReplyTo("fzarater@gmail.com","Copia de registro");
    $mailer->Subject = "Confirmar cuenta de TIESOUT";

    $mailer->IsHTML(true);
    ob_start();
?>
    <body>
        <body>
            <div style="width:800px; margin-left:auto; margin-right:auto;">
                <center >
                    <figure>
                        <div style="display:inline-block; top:0; margin-right:30px;">
                            <img alt="Logo" src="logoTieOut.png" width="50" height="50">
                        </div>
                        <figcaption style="display:inline-block;">
                            <h2 style="margin:0;"><b>Confirmar registro de cuenta en TIESOUT</b></h2><br>
                        </figcaption>
                    </figure>
                    <hr style="margin-bottom:30px;">
                </center>
                <span style="font-family:'Arial';font-size:16px;">Hola <b>usuario</b>,</span><br><br>
                <span style="font-family:'Arial';font-size:16px;">Te registraste recientemente en TIESOUT, para completar tu registro da clic en confirmar cuenta.</span><br><br>
                <button onclick="" style="cursor:pointer; padding:10px;background-color:#C34C02;color:#FFF;font-family:'Arial';font-size:16px;border-radius: 9px 9px 9px 9px;-moz-border-radius: 9px 9px 9px 9px;-webkit-border-radius: 9px 9px 9px 9px;border: 0px solid #000000;">
                    Confirmar cuenta
                </button><br><br>
                <hr style="margin-bottom:30px;">
                <span style="font-family:'Arial';color:#CCC;">Este mensaje se envi&oacute; a >correo>. Si no pediste est&aacute; informaci&oacute;n, favor de ignorarla.
                    Para dejar de recibir correos <a>Cancela tu suscripci&oacute;n</a></span>
            </div>
        </body>
    </body>
<?php
    $body = ob_get_contents();
    ob_end_clean();
	$mail->Body = $body;

    if($correo != ""){
        $mailer->AddAddress($correo);
    }
    $exito = false;
    if(!$mailer->Send()) {
      $exito = false;
    } else {
      $exito = true;
    }

?>
