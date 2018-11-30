<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUserUnsubscribeModel extends Model
{

    protected $table = 'API_USER_UNSUBSCRIBE_LOG';

    protected $primaryKey = 'UNSUB_ID';

    public $timestamps = false;
}
