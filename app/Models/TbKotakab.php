<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbKotakab
 * 
 * @property int $id
 * @property string $nama_kotakab
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|TbDatabencana[] $tb_databencanas
 * @property Collection|TbKecamatan[] $tb_kecamatans
 *
 * @package App\Models
 */
class TbKotakab extends Model
{
	protected $table = 'tb_kotakab';

	protected $fillable = [
		'nama_kotakab'
	];

	public function tb_databencanas()
	{
		return $this->hasMany(TbDatabencana::class, 'id_kotakab');
	}

	public function tb_kecamatans()
	{
		return $this->hasMany(TbKecamatan::class, 'id_kotakab');
	}
}
