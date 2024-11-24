<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbDatabencana
 *
 * @property int $id
 * @property int $id_kotakab
 * @property int $id_kecamatan
 * @property int $id_jenisbencana
 * @property string $tahun
 * @property int $frekuensi_kejadian
 * @property int $total_kerusakan
 * @property int $total_korban
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property TbJenisbencana $tb_jenisbencana
 * @property TbKecamatan $tb_kecamatan
 * @property TbKotakab $tb_kotakab
 *
 * @package App\Models
 */
class TbDatabencana extends Model
{
	protected $table = 'tb_databencana';

	protected $casts = [
		'id_kotakab' => 'int',
		'id_kecamatan' => 'int',
		'id_jenisbencana' => 'int',
		'frekuensi_kejadian' => 'int',
		'total_kerusakan' => 'int',
		'total_korban' => 'int'
	];

	protected $fillable = [
		'id_kotakab',
		'id_kecamatan',
		'id_jenisbencana',
		'tahun',
		'frekuensi_kejadian',
		'total_kerusakan',
		'total_korban'
	];

	public function tb_jenisbencana()
	{
		return $this->belongsTo(TbJenisbencana::class, 'id_jenisbencana');
	}

	public function tb_kecamatan()
	{
		return $this->belongsTo(TbKecamatan::class, 'id_kecamatan');
	}

	public function tb_kotakab()
	{
		return $this->belongsTo(TbKotakab::class, 'id_kotakab');
	}
}
