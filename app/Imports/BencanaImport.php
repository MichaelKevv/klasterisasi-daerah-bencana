<?php

namespace App\Imports;

use App\Models\TbDatabencana;
use App\Models\TbKotakab;
use App\Models\TbKecamatan;
use App\Models\TbJenisbencana;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class BencanaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Mendapatkan ID berdasarkan nama yang ada di tabel referensi
        $id_kotakab = TbKotakab::whereRaw('LOWER(nama_kotakab) = ?', [Str::lower($row['kabupaten_kota'])])->value('id');
        $id_kecamatan = TbKecamatan::whereRaw('LOWER(nama_kecamatan) = ?', [Str::lower($row['kecamatan'])])->value('id');
        $id_jenisbencana = TbJenisbencana::whereRaw('LOWER(nama_bencana) = ?', [Str::lower($row['jenis_bencana'])])->value('id');
// var_dump($id_kecamatan);
        return new TbDatabencana([
            'id_kotakab' => $id_kotakab,
            'id_kecamatan' => $id_kecamatan,
            'id_jenisbencana' => $id_jenisbencana,
            'tahun' => $row['tahun'],
            'frekuensi_kejadian' => $row['frekuensi_kejadian'],
            'total_kerusakan' => $row['total_kerusakan'],
            'luas_terdampak' => $row['luas_daerah'],
            'total_korban' => $row['total_korban'],
        ]);
    }
}
