<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
$mail->setLanguage('ru', 'phpmailer/language/');
$mail->IsHTML(true);

//От кого письмо
$mail->setForm('pavel_soros@mail.ru', 'Клиент');
//Кому отправить
$mail->addAddress('pushscriptum@mail.ru');
//Тема письма
$mail->Subject = 'Сообщение с сайта GRUZOVOZ!';

//Загрузка
$load = "С грузчиками!";
if ($_POST['loader'] == "load_no") {
	$load = "Без грузчиков.";
}

//Тело письма
	$body = '<h1>Сообщение с сайта GRUZOVOZ!</h1>';

	if (trim(!empty($_POST['name']))){
		$body.='<p><strong>Имя клиента:</strong> '.$_POST['name'].'</p>';
	}
	if (trim(!empty($_POST['phone_number']))){
		$body.='<p><strong>Телефон клиента:</strong> '.$_POST['phone_number'].'</p>';
	}
	if (trim(!empty($_POST['load']))){
		$body.='<p><strong>Адрес загрузки:</strong> '.$_POST['load'].'</p>';
	}
	if (trim(!empty($_POST['unload']))){
		$body.='<p><strong>Адрес разгрузки:</strong> '.$_POST['unload'].'</p>';
	}
	if (trim(!empty($_POST['loader']))){
		$body.='<p><strong>Погрузка:</strong> '.$load.'</p>';
	}
	if (trim(!empty($_POST['message']))){
		$body.='<p><strong>Сообщение от клиента:</strong> '.$_POST['message'].'</p>';
	}

	//Прикрепить фото
	if (!empty($_FILES['image']['tmp_name'])) {
		//путь загрузки файла
		$filePath = __DIR__ . "/files/" . $_FILES['image']['name'];
		//грузим файл
		if (copy($_FILES['image']['tmp_name'], $filePath)) {
			$fileAttach = $filePath;
			$body.='<p><strong>К заказу прилогается фото</strong></p>';
			$mail->addAttachment($fileAttach);
		}
	}

	$mail->Body = $body;

	//Отправляем
	if (!$mail->send()) {
		$message = 'Ошибка!';
	} else {
		$message = 'Данные отправлены!';
	}

	$response = ['message' => $message];

	header('Content-type: application/json');
	echo json_encode($response);
?>