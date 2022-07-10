<?php
/**
 * Dependency Inversion:
 * Yuqori darajadagi modullar quyi darajadagi modullarga bog'liq bo'lmasligi kerak.
 * Ikkala turdagi modullar ham abstraktsiyalarga(interfeyslarga) bog'liq bo'lishi kerak.
 * Ya'ni klasslarda metod argumenti yoki xususiyati sifatida obyektlardan emas interfeyslardan foydalanish to'g'ri bo'ladi.
 * Prinsip yana ham tushunarli bo'lishi uchun quyidagi ma'lumotlar bazasi va UserDB moduli misolini ko'rib chiqamiz.
 */

// Dependency Inversion ga zid bo'lgan holat:
// Bu yerda UserDB yuqori modul, MySQLConnection esa quyi modul hisoblanadi.
class UserDB
{
    private $dbConnection;

    // Agar qachondir tizim MySQL dan PostgreSQL ga o'tkazilsa
    // UserDB modulni qayta yozib chiqishga to'g'ri keladi (Bu esa OCP prinsipiga ham zid).
    // Chunki bu tizim abstraksiyaga emas aynan bitta klass ga qaram bo'lib qolgan.
    public function __construct(MySQLConnection $dbConnection)
    {
        $this->$dbConnection = $dbConnection;
    }

    public function store(User $user)
    {
        // Store the user into a database...
    }
}


// Dependency Inversion prinsipi asosida yozilgan to'g'ri yechim:
// Yuqori va quyi modullar abstraksiyaga bo'g'liq bo'lishi uchun avval shu asbtraksiyani yaratib olamiz.
interface DBConnectionInterface {
    public function connect();
}

// Istalgan vaqtda istalgan ma'lumotlar bazasi uchun yangi modul yozishimiz mumkun.
// Lekin ushbu quyi modullar ham asbtraksiyaga bog'liq bo'lishi kerak.
class MySQLConnection implements DBConnectionInterface {
    public function connect() {
        // Return the MySQL connection...
    }
}

class UserDB {

    private $dbConnection;

    // 1. Bu yerda quyi modullar yuqori(UserDB) modulga umuman bog'liq emas.
    // 2. Ammo ikkala modul ham bitta abstraksiya(interfeys) ga bog'liq.
    // Sodda qilib aytganda argument sifatida biror bir klass obyektidan emas uning interfeysida foydalandik.
    // Demak tizim Dependency inversion prinsipi talabiga to'liq javob beradi.
    public function __construct(DBConnectionInterface $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function store(User $user) {
        // Store the user into a database...
    }
}
