<?php

/*필요한 변수 정보
$user_email;
$user_name;
$user_uuid;
$verify_code;

*/

/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
 * example to see how to use XOAUTH2.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
require '../../../vendor/autoload.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP

//$mail->CharSet = "euc-kr"; //한글깨짐 때문에 설정
$mail->CharSet = "UTF-8"; //한글깨짐 때문에 설정
$mail->Encoding = "base64"; //한글깨짐 때문에 설정


$mail->isSMTP();


//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;  //서버 등에서 오류메세지 보려면 2
//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;

$mail->setLanguage('ko', '../PHPMailer/language/');

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "admin@neulflower.kr";
//Password to use for SMTP authentication
$mail->Password = "kse!1204";
//Set who the message is to be sent from
$mail->setFrom('admin@neulflower.kr', "=?UTF-8?B?".base64_encode("(주)늘 관리자팀")."?="."\r\n"); //한글 깨짐 때문에 인코딩 설정
//Set an alternative reply-to address
$mail->addReplyTo('admin@neulflower.kr', "=?UTF-8?B?".base64_encode("(주)늘 관리자팀")."?="."\r\n");//한글 깨짐 때문에 인코딩 설정
//Set who the message is to be sent to
$mail->addAddress($user_email, "=?UTF-8?B?".base64_encode($user_name)."?="."\r\n"); //한글 깨짐 때문에 인코딩 설정
//Set the subject line
$mail->Subject = "=?UTF-8?B?".base64_encode("(주)늘 비밀번호 재설정을 위한 본인 인증")."?="."\r\n"; //한글 깨짐 때문에 인코딩 설정
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body



$content = "
<html>
 <head>

  <title>(주)늘 비밀번호 재설정</title>
 </head>
 <body >

  <br><br><br>
  본 메일은 (주)늘 관리자 페이지의 로그인 비밀번호 재설정을 요청하셔서 발송된 메일입니다.<br>
  <p>(주)늘 관리자 페이지 비밀번호 재설정을 원하시면 본인확인을 위해 아래의 링크를 클릭하여 주세요.</p>
  <p>링크 클릭 후 15분 이내에 비밀번호 재설정을 완료하셔야 합니다.</P>

  <p>
  <br><br>
  
  <a href='".$admin_domain."/admin/login/reset_pw.php?uuid=".$user_uuid."&vcode=".$verify_code."' target='_blank'>비밀번호 재설정 하기</a>

  </P>

 </body>
</html>


";



$content = iconv("UTF-8", "EUC-KR", $content);



//$mail->msgHTML(file_get_contents('test.html'), __DIR__);
$mail->msgHTML($content, __DIR__);






//제목과 보내는 사람 이름 등등은 직접적으로 인코딩 변경
$subject = "=?UTF-8?B?".base64_encode($subject)."?="."\r\n"; 
$mail_from = "=?UTF-8?B?".base64_encode($mail_from )."?="."\r\n"; 
$mail_to = "=?UTF-8?B?".base64_encode($mail_to)."?="."\r\n"; 






//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
if (!$mail->send()) {
    //echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    //echo "Message sent!";
    //Section 2: IMAP
    //Uncomment these to save your message in the 'Sent Mail' folder.
    #if (save_mail($mail)) {
    #    echo "Message saved!";
    #}
}
//Section 2: IMAP
//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
//You can use imap_getmailboxes($imapStream, '/imap/ssl') to get a list of available folders or labels, this can
//be useful if you are trying to get this working on a non-Gmail IMAP server.
function save_mail($mail)
{
    //You can change 'Sent Mail' to any other folder or tag
    $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";
    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
    $imapStream = imap_open($path, $mail->Username, $mail->Password);
    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    imap_close($imapStream);
    return $result;
}

?>