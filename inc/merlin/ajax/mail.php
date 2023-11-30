<?php
require_once('../../../../../../wp-load.php');


/*
$message_name = 'Skillhouse Заявки';
$message_email = 'boffeer@beefheads.ru';

//echo $message_name;
$fields = $_POST;

// Задаем email адреса получателей
$to = explode_textarea(carbon_get_theme_option('leads_emails'));
$to = implode(',', $to);

var_dump(array(
    $message_name,
    $message_email,
    $to,
));
// Заголовки письма
$subject = 'Новое сообщение с сайта';
$headers = array(
  'From: ' . $message_name . ' <' . $message_email . '>',
  'Reply-To: ' . 'info@skillhouse.ru',
  'Content-Type: text/html; charset=UTF-8',
  // 'multipart' => true,
  // 'Content-Type: multipart/mixed; boundary=boundary'
);

// Создаем тело письма
$message = '<html><body>';
foreach ($fields as $key => $value) {
  if ($key !== 'user_file') continue;
    $message .= '<p><strong>' . ucfirst($key) . ':</strong> ' . $value . '</p>';
}
$message .= '</body></html>';
*/

// Если файл был загружен, добавляем его как вложение
/*
if ($file['size'] > 0) {
  $file_name = $file['name'];
  $file_type = $file['type'];

  // Получаем расширение файла из MIME-типа
  $mime_types = wp_get_mime_types();
  $ext = array_search($file_type, $mime_types, true);
  if (!$ext) {
    // Если не удалось получить расширение из MIME-типа, пытаемся получить его из имени файла
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
  }

  // Генерируем имя файла с расширением
  $file_name_with_ext = basename($file_name, '.' . $ext) . '.' . $ext;

  // Создаем массив вложений
  $attachments = array(
    'name' => $file_name_with_ext,
    'type' => $file_type,
    'tmp_name' => $file['tmp_name'],
    'error' => $file['error'],
    'size' => $file['size']
  );
} else {
  $attachments = array();
}
*/

/*
$result = array();
if (mail($to, $subject, $message, $headers)) {
  $result['status'] = 'ok';
} else {
  $result['status'] = 'fail';
}

echo json_encode($result);
die();
*/


/**
 * Telegram
 */


function replacePostKey($key) {
    $keys = array(
        'referrer' => 'Реферер',
        'page' => 'Отрпавлено со страницы',
        'formname' => 'Форма',
        'requestTime' => 'Форма отрпавлена',
        'user_name' => 'Имя',
        'user_tel' => 'Телефон',
    );


    return isset($keys[$key]) ? $keys[$key] : $key;
}

function sortArrayByKeyNames($keyNames, $arrayToSort) {
    $sortedArray = array();
    foreach ($keyNames as $keyName) {
        if (array_key_exists($keyName, $arrayToSort)) {
            $sortedArray[$keyName] = $arrayToSort[$keyName];
            unset($arrayToSort[$keyName]);
        }
    }
    return array_merge($sortedArray, $arrayToSort);
}

function makeTelegramMessageBody($fields) {
    $message = '';
    $line_breaker = '%0A';

    $additional_break_keys = array(
        'user_tel',
        'page',
    );

    foreach ($fields as $key => $value) {
        $separator = ": ";
        $initial_key = $key;
        if (strpos($key, 'quiz_question_') !== false) continue;
        if ($value == '') continue;

//        if ($key == 'page' || $key == 'referrer') $value = urlencode($value);
        if ($key == 'requestTime') {
            $dt = (int) $value / 1000;//new DateTime;
            $formatter = new IntlDateFormatter(
                'ru_RU',
                IntlDateFormatter::LONG,
                IntlDateFormatter::LONG
            );
            $formatter->setPattern('d MMMM yyyy');
            $value = $formatter->format($dt);
        } elseif (strpos($initial_key, 'quiz_step_') !== false) {
            $question_key = str_replace("quiz_step_", "quiz_question_", $key);
            $key = $fields[$question_key];
            $separator .= $line_breaker;
        }

        $message .= '<b>' . replacePostKey($key) . $separator .'</b>' . $value . $line_breaker;

        if (in_array($key, $additional_break_keys) ||
            strpos($initial_key, 'quiz_step_') !== false
        ) {
            $message .= $line_breaker;
        }
    }

    return $message;
}

$config = array(
    'telegram' => true,
);

$post_keys_order = array(
    'formname',
    'user_name',
    'user_tel',
    'requestTime',
);

if ($config['telegram']) {
//    $token = "6087568357:AAG3vvnV91U-u9lkp4-RdqvpWy4JUSNY9nM"; //В переменную $token нужно вставить токен, который нам прислал @botFather
//    $chat_id = "-1001726120894"; //Сюда вставляем chat_id

    $token = "6469208774:AAG6BD94wcrCeHa7lGiZqXV7kX9LhTGH9Gs"; //В переменную $token нужно вставить токен, который нам прислал @botFather
    $chat_id = "-4078573944"; //Сюда вставляем chat_id


    $fields = sortArrayByKeyNames($post_keys_order, $_POST);
    $message = makeTelegramMessageBody($fields);

    $sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$message}", "r");
}
