<?php

class Email
{

    public static $from = array(
        'email' => 'wtc3@schenker.co.rs',
        'name' => 'WTC3 Bot'
    );

    public static function send($subject, $body, $email, $attachment=false, $from = false)
    {
       
/*
        if ($from) {
            self::$from = $from;
        }
  */     
        
        $mail = new YiiMailer();
        $mail->setFrom(self::$from['email'], self::$from['name']);
  
        $mail->IsSMTP();
        $mail->SMTPDebug = 2;
        $mail->SMTPSecure = 'ssl';                        // Enable TLS encryption, `ssl` also accepted
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
                                            // Set mailer to use SMTP
	    $mail->Host = 'mail.schenker.co.rs';  // Specify main and backup SMTP servers
	    $mail->Port = 465;
	    $mail->Username = 'wtc3@schenker.co.rs';                 // SMTP username
	    $mail->Password = '45k1Ma2sn9iF';
	    
        
/*
        $mail->Host = 'localhost';
        $mail->Port = 587;
        $mail->username = 'no-reply@wtc3.schenker.co.rs';
        $mail->Password = 'IeCfvYAzDRH4';
   */
  $mail->setTo(trim($email[0]));
  if (count($email) > 1) {
      array_slice($email,1);
      foreach ($email as $e) {
          $mail->AddCC(trim($e));
      }
  }
        $mail->setSubject($subject);
        $mail->setBody($body);

        if ($attachment) {
            $mail->addAttachment($attachment);
        }
        if (!$mail->send()) {
            echo $mail->ErrorInfo;
        }

    }

    public function init()
    {
        // return false; // FOR TESTING PURPOSES ONLY!!!
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

