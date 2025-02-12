<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Task extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['title', 'description', 'created_by'];

    // Связь "Task принадлежит пользователю" (создатель задачи)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Связь "Task принадлежит многим пользователям" (пользователи с доступом к задаче)
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }
}
