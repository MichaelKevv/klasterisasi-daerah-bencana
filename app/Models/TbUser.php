<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class TbUser
 *
 * @property int $id
 * @property string $nama_user
 * @property string $email
 * @property string $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TbUser extends Authenticatable
{
	protected $table = 'tb_user';

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'nama_user',
		'email',
		'password'
	];
}
