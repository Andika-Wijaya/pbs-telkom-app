<?php
class Validation {
    public static function validatePelangganData(array $data) : array {
        $errors = [];

        if (empty(trim($data['no_internet'] ?? ''))) {
            $errors['no_internet'] = 'No Internet wajib diisi.';
        }

        if (empty(trim($data['nama'] ?? ''))) {
            $errors['nama'] = 'Nama wajib diisi.';
        }

        if (empty(trim($data['no_tlp'] ?? ''))) {
            $errors['no_tlp'] = 'No Tlp wajib diisi.';
        }

        if (empty(trim($data['layanan'] ?? ''))) {
            $errors['layanan'] = 'Layanan wajib dipilih.';
        }

        if (!isset($data['harga']) || $data['harga'] === '') {
            $errors['harga'] = 'Harga wajib diisi.';
        } elseif (!is_numeric($data['harga'])) {
            $errors['harga'] = 'Harga harus berupa angka.';
        }

        $allowedTagihan = ['lunas', 'belum bayar'];
        if (!isset($data['tagihan']) || !in_array($data['tagihan'], $allowedTagihan)) {
            $errors['tagihan'] = 'Tagihan tidak valid.';
        }

        $allowedStatus = ['aktif','pending','terisolir'];
        if (!isset($data['status']) || !in_array($data['status'], $allowedStatus)) {
            $errors['status'] = 'Status tidak valid.';
        }

        return ['valid' => empty($errors), 'errors' => $errors];
    }
}
