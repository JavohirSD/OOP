<?php
/**
 *  Abstrakt klassni ahamiatini tushunish uchun avval quyidagi muammoli vaziyatni ko'rib chiqaylik.
 *  Yangiliklar sayti obunchilarining email manizillariga har doim yangi postlarni yuborib turishimiz kerak bo'ladi.
 *  Sayt boshida faqat MailRu servisi orqali barchaga email yuborib turar edi.
 *  Lekin keyinchalik MailRu bilan muammo bo'lgach YandexMail orqali yuborishga qaror qildik.
 *  Ammo MailRu va YandexEmail dan qaytadigan response ikki xil. Ya'ni endi MailRu uchun yozilgan kodimiz YandexMail uchun ishlamaydi.
 *  Shu sababli YandexMail uchun butunlay boshqacha kod yozib chiqdik.
 *  Oradan vaqt o'tgach Yandexda ham muammolar chiqa boshladi va biz Gmail dan foydalanishga qaror qildik.
 *  Endi Yandex uchun yozgan kodimiz ham Gmail uchun mos kelmasligi aniq.
 *  Natijada saytimizda juda ko'plab duplikat kodlar, keraksiz metodlar va klasslar ko'payib tizim arxitekturasi tushunarsiz axvolga kelib qoldi.
 *  Tasavvur qiling agar endi yana Gmail dan kechib boshidagi MailRu ga qaytmoqchi bo'lsak yoki yana yangi email servis qo'shoqchi bo'lsakchi ?
 *  Bunday muammoli vaziyatlarni biz abstrakt klasslar yordamida hal qilishimiz mumkun.
 *  Abstrakt klasslar bizga duplikat kodlar bo'lmasligini va tizimni ixcham, o'zgaruvhan qilishga yordam beradi.
 *
 * Quyidagi sodda misolda muammoni oddiy yechimini ko'rib chiqamiz:
 */


// Umumiy va takrorlanuvchi qismlarni o'z ichiga oladigan asosiy ota abstrakt klassni yaratib olamiz
abstract class EmailSender
{ 
    protected string $token;
    protected string $endpoint;
    
    public function __construct(string $token,string $endpoint) 
    {
        $this->token = $token;
        $this->endpoint = $endpoint;
    }

    public function sendEmail(string $receiver, string $subject, string $body): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => [
                "to"      => $receiver,
                "subject" => $subject,
                "body"    => $body
            ],
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json',
            ],
        ]);

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        return $response;
    }

    // Abstrakt klassdagi abstrakt metodlarni voris klasslarda elon qilinishi majburiy bo'ladi.
    abstract public function getStatus(array $response) : string;
}

// Avval boshida biz faqat MailRu servisidan foydalangan edik.
// Bu klass ota abstract klassga voris bo'ladi.
class MailruEmail extends EmailSender
{

    // Turli servislardan turlicha formatdagi javob qaytadi.
    // Shu sababli bu metod barcha voris klasslarda turlicha logikada ishlaydi.
    public function getStatus(array $response) : string
    {
        if (isset($response['delivered'])) {
            return "Email sent successfully";
        }
        return "Something went wrong: " . $response['data']['error'];
    }
}

// Keyingi Yandex Email servisi uchun ham xuddi yuqoridagi abstrakt ota klassdan meros olamiz.
class YandexEmail extends EmailSender
{
    public function getStatus(array $response): string
    {
        if ($response['success'] === 'ok') {
            return "Email sent successfully";
        }
        return "Something went wrong: " . $response['error']['text'];
    }
}

// Gmail va bundan keyin qo'shiladigan yangi email servislar ham xuddi shu tartibda yaratib boriladi.
class GoogleEmail extends EmailSender
{
    public function getStatus(array $response): string
    {
        if ($response['status'] === true) {
            return "Email sent successfully";
        }
        return "Something went wrong: " . $response['gmailError'];
    }
}

// Endi yuqoridagi barcha email servislarni boshqaruvchi asosiy klassni yaratib olamiz.
// Email yuborish jarayoni to'liq shu klassda nazorat qilinadi.
class EmailController
{
    //  Hozirda amalda bo'lgan email servisni tanlab olamiz
    public EmailSender $gateway;
    
    public function __construct(EmailSender $gateway) 
    {
        $this->gateway = $gateway;
    }

    // Ommaviy xabar yuborish metodini yartib olamiz.
    // Bu metod faqatgina mijozlar email manzili va xabar matnidan iborat massiv qabul qiladi.
    public function sendBulkEmail(array $messages)
    {
        foreach ($messages as $message) {

            // Barcha servislarda email yuborish bir xil nomli metod orqali bajariladi.
            $response = $this->gateway->sendEmail($message['email'], $message['subject'], $message['body']);

            // Xabar statusini olish esa barcha servislar uchun individual logika asosida bajariladi.
            echo $this->gateway->getStatus($response);
        }
    }
}

// Mijozlar va xabar matnidan iborat massiv. Buni ma'lumotlar bazasidan yuklab olinadi
// (PHP Generatorlardan foydalanish tavsiya etiladi.)
$messages = [
    ['email' => 'johndoe1@example.com', 'subject' => 'Greeting', 'body' => 'Hello John !'],
    ['email' => 'johndoe2@example.com', 'subject' => 'Greeting', 'body' => 'Hello John !'],
    ['email' => 'johndoe3@example.com', 'subject' => 'Greeting', 'body' => 'Hello John !'],
];

// Amaldagi aktiv email servis klassidan obyekt yaratamiz.
// Bir qator kod bilan istalgan vaqtda boshqa servisga o'tishimiz mumkun.

$gateway = new GoogleEmail('GOOGLE_TOKEN', 'https://google.com');
// $gateway = new MailruEmail('MAILRU_TOKEN', 'https://mail.ru');
// $gateway = new YandexEmail('YANDEX_EMAIL', 'https://yandex.ru');


// Boshqaruvchi klass obyektini yaratamiz va unga aktiv email servis obyektini yuboramiz.
$controller = new EmailController($gateway);

// Xabar yuborish jarayonini boshlaymiz.
$controller->sendBulkEmail($messages);
