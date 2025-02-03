<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbSilhouetteScore
 * 
 * @property int $id
 * @property float $avg_silhouette_score
 * @property string $tahun
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TbSilhouetteScore extends Model
{
	protected $table = 'tb_silhouette_score';

	protected $casts = [
		'avg_silhouette_score' => 'float'
	];

	protected $fillable = [
		'avg_silhouette_score',
		'tahun'
	];
}
