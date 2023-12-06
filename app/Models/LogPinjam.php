<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogPinjam extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'log_pinjams';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const JENIS_SELECT = [
        'diajukan'  => 'Diajukan',
        'diproses' => 'diproses',
        'ditolak'   => 'Ditolak',
        'selesai'   => 'Selesai',
    ];

    protected $fillable = [
        'peminjaman_id',
        'kendaraan_id',
        'peminjam_id',
        'jenis',
        'log',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function peminjaman()
    {
        return $this->belongsTo(Pinjam::class, 'peminjaman_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }
}
