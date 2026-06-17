<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'igrpwt.tbmaster_user'; // <- pakai nama tabel kamu

    protected $primaryKey = 'userid'; // <- jika primary key kamu bukan 'id'

    public $timestamps = false; // <- jika tabel kamu tidak punya created_at/updated_at

    protected $fillable = ['userid', 'userpassword']; // sesuaikan dengan kolom yang ada

    protected $hidden = ['userpassword']; // opsional

    public function getAuthPassword()
    {
        return $this->userpassword;
    }
}
