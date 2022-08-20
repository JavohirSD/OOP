<?php

/**
 * Abstrakt klass bu bir xil vazifani turli yo'llar bilan bajaruvchi klasslardagi takrorlanuvchi qismlarni o'z ichiga olgan shablondir.
 * Masalan quyidagi misolda mijozlarga email yuborish uchun bir nechta servislardan foydalanganmiz va ularni har biri uchun alohida klasslar yozilgan.
 * Ammo bu klasslarda email yuborish deyarli bir xil ketma-ketlik va metod orqali bajariladi.
 * Umumiy qismlarni har bir klassda takror-takror yozib chiqmaslik uchun ularni bitta abstrakt klassga yozib olamiz.
 * Keyinchalik qo'shiladigan yangi servis klasslar ham shu shablondan meros olib yoziladi.
 * Abstrakt klassdan foydalanish orqali biz umumiy klasslarda bir xil interfeysga ega bo'lamiz va takrorlanuvchi kodlarni oldini olamiz.
 *
 */

// Umumiy va takrorlanuvchi qismlarni o'z ichiga oladigan asosiy ota abstrakt klassni yaratib olamiz
// Bu klass voris klasslar uchun shablon vazifasini bajaradi.
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

// Aabstrakt ota klassdan meros olamish orqali undagi metod va xususiyatlarni ham o'zlashiramiz.
// Yani har bir email servis klassda construktor va sendEmail() metodlarini qaytadan yozib chiqishimiz shart emas
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
            
            // Barcha servislar bir xil interfeysga ega.
            $response = $this->gateway->sendEmail($message['email'], $message['subject'], $message['body']);
            echo $this->gateway->getStatus($response);
        }
    }
}

// Mijozlar va xabar matnidan iborat massiv.
$messages = [
    ['email' => 'johndoe1@example.com', 'subject' => 'Greeting', 'body' => 'Hello John !'],
    ['email' => 'johndoe2@example.com', 'subject' => 'Greeting', 'body' => 'Hello John !'],
    ['email' => 'johndoe3@example.com', 'subject' => 'Greeting', 'body' => 'Hello John !'],
];

// Amaldagi aktiv email servis klassidan obyekt yaratamiz.
$gateway = new GoogleEmail('GOOGLE_TOKEN', 'https://google.com');

// Boshqaruvchi klass obyektini yaratamiz va unga aktiv email servis obyektini yuboramiz.
$controller = new EmailController($gateway);

// Xabar yuborish jarayonini boshlaymiz.
$controller->sendBulkEmail($messages);
