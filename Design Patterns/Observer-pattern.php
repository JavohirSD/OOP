<?php
/**
 * Observer Pattern:
 * Ushbu patternda bitta kuzatiluvchi (Observable/Subject/Publisher kabi ham nomlanadi) obyektga bir nechta kuzatuvchi (Observers yoki Subscribers) obyektlar biriktirib qo'yiladi.
 * Ya'ni Observable obyekt barcha Observers ro'yxatini o'zida saqlab turadi.
 * Agar kuzatiluvchi obyektda o'zgarish sodir bo'lsa bu haqida barcha kuzatuvchilarning biror metodiga shu o'zgarishlar yuboriladi va ularni xabardor qilinadi.
 * Observer pattern birga ko'p (one to many) munosabatdan foydalangan holda ishlaydi. Ya'ni bir nechta kuzatuvchi bitta obyektdagi o'zgarishlarni kuzatadi.
 * Observer Patternning asosiy vazifasi bir obyektdagi o'zgarishlarni boshqa bir nechta (aniq sonini bilmagan holda ham) obyektlarga buni ortiqcha kod yozmasdan avtomatik tarzda bildirib turishdir.
 * Ushbu patternning asosiy kamchliklari bu kuzatuvchilarni xabardor qilishda ularni tartibini nazorat qila olmasligimiz va operativ xotiradan ko'p joy egallashidir.
 * Yuqoridagi nazariyani quyidagi kodda ko'rib chiqamiz.
 **/


/**
 * 1. Observable (yoki Subject) ya'ni kuzatiluvchi klassni yaratib olamiz.
 * Ushbu misolda Observable klassni PHP ning SplSubject interfeysiga bog'lab ishlatamiz.
 * PHP da Observer patter uchun ko'plab boshqa interfeyslar ham mavjud.
 */
class Newspaper implements SplSubject {
    private string $name;
    private string $content;

    // 2. Kuzatuvchi klasslar obyetklarini shu xususiyat ichida saqlab boramiz.
    // PHP da obyektlarni saqlab turish uchun SplObjectStorage klassidan foydalanish qulay va shu tavsiya qilinadi.
    private SplObjectStorage $observers;

    // 3. Konstruktor orqali boshlang'ich qiymatlar bilan obyektni yaratib olamiz.
    public function __construct(string $name) {
        $this->name = $name;
        $this->observers = new SplObjectStorage;
    }

    // 4. Yangi kuzatuvchi qo'shish uchun SplSubject interfeysining attach metodidan foydalanamiz
    // SplSubject metodlari faqatgina SplObserver interfeysi joriy qilingan klasslar obyetlarini qabul qiladi.
    public function attach(SplObserver $observer) {
        $this->observers->attach($observer);
    }

    // 5. Bu metod ham SplSubject interfeysiga tegishli bo'lib kuzatuvchilarni o'chirish uchun ishlatiladi.
    // Bizga kerak bo'lmay qolgan kuzatuvchini massivdan o'chirish uchun ishlatiladi.
    // Obyektdagi o'zgarishlar haqida o'chirilgan kuzatuvchiga xabar berilmaydi.
    public function detach(SplObserver $observer) {
        $this->observers->detach($observer);
    }

    // 6. Joriy klassning content xususiyatiga o'zgartirish kiritilganda barcha kuzatuvchilarni bu haqida xabar yuboramiz.
    public function changeContent($content) {

        // O'zgarish amalga oshirildi.
        $this->content = $content;

        // Kuzatuvchilarga xabar yuborish.
        $this->notify();
    }

    // 7. Ushbu metod orqali barcha kuzatuvchilarning ma'lum bir metodiga o'zgargan yangi obyektni yuboramiz.
    // nofiy() metodi ham SplSubject interfeysi tarkibidagi metod hisoblanadi.
    public function notify() {
        foreach ($this->observers as $value) {
            $value->update($this);
        }
    }

    // 8. Content xususiyatining joriy qiymatini qaytaruvchi getter metodi.
    public function getContent(): string
    {
        return $this->content." in $this->name";
    }
}



/**
 * 9. Kuzatuvchi (Observer) klassni yaratib olamiz.
 * Yuqoridagi Observable klassda SplSubject interfeysidan foydalanganimiz uchun Observer klassimizda SplObserver interfeysidan foydalanamiz.
 * Chunki PHP ning bu ikkta interfeysi doimo birga ishlatiladi.
 */
class Reader implements SplObserver{
    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    // 10. SplObserver interfeysining yagona metodi bo'lgan update() metodini elon qilib olamiz.
    // Ushbu metod kuzatilayotgan obyektdagi o'zgarishlarni qabul qilib oladi va shunga mos logikani amalga oshiradi.
    public function update(SplSubject $subject) {
        echo $this->name.' is reading post about <b>'.$subject->getContent().'</b><br>';
    }
}

// 11. Yangi kuzatiluvchi (Observable) obyektni yaratib olamiz.
$newspaper = new Newspaper('Forbes');

// 12. Bir nechta kuzatuvchilarni ham yaratib olamiz.
$elon = new Reader('Elon Musk');
$bill = new Reader('Bill Gates');
$jeff = new Reader('Jeff Bezos');

// 13. newspaper obyektiga kuzatuvchilarni biriktiramiz
$newspaper->attach($elon);
$newspaper->attach($bill);
$newspaper->attach($jeff);

// 14. kerksiz kuzatuvchini o'chiramiz (agar kerak bo'lsa).
$newspaper->detach($bill);

// 15. newspaper obyektida o'zgarish qilamiz va barcha kuzatuvchilarga bu haqida xabar yuboramiz.
$newspaper->changeContent('Billionaires');

//================ OUTPUT ========================
// Elon Musk is reading post about Billionaires in Forbes
// Jeff Bezos is reading post about Billionaires in Forbes
