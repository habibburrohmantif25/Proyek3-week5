<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'id_barang' => 'BRG001',
                'nama_barang' => 'Laptop Sleek 14 Inch',
                'deskripsi' => 'Laptop portabel dengan performa tinggi untuk bekerja dan belajar.',
                'harga' => 7500000.00,
                'stok' => 5,
                'gambar' => 'laptop.svg',
            ],
            [
                'id_barang' => 'BRG002',
                'nama_barang' => 'Mouse Wireless Ergonomis',
                'deskripsi' => 'Mouse nirkabel 2.4GHz dengan desain ergonomis nyaman untuk genggaman.',
                'harga' => 150000.00,
                'stok' => 15,
                'gambar' => 'mouse.svg',
            ],
            [
                'id_barang' => 'BRG003',
                'nama_barang' => 'Keyboard Mekanikal TKL',
                'deskripsi' => 'Keyboard mekanikal switch biru dengan respon ketik yang renyah.',
                'harga' => 450000.00,
                'stok' => 8,
                'gambar' => 'keyboard.svg',
            ],
            [
                'id_barang' => 'BRG004',
                'nama_barang' => 'Headset Gaming Stereo',
                'deskripsi' => 'Headset over-ear dengan mikrofon peredam bising dan bass mendalam.',
                'harga' => 320000.00,
                'stok' => 10,
                'gambar' => 'headset.svg',
            ],
            [
                'id_barang' => 'BRG005',
                'nama_barang' => 'Monitor 24 Inch Full HD',
                'deskripsi' => 'Layar IPS 1080p 75Hz bezel tipis memberikan sudut pandang luas.',
                'harga' => 1650000.00,
                'stok' => 6,
                'gambar' => 'monitor.svg',
            ],
            [
                'id_barang' => 'BRG006',
                'nama_barang' => 'Flashdisk 64GB USB 3.0',
                'deskripsi' => 'Penyimpanan data cepat dengan bodi metal tahan banting.',
                'harga' => 85000.00,
                'stok' => 25,
                'gambar' => 'flashdisk.svg',
            ],
            [
                'id_barang' => 'BRG007',
                'nama_barang' => 'Webcam 1080p HD',
                'deskripsi' => 'Kamera web autofokus dengan mikrofon ganda untuk video call jernih.',
                'harga' => 275000.00,
                'stok' => 12,
                'gambar' => 'webcam.svg',
            ],
            [
                'id_barang' => 'BRG008',
                'nama_barang' => 'Speaker Bluetooth Portable',
                'deskripsi' => 'Speaker nirkabel tahan cipratan air dengan baterai tahan 8 jam.',
                'harga' => 210000.00,
                'stok' => 7,
                'gambar' => 'speaker.svg',
            ],
            [
                'id_barang' => 'BRG009',
                'nama_barang' => 'Harddisk Eksternal 1TB',
                'deskripsi' => 'Kapasitas besar 1TB untuk backup data penting dan media.',
                'harga' => 820000.00,
                'stok' => 4,
                'gambar' => 'harddisk.svg',
            ],
            [
                'id_barang' => 'BRG010',
                'nama_barang' => 'Smartwatch Fitness Tracker',
                'deskripsi' => 'Jam tangan pintar pengukur detak jantung dan pelacak aktivitas harian.',
                'harga' => 490000.00,
                'stok' => 0,
                'gambar' => 'smartwatch.svg',
            ],
            [
                'id_barang' => 'BRG011',
                'nama_barang' => 'Mousepad Gaming XL',
                'deskripsi' => 'Alas mouse ukuran 80x30cm dengan lapisan kain halus dan dasar karet.',
                'harga' => 65000.00,
                'stok' => 20,
                'gambar' => 'mousepad.svg',
            ],
            [
                'id_barang' => 'BRG012',
                'nama_barang' => 'USB Hub 4 Port Type-C',
                'deskripsi' => 'Hub multifungsi 4 in 1 untuk konektivitas berbagai perangkat USB.',
                'harga' => 95000.00,
                'stok' => 14,
                'gambar' => 'usbhub.svg',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['id_barang' => $product['id_barang']],
                $product
            );
        }
    }
}
