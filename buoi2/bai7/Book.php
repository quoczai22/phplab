<?php
class Book
{
    private $title;
    private $author;
    private $price;

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setTitle($title)
    {
        $trimedTitle = trim($title);
        if (strlen($trimedTitle) == 0) {
            echo "Khong co ten sach";
            return;
        }
        $this->title = $trimedTitle;
    }

    public function setAuthor($author)
    {
        $trimedAuthor = trim($author);
        if (strlen($trimedAuthor) == 0) {
            echo "Khong biet la truyen gi!!!";
            return;
        }
        $this->author = $trimedAuthor;
    }

    public function setPrice($price)
    {
        if (!is_numeric($price) || $price <= 0) {
            echo "Gia khong duoc nho hon khong";
            return;
        }
        $this->price = $price;
    }

    public function __construct($title, $author, $price)
    {
        $this->title = $title;
        $this->author = $author;
        $this->price = $price;
    }
}
