 <?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../phplibs/phpmailer/src/Exception.php';
    require '../phplibs/phpmailer/src/PHPMailer.php';
    require '../phplibs/phpmailer/src/SMTP.php';

    require '../phplibs/sendpulse-rest-api-php/src/ApiInterface.php';
    require '../phplibs/sendpulse-rest-api-php/src/ApiClient.php';
    require '../phplibs/sendpulse-rest-api-php/src/Storage/TokenStorageInterface.php';
    require '../phplibs/sendpulse-rest-api-php/src/Storage/FileStorage.php';
    require '../phplibs/sendpulse-rest-api-php/src/Storage/SessionStorage.php';
    require '../phplibs/sendpulse-rest-api-php/src/Storage/MemcachedStorage.php';
    require '../phplibs/sendpulse-rest-api-php/src/Storage/MemcacheStorage.php';


    define('API_USER_ID', '837b35d7d3992235fdbc28424a3e4884');
    define('API_SECRET', 'a65752ffbb6fdedf2010265a661e8b3f');
    define('PATH_TO_ATTACH_FILE', __FILE__);

    $SPApiClient = new ApiClient(API_USER_ID, API_SECRET, new FileStorage());

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $tel = trim($_POST["tel"]);

    $message = "Заявка на Онлайн-курс\nЭлектронная почта: $email\nИмя: $name\nТел: $tel";

    $bookID = 1176879;
    $emails = array(
       array(
           'email' => $email,
           'variables' => array(
           	'phone' => $tel,
           	'Имя' => $name
           )
       )
    );
    $SPApiClient->addEmails($bookID, $emails);

    $mail = new PHPMailer(true);

    try {
        $mail->CharSet = 'utf-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.beget.com';
        $mail->SMTPAuth = true; 
        $mail->Username = 'info@yodizschool.ru';
        $mail->Password = 'r2IV6%uy';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 2525;

        $mail->setFrom('info@yodizschool.ru', 'Yodiz School');
        $mail->addAddress('yes@yodiz.ru');
        $mail->addAddress('studio@yodiz.ru');

        $mail->isHTML(true);
        $mail->Subject = 'Заявка на Онлайн-курс';
        $mail->Body    = '';
        if ($email) {
            $mail->Body .= "<p>Заявка на Онлайн-курс</p><p>Имя: {$name}</p><p>Email: {$email}</p><p>Тел: {$tel}</p>";
        }

        $mail->send();

        echo "Done!";
    } catch (Exception $e) {
        echo 'Произошла ошибка. Error: ', $mail->ErrorInfo;
    }
}

?>

