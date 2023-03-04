<?php
/**
 * Strategy (AKA Policy) pattern:
 * Bazida bir xil ketma-ket takrorlanuvchi amallarni turli usullarda bajarishga to'g'ri keladi.
 * Masalan biror maxsulot narxini uni turiga qarab turlicha algoritm bilan hisoblashga kerak bo'lsa.
 * Strategiya patter bizga shunday vaziyatlarda judayam qo'l keladi. Yani bir xil operatsiyalar uchun
 * vaziyatga qarab algoritmlarni almashtirib turish imkonini beradi. Ushbu pattern odatda bitta asosiy context
 * classdan, strategiyalar interdfeysidan va strategiya klasslaridan tashkil topadi.
 * Bunda context klass asosiy biznes logikani va strategiyalarni boshqaruvchi klass hisoblanadi.
 * Strategiya interfeysida algoritmni bajaruvchi klasslar uchun umumiy bo'lgan metodlar e'lon qilinadi.
 * Har bir strategiya kalssda abstract metodlar bo'lib ularda bir xil logika turli xil usullarda bajariladi.
 *
 * Yanada tushunarliroq bo'lishu uchun ushbu patternni quyidagi oddiy misollarda ko'rib chiqamiz.
 * Tasavvur qiling siz ishlaydigan internet do'konida maxsulotlarga chegirma joriy qilish vazifasi qo'yildi.
 * Muammo shundaki chegirma miqdori har bir kategoriya uchun turlicha bo'lishi kerak.
 * Bunday vaziyatda buyurtmalar tarkibidagi maxsulotlarning umumiy narxini hisoblashda Strategiya patterndan foydalanish mumkun.
 */


// Asosiy context klass.
class DiscountCalculator {
    private DiscountStrategy $strategy;

    // Kerakli strategiyani konstruktor orqali tanlash mumkun...
    public function __construct($strategy = null)
    {
        $this->strategy = $strategy;
    }

    // Yoki setter metod orqali ham tanlab olish mumkun.
    // setter metoddan foydalanish strategiyani runtime da o'zgartirish imkonini beradi.
    public function setStrategy(DiscountStrategy $strategy): DiscountCalculator
    {
        $this->strategy = $strategy;
        return $this;
    }

    // Chegirmalarni hisoblab beruvchi metod.
    // Bu metod orqli o'rnatilgan joriy strategiyadan kelib chiqib maxsulotning chegirma narxini olinadi.
    public function getDiscountedPrice(float $price): float
    {
       // Agar strategiya ko'rsatilmagan bo'lsa maxsulot narxini o'zi qaytariladi.
       return $this->strategy ? $this->strategy->getPrice($price) : $price;
    }
}


// Barcha strategiyalar uchun umumiy bo'lgan metodlar bitta interfeys orqali belgilab olinadi.
interface DiscountStrategy {

    // Bizning holatda barcha strategiyalar maxsulotning chegirma narxini hisoblab berishi kerak xolos.
    public function getPrice(float $price): float;
}


// Endi esa strategiya interfeysiga voris bo'lgan strategiya klasslarini elon qilishimiz mumkun.
// Ushbu klass elektronika kategioriyasi uchun chegirma narxini hisoblab beradi.
class ElectronicsDiscount implements DiscountStrategy {

    // Har bir strategiyaning getPrice() metodi turli xil miqdordagi chegirma narxini qaytaradi.
    public function getPrice(float $price): float
    {
        return $price * 0.7;
    }
}

class WearsDiscount implements DiscountStrategy {
    public function getPrice(float $price): float
    {
        return $price * 0.8;
    }
}

class FoodsDiscount implements DiscountStrategy {
    public function getPrice(float $price): float
    {
        return $price * 0.9;
    }
}

// Buyurtma tarkibidagi maxsulotlar:
$cart = [
    ['id' => 10001, 'title' => 'Snickers'],
    ['id' => 10002, 'title' => 'Adidas Cap'],
    ['id' => 10003, 'title' => 'MacBook Pro'],
];

// Algoritmlarni boshqarish uchun Context klassni yaratib olamiz.
$calculator = new DiscountCalculator();

// Buyurtma boshlang'ich narxini nolga tenglab olamiz.
$totalPrice = 0;

// Maxsulotlarni bazadan topib ularni Product modeliga yuklab olamiz.
$products = Product::findWhereIdIn([10001,10002,1003]);

foreach ($products as $product){

    // Kategoriyalarni tekshiramiz va kerakli strategiyani aniqlab olamiz.
    switch ($product->getCategory()) {
        case 'Foods':
            $strategy = new FoodsDiscount();
            break;
        case 'Wears':
            $strategy = new WearsDiscount();
            break;
        case 'Laptops':
            $strategy = new ElectronicsDiscount();
            break;
        default:
            throw new \Exception('Strategy not found for this category.');
    }

    // Aniqlanga kategoriya asosida joriy maxsulotning chegirma narxini olamiz.
    $totalPrice += $calculator->setStrategy($strategy)->getDiscountedPrice($product->price);
}

// Yuqoridagi misolda tushunarli va sodda bo'lishi uchun eng oddiy algoritmlar va hisoblashlardan foydalanildi.
// Kichik loyihalarda Strategiya pattern orqali emas balki oddiy switch case/if else yordamida ham bunday
// hisoblashlarni amalga oshirish mumkun. Ammo katta, ko'p yillik va jamoaviy loyihalarda Strategiyadan
// foydalanish orqali noto'g'ri va chigal vorisliklar yaratilishini oldini olish mumkun.

echo $totalPrice;



