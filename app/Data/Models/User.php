<?php

namespace App\Data\Models;

use App\Data\Models\Approval;
use App\Data\Models\Like;
use App\Data\Models\Proposal;
use App\Data\Models\Role;
use App\Support\Constants;
use Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\PasswordReset;
use OwenIt\Auditing\Auditable as AuditableI;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use AuditableI, SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'uf',
        'role_id',
        'cpf',
        'uuid',
        'last_login_at',
        'city_id',
        'whatsapp',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'last_login_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'social' => 'array',
    ];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['whatsapp_formatted'];

    /* EM TESTE DE USO - NÃO MEXER     não está usando esse código, porém no 'User/Show' há uma menção $user->proposals_count
     User has many Proposals*/
    /*public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }*/

    /* EM TESTE DE USO - NÃO MEXER    ESTÁ SENDO USADA! - 'APOIAR UMA IDEIA'/'APOIAR PROPOSTA *************
     User approvals Proposals */
    public function approvals()
    {
        return $this->belongsToMany(Proposal::class, 'approvals', 'user_id', 'proposal_id');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    /*EM TESTE DE USO - NÃO MEXER    não está usando esse código, porém no 'User/Show' há uma menção $user->likes_count
     User likes Proposals*/
    /*public function likes()
    {
        return $this->belongsToMany(Proposal::class, 'likes', 'user_id', 'proposal_id');
    }*/

    /* EM TESTE DE USO - NÃO MEXER     Não está sendo usado!
     User has only one State*/
    /* public function states()
     {
         return $this->hasOne(State::class);
     }*/

    /*EM TESTE DE USO - NÃO MEXER    Não está sendo usado!
     User has one Role*/
    /*public function roles()
    {
        return $this->hasOne(Role::class, 'id');
    }*/

    // Get is_admin attribute
    public function getIsAdminAttribute()
    {
        return Auth::user()->role_id == get_role_id(Constants::ROLE_ADMIN) or
            Auth::user()->role_id == get_role_id(Constants::ROLE_APPROVAL) or
            Auth::user()->role_id == get_role_id(Constants::ROLE_COMMISSION);
    }

    // Get is_super_user attribute
    public function getIsSuperUserAttribute()
    {
        return Auth::user()->role_id == get_role_id(Constants::ROLE_ADMIN);
    }

    // Get is_committee_user attribute
    public function getIsCommitteeUserAttribute()
    {
        return Auth::user()->role_id == get_role_id(Constants::ROLE_ADMIN) or
            Auth::user()->role_id == get_role_id(Constants::ROLE_COMMISSION);
    }

    // Get Role Name
    public function getRoleNameAttribute()
    {
        return Role::find($this->role_id)->role;
    }

    // Get Proposals Count
    public function getProposalsCountAttribute()
    {
        return Proposal::where('user_id', $this->id)->count();
    }

    // Get Likes Count
    public function getLikesCountAttribute()
    {
        return Like::where('user_id', $this->id)->count();
    }

    // Get Approvals Count
    public function getApprovalsCountAttribute()
    {
        return Approval::where('user_id', $this->id)->count();
    }

    // Get Responded Count
    public function getProposalsRespondedCountAttribute()
    {
        return Proposal::where('responder_id', $this->id)->count();
    }

    public function getAvatarAttribute()
    {
        return 'https://www.gravatar.com/avatar/' .
            md5(strtolower(trim($this->email))) .
            '?d=identicon' .
            '&s=80';
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M y', strtotime($value));
    }

    public function getUserByEmail($email)
    {
        return self::Where('email', $email)->first();
    }

    // Socialite
    public function socialNetworks()
    {
        return $this->belongsToMany('App\Data\Models\SocialNetwork', 'social_users', 'user_id');
    }

    public function socialUser()
    {
        return $this->hasMany('App\Data\Models\SocialUser');
    }

    // Return users of system
    public function getUsersCommon()
    {
        return $this->where('role_id', get_role_id(Constants::ROLE_CIDADAO))->get();
    }

    // Return users Adm, Approvers and Commission
    public function getUsersAdm()
    {
        return $this->whereIn('role_id', [
            get_role_id(Constants::ROLE_ADMIN),
            get_role_id(Constants::ROLE_APPROVAL),
            get_role_id(Constants::ROLE_COMMISSION),
        ])->get();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public function getWhatsappFormattedAttribute()
    {
        return strlen($this->whatsapp) == 11
            ? vsprintf('(%s%s)%s%s%s%s%s-%s%s%s%s', str_split($this->whatsapp))
            : $this->whatsapp;
    }
}
