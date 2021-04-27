<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


class Mail {


	private $config;


	public function __construct(array $config,)
	{
		$this->config = $config;
	}


	public function send()
	{
		//Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
		    //Server settings
		    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                   //Enable verbose debug output
		    $mail->isSMTP();                                            //Send using SMTP
		    $mail->Host       = $this->config['smtp_host'];             //Set the SMTP server to send through
		    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		    $mail->Username   = $this->config['smtp_login'];            //SMTP username
		    $mail->Password   = $this->config['smtp_password'];         //SMTP password
		    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		    $mail->Port       = $this->config['smtp_port'];             //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

		    //Recipients
		    $mail->setFrom($this->config['mail_from'], $this->config['mail_from_name']);
		    $mail->addAddress($this->config['mail_to'], $this->config['mail_to_name']);

		    //Attachments
		    if(isset($this->cofig['file']) && !empty($this->cofig['file']))
		    	$mail->addAttachment($this->cofig['file']);      			//Add attachments

		    //Content
		    $mail->isHTML(true);                                  		//Set email format to HTML
		    $mail->Subject = $this->config['mail_subject'];
		    $mail->Body    = $this->config['mail_body'];
		    $mail->AltBody = 'Body message not supported HTML';

		    return $mail->send();
		} catch (Exception $e) {
		    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		    return false;
		}
	}
}

##########################################################

require 'config.php';

$mail = new Mail($config);
$result = $mail->send();

print_r($result);