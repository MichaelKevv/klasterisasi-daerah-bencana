<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbJenisbencana
 * 
 * @property int $id
 * @property string $nama_bencana
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|TbDatabencana[] $tb_databencanas
 *
 * @package App\Models
 */
class TbJenisbencana extends Model
{
	protected $table = 'tb_jenisbencana';

	protected $fillable = [
		'nama_bencana'
	];

	public function tb_databencanas()
	{
		return $this->hasMany(TbDatabencana::class, 'id_jenisbencana');
	}
}
