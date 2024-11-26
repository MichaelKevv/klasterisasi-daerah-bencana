<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbLogIterasi
 * 
 * @property int $id
 * @property int $tahun
 * @property int $iteration
 * @property string|null $cluster_label
 * @property int|null $id_kotakab
 * @property int|null $id_kecamatan
 * @property float|null $centroid_frekuensi
 * @property float|null $centroid_kerusakan
 * @property float|null $centroid_korban
 * @property int|null $frekuensi_kejadian
 * @property int|null $total_kerusakan
 * @property int|null $total_korban
 * @property float $c1
 * @property float $c2
 * @property float $c3
 * @property int|null $member_count
 * @property float|null $euclidean_distance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $id_data
 * @property string $type
 * @property string|null $terdekat
 *
 * @package App\Models
 */
class TbLogIterasi extends Model
{
	protected $table = 'tb_log_iterasi';

	protected $casts = [
		'tahun' => 'int',
		'iteration' => 'int',
		'id_kotakab' => 'int',
		'id_kecamatan' => 'int',
		'centroid_frekuensi' => 'float',
		'centroid_kerusakan' => 'float',
		'centroid_korban' => 'float',
		'frekuensi_kejadian' => 'int',
		'total_kerusakan' => 'int',
		'total_korban' => 'int',
		'c1' => 'float',
		'c2' => 'float',
		'c3' => 'float',
		'member_count' => 'int',
		'euclidean_distance' => 'float',
		'id_data' => 'int'
	];

	protected $fillable = [
		'tahun',
		'iteration',
		'cluster_label',
		'id_kotakab',
		'id_kecamatan',
		'centroid_frekuensi',
		'centroid_kerusakan',
		'centroid_korban',
		'frekuensi_kejadian',
		'total_kerusakan',
		'total_korban',
		'c1',
		'c2',
		'c3',
		'member_count',
		'euclidean_distance',
		'id_data',
		'type',
		'terdekat'
	];
}
