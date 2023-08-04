<?php
/**
 * Facade pattern:
 * Katta loyihalarda bitta vazifani bajarish uchun bir biriga bog'liq bo'lgan ko'plab klasslar va repozitoryalardan
 * foydalanishga to'g'ri keladi. Agar loyihada bunday vazifalar ko'p bo'lsa, tizim arxitekturasi ham juda
 * murakkablashib ketadi. Facade klasslar yordamida biz murakkab logikalar ketma ketligini bitta oddiy va sodda metod
 * orqali bajarishimiz mumkun bo'ladi. Bunda har bir dasturchi butun tizimni qanday ishlashini va ular orasidagi
 * bog'lanishlarni bilishi shart bo'lmadyi (ammo o'rganib chiqishi tavsiya qilinadi). Oddiy qilib aytganda Facade
 * pattern bu bir nechta ketma-ket bajariladigan metodlarni bitta metod ichida ishga tushurishdir.
 *
 * Endi quyidagi sodda misol orqali bu patternni yana ham yaxshiroq tushunib olish mumkun:
 * Saytda har safar yangi post yaratilganda uni ijtimoiy tarmoqlarga ham joylashtirish kerak bo'ladi.
 * Har bir ijtimoiy tarmoq uchun bizda alohida klasslar bor.
 * Demak har safar yangi post yaratganimizda ko'plab klasslarni elon qilishmiz va ko'plab metodlarga murojat qilish
 * kerak. Agar post yaratish logikasi loyihaning bir nechta joyida ishlatilgan bo'lsachi ? Unda kodlar duplicat bo'ladi
 * va loyiha arxitekturasi juda tushunarsiz axvolga kelib qoladi. Bunday holatlarda bitta Facade klass orqali muammoni
 * hal qilish mumkun.
 */


// Twitterda postlarni ulashish uchun klass
class TwitterShare
{
    function tweet($status, $url)
    {
        // Bu yerda 50 qator kod bo'lishi mumkun
        echo 'Tweet: ' . $status . ' from:' . $url;
    }
}

// Facebookda postlarni ulashish uchun klass
class FacebookShare
{
    function share($url)
    {
        // Bu yerda 50 qator kod bo'lishi mumkun
        echo 'Shared on facebook:' . $url;
    }
}

// Redditda postlarni ulashish uchun klass
class RedditShare
{
    function reddit($url, $title)
    {
        // Bu yerda 50 qator kod bo'lishi mumkun
        echo 'Reddit! url:' . $url . ' title:' . $title;
    }
}

// Facade klass
class shareFacade
{
    // Postlarni ulashish jarayonida ishlatiladigan barcha klasslarni belgilab olamiz
    protected TwitterShare $twitter;
    protected FacebookShare $facebook;
    protected RedditShare $reddit;

    // Obyektlarni yuklab olamiz
    function __construct(TwitterShare $twitterObj, FacebookShare $facebookObj, RedditShare $redditObj)
    {
        $this->twitter  = $twitterObj;
        $this->facebook = $facebookObj;
        $this->reddit   = $redditObj;
    }

    // Barcha klasslaridagi kerakli metodlarni bitta metod ichida amalga oshiramiz
    function share($url, $title, $status)
    {
        $this->twitter->tweet($status, $url);
        $this->facebook->share($url);
        $this->reddit->reddit($url, $title);
    }
}

// Kerakli argumentlar asosida Facade obyektimizni yaratib olamiz.
$facadeObj = new shareFacade(new TwitterShare(), new FacebookShare(), new RedditShare());

// Yangi postni barcha ijtimoiy tarmoqlarga joylash uchun atigi bitta metodda foydalanamiz.
$facadeObj->share('https://example.com', 'My greatest post', 'Read my greatest post ever.');
