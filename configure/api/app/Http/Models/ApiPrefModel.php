<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ApiPrefModel extends Model
{

    protected $table = 'API_PREF';

    protected $primaryKey = 'PREF_CODE';

    public $incrementing = false;
}
