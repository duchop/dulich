<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ApiIndivisualUserInfoModel extends Model
{

    const CREATED_AT = 'REGIST_DTIME';

    const UPDATED_AT = 'UPDATE_DTIME';

    protected $table = 'API_INDIVISUAL_USER_INFO';

    protected $primaryKey = 'API_USER_ID';
}
