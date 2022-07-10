<?php
/** Liskov substitution:
 * Ota klass obyektini voris klass obyekti bilan hech qanday muammosiz almashtira olishimiz kerak.
 * Ya'ni B class A classdan voris olsa, A class obyekti ishlatilgan joyda B klass obyektini ham ishlata olishimiz kerak.
 * Agar voris klass obyekti ota klass obyektini o'rnini bosa olsa demak vorislik to'g'ri bajarilgan bo'ladi.
 * Buning uchun voris classda override qilingan ota klass metodining qabul qiladigan argumentlari kamida ota classdagi bilan teng bo'lishi kerak.
 * Xuddi shunday overrida bo'lgan metodning return tipi ham ota klassdagi metod bilan kamida bir xil bo'lishi talab qilinadi.
 */



interface UserInterface{
    public function makeAction($action);
}

class User implements UserInterface {
    public function makeAction($action): string
    {
        switch ($action) {
            case 'VIEW':
                return $this->view();
            case 'UPDATE':
                return $this->update();
            default:
                throw new ActionException('Action not allowed');
        }
    }

    public function view(){
        return 'view page';
    }

    public function update(){
        return 'update page';
    }
}

class Admin extends User {

    // Liskov prinsipiga ko'ra voris class da override qilingan metodlarning argumentlari
    // ota klassdagi metod bilan kamida teng bo'lishi shart.
    // Override qilingan metodning qaytaradigan return tipi ham kamida ota klassdagi metod bilan bir xil bo'lishi shart.
    public function makeAction($action): string
    {
        switch ($action) {
            case 'VIEW':
                return $this->view();
            case 'UPDATE':
                return $this->update();
            case 'DELETE':
                return $this->delete();
            default:
                throw new ActionException('Action not allowed');
        }
    }

    public function delete(){
        return 'delete page';
    }
}

function getUser($role) {
    switch ($role) {
        case 'USER':
            return new User();
        case 'ADMIN':
        default:
            return new Admin();
    }
}

// Admin class obyektini qaytadi. Admin class bu User classning vorisi hisoblanadi.
$user = getUser('ADMIN');

// Voris class obyekti orqali ota classdagi view() metodga murojat qildik.
// Demak ota klass obyekti o'rniga voris klass obyektidan muammosiz foydalana oldik.
$user->makeAction('VIEW');
