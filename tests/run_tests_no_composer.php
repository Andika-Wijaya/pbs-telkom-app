<?php
require_once __DIR__ . '/../lib/Validation.php';

$results = [];

$tests = [
    'testValidPelangganData' => function() {
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
        return $res['valid'] === true;
    },

    'testInvalidPelangganData' => function() {
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
        return $res['valid'] === false && !empty($res['errors']);
    }
];

$pass = 0; $fail = 0;
foreach ($tests as $name => $fn) {
    try {
        $ok = $fn();
    } catch (Throwable $e) {
        $ok = false;
    }

    if ($ok) {
        echo "[PASS] $name\n";
        $pass++;
    } else {
        echo "[FAIL] $name\n";
        $fail++;
    }
}

echo "\nSummary: $pass passed, $fail failed\n";
exit($fail > 0 ? 1 : 0);
