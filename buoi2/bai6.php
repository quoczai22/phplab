<?php
class BankAccount
{
    private $soTaiKhoan;
    private $tenTaiKhoan;
    private $soDu;

    public function getSoTaiKhoan()
    {
        return $this->soTaiKhoan;
    }

    public function getTenTaiKhoan()
    {
        return $this->tenTaiKhoan;
    }

    public function getSoDu()
    {
        return $this->soDu;
    }

    public function setSoTaiKhoan($soTaiKhoan)
    {
        if (is_string($soTaiKhoan) && strlen($soTaiKhoan) == 10) {
            $this->soTaiKhoan = $soTaiKhoan;
        }
    }

    public function setTenTaiKhoan($tenTaiKhoan)
    {
        if (is_string($tenTaiKhoan) && strlen($tenTaiKhoan) > 0) {
            $this->tenTaiKhoan = $tenTaiKhoan;
        }
    }

    public function setSoDu($soDu)
    {
        if (is_numeric($soDu) && $soDu >= 0) {
            $this->soDu = $soDu;
        }
    }

    public function __construct($soTaiKhoan, $tenTaiKhoan, $soDu)
    {
        $this->setSoTaiKhoan($soTaiKhoan);
        $this->setTenTaiKhoan($tenTaiKhoan);
        $this->setSoDu($soDu);
    }

    public function KiemTraSoDu()
    {
        echo "So du cua tai khoan ban la: " . $this->soDu . "\n";
    }

    public function NapTien($soTien)
    {
        if (is_numeric($soTien) && $soTien > 0) {
            $this->soDu += $soTien;
            echo "Nap tien thanh cong!\n";
        } else {
            echo "Nap tien that bai!\n";
        }
    }

    public function RutTien($soTien)
    {
        if (!is_numeric($soTien)) {
            echo "Phai nhap chu so\n";
        } else if ($soTien <= 0) {
            echo "So tien phai lon hon khong\n";
        } else if ($soTien > $this->soDu) {
            echo "So tien khong duoc lon hon so du\n";
        } else {
            $this->soDu -= $soTien;
            echo "Rut tien thanh cong!\n";
        }
    }

    public function ThongTinTaiKhoan()
    {
        echo "So tai khoan: " . $this->soTaiKhoan . ", Ten tai khoan: " . $this->tenTaiKhoan . ", So du: " . $this->soDu . "\n";
    }
}

$bank = new BankAccount("1234567890", "Trinh Huu Kien Quoc", 10000);
$bank->ThongTinTaiKhoan();
$bank->NapTien(500000000);
$bank->RutTien(200000000);
$bank->KiemTraSoDu();
$bank->ThongTinTaiKhoan();
