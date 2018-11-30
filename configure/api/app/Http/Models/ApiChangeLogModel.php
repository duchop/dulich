<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ApiChangeLogModel extends Model
{

    const CREATED_AT = 'LOG_DTIME';

    const UPDATED_AT = 'OPERATION_DTIME';

    protected $table = 'API_CHANGE_LOG';

    protected $primaryKey = 'API_CHANGE_LOG_ID';
}
