<?php
/**
Polymorphism - umumiy bir xil maqsadga yo'naltirilgan classlarning metod va xususiyatlariga ularning ota interfeysi orqali murojaat qilish.
Sodda qilib aytganda classning ota interfeysi (yoki abstract class) dagi metodlardan foydalanish.
Masalan bizda Person nomli interfeys bor va qolgan klasslar undan meros olgan.
Demak voris classlarda ham ota interfeysdagi metodlar bo'lishi shart.
Bu holatda biz ota interfeysdagi metod orqali barcha voris klasslardagi xuddi shunday metodlarga kira olamiz.
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
