<?php
/**
 * Interface Segregation:
 * Foydalanuvchilarni hech qachon ularga keraksiz interfeysdan foydalanishga majbur qilmaslik kerak.
 * Ya'ni foydalanuvchi biror interfeysni implement qilganida undagi keraksiz metodlarni ham elon qilishga majbur bo'lmasin.
 * Bitta katta umumiy interfeysdan ko'ra alohida-alohida bir nechta maxsus interfeyslardan foydalanish samaraliroqdir.
 */

// Interface Segregation prinsipiga zid holat.
// Barcha turdagi transport uchun yagona interfeysdan foydalanilmoqda.
interface VehicleInterface
{
    public function drive();
    public function fly();
}

// FutureCar uchun drive() va fly() metodlaridan foydalanamiz.
class FutureCar implements VehicleInterface
{
    public function drive()
    {
        echo 'Driving a future car!';
    }

    public function fly()
    {
        echo 'Flying a future car!';
    }
}

// Ammo oddiy  Car uchun fly() metodidan foydalanmaymiz.
class Car implements VehicleInterface
{
    public function drive()
    {
        echo 'Driving a car!';
    }

    // Umumiy interfeys bu keraksiz metodni elon qilishga majbur qiladi.
    public function fly()
    {
        throw new Exception('Not implemented method');
    }
}

// Airplane uchun esa drive() metodi aslida kerak emas.
class Airplane implements VehicleInterface
{
    public function drive()
    {
        throw new Exception('Not implemented method');
    }

    public function fly()
    {
        echo 'Flying an airplane!';
    }
}





// Interface Segregation prinsipini to'g'ri qo'llash.
// Har bir transport turi uchun alohida interfeyslar yaratilgan.
// Yani mashina va samaliyot uchun alohida interfeys yaratilgan.
interface CarInterface {
    public function drive();
}

interface AirplaneInterface {
    public function fly();
}

// FutureCar classiga ikkala metod ham kerak.
class FutureCar implements CarInterface, AirplaneInterface {

    public function drive() {
        echo 'Driving a future car!';
    }

    public function fly() {
        echo 'Flying a future car!';
    }
}

// Car classiga faqat drive() metodi yetarli.
class Car implements CarInterface {

    public function drive() {
        echo 'Driving a car!';
    }
}

// Airplane uchun esa fly() metodi kerak xolos.
class Airplane implements AirplaneInterface {

    public function fly() {
        echo 'Flying an airplane!';
    }
}
