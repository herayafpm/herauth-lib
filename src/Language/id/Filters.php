<?php 

return [
    'apiKey' => [
        'IsRequired' => 'Header api-key harus ada',
        'cannotEmpty' => 'Isi Header api-key tidak boleh kosong',
        'notFound' => 'Client tidak ditemukkan',
    ],
    'androidKey' => [
        'IsRequired' => 'Header android-key harus ada',
        'cannotEmpty' => 'Isi Header android-key tidak boleh kosong',
        'notFound' => 'Client tidak ditemukkan',
    ],
    'iosKey' => [
        'IsRequired' => 'Header ios-key harus ada',
        'cannotEmpty' => 'Isi Header ios-key tidak boleh kosong',
        'notFound' => 'Client tidak ditemukkan',
    ],
];