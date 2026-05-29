<?php

namespace App\Http\Controllers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Illuminate\Http\Request;

class MailController extends Controller
{
    function sendEmail(Request $request)
    {
        $mail = new PHPMailer(true);
        $host     = 'smtp.gmail.com';

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();        
            $mail->CharSet 	  = "UTF-8";                                    //Send using SMTP
            $mail->Host = $host;   
                              //Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication
            $mail->Username = 'jpmolinar@ufpso.edu.co';                     //SMTP username
            $mail->Password = 'vimf adjd zaxt ptaw';                               //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port = '587';                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('jpmolinar@ufpso.edu.co', 'Mailer');
            $mail->addAddress('mquinterorin@ufpso.edu.co', 'Joe User');     //Add a recipient
            

            //Attachments
              //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'hola marlont';
            $mail->Body = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
