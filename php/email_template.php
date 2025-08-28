<?php

function emailTemplateOTP($title, $purpose, $otp) {
    $html = <<<EOT
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OTP Email</title>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Shrikhand&display=swap"
            rel="stylesheet"
        />
    </head>
    <body style="background-color: #ddddfc; align-items: center;">
        <div class="logo" style="padding: 30px; font-family: 'Shrikhand', serif; font-size: 30px; display: flex; justify-content: center;">
            <span>Kwaze</span>
            <span style="color: #de581b;">Edu</span>
            <span class="dot">.</span>
        </div>

        <div class="content" style="border: 3px solid white; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 500px; width: 700px; margin: 0 auto; background-color: white; border-radius: 20px; text-align: center; padding: 10px;">
            <div class="word">
                <h1>(Title)</h1>
                <h2>Your Verification Code is:</h2><br>
                <p><span style="font-size: 50px; font-weight: bolder;">(OTP)</span></p><br>
                <P><span style="font-size: 20px;">This is your verification code for (Purpose). Please make sure to verify within 90 seconds.</span></P>
                
                <br><hr><br>
                <p><span style="color: #c6ccc7; font-size: 20px;">This is an automated email. Please do not reply.</span></p>
                <p><span style="color: #c6ccc7; font-size: 15px;">This email was sent at: (TimeStamp)</span></p>
            </div>
        </div>

        <div class="endBorder" style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100px; width: 700px; margin: 0 auto; border-radius: 20px; text-align: center; padding: 10px;">
        </div>
    </body>
    </html>
    EOT;

    $otp_str = "";

    for ($i = 0; $i < (strlen($otp) - 1); $i++) {
        $otp_str .= $otp[$i] . " ";
    }
    
    $otp_str .= $otp[strlen($otp) - 1];    

    $html = str_replace('(OTP)', $otp_str, $html);
    $html = str_replace('(Title)', $title, $html);
    $html = str_replace('(Purpose)', $purpose, $html);

    $timestamp = date('Y-m-d H:i:s');
    $html = str_replace('(TimeStamp)', $timestamp, $html);

    return $html;
}

?>
