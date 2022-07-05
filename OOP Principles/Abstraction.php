<?php 
/**
Abstraction - bu foydalanuvchiga faqatgina kerakli ma'lumot ko'rsatib imkon qadar keraksiz ma'lumotlarni yashirishdir.
Quyidagi misolda uchburchak yuzasini hisoblash uchun classda calcArea() metodi bor.
Uchburchak yuzini hisoblash uchun bu metodga tomonlarni berishni o'zi yetarli.
Hisoblash jarayonini esa foydalanuchiga ko'rsatilmaydi.
**/

class Triangle
{
    private $a;
    private $b;
    private $c;

    public function __construct($aVal, $bVal, $cVal)
    {
        $this->a = $aVal;
        $this->b = $bVal;
        $this->c = $cVal;
    }

    public function calcArea()
    {
        $a = $this->a;
        $b = $this->b;
        $c = $this->c;

        $p = ($a + $b + $c) / 2;

        return sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
    }
}

$triangle = new Triangle(3, 4, 5);

// Bizga faqat natija kerak. Hisoblash jarayoni esa mavhumligicha qolgani maqul.
echo "Area = " . $triangle->calcArea();
