<?php
class Drink {
    private $maDoUong;
    private $tenDoUong;
    private $giaBan;
    private $soLyDaBan;

    // Constructor
    public function __construct($maDoUong, $tenDoUong, $giaBan, $soLyDaBan) {
        $this->maDoUong = $maDoUong;
        $this->tenDoUong = $tenDoUong;
        $this->giaBan = $giaBan;
        $this->soLyDaBan = $soLyDaBan;
    }

    // Getter methods
    public function getMaDoUong() {
        return $this->maDoUong;
    }

    public function getTenDoUong() {
        return $this->tenDoUong;
    }

    public function getGiaBan() {
        return $this->giaBan;
    }

    public function getSoLyDaBan() {
        return $this->soLyDaBan;
    }

    // Method to calculate doanh thu
    public function doanhThu() {
        return $this->giaBan * $this->soLyDaBan;
    }

    // Method to determine ranking
    public function xepHang() {
        if ($this->soLyDaBan >= 100) {
            return "Bán chạy";
        } else {
            return "Bình thường";
        }
    }
}
?>