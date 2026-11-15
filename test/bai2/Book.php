<?php
class Book {
    private $maSach;
    private $tenSach;
    private $tacGia;
    private $namXB;
    private $donGia;
    private $soLuong;

    public function __construct($maSach, $tenSach, $tacGia, $namXB, $donGia, $soLuong) {
        $this->maSach = $maSach;
        $this->tenSach = $tenSach;
        $this->tacGia = $tacGia;
        $this->namXB = $namXB;
        $this->donGia = $donGia;
        $this->soLuong = $soLuong;
    }

    public function getMaSach() {
        return $this->maSach;
    }

    public function getTenSach() {
        return $this->tenSach;
    }

    public function getTacGia() {
        return $this->tacGia;
    }

    public function getNamXB() {
        return $this->namXB;
    }

    public function getDonGia() {
        return $this->donGia;
    }

    public function getSoLuong() {
        return $this->soLuong;
    }

    public function thanhTien() {
        return $this->donGia * $this->soLuong;
    }

    public function loaiSach() {
        return $this->namXB >= 2022 ? "Sách mới" : "Sách cũ";
    }
}
?>