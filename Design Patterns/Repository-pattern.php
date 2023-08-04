<?php
/**
 * Repository pattern:
 * Repository pattern bu model va controller o'rtaasidagi ko'prik vazifasini bajaruvchi interfeyslar to'plamidan iborat qatlamdir.
 * Ya'ni model klass bazaga murojaat qilish uchun yoki undan ma'lumot olish  uchun xizmat qilmasligi kerak.
 * Model bu faqatgina baza(yoki boshqa biror manba)dan olingan ma'lumotlarning barcha xususiyatlarini o'zida aks ettiradigan obyekt bo'lishi kerak xolos.
 * Repozitoriyalar model klass ichida bazaga murojaatlar bo'lmasligini ta'minlab beradi.
 * Ya'ni bazadan yoki boshqa manbadan ma'lumot olish faqat repozitoryalar orqali amalga oshiriladi.
 * Bu patterdan foydalanish afzalligi shundaki, agar siz ma'lumotlar bazasini yoki manbasini o'zgartirsangiz ham bu tizim ishlashiga ta'sir ko'rsatmaydi.
 * Bundan tashqari loyihada duplicat metodlar va mantiqan bir xil bo'lgan kodlarni takrorlanishini oldini oladi.
 * Repository patterndan foydalanish kichik loyihalarda o'zini oqlamaydi ammo uzoq yillik katta loyihalarda qo'llash orqali ko'plab muammolarni oson hal qilish mumkun.
 * Quyida Laravel framework misolida ushbu patternni ko'rib chiqamiz.
 */


// Kichik loyihalar uchun ushbu usulda barcha foydalanauvchilarni olish rostdan ham juda qulay va to'g'ri yechim hisoblanadi.
class UsersController extends Controller
{
    public function index()
    {
        // User model orqali barcha foydalanuvchilarni bazadan olish.
        $users = User::all();
        return view('users.index', [
            'users' => $users
        ]);
    }
}

// Ammo tasavvurr qiling tizim kattalashgach har bir modul alohida mikroservislarga bo'lindi.
// Endi foydalanuvchilar bazadan emas boshqa serverdan API orqali yoki boshqa biror protokol orqali olinadigan bo'lsachi ?
// Bu holda EloquentORM ishlatilgan barcha qismlar qayta yozib chiqilishi kerak bo'ladi.
// Muammoni quyidagicha hal qilish mumkun.


// Repository interfeysini yaratib olamiz va kerakli metodlarni e'lon qilamiz.
interface UserRepositoryInterface
{
    public function all();
    public function create(array  $data);
    public function update(array $data, $id);
    public function delete($id);
    public function find($id);
}

// Kerakli model uchun repository klassni yaratib olamiz.
// Quyida userlarni olish uchun faqat Eloquent ORM orqali bazadaga murojaat qiluvchi repository klass keltirilgan.
// Bu yerda userlarni API orqali yoki boshqa protokollar orqali olinadigan holatlar uchun ham alohida repository klass yaratish mumkun.
class EloquentDbUserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(array $data, $id)
    {
        return  User::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return  User::destroy($id);
    }

    public function find($id)
    {
        if (null == $user =  User::find($id)) {
            throw new ModelNotFoundException("User not found");
        }
        return $user;
    }
}

class UserController extends Controller
{
    // Ayni vaqtdagi aktual repository klass obyektini ushlab turish uchun quyidagicha xususiyat elon qilamiz.
    private UserRepositoryInterface $userRepository;

    // Repository klass obyektini konstruktor orqali User klassga inject qilib olamiz
    public function __construct(UserRepositoryInterface $user)
    {
        $this->userRepository = $user;
    }

    // Bazaga barcha murojatlar model orqali emas balki repository klass metodlari orqali amalga oshiriladi.
    // Keyinchalik User olinadigan manba(baza,api yoki protokol) o'zgarganida User controller klassga hech qanday tasir ko'rsatmaydi.
    // Shunchaki respozitoryani yangisiga o'zgartirish yetarli bo'ladi.
    public function index()
    {
        return $this->userRepository->all();
    }

    public function create(array $data)
    {
        return$this->userRepository->create($data);
    }

    public function update(array $data, $id)
    {
        $this->userRepository->update($data, $id);
    }

    public function delete($id)
    {
        $this->userRepository->delete($id);
    }

    public function find($id)
    {
        return $this->userRepository->find($id);
    }
}
/**
 * 1. Repositorylarni realizatsiya qilish uchun Laravel frameworkda yangi servis provayder yaratib olamiz.
 * 
 *   php artisan make:provider UserRepositoryServiceProvider
 *
 * 2. Yaratilgan servis provayderning register metodi orqali repository interfeysni repository klassga bind qilamiz.
 * Shundan so'ng UserRepositoryInterface obyekti so'ralgan metodlar parametriga avtomatik tarzda EloquentUserRepository klass obyekti berib yuboriladi.
 *
 *   public function register()
 *   {
 *      $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
 *   }
 *
 * 3. Ushbu servis provayderimiz avtomatik va global tarzda yuklanishi uchun uni config/app.php fayldagi
 * Servis provayderlar ro'yxatiga qo'shib qo'yishimiz kerak bo'ladi.
 * 
 *    'providers' => [
 *        App\Providers\RepositoryServiceProvider::class,
 *    ];
**/
