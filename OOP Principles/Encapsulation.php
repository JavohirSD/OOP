<?php
/**
Encapsulation - classdagi muhum xususiyatlarini tashqaridan turib o'zgartirishni oldini olish. Yoki private metodlarni o'zgartitish uchun ishlatiladi.
Encapsulation bu kapsulaga o'ralgan xususiyat va metodlarga to'g'ridan to'g'ri murojaat qilish o'rniga public getter/setter metodlardan foydalanishdir.
Masalan User classda password nomli private xususiyat bor va bu xususiyat faqat heshlangan holatda saqlanishi shart.
Bunday xususiyatlarni o'zgartirish faqatgina setter funksiyalar orqali amalga oshirilishi kerak.
 */

class User
{
    private $password;

    // Setter metodi
    public function updatePassword($password)
    {
        // Parollar doimo heshlangan holatda saqlanishi kerak!
        $this->password = md5($password);
    }

    // Getter metodi
    public function getPassword()
    {
        return 'Your password is: ' . $this->password;
    }
}

$obj = new User();
$obj->updatePassword('12345678');

echo $obj->getPassword();
