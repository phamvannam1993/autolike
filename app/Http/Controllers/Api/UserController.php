<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/27/2018
 * Time: 11:32 PM
 */

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\User;
use App\Models\Setting;
use App\Helpers\StringHelper;
use App\Models\CloneFacebook;
use App\Models\UserHistory;
use App\Models\Transaction;
use App\Models\Admin;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function InsertUser(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        if($userDataArr['user']) {
            $dataUser = $userDataArr['user'];
            if(isset($dataUser['_id'])) {
                $id = $dataUser['_id'];
                if(isset($dataUser['balance'])) {
                    unset($dataUser['balance']);
                }
                if(isset($dataUser['_id'])) {
                    unset($dataUser['_id']);
                }
                User::where('_id', $id)->update($dataUser);
                return json_encode(['success' => true]);
            } else {
                $dataUser['token'] = StringHelper::generateCode(32);
                $dataUser['role'] = 3;
                $dataUser['created_at'] = time()*1000;
                $dataUser['createdAt'] = time()*1000;
                $checkUser = User::where('username', $dataUser['username'])->first();
                if(!empty($checkUser)) {
                    return json_encode(['success' => false, 'message' => 'Username available']);
                } else {
                    User::insert($dataUser);
                }
                return json_encode(['success' => true]);
            }
        } else {
            return json_encode(['success' => false, 'message' => 'Param false']);
        }
    }

    public function SignUp(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        if($userDataArr['user']) {
            $dataUser = $userDataArr['user'];
            $dataUser['token'] = StringHelper::generateCode(32);
            $dataUser['role'] = 3;
            $dataUser['created_at'] = time()*1000;
            $dataUser['createdAt'] = time()*1000;
            $checkUser = User::where('username', $dataUser['username'])->first();
            if(!empty($checkUser)) {
                return json_encode(['success' => false, 'message' => 'Username available']);
            } else {
                User::insert($dataUser);
            }
            return json_encode(['success' => true, 'user' => $dataUser]);
        }
        return json_encode(['success' => false, 'message' => 'server error']);
    }

    public function checkNumber(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        if(isset($userDataArr['username'])) {
            $checkUser = User::where('username', $userDataArr['username'])->get()->toArray();
            if(!empty($checkUser)) {
                return json_encode(['success' => true, 'data' => $checkUser]);
            } else {
                return json_encode(['success' => false, 'message' => 'User not exist']);
            }
        } else {
            return json_encode(['success' => false, 'message' => 'param false']);
        }
    }

    public function getUser(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $limit = isset($dataContentArr['limit']) ? (int)$dataContentArr['limit'] : 100;
        $page = isset($dataContentArr['page']) ? (int)$dataContentArr['page'] : 1;
        $offset = ($page - 1)*$limit;
        if(isset($dataContentArr['token'])) {
            $count = User::orderBy('_id', 'DESC')->where('token', $dataContentArr['token'])->count();
            $ListUser = User::orderBy('_id', 'DESC')->where('token', $dataContentArr['token'])->offset($offset)->limit($limit)->get();
        } else if(isset($dataContentArr['username'])) {
            $count = User::orderBy('_id', 'DESC')->where('username', $dataContentArr['username'])->count();
            $ListUser = User::orderBy('_id', 'DESC')->where('username', $dataContentArr['username'])->offset($offset)->limit($limit)->get();
        } else if(isset($dataContentArr['type']) && $dataContentArr['type'] == 'Congaubeo@123') {
            $count = User::orderBy('_id', 'DESC')->count();
            $ListUser = User::orderBy('_id', 'DESC')->offset($offset)->limit($limit)->get();
        }
        return json_encode(['success' => true, 'total' => $count, 'data' => $ListUser]);
    }

    public function checkToken(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        if($userDataArr['token']) {
            $checkUser = User::where('token', $userDataArr['token'])->get()->toArray();
            if(!empty($checkUser)) {
                return json_encode(['success' => true, 'data' => $checkUser]);
            } else {
                return json_encode(['success' => false, 'message' => 'User not exist']);
            }
        } else {
            return json_encode(['success' => false, 'message' => 'Request false']);
        }
    }

    public function checkLogin(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        $checkUser = User::where('username', $userDataArr['username'])->where('password', $userDataArr['password'])->first();
        if(!empty($checkUser)) {
            return json_encode(['success' => true, 'data' => $checkUser]);
        } else {
            return json_encode(['success' => false, 'message' => 'username or password false']);
        }
    }

    public function LoginAdmin(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        $checkUser = Admin::where('username', $userDataArr['username'])->where('password', $userDataArr['password'])->first();
        if(!empty($checkUser)) {
            return json_encode(['success' => true, 'data' => $checkUser]);
        } else {
            return json_encode(['success' => false, 'message' => 'username or password false']);
        }
    }

    public function DeleteUser(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        if($userDataArr['_id']) {
            User::where('_id', $userDataArr['_id'])->delete();
            return json_encode(['success' => true]);
        } else {
            return json_encode(['success' => false]);
        }
    }

    public function UpdateBalance(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        if($userData['token'] && $userData['balance']) {
            $checkUser = User::where('token', $userData['token'])->first();
            if(!empty($checkUser)) {
                $balance = isset($checkUser['balance']) ? (int)$checkUser['balance'] + (int)$userData['balance'] : $userData['balance'];
                $dataUpdate = [
                    'balance' =>  $balance,
                    'updatedAt' => time()*1000
                ];
                User::where('_id', $checkUser['_id'])->update($dataUpdate);
                return json_encode(['success' => true, 'balance' => $balance, 'username' => $checkUser['username'], 'fullname' => $checkUser['fullname']]);
            } else {
                return json_encode(['success' => false]);
            }
        } else {
            return json_encode(['success' => false]);
        }
    }

    public function InsertSetting(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        if($userDataArr['data']) {
            $checkSetting = Setting::orderBy('_id', 'DESC')->first();
            if(!empty($checkSetting)) {
                if(isset($userDataArr['data']['_id'])) {
                    unset($userDataArr['data']['_id']);
                }
                Setting::where('_id', $checkSetting['_id'])->update($userDataArr['data']);
            } else {
                Setting::insert($userDataArr['data']);
            }

            return json_encode(['success' => true]);
        } else {
            return json_encode(['success' => false]);
        }
    }

    public function  getSetting(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        $checkSetting = Setting::orderBy('_id', 'DESC')->first();
        return json_encode(['success' => true, 'data' => $checkSetting]);
    }

    public function ResetPassword(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        if($userDataArr['username'] && $userDataArr['password']) {
            $checkUser = User::where('username', $userDataArr['username'])->first();
            if(!empty($checkUser)) {
                User::where('_id', $checkUser['_id'])->update(['password' => md5($userDataArr['password'])]);
                return json_encode(['success' => true, 'message' => 'Password change success']);
            } else {
                return json_encode(['success' => false, 'message' => 'Username not exist']);
            }
        } else {
            return json_encode(['success' => false, 'message' => 'Request false']);
        }
    }

    public function checkBalance(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        $checkSetting = Setting::orderBy('_id', 'DESC')->first();
        if(empty($checkSetting)) {
            return json_encode(['success' => false, 'message' => 'setting price error']);
        }
        $userDetail = User::where('token', $userDataArr['token'])->first();
        if(!empty($userDetail)) {
            $dataArr = [
                'success' => true,
                'LikePagePrice' => isset($checkSetting['likepage']) ? $checkSetting['likepage'] : 0,
                'SubFollowPrice' => isset($checkSetting['follow']) ? $checkSetting['follow'] : 0,
                'balance' => $userDetail['balance']
            ];
            return json_encode($dataArr);
        }
        return json_encode(['success' => false, 'message' => 'Request false']);
    }
    
    public function UpdateUser() {

    }

    public function apiGetListService(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        $checkUser = User::where('username', $userDataArr['username'])->first();
        if(!empty($checkUser)) {
            $token = $checkUser['token'];
            $ListTransaction = Transaction::where('token', $token)->where('status', 'Active')->get()->toArray();
            $ListService = Service::where('token', $token)->get()->toArray();
            return json_encode(['ListTransaction' => $ListTransaction, 'ListService' => $ListService]);
        }
    }

    public function apiUpdateService(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        $checkUser = User::where('username', $userDataArr['username'])->first();
        $balance = 0;
        if(!empty($checkUser)) {
            $token = $checkUser['token'];
            $ListTransaction = Transaction::where('token', $token)->where('status', 'Active')->get()->toArray();
            foreach ($ListTransaction as $tran) {
                $balance = $balance + $tran['value'] + $tran['bonus'];
            }
            $ListService = Service::where('token', $token)->get()->toArray();
            foreach ($ListService as $item) {
                if($item['status'] === 'pause') {
                    $balance = $balance + $item['price']*$item['number_success'];
                } else {
                    $balance = $balance + $item['price']*$item['number'];
                }
            }
            User::where('_id', $checkUser['_id'])->update(['balance' => $balance]);
            return json_encode(['success' => true]);
        } else {
            return json_encode(['success' => false]);
        }
    }

    public function HistoryTransaction(Request $request) {
        $userData = $request->getContent();
        $userDataArr = json_decode($userData, TRUE);
        $ListHistory = UserHistory::orderBy('createdAt', 'DESC')->where('token', $userDataArr['token'])->get()->toArray();
        return json_encode(['data' => $ListHistory]);
    }
}