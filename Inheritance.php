<?php
/**
Inheritance - Bir class boshqa bir classga voris bo'lishidir.
Voris class ota classning barcha public va protected xususiyatlarini o'ziga meros qilib oladi. Bundan tashqari voris class u o'zining shaxsiy xususiyat va metodlariga ham ega bo'ladi. 
Ota klassdan voris olish extends kalit so'zi orqali amalga oshiriladi.
**/

class Fruit {
  public $name;
  public $color;

  public function __construct($name, $color) {
    $this->name = $name;
    $this->color = $color;
  }

  public function intro() {
    echo "The fruit is {$this->name} and the color is {$this->color}.";
  }
}

// Apple nomli class Fruit nomli classga voris bo'ldi.
class Apple extends Fruit {
  public function message() {
    echo "Am I a fruit or a berry? ";
  }
}

// Voris class obyektini yaratish
$apple = new Apple("Apple", "red");

// Voris classning shaxsiy metodi
$apple->message();

// Ota classdan voris bo'lib o'tgan metod
$apple->intro();
?>
