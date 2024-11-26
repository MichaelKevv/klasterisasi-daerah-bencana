<?php

namespace App\Imports;

use App\Models\TbDatabencana;
use App\Models\TbKotakab;
use App\Models\TbKecamatan;
use App\Models\TbJenisbencana;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;

class BencanaImport implements ToModel, WithHeadingRow
{
    private $errors = [];

    public function model(array $row)
    {
        DB::beginTransaction(); // Mulai transaksi

        try {
            // Mendapatkan ID berdasarkan nama yang ada di tabel referensi
            $id_kotakab = TbKotakab::whereRaw('LOWER(nama_kotakab) = ?', [Str::lower($row['kabupaten_kota'])])->value('id');

            // Validasi apakah data Kabupaten/Kota ditemukan
            if (!$id_kotakab) {
                throw new Exception("Data Kabupaten/Kota '{$row['kabupaten_kota']}' tidak ditemukan.");
            }

            // Mendapatkan ID Kecamatan berdasarkan nama kecamatan dan id kabupaten/kota
            $id_kecamatan = TbKecamatan::whereRaw('LOWER(nama_kecamatan) = ? AND id_kotakab = ?', [Str::lower($row['kecamatan']), $id_kotakab])->value('id');

            // Validasi apakah data Kecamatan ditemukan berdasarkan nama kecamatan dan kabupaten/kota
            if (!$id_kecamatan) {
                throw new Exception("Data Kecamatan '{$row['kecamatan']}' tidak ditemukan di Kabupaten/Kota '{$row['kabupaten_kota']}'.");
            }

            // Mendapatkan ID Jenis Bencana
            $id_jenisbencana = TbJenisbencana::whereRaw('LOWER(nama_bencana) = ?', [Str::lower($row['jenis_bencana'])])->value('id');

            // Validasi apakah data Jenis Bencana ditemukan
            if (!$id_jenisbencana) {
                throw new Exception("Data Jenis Bencana '{$row['jenis_bencana']}' tidak ditemukan.");
            }

            // Validasi nilai lainnya (khusus untuk kolom yang tidak boleh null)
            if (empty($row['tahun'])) {
                throw new Exception("Kolom 'tahun' tidak boleh kosong untuk data: " . json_encode($row));
            }
            if (!is_numeric($row['frekuensi_kejadian']) || $row['frekuensi_kejadian'] <= 0) {
                throw new Exception("Kolom 'frekuensi_kejadian' harus berupa angka positif untuk data: " . json_encode($row));
            }
            if (!is_numeric($row['total_kerusakan']) || $row['total_kerusakan'] < 0) {
                throw new Exception("Kolom 'total_kerusakan' harus berupa angka positif untuk data: " . json_encode($row));
            }
            if (!is_numeric($row['total_korban']) || $row['total_korban'] < 0) {
                throw new Exception("Kolom 'total_korban' harus berupa angka positif untuk data: " . json_encode($row));
            }

            // Jika semua validasi lolos, buat data baru
            $data = new TbDatabencana([
                'id_kotakab' => $id_kotakab,
                'id_kecamatan' => $id_kecamatan,
                'id_jenisbencana' => $id_jenisbencana,
                'tahun' => $row['tahun'],
                'frekuensi_kejadian' => $row['frekuensi_kejadian'],
                'total_kerusakan' => $row['total_kerusakan'],
                'total_korban' => $row['total_korban'],
            ]);

            DB::commit(); // Simpan transaksi jika tidak ada kesalahan
            return $data;
        } catch (Exception $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan
            $this->errors[] = $e->getMessage(); // Tambahkan pesan error ke daftar
            throw $e; // Lempar ulang exception untuk menghentikan proses import
        }
    }

    /**
     * Mengembalikan daftar error yang ditemukan selama proses import.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
