<?php
/**
 * Singleton pattern:
 * Shunday classlar borki har safar ularning yangi obyektini yaratgandan RAM yoki CPU dan ortiqcha resurs talab qiladi.
 * Bunday jarayonlar tez-tez yoki qayta-qayta takrorlanib tursa tizimning ishlashiga salbiy tasir ko'rsatadi.
 * Masalan bazaga bog'lanish jarayonini oladigan bo'lsak, har bir "connection" RAM va CPU dan alohida resurs talab
 * qiladi. Singleton pattern bu resurs talab qiladigan obyektlarni bir marotaba yaratib olib keyin butun jarayon
 * davomida shu bitta obyektdan foydalanish usulidir. Yani bitta classdan qayta-qayta obyekt yaratmasdan doim birinchi
 * yaratilgan obyektni o'zidan foydalanish.
 */

// Singleton classning umumiy tuzulishi quyidagicha bo'ladi.
class Singleton
{

    // Obyektni ushlab turish uchun tashqaridan izolyatsiya qilingan private property yaratiladi.
    private static $instance = null;

    // Tashqarishdan murojaat bo'lmasligi uchun konstruktor ham private bo'lishi kerak.
    private function __construct()
    {
        // Resurs talab qiladigan jarayon (masalan: bazaga ulanish)
    }

    // Agar bu klass obyekti avval yaratilmagan bo'lsa yani bu klassni birorta ham obyekti mavjud bo'lmasa
    // klassning yangi obyekti yaratiladi.
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Singleton();
        }

        return self::$instance;
    }
}


// Real loyihada ishlatishga misol.
// Singleton pattern asosida bazaga bog'lanish:
class ConnectDb
{

    // Barcha xususiyatlar tashqaridan qiymat olmasligi uchun private qilamiz.
    // Obyektni ushlab turish uchun ushbu xususiyatni yaratib olamiz.
    private static $instance = null;

    // Bog'lanishni ushlab turish uchun ushbu xususiyatni yaratib olamiz.
    private $conn;

    // Bazaga kirish ma'lumotlari
    private $host = 'localhost';
    private $name = 'DB_NAME';
    private $user = 'DB_USERNAME';
    private $pass = 'DB_PASSWORD';

    // Private constructor orqali bazaga bog'lanish
    private function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->name}", $this->user, $this->pass);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Agar ushbu class obyekti mavjud bo'lsa shu mavjud obyektni qaytariladi
    // Aks holda yangi obyekt yaratiladi.
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ConnectDb();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}

// Birinchi obyekt yaratildi va bazaga bog'lanish amalga oshirildi.
$instance = ConnectDb::getInstance();
$conn     = $instance->getConnection();
var_dump($conn);

// Bu yerda yangi obyekt o'rniga yana birinchi obyektni o'zi qaytarib beriladi.
$instance = ConnectDb::getInstance();
$conn     = $instance->getConnection();
var_dump($conn);

// Bu yerda ham yangi obyekt o'rniga yana birinchi obyektni o'zi qaytarib beriladi.
// Ya'ni bazaga ulanish jarayoni faqat bir martta bajariladi.
$instance = ConnectDb::getInstance();
$conn     = $instance->getConnection();
var_dump($conn);
