<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ApiConfUrlModel extends Model
{

    const CREATED_AT = 'REGIST_DTIME';

    const UPDATED_AT = 'UPDATE_DTIME';

    protected $table = 'API_CONF_URL';

    protected $primaryKey = 'API_CONF_ID';

    public $incrementing = false;
}
