<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/27/2018
 * Time: 11:32 PM
 */

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Packages;
use App\Helpers\LoginHelper;
use App\Helpers\StringHelper;
use App\User;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function InsertPackage(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if(isset($dataContentArr['_id'])) {
            $id = $dataContentArr['_id'];
            $dataContentArr['updatedAt'] = time()*1000;
            unset($dataContentArr['_id']);
            Packages::where('_id', $id)->update($dataContentArr);
        } else {
            $dataContentArr['createdAt'] = time()*1000;
            Packages::insert($dataContentArr);
        }
        return json_encode(['success' => true]);
    }

    public function GetPackage(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $limit = isset($dataContentArr['limit']) ? (int)$dataContentArr['limit'] : 100;
        $page = isset($dataContentArr['page']) ? (int)$dataContentArr['page'] : 1;
        $offset = ($page - 1)*$limit;
        $count = Packages::orderBy('_id', 'DESC')->count();
        if(isset($dataContentArr['_id'])) {
            $ListUser = Packages::where('_id', $dataContentArr['_id'])->offset($offset)->limit($limit)->get();
        } else {
            $ListUser = Packages::orderBy('_id', 'DESC')->offset($offset)->limit($limit)->get();
        }
        return json_encode(['success' => true, 'total' => $count, 'data' => $ListUser]);
    }

    public function DeletePackage(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if($dataContentArr['_id']) {
            Packages::where('_id', $dataContentArr['_id'])->delete();
        }
        return json_encode(['success' => true]);
    }
}