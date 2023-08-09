<?php
/**
 * Open-closed Principle:
 * Tizim strukturasi uni kengaytirish uchun ochiq va qulay bo'lishi kerak ammo ichkariga o'zgartirish kiritish uchun
 * yopiq bo'lishi kerak. Ya'ni tizimning kengaytirilishi quyi pog'onadagi classlar va ularning metodlarini ishlashiga
 * hech qanday ta'sir qilmasligi kerak.
 */


// OCP ga to'g'ri kelmaydi:
// Ushbu misolda agar strukturaga to'rtburchakdan boshqa shakllar ham qo'ishladigan bo'lsa
// quyi Board classdagi yuzani hisoblash metodida ham o'zgarish qilishga to'g'ri keladi.
// Demak tizim kengayishi boshqa classlardagi metodlarga ta'sir qilgani sababli bu OCP prinsipiga zid bo'ladi.
class Rectangle
{
    public $width;
    public $height;

    public function __construct(float $width, float $height)
    {
        $this->width  = $width;
        $this->height = $height;
    }
}

class Board
{
    public $rectangles = [];

    // Tizimga har safar yangi shakl qo'shilganda bu metodni o'zgartirish kerak.
    public function calculateArea()
    {
        $area = 0;
        foreach ($this->rectangles as $rectangle) {
            // Agar doska yuzi turli xil shakllardan iborat bo'lsa, ular uchun bu formula ishlamaydi.
            $area += $rectangle->width * $rectangle->height;
        }
        return $area;
    }
}

$rectangles = [
    new Rectangle(2, 3),
    new Rectangle(3, 4),
    new Rectangle(4, 5),
];

// Faqat to'rtburchaklardan iborat doska yuzini hisoblash
$board = new Board();
$board->rectangles = $rectangles;
echo "AREA OF THE BOARD: " . $board->calculateArea();






// OCP ga to'g'ri keladi.
// 1. Barcha turdagi shakllar uchun umumiy interfeys yaratib olamiz
interface Shape
{
    public function area();
}

// 2. Keyinchalik tizimga qo'shiladigan barcha shakllar shu interfeysdan meros oladi.
class Rectangle implements Shape
{
    public $width;
    public $height;

    public function __construct(float $width, float $height)
    {
        $this->width   = $width;
        $this->height = $height;
    }

    // 3. Har bir shakl uchun maxsus yuzani hisoblash metodi va formulasi mavjud.
    public function area()
    {
        return $this->width * $this->height;
    }
}

// 4. Tizimga har safar yangi shakl qo'shish jarayoni xuddi shunday davom etaveradi.
// Yangi shakl qo'shilishi boshqa klasslarning ishlashiga umuman ta'sir qilmayapti.
class Circle implements Shape
{
    public $radius;

    public function __construct(float $radius)
    {
        $this->radius = $radius;
    }

    public function area()
    {
        return $this->radius * $this->radius * pi();
    }
}

// Asosiy class esa o'zgartirishlar kiritish uchun yopiq holda qoladi.
class Board
{
    public $shapes;

    public function calculateArea()
    {
        $area = 0;
        foreach ($this->shapes as $shape) {
            $area += $shape->area();
        }
        return $area;
    }
}

$shapes = [
    new Circle(1),
    new Circle(2),
    new Circle(3),
    new Rectangle(4, 5),
    new Rectangle(5, 6),
    new Rectangle(6, 7),
];

// Turli xil shakllardan iborat doska yuzasini hisoblash.
$board = new Board();
$board->shapes = $shapes;
echo "AREA OF THE BOARD: " . $board->calculateArea();


