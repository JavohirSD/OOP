<?php
/**
 * Single-responsibility:
 * SRP bu har bir classdan faqat yagon maqsadda foydalanishdir.
 * Ya'ni bitta classga ko'plab turli xil vazifalarni yuklatish tizim arxitekturasini buzulishiga olib kelishi mumkun.
 */


// SRP ga to'g'ri kelmaydigan holat:
// Document nomli classdan hujjat haqidagi asosiy malumotlarni olish uchun va shu hujjatni turli formatlarga konvertatsiya qilish uchun foydalanilgan.
// Bitta classga turli xil vazifalarni yuklatish SRP prinsipini buzulishiga olib keladi.
class Document
{
    protected $title;
    protected $content;

    public function __construct(string $title, string $content)
    {
        $this->title = $title;
        $this->content= $content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function convertToHtml() {
        echo "DOCUMENT CONVERTED TO HTML";
    }

    public function convertToPdf() {
        echo "DOCUMENT CONVERTED TO PDF";
    }
}


// SRP ga to'g'ri keladigan holat:
// Bunda Document classi faqat hujjat haqida ma'lumot berish uchun xizmat qiladi.
// Convertatsiya va boshqa amallar uchun esa alohida interfeys va/yoki classlar yaratish to'g'ri yechim bo'ladi.
interface ConvertDocumentInterface
{
    public function convert(Document $document);
}

class ConvertToHtml implements ConvertDocumentInterface
{
    public function convert(Document $document)
    {
        echo "DOCUMENT CONVERTED TO HTML";
    }
}

class ConvertToPdf implements ConvertDocumentInterface
{
    public function convert(Document $document)
    {
        echo "DOCUMENT CONVERTED TO PDF";
    }
}

class Document2
{
    protected $title;
    protected $content;

    public function __construct(string $title, string $content)
    {
        $this->title = $title;
        $this->content= $content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
