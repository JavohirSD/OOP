<?php
/**
Abstraction - bu foydalanuvchiga faqatgina kerakli ma'lumot ko'rsatib imkon qadar keraksiz ma'lumotlarni yashirishdir.
Ya'ni klass tarkibida qanday metodlar borligini va ular qanday vazifani bajarishini bilamiz bu biz uchun aniq bo'ladi.
Ammo u(abstract/mavhum) metodlar ichida qanday logika va amallar bajarilishi bizga nomalum.
Biror abstract metodga qandaydir o'zgarish kiritilsa,loyihadagi shu metod ishlatilgan qismlarga ta'sir qilmasligi kerak.
Abstraksiyani quyidagi sodda misolda ko'rib chiqamiz.
 */


// Asosiy klassni yaratib olamiz
class Triangle
{
    private int $a;
    private int $b;
    private int $c;

    // Boshlang'ich qiymatlarni o'zlashtiramiz
    public function __construct(int $aVal,int $bVal,int $cVal)
    {
        $this->a = $aVal;
        $this->b = $bVal;
        $this->c = $cVal;
    }

    // Abstrakt metod yaratib olamiz.
    public function calcArea(): float
    {
        // Bu yerdagi hisoblash logikasi va amallarni foydalanuvchi bilishi muhum emas.
        $a = $this->a;
        $b = $this->b;
        $c = $this->c;

        $p = ($a + $b + $c) / 2;

        return sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
    }
}

$triangle = new Triangle(3, 4, 5);

// $triangle obyektida calcArea() nomli metod borligi bizga ma'lum.
// Va ushbu metod berilgan uchburchak yuzasini hisoblab berishi ham ma'lum.
// Ammo qanday bu metod ichida qanday jarayon borishi biz uchun mavhumdir.
echo "Area = " . $triangle->calcArea();
