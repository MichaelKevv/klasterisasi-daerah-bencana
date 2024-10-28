<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbKecamatan
 * 
 * @property int $id
 * @property int $id_kotakab
 * @property string $nama_kecamatan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property TbKotakab $tb_kotakab
 * @property Collection|TbDatabencana[] $tb_databencanas
 *
 * @package App\Models
 */
class TbKecamatan extends Model
{
	protected $table = 'tb_kecamatan';

	protected $casts = [
		'id_kotakab' => 'int'
	];

	protected $fillable = [
		'id_kotakab',
		'nama_kecamatan'
	];

	public function tb_kotakab()
	{
		return $this->belongsTo(TbKotakab::class, 'id_kotakab');
	}

	public function tb_databencanas()
	{
		return $this->hasMany(TbDatabencana::class, 'id_kecamatan');
	}
}
