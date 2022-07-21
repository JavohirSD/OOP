<?php
/**
 * Adapter (Wrapper) pattern:
 * Adapter pattern bu yangi klass interfeysini oldindan mavjud bo'lgan klass interfeysiga tarjima qilib beruvchi klassdan foydalanishdir.
 * Bir nechta klasslar mantiqan bir xil ishni bajarsa ammo ularning barchasi turli nomli metodlarga ega bo'lgan holatlarni optimallashtirish uchun adapter patterndan foydalanish mumkun.
 * Adapter klasslar orqali tizim arxitekturasini buzmagan holda eski klasslarni o'rnini bosuvchi yangi klasslarni joriy qilish mumkun.
 * Yangi klass adapteri eski klass interfeysiga implement qilinadi va eski klass metodlari orqali yangi klassdagi mos keluvchi metodlarni ishga tushuradi.
 * Ushbu patternni quyidagi misolda batafsil ko'rib chiqamiz:
 */

// 1. To'lov tizimi interfeysini yaratib olamiz.
interface PayVisa {
    function addItem($itemName);
    function addPrice($itemPrice);
}

// Hozirda faqat Visa to'lov tizimi mavjud.
// Bu klassda 2 ta addItem va addPrice metodlari mavjud.
class PayWithVisa implements PayVisa {
    function addItem($itemName)
    {
        echo "1 item added: " . $itemName;
    }

    function addPrice($itemPrice)
    {
       echo "1 item added to total with the price of: " . $itemPrice;
    }
}

// Customer klassda harid qilish logikasi bajariladi.
class Customer {
    private $pay;

    function __construct($pay)
    {
        $this->pay = $pay;
    }

    function buy($itemName, $itemPrice)
    {
        $this->pay->addItem($itemName);
        $this->pay->addPrice($itemPrice);
    }
}

// Bu yerda to'lov uchun Visa to'lov tizimidan foydalaniladi.
$pay = new PayWithVisa();
$customer = new Customer($pay);
$customer -> buy("CocaCola", 2);



// Shu joyda bizda muammo chiqadi. Ya'ni kompaniya Visa to'lov tizimi bilan shartnomani bekor qilmoqchi.
// Uning o'rniga tizimga yangi Paypal to'lov tizimini qo'shmoqchi.
// Paypal tizimi esa butunlay boshqacha nomdagi va qo'shimcha metodlarga (interfeysga) ega.
// Endi biz PayWithVisa klassi metodlari ishlatilgan barcha joylarni o'zgartirib chiqishimiz kerak bo'ladi.
// Bu muammoni biz bitta adapter klass yaratish orqali hal qilishimiz mumkun.

class PayPal
{
    // Yangi metodlar ham Visa klassdagi kabi mantiqan bir xil operatsiya bajaradi.
    // Shunchaki ular turlicha nomlangan.
    function addOneItem($name)
    {
        echo "1 item added: " . $name;
    }

    function addPriceToTotal($price)
    {
        echo "1 item added to total with the price of: " . $price;
    }

    // Qo'shimcha yangi metod ham qo'shildi
    public function addItemAndPrice($name, $price)
    {
        $this->addOneItem($name);
        $this->addPriceToTotal($price);
    }
}

// Adapter klass eski to'lov tizimi interfeysiga voris bo'ladi.
class PaypalToVisaAdapter implements PayVisa {

    // Yangi klass metodlariga murojaat qilish uchun uning obyekti kerak bo'ladi.
    private $visaObject;

    // Konstruktor orqali yangi to'lov tizimi obyektini qabul qilib olamiz.
    function __construct($payObj)
    {
        $this->payObj = $payObj;
    }

    // Eski klass interfeysidagi metodlarni elon qilish shart.
    // Ammo bu metodlar yangi to'lov tizimidagi xuddi shu vazifani bajaruvchi metodni ishga tushurib beradi.
    function addItem($itemName)
    {
        $this->payObj->addOneItem($itemName);
    }

    function addPrice($itemPrice)
    {
        $this->payObj->addPriceToTotal($itemPrice);
    }
}

// Yangi to'lov tizmi obyekti
$paypal = new PayPal();

// Yangi to'lov tizimi obyektini eski to'lov tizimi obyektiga tarjima qilamiz
$pay = new PaypalToVisaAdapter($paypal);

// Customer klass tarkibida hech qanday o'zgarish bo'lmadi.
$customer = new Customer($pay);
$customer -> buy("CocaCola", 2);
