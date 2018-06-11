<?php
/**
* Alert when server is offline
*
* This cron has the responsability to alert all the key users when a server host
* is offline
*/
date_default_timezone_set('America/Sao_Paulo');

include_once('mails/Outlook_Mails.php');
/**
* Hosts
* @var array All hosts to be checked
*/
$hosts = ['nead.ugb.edu.br', 'revista.ugb.edu.br', 'nead.ugb.edu.br',
          'portal.ugb.edu.br'];

/**
* All key users to be alerted when a host is off
* @var array
*/
$keys = ['alannunes@ugb.edu.br', 'gustavobrandao@ugb.edu.br',
        'guybrito@ugb.edu.br', 'rosenclevergazoni@ugb.edu.br',
        'suportesistema@ugb.edu.br'];

foreach ($hosts as $host) {
  if($socket =@ fsockopen($host, 80, $errno, $errstr, 30)) {
  fclose($socket);
  } else {
    /**
    * Create a new instance for Outlook Mails
    */
    $mail = new Outlook_Mails();
    /**
    * Set the basics information such as password and from
    */
    $mail->setConfig();
    $mail->subject = 'SERVIDOR FORA DO AR (MENSAGEM AUTOMATICA)';
    $mail->saudacao = welcome(date('H')) . '!';
    $mail->msg = "O servidor com o domínio <strong>{$host}</strong> acabou de
    sair do ar.<br/><br/>Número do erro: <strong>{$errno}</strong><br/>Mensagem
    de Erro: <strong>{$errstr}</strong><br/><br/><strong>Att.</strong><p><i>Esta
     mensagem foi enviada automaticamente</i>.</p>";
    /**
    * Walks through all key users and send a email to them
    */
    foreach ($keys as $to) {
      $mail->to = $to;
      $mail->sendMail();
    }
  }
}
/**
* Greeting Function
*
* @return string Returns a greeting according to time
*/
function welcome($h){
   if($h < 12){
     return "Bom dia";
   }elseif($h > 11 && $h < 18){
     return "Boa tarde";
   }elseif($h > 17){
    return "Boa noite";
   }
}
?>
