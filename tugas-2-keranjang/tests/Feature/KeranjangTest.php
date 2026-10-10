<?php

namespace Tests\Feature;

use App\Models\Barang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeranjangTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_halaman_daftar_barang_bisa_diakses(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Toko Alat Tulis');
        $response->assertSee('Buku Tulis');
        $response->assertSee('[ Keranjang (0) ]');
    }

    public function test_tambah_barang_ke_keranjang(): void
    {
        $barang = Barang::first();

        $response = $this->post(route('keranjang.tambah', $barang->id));
        $response->assertRedirect(route('daftar_barang'));
        $response->assertPlainCookie('keranjang', json_encode([(string) $barang->id => 1]));
    }

    public function test_tambah_barang_yang_sama_menambah_jumlah(): void
    {
        $barang = Barang::first();
        $initialCookie = [(string) $barang->id => 1];

        $response = $this->withUnencryptedCookie('keranjang', json_encode($initialCookie))
            ->post(route('keranjang.tambah', $barang->id));

        $response->assertRedirect(route('daftar_barang'));
        $response->assertPlainCookie('keranjang', json_encode([(string) $barang->id => 2]));
    }

    public function test_halaman_keranjang_menampilkan_rincian_dan_total(): void
    {
        $barang1 = Barang::find(1);
        $barang2 = Barang::find(2);

        $cookie = [
            (string) $barang1->id => 2,
            (string) $barang2->id => 1,
        ];

        $expectedTotal = ($barang1->harga * 2) + ($barang2->harga * 1);

        $response = $this->withUnencryptedCookie('keranjang', json_encode($cookie))->get(route('keranjang'));
        $response->assertStatus(200);
        $response->assertSee($barang1->nama);
        $response->assertSee($barang2->nama);
        $response->assertSee('Rp ' . number_format($expectedTotal, 0, ',', '.'));
    }

    public function test_kurang_qty_sampai_nol_menghapus_item(): void
    {
        $barang = Barang::find(1);
        $cookie = [(string) $barang->id => 1];

        $response = $this->withUnencryptedCookie('keranjang', json_encode($cookie))
            ->post(route('keranjang.kurang', $barang->id));

        $response->assertRedirect(route('keranjang'));
        $response->assertPlainCookie('keranjang', json_encode([]));
    }

    public function test_hapus_satu_item(): void
    {
        $barang1 = Barang::find(1);
        $barang2 = Barang::find(2);
        $cookie = [
            (string) $barang1->id => 2,
            (string) $barang2->id => 1,
        ];

        $response = $this->withUnencryptedCookie('keranjang', json_encode($cookie))
            ->post(route('keranjang.hapus', $barang1->id));

        $response->assertRedirect(route('keranjang'));
        $response->assertPlainCookie('keranjang', json_encode([(string) $barang2->id => 1]));
    }

    public function test_kosongkan_keranjang(): void
    {
        $cookie = ['1' => 2, '2' => 1];

        $response = $this->withUnencryptedCookie('keranjang', json_encode($cookie))
            ->post(route('keranjang.kosongkan'));

        $response->assertRedirect(route('keranjang'));
        $response->assertCookieExpired('keranjang');
    }

    public function test_manipulasi_cookie_999_langsung_terbaca_server(): void
    {
        $barang = Barang::find(1);
        $manipulatedCookie = [(string) $barang->id => 999];
        $expectedTotal = $barang->harga * 999;

        $response = $this->withUnencryptedCookie('keranjang', json_encode($manipulatedCookie))
            ->get(route('keranjang'));

        $response->assertStatus(200);
        $response->assertSee('999');
        $response->assertSee('Rp ' . number_format($expectedTotal, 0, ',', '.'));
    }
}
