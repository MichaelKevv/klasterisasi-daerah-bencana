<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbClustering
 *
 * @property int $id
 * @property int $id_kotakab
 * @property int $id_kecamatan
 * @property int $frekuensi_kejadian
 * @property int $total_kerusakan
 * @property int $luas_terdampak
 * @property int $total_korban
 * @property string $cluster
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $data_hash
 *
 * @property TbKecamatan $tb_kecamatan
 * @property TbKotakab $tb_kotakab
 *
 * @package App\Models
 */
class TbClustering extends Model
{
	protected $table = 'tb_clustering';

	protected $casts = [
		'id_kotakab' => 'int',
		'id_kecamatan' => 'int',
		'frekuensi_kejadian' => 'int',
		'total_kerusakan' => 'int',
		'luas_terdampak' => 'double',
		'total_korban' => 'int'
	];

	protected $fillable = [
		'id_kotakab',
		'id_kecamatan',
		'frekuensi_kejadian',
		'total_kerusakan',
		'luas_terdampak',
		'total_korban',
		'cluster',
		'data_hash'
	];

	public function tb_kecamatan()
	{
		return $this->belongsTo(TbKecamatan::class, 'id_kecamatan');
	}

	public function tb_kotakab()
	{
		return $this->belongsTo(TbKotakab::class, 'id_kotakab');
	}
}
