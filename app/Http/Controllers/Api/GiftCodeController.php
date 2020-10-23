<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/27/2018
 * Time: 11:32 PM
 */

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GiftCode;
use App\Models\GiftCodeUsed;
use App\Models\Setting;
use App\Helpers\StringHelper;
use Illuminate\Http\Request;

class GiftCodeController extends Controller
{
    public function InsertGiftCode(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $dataCode = [];
        if($dataContentArr['value'] && $dataContentArr['number']) {
            for ($i = 0; $i < $dataContentArr['number']; $i++) {
                $cd = StringHelper::generateCode();
                $dataSave = [
                    'code' => $cd,
                    'value' => $dataContentArr['value'],
                    'status' => 'Active',
                    'createdAt' => time()*1000
                ];
                GiftCode::insert($dataSave);
                $dataCode[] = $cd;
            }
        }
        return json_encode(['success' => true, 'code' => $dataCode]);
    }

    public function applyGiftCode(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if($dataContentArr['code'] && $dataContentArr['token']) {
            $checkGiftCode = GiftCode::where('code', $dataContentArr['code'])->where('status', 'Active')->first();
            if(!empty($checkGiftCode)) {
                GiftCode::where('_id', $checkGiftCode['_id'])->update(['status' => 'Used', 'updatedAt' => time()*1000]);
                $checkUser = User::where('token', $dataContentArr['token'])->first();
                if(!empty($checkUser)) {
                    $dataUse = [
                        'username' => $checkUser['username'],
                        'fullname' => $checkUser['fullname'],
                        'code' => $checkGiftCode['code'],
                        'value' => $checkGiftCode['value'],
                        'createdAt' => time()*1000
                    ];
                    $balance = $checkUser['balance'] + $checkGiftCode['value'];
                    GiftCodeUsed::insert($dataUse);
                    User::where('_id', $checkUser['_id'])->update(['balance' => $balance]);
                    return json_encode(['success' => true, 'message' => 'Your money is added '.number_format($balance).' VNĐ', 'balance' => $balance]);
                } else {
                    return json_encode(['success' => false, 'message' => 'Token false']);
                }
            }
        }
    }

    public function GetGiftCode(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $limit = isset($dataContentArr['limit']) ? (int)$dataContentArr['limit'] : 100;
        $page = isset($dataContentArr['page']) ? (int)$dataContentArr['page'] : 1;
        $offset = ($page - 1)*$limit;
        $count = GiftCode::orderBy('_id', 'DESC')->count();
        $ListUser = GiftCode::orderBy('_id', 'DESC')->offset($offset)->limit($limit)->get();
        return json_encode(['success' => true, 'total' => $count, 'data' => $ListUser]);
    }

    public function GetGiftCodeUsed(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $limit = $dataContentArr['limit'] ? (int)$dataContentArr['limit'] : 100;
        $page = $dataContentArr['page'] ? (int)$dataContentArr['page'] : 1;
        $offset = ($page - 1)*$limit;
        $count = GiftCodeUsed::orderBy('_id', 'DESC')->count();
        $ListUser = GiftCodeUsed::orderBy('_id', 'DESC')->offset($offset)->limit($limit)->get();
        return json_encode(['success' => true, 'total' => $count, 'data' => $ListUser]);
    }

    public function DeleteGiftCode(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if($dataContentArr['_id']) {
            GiftCode::where('_id', $dataContentArr['_id'])->delete();
        }
        return json_encode(['success' => true]);
    }

}