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
use App\Models\User;
use App\Helpers\LoginHelper;
use App\Helpers\StringHelper;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function InsertTransaction(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if(isset($dataContentArr['package_id']) && isset($dataContentArr['token'])) {
            $checkPackage = Packages::where('_id', $dataContentArr['package_id'])->first();
            if(empty($checkPackage)) {
                return json_encode(['success' => false, 'message' => 'package not found']);
            }
            $checkUser = User::where('token', $dataContentArr['token'])->first();
            if(empty($checkUser)) {
                return json_encode(['success' => false, 'message' => 'User not exist']);
            }
            $dataTrasaction = [
                'code' => 'ALIKE'.StringHelper::generateCode(6),
                'username' => $checkUser['username'],
                'fullname' => $checkUser['fullname'],
                'value' => $checkPackage['money'],
                'token' => $dataContentArr['token'],
                'date' => date('d-m-Y'),
                'cron_check' => 1,
                'status' => 'Pending',
                'bonus' => $checkPackage['money']*$checkPackage['bonus']/100,
                'createdAt' => time()*1000,
            ];
            $result = Transaction::create($dataTrasaction);
            return json_encode(['success' => true, 'id' => $result['_id']]);
        } else if(isset($dataContentArr['_id'])){
            $checkTransaction = Transaction::where('_id', $dataContentArr['_id'])->where('status', 'Pending')->first();
            if(!empty($checkTransaction)) {
                $checkUser = User::where('token', $checkTransaction['token'])->first();
                Transaction::where('_id', $checkTransaction['_id'])->update(['status' => 'Active']);
                if(!empty($checkUser)) {
                    $balance = isset($checkUser['balance']) ? $checkUser['balance'] + $checkTransaction['value'] + $checkTransaction['bonus'] : $checkTransaction['value'] + $checkTransaction['bonus'];
                    User::where('_id', $checkUser['_id'])->update(['balance' => $balance]);
                    return json_encode(['success' => true]);
                } else {
                    return json_encode(['success' => false, 'message' => 'User not exist']);
                }
            } else {
                return json_encode(['success' => false, 'message' => 'Transaction not exist']);
            }
        }
    }

    public function GetTransaction(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $limit = isset($dataContentArr['limit']) ? (int)$dataContentArr['limit'] : 100;
        $page = isset($dataContentArr['page']) ? (int)$dataContentArr['page'] : 1;
        $offset = ($page - 1)*$limit;
        if(isset($dataContentArr['transaction_id'])) {
            $count = Transaction::where('_id', $dataContentArr['transaction_id'])->count();
            $ListUser = Transaction::where('_id', $dataContentArr['transaction_id'])->get()->toArray();
        } else if(isset($dataContentArr['user_id'])) {
            $count = Transaction::orderBy('_id', 'DESC')->where('token', $dataContentArr['user_id'])->count();
            $ListUser = Transaction::orderBy('_id', 'DESC')->where('token', $dataContentArr['user_id'])->offset($offset)->limit($limit)->get();
        } else {
            $count = Transaction::orderBy('_id', 'DESC')->count();
            $ListUser = Transaction::orderBy('_id', 'DESC')->offset($offset)->limit($limit)->get();
        }
        return json_encode(['success' => true, 'total' => $count, 'data' => $ListUser]);
    }

    public function DeleteTransaction(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if(!empty($dataContentArr['_id'])) {
            Transaction::where('_id', $dataContentArr['_id'])->delete();
        }
        return json_encode(['success' => true]);
    }

    public function UpdateTransaction(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if(!empty($dataContentArr['code']) && !empty($dataContentArr['amount'])) {
            $checkTransaction = Transaction::where('code', $dataContentArr['code'])->where('status', 'Pending')->first();
            if(!empty($checkTransaction)) {
                if($checkTransaction['value'] != $dataContentArr['amount']) {
                    return json_encode(['success' => false, 'message' => 'Request error']);
                }
                $checkUser = User::where('token', $checkTransaction['token'])->first();
                Transaction::where('_id', $checkTransaction['_id'])->update(['status' => 'Active']);
                if(!empty($checkUser)) {
                    $balance = isset($checkUser['balance']) ? $checkUser['balance'] + $checkTransaction['value'] + $checkTransaction['bonus'] : $checkTransaction['value'] + $checkTransaction['bonus'];
                    User::where('_id', $checkUser['_id'])->update(['balance' => $balance]);
                    return json_encode(['success' => true]);
                } else {
                    return json_encode(['success' => false, 'message' => 'User not exist']);
                }
            } else {
                return json_encode(['success' => false, 'message' => 'Transaction not exist']);
            }
        } else {
            return json_encode(['success' => false, 'message' => 'Request false']);
        }
    }
}