<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../lib/Validation.php';

final class ValidationTest extends TestCase {
    public function testValidPelangganData() {
        $data = [
            'no_internet' => '001',
            'nama' => 'Test',
            'no_tlp' => '081234',
            'layanan' => 'HSI50',
            'harga' => 100000,
            'tagihan' => 'lunas',
            'status' => 'aktif'
        ];

        $res = Validation::validatePelangganData($data);
        $this->assertTrue($res['valid']);
        $this->assertEmpty($res['errors']);
    }

    public function testInvalidPelangganData() {
        $data = [
            'no_internet' => '',
            'nama' => '',
            'no_tlp' => '',
            'layanan' => '',
            'harga' => 'abc',
            'tagihan' => 'invalid',
            'status' => 'unknown'
        ];

        $res = Validation::validatePelangganData($data);
        $this->assertFalse($res['valid']);
        $this->assertNotEmpty($res['errors']);
        $this->assertArrayHasKey('no_internet', $res['errors']);
        $this->assertArrayHasKey('harga', $res['errors']);
    }
}
