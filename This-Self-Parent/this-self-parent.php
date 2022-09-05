<?php
/**
 * self - joriy (yoki parent) klassni o'zini anglatadi. Ya'ni uni klass nomi bilan almashtirish mumkun.
 * self - orqali klass ichidagi static metodlar va xususiyatlarga murojaat qilinadi.
 * self - statik metodlar ichida qo'llaniladi. Ya'ni statik xususiyatlarga murojat qilish uchun.
 * self - Parent klassda 'new self' ota klass obyektini qaytaradi.
 *
 * this - bu ayni joriy obyektni o'zini bildiradi.
 * this - orqali statik metodlarga murojaat qilish mumkun. Statik xususiyatlarga emas!
 * this - orqali non-static metodllarga ham xususiyatlarga ham murojat qilish mumkun.
 * this - statik metodlar ichida qo'llab bo'lmaydi.
 * this - Parent klassda 'new $this' voris klass obyektini qaytaradi.
 *
 * parent - joriy klassning otasini ko'rsatadi. Ya'ni so'ralgan obyekt klasining eng quyi ota klassni.
 *          Quyidagi misolda natija A emas B klassni ko'rsatadi:
 *          class A {}
 *          class B extends A {}
 *          class C extends B { public static function getParent(){ return new parent; } }
 *          var_dump(C::getParent());
 *
 */


class Colors{
    public function getSelfClass()
    {
        return new self;
    }

    public function getThisClass()
    {
        return new $this;
    }
}

class ColorsChild extends Colors
{
    public static string $black;  // statik xususiyat
    public string $white;         // non-static xususiyat


    public static function setStaticProperty()
    {
        self::$black = "#000";

        // Xatolik yuz beradi. Mavjud bo'lmagan obyektning xususiyatiga kirishga urunish bo'lmoqda.
        // Fatal error: Using $this when not in object context
        // $this->white = '#fff';
    }


    // Non-static xususiyatlarga this bilan qiymat beramiz.
    public function setNonstaticProperty()
    {
        $this->white = '#fff';
    }


    // Statik xususiyatlarga self orqali ham this orqali ham qiymat berish mumkun.
    public function setAllProperties(string $color)
    {
        // Buday qilishimiz ham mumkun. Xato emas!
        // self::$black = $color;

        $this->black = $color;
        $this->white = $color;
    }


    // this orqali static metodlarga murojaat qilish mumkun.
    public function callStaticMethod()
    {
        $this->setStaticProperty();
    }


    public function getColors(): string
    {
        // self shu klassni o'zini anglatadi. Ya'ni self::$black = ColorsChild::$black
        return "Black: " . ColorsChild::$black . " White: " . $this->white;
    }

    public function getParentClass()
    {
        return new parent;
    }

}

// Klass obyektini yaratmasdan ham statik metod va xususiyatlarg murojaat qilish mumkun.
ColorsChild::setStaticProperty();


// Quyidagi ikki qator(82-83) kod, 78-qatordagi kod bilan bir xil amal bajaradi. Yuqoridagi kod optimal.
$object = new ColorsChild();
$object->callStaticMethod();


// obyekt orqali statik va non-static xsusiyatlarga qiymat beramiz.
$object->setAllProperties("#f00");


// Colors (ota) klass obyektini qaytaradi. Chunki self ga qayssi klassdan murojat qilinsa o'sha klassni ko'rsatadi.
// Ya'ni agar getSelfClass() metodi voris klassda override qilinsa voris klass obyektini qaytaradi.
var_dump($object->getSelfClass());


// ColorsChild (voris) klass obyektini qaytaradi.
// Chunki ota klassdan voris klass obyekti orqali so'rayapmiz.
// this doimo eng quyi klass obyektini ko'rsatadi.
var_dump($object->getThisClass());


// Colors (ota) klass obyektini qaytaradi.
// Chunki ColorsChild klass Colors klassning vorisi hisoblanadi.
var_dump($object->getParentClass());
