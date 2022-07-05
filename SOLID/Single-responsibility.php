<?php
/**
 * Single-responsibility:
 * SRP bu har bir classdan faqat yagon maqsadda foydalanishdir.
 * Ya'ni quyidagi 1-misolda Document nomli classdan hujjat haqidagi asosiy malumotlarni olish uchun va shu hujjatni turli formatlarga konvertatsiya qilish uchun foydalanilgan.
 * 1-misoldagi Document class ham ma'lumot berish uchun ham konvertatsiya uchun xizmat qilmoqda va SRP prinsipini buzilmoqda.
 * 2-misolda esa Document class faqat document haqida malumot berish uchun xizmat qiladi. Konvertatsiya uchun esa alohida classlar yaratilgan.
 */


// SRP ga to'g'ri kelmaydigan holat:
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
// Convertatsiya uchun esa alohida interfeys va classlar yaratish to'g'ri yechim bo'ladi.
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
}
