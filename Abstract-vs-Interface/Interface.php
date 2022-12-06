<?php
/**
 * Interfeys - bu bitta loyihada ishlayotgan dasturchilar o'rtasidagi (kontrakt) kelishuvdir.
 * Bundan tashqari agar biror umumiy vazifani bajarishni bir nechta usullari mavjud bo'lsa interfeysdan foydalanish kerak bo'ladi.
 * Shunda vaziyatga qarab bir usuldan boshqasiga o'tganizda yoki yangi usulni kashf etganizda muammoga duch kelmaysiz.
 * Masalan oddiy keshlash masalasini olaylik. Hozirda bazaga saqlayotgan bo'lsangiz keyinchalik albatta uni faylga yoki boshqa
 * optimal manbaga o'tkazishga to'g'ri keladi.
 *
 * Interfeys bu kelishuv. Agar siz bir o'zingiz kichik loyihada ishlayotgan bo'lsangiz interfeysdan foydalanishga ehtiyoj bo'lmasligi aniq.
 * Ammo siz katta jamoa bilan katta loyihada ishlayotgan bo'lsangiz boshqalar bilan kelishmagan holda kod yoza olmaysiz.
 * Va siz bilan kelishmay turib boshqalar ham kod yoza olmaydi. Bunga interfeyslar yo'l qo'ymaydi.
 *
 * Interfeyslar loyihada dokumentatsiya vazifasini ham bajaradi. Yani biror klassni vazifasini tushunish uchun uni ichidagi yuzlab
 * qator kodlarni va metodlarni o'qib chiqishingiz shart emas. Buni klass interfeylari orqali osongina bilib olish mumkun.
 * Masalan:   class Image implements Validation, Compress, Colorize {}
 * Yuqoridagi koddan ko'rinib turibtiki Image klassda validatsiya,siqish va rang berish kabi funksiyalar bajariladi.
 *
 * Interfeyslarning yana bir muhum jihati, aniq bir klassga bog'lanib qolmasdan uning abstraksiyasi orqali ishlashimizga yordam berishidir.
 * Bu esa o'z o'rnida SOLID tamoyillariga to'liq mos keladigan toza kod yozilishini ta'minlab beradi.
 *
 * Interfeysni abstract klassdan farqi:
 * 1. Bitta klass bir nechta interfeysdan foydalana oladi. Ammo bitta klass bitta abstract klassdan voris olishi mumkun.
 * 2. Interfeysning barcha metodlari public bo'ladi va ularni barchasini klassda elon qilish majburiydir.
 * 3. Interfeyslar klassda qanday metodlar bo'lishi kerakligini belgilab beradi xolos ularni ichida qanday kod/logika yozishni belgilamaydi
 *
 * Endi interfeysni qo'llashni  quyidagi sodda misolda ko'rib chiqamiz.
 */


// Quyidagi kodda interfeys ishlatmagan holda
// malumotni bazaga saqlashni realizatsiya qilamiz.
class Data{
    public $data;

    public function save($data)
    {
        $data = $this->validate($this->data);

        $connection = new DBConnection();

        $connection->save($data);
    }

    public function validate($data)
    {
        // tekshirilgan malumotni qaytarish
        return $data;
    }
}

$obj = new Data();
$obj->save('Data');

// Ammo keyinchalik biz malumotni bazaga eams sessiyaga saqlashga qaror qilsakchi ?
// Unda Data klassni yana qaytadan yozishga to'g'ri keladi.
// Keyinchalik esa faylga yozishga to'g'ri kelsachi ?
// Har safar loyiha strukturasini qaytadan yozish kerak bo'ladi.
// Bu muammoni interfeys orqali oson hal qilish mumkun.


// Umumiy interfeys yaratib olamiz
interface Save
{
    public function save();
}


// Interfeys bizga umumiy va majburiy metodlarni belgilab beradi.
// Ammo uni realizatsiyasi har bir klass uchun turlicha bo'ladi.
class SaveDB implements Save
{
    protected function connectDb(){}

    // Saqlash jarayoni barcha klasslarda turlichi bo'ladi.
    public function save($date){
        echo $date.' Bazaga saqlandi <br>';
    }
}


class SaveFile implements Save
{
    protected function openFile(){}

    public function save($date){
        echo $date.' faylga saqlandi <br>';
    }

    protected function closeFile(){}
}


class SaveSession implements Save
{
    protected function openSession(){}

    public function save($date){
        echo $date.' sessiyaga saqlandi <br>';
    }
}

// Asosiy klassni yaratib olamiz.
class Data {
    public $data;
    public Save $driver; // drayver bir turdagi klasslarning umumiy abstraksiyasiga bog'liq qilib yaratiladi.

    // drayver va malumotni yuklab olamiz.
    public function __construct($driver, $data) {
        $this->date = $data;
        $this->driver = $driver;
    }

    // Ushbu metodni o'zgartirmasdan istalgancha yangi driver qo'shish/almashtirish mumkun.
    public function save(){
        $data = $this->validate($this->date);
        $this->driver->save($data);
    }

    public function validate($data){
        return $data;
    }
}

// Asosiy yuqori klass hisoblangan Data klassga hech qanday o'zgarish kiritmagan holda
// Tizimga yangi drayverlarni alohida alohida klass qilib qo'shib ketaveramiz

$data = new Data(new SaveDb(),'My data');
//  $data = new Data(new SaveFile(),'My data');
//  $data = new Data(new SaveSession(),'My data');
