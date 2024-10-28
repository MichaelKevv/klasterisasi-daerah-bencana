<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbKriterium
 *
 * @property int $id
 * @property string $kode_kriteria
 * @property string $nama_kriteria
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TbKriteria extends Model
{
	protected $table = 'tb_kriteria';

	protected $fillable = [
		'kode_kriteria',
		'nama_kriteria'
	];
}
