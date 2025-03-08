<?php
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try{
	$mail = new PHPMailer;
	$mail->Host = 'mail.neulflower.kr'; // SMTP ���� �ּ� �Է�
	$mail->SMTPAuth = true; // SMTP ���� ���
	$mail->Username = 'neulflowerplus@neulflower.kr'; // SMTP ����(���� �ּ�)
	$mail->Password = 'triplen123'; // SMTP �н�����
	$mail->setFrom('neulflowerplus@neulflower.kr', 'test'); // ������ ��� ���� �ּ�, ������ ��� �̸�
	$mail->addAddress('triplen123@naver.com', 'test'); // �޴� ��� ���� �ּ�, �޴� ��� �̸�
	$mail->Subject = 'test'; 
	$mail->Body = 'test'; 

	    $mail->send();
		        echo 'Message has been sent';
} catch (Exception $e) {
	        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}


?>
