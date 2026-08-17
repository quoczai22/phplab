<?php
require_once "Book.php";
require_once "IDownloadable.php";

class Ebook extends Book implements Downloadable
{
    private $fileSize;

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setFileSize($fileSize)
    {
        if ($fileSize <= 0) {
            echo "Dung luong file khong hop le";
            return;
        }
        $this->fileSize = $fileSize;
    }

    public function __construct($title, $author, $price, $fileSize)
    {
        parent::__construct($title, $author, $price);
        $this->setFileSize($fileSize);
    }

    public function download()
    {
        echo "Download Ebook: " . $this->getTitle() . "<br>";
    }
}

$ebook = new Ebook("Doraemon", "Truyen Tranh", 1000000, 10000);
$ebook->download();
