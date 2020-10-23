<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/27/2018
 * Time: 11:32 PM
 */

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\CloneFacebook;
use App\Models\LogActionFacebook;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index(Request $request){
        return redirect()->route('admin.login');
    }


}
