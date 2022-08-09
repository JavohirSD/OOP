<?php
/**
 * Polymorphism - Voris klasslar ota klassning ochiq interfeysiga ega bo'lishi va uni override/overload qila olishidir.
Polimorfizmning quyidagi uchta asosiy xususiyati mavjud:
1. Voris klasslarni ota klass o'rnida ham ishlatish mumkun.
2. Voris klasslarda ota klassning ochiq(public/protected) metodlaridan foydalanaish mumkun.
3. Ota klassdan meros bo'lib o'tgan metodlarni voris klassda xuddi shu nom bilan override yoki overload qilish mumkun.
Polimorfizmni quyidagi sodda misolda ko'rib chiqishimiz mumkun:
 */

// Asosiy ota interfeys
interface PersonInterface
{
    public function greet();
}

class Person implements PersonInterface
{
    public function greet(): string
    {
       return 'Greeting!';
    }
}

// voris klasslar
class English extends Person
{
    // voris klasslarda meros bo'lib o'tgan metodni override qilish mumkun.
    public function greet(): string
    {
        return 'Hello!';
    }
}

class German extends Person
{
    public function greet(): string
    {
        return 'Hallo!';
    }
}

class French extends Person
{
    public function greet(): string
    {
        return 'Bonjour!';
    }
}

// Voris klasslarni ota klass o'rnida ham ishlatish mumkun.
function greetings(Person $person){
    return $person->greet();
}

$people = [
    new English(),
    new German(),
    new French()
];

foreach ($people as $person) {
    echo greetings($person) . '<br>';
}
