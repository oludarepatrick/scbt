<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'class',
        'class_division',
        'email',
        'password',
        'visible_password',
        'category',
        'phone',
        'status',
        'term',
        'session',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    private $limit=10;

    

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
   

    public function storeUser($data){
        $data['visible_password'] = $data['password'];
        $data['password'] = bcrypt($data['password']);
        $data['is_admin'] =0;
        return User::create($data);
    }

    public function allUsers(){
        return User::latest()->paginate($this->limit);
    }

    public function findUser($id){
        return User::find($id);
    }

    public function updateUser($data, $id)
    {
        $user = User::findOrFail($id);

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
            $user->visible_password = $data['password'];
        }

        $user->firstname = $data['firstname'] ?? $user->firstname;
        $user->lastname = $data['lastname'] ?? $user->lastname;
        $user->class = $data['class'] ?? $user->class;
        $user->class = $data['class_division'] ?? $user->class;
        $user->email = $data['email'] ?? $user->email;
        $user->phone = $data['phone'] ?? $user->phone;
        $user->category = $data['category'] ?? $user->category;
        $user->is_admin = $data['is_admin'] ?? $user->is_admin;

        $user->save();

        return $user;
    }


    public function deleteUser($id){
        return User::find($id)->delete();
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizUser::class, 'user_id');
    }

}
