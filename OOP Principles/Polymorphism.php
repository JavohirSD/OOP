<?php
/**
Polymorphism - Voris klasslar ota klassning ochiq interfeysiga ega bo'lishi yoki ularni override qilishi.
Sodda qilib aytganda ota klassning public va protected metodlaridan foydalanish yoki ularni voris klassda qayta yozish.
Masalan bizda Person nomli interfeys bor va qolgan klasslar undan meros olgan.
Demak voris klasslarda ham ota interfeysdagi metodlardan foydalanish mumkun.
 */

interface Person
{
    public function greet();
}

class English implements Person
{
    public function greet()
    {
        return 'Hello!';
    }
}

class German implements Person
{
    public function greet()
    {
        return 'Hallo!';
    }
}

class French implements Person
{
    public function greet()
    {
        return 'Bonjour!';
    }
}

$people = [
    new English(),
    new German(),
    new French()
];

foreach ($people as $person) {
    echo $person->greet() . '<br>';
}
