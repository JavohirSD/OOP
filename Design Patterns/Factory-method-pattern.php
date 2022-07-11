<?php
/** Factory method pattern:
 * Factory metod bu biror klassni obyektini yaratish uchun o'sha klass konstruktori o'rnini bosuvchi metoddir.
 * Ya'ni bir xil turdagi klasslar uchun umumiy Factory klass yaratib olinadi va ularning obyektlari faqat shu factory ichidagi metod orqali yaratiladi.
 * Bu patterdan foydalanishning 2 ta asosiy afzalligi va foydali tomoni bor.
 * Birinchisi. Sub klasslarni o'zgartirganda, o'chirganda, qayta nomlaganda yoki boshqasi bilan almashtirganda lohiyadagi
 * shu klass ishlatilgan barcha qismlarni o'zgartirib chiqilmaydi.
 * Ikkinchisi. Agar har safar sub klass obyektini yaratish uchun yoki yaratishdan oldin biror logika ishlatishga to'g'ri kelsa
 * shu logikani loyihaning barcha qismlarida ortiqcha va takrorlanuvchi kod sifatida olib yurilmaydi.
 * Factory patternda foydalanish majburiy emas va doimo to'g'ri yechim bo'lmasligi ham mumkun.
 * Ammo katta tizimlarni loyihalashda ushbu pattern yordamida turli xil muammolarni oldini olish mumkun.
 * Bu patternni yana ham yaxshiroq tushunish uchun quyidagi oddiy misolni ko'rib chiqamiz.
 */


// 1. Factory va sub klasslar uchun inteferyslar yaratib olamiz.
interface CarFactoryInterface
{
    public function createCar(): CarInterface;
}

interface CarInterface
{
    public function getModel(): string;
}

// 2. Bir nechta sub klasslar yaratib olamiz.
class Mercedes implements CarInterface
{
    public function getModel(): string
    {
        return 'Sedan AMG';
    }
}

class Tesla implements CarInterface
{
    public function getModel(): string
    {
        return 'Model X';
    }
}

class BMW implements CarInterface
{
    public function getModel(): string
    {
        return 'BMW X7';
    }
}

// 3. Asosiy Factory klassni yaratib olamiz.
class CarFactory implements CarFactoryInterface
{
    // Car interfeysidagi barcha klasslarni shu metod orqali yaratib olishimiz mumkun.
    // Agar sub klasslardan birortasida o'zgarish bo'lsa(yangi qo'shilsa, o'chirilsa...) faqat shu metodni o'zgartirish yetarli bo'ladi.
    // Bu esa Dependency Inversion prinsipiga ham mos keladi.
    // Ushbu metod bizga tayyor obyekt qaytaradi.
    public function createCar(User $user): CarInterface
    {
        // Sub klasslardan obyekt yaratish uchun quyidagicha ko'rinishdagi murakkab logikalarni
        // butun loyiha bo'ylab turli joylarda takrorlashni oldi olinadi.
        $user_status = (($user->balance * $user->salary / 100000) * sqrt($user->income) + $user->prepayment);

        switch ($user_status){
            case 1:
                // CarFactory bu factory klass bo'lsa createCar() metodi esa factory metod hisoblanadi.
                // Bizga kerakli bo'lgan obyekt shu factory metod orqali yaratiladi va return qilinadi.
                return new Mercedes();
                break;
            case 2:
                return new BMW();
                break;
            default:
                return new Tesla();
                break;
        }
    }
}

// Foydalanuvchiga qandaydir turdagi mashina kerak bo'lsa avvalo mashina zavodiga murojat qilishi kerak bo'ladi.
$factory = new CarFactory();

// Zavod foydalanuvchining imkoniyatlaridan kelib chiqib unga mos keladigan mashinani yuboradi.
$car = $factory->createCar($user);
echo $car->getModel();
