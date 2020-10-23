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
use App\Models\Services;
use App\Models\ServiceLog;
use App\Models\Setting;
use App\Models\Report;
use App\Models\Viplikes;
use App\Models\ReportCheck;
use App\Models\Comment;
use App\Models\CommentContent;
use App\Models\Transaction;
use App\Models\ViplikeActions;
use App\Helpers\StringHelper;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function getServiceLog(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $limit = $dataContentArr['limit'] ? (int)$dataContentArr['limit'] : 100;
        $page = $dataContentArr['page'] ? (int)$dataContentArr['page'] : 1;
        $offset = ($page - 1) * $limit;
        if(!isset($dataContentArr['time'])) {
            $count = ServiceLog::where('service_code', $dataContentArr['service_code'])->count();
            $ListServiceLog = ServiceLog::orderBy('_id', 'DESC')->where('service_code', $dataContentArr['service_code'])->offset($offset)->limit($limit)->get();
        } else {
            $count = Services::where('service_code', $dataContentArr['service_code'])->count();
            $ListServiceLog = Services::orderBy('_id', 'ASC')->where('service_code', $dataContentArr['service_code'])->offset($offset)->limit($limit)->get();
        }

        return json_encode(['success' => true, 'total' => $count, 'data' => $ListServiceLog]);
    }

    public function InsertService(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if ($dataContentArr['service']) {
            $dataService = $dataContentArr['service'];
            $checkToken = User::where('token', $dataService['token'])->first();
            if (!empty($checkToken)) {
                $setting = Setting::orderBy('_id', 'ASC')->first();
                if (empty($setting)) {
                    return json_encode(['success' => false, 'status' => 2]);
                }
                $price = 0;
                if ($dataService['type'] == 'follow') {
                    $price = $setting['follow'];
                } else if ($dataService['type'] == 'likepage') {
                    $price = $setting['likepage'];
                } else if ($dataService['type'] == 'bufflike') {
                    $price = $setting['bufflike'];
                } else if ($dataService['type'] == 'buffcomment') {
                    $price = $setting['buffcomment'];
                } else if ($dataService['type'] == 'viplikeService') {
                    $price = $setting['viplike'];
                } else if ($dataService['type'] == 'vipcommentService') {
                    $price = $setting['vipcomment'];
                }
                if ($checkToken['balance'] < $price * $dataService['number']) {
                    return json_encode(['success' => false, 'status' => 3]);
                }
                $serviceId = StringHelper::generateCode(6);
                $balance = $checkToken['balance'] - $price * $dataService['number'];
                $dataService['service_code'] = StringHelper::generateCode(6);
                $dataService['status'] = 'Active';
                $dataService['number'] = (int)$dataService['number'];
                $dataService['price'] = $price;
                $dataService['username'] = $checkToken['username'];
                $dataService['fullname'] = $checkToken['fullname'];
                $dataService['createdAt'] = date('Y-m-d H:i:s');
                $dataService['date'] = date('d-m-Y');
                $dataService['cron_check'] = 1;
                $dataService['serviceId'] = $serviceId;
                $dataService['updated_at'] = time() * 1000;
                $dataService['created_at'] = time() * 1000;
                User::where('_id', $checkToken['_id'])->update(['balance' => $balance]);
                Services::insert($dataService);
                if($dataService['type'] == 'viplikeService' || $dataService['type'] == 'vipcommentService') {
                    $this->insertMultiService($dataService);
                }
                return json_encode(['success' => true, 'balance' => $balance]);
            } else {
                return json_encode(['success' => false, 'status' => 1]);
            }
        }
    }

    function insertMultiService($dataService) {
        $date = date('Y-m-d H:i:s');
        for($i = 1; $i <= 29; $i++) {
            $time = time() * 1000 + $i*24*60*60*1000;
            $dataService['createdAt'] = date('Y-m-d H:i:s', strtotime($date. ' + '.$i.' days'));
            $dataService['date'] = date('Y-m-d', strtotime($date. ' + '.$i.' days'));
            $dataService['updated_at'] = $time;
            $dataService['created_at'] = $time;
            $dataService['service_code'] = StringHelper::generateCode(6);
            Services::insert($dataService);
        }
    }

    function updateUid($service_code) {
        $serviceItem = Services::where('service_code', $service_code)->first();
        if(!empty($serviceItem)) {
            if($serviceItem['type'] === ' follow') {
                $url = 'http://scorpion.esrax.com/?method=CountFollowersV2&object=Api.Facebook&id='.$serviceItem['fanpage_id'];
            } else {
                $url = 'http://scorpion.esrax.com/?method=CountFollowersV2&object=Api.Facebook&id='.$serviceItem['fanpage_id'];
            }
            $uidItem = file_get_contents($url);
            $uidItemArr = json_decode($uidItem, true);
            if(isset($uidItemArr['Data'])) {
                $countBefore = $uidItemArr['Data'];
                Services::where('_id', $serviceItem['_id'])->update(['countBefore' => $countBefore]);
            }
        }
    }

    public function ServiceUpdate(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if(isset($dataContentArr['updateUrl'])) {
            $serviceId = $dataContentArr['_id'];
            unset($dataContentArr['_id']);
            unset($dataContentArr['updateUrl']);
            Services::where('_id', $serviceId)->update($dataContentArr);
            return json_encode(['success' => true]);
        } else if (!isset($dataContentArr['status'])) {
            Services::where('_id', $dataContentArr['_id'])->update(['note' => $dataContentArr['note']]);
            return json_encode(['success' => true]);
        } else if(isset($dataContentArr['status'])){
            $checkToken = Services::where('_id', $dataContentArr['_id'])->where('status', 'Active')->first();
            if(!empty($checkToken)) {
                $date = date('d-m-Y');
                $token = $checkToken['token'];
                $countWatting = Services::where('token', $token)->where('status', 'waitting')->where('date', $date)->count();
                $countPause = Services::where('token', $token)->where('status', 'pause')->where('date', $date)->count();
                if($countWatting + $countPause >= 5 && $token !== 'QN7TCK8VLWNUP99Q2CBT3XAS84BBV2B5' && !isset($dataContentArr['isAdmin'])) {
                    return json_encode(['success' => false, 'message' => 'Bạn chỉ được phép huỷ 5 gói 1 ngày']);
                }
            }
            $checkService = Services::where('_id', $dataContentArr['_id'])->where('status', 'Active')->first();    
            if ($checkService) {
                $service = Services::where('_id', $dataContentArr['_id'])->update(['status' => 'waitting', 'updateTime' => time()*1000]);
                $checkUser = User::where('token', $checkService['token'])->first();
                if (!empty($checkUser)) {
                    return json_encode(['success' => true, 'balance' => $checkUser['balance']]);
                }
            } else {
                return json_encode(['success' => false, 'message' => 'Service not exist']);
            }
        } 
    }

    public function getService(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $limit = $dataContentArr['limit'] ? (int)$dataContentArr['limit'] : 100;
        $page = $dataContentArr['page'] ? (int)$dataContentArr['page'] : 1;
        $offset = ($page - 1) * $limit;
        if(isset($dataContentArr['service_code'])) {
            $count = Services::where('service_code', $dataContentArr['service_code'])->count();
            $ListService = Services::where('service_code', $dataContentArr['service_code'])->offset($offset)->limit($limit)->get();
        } else if (isset($dataContentArr['viplike']) && !empty($dataContentArr['viplike'])) {
            if(isset($dataContentArr['_id'])) {
                Viplikes::where('_id', $dataContentArr['_id'])->delete();
                return json_encode(['success' => true]);
            }
            $count = Viplikes::orderBy('createdAt', 'DESC')->count();
            $ListService = Viplikes::orderBy('createdAt', 'DESC')->offset($offset)->limit($limit)->get();
        } else if (isset($dataContentArr['search']) && !empty($dataContentArr['search'])) {
            if(isset($dataContentArr['token'])) {
                $count = Services::where(function ($query) use ($dataContentArr) {
                    $query->where('type', $dataContentArr['type'])->where('token', $dataContentArr['token'])->where('fanpage_id', 'like', '%' . $dataContentArr['search'] . '%');
                })->orWhere(function($query) use ($dataContentArr) {
                    $query->where('type', $dataContentArr['type'])->where('token', $dataContentArr['token'])->where('service_code', 'like', '%' . $dataContentArr['search'] . '%');
                })->count();
                $ListService = Services::where(function ($query) use ($dataContentArr) {
                    $query->where('type', $dataContentArr['type'])->where('token', $dataContentArr['token'])->where('fanpage_id', 'like', '%' . $dataContentArr['search'] . '%');
                })->orWhere(function($query) use ($dataContentArr) {
                    $query->where('type', $dataContentArr['type'])->where('token', $dataContentArr['token'])->where('service_code', 'like', '%' . $dataContentArr['search'] . '%');
                })->offset($offset)->limit($limit)->get();
            } else {
                $count = Services::where(function ($query) use ($dataContentArr) {
                    $query->where('type', $dataContentArr['type'])->where('fanpage_id', 'like', '%' . $dataContentArr['search'] . '%');
                })->orWhere(function($query) use ($dataContentArr) {
                    $query->where('type', $dataContentArr['type'])->where('service_code', 'like', '%' . $dataContentArr['search'] . '%');
                })->count();
                $ListService = Services::where(function ($query) use ($dataContentArr) {
                    $query->where('type', $dataContentArr['type'])->where('fanpage_id', 'like', '%' . $dataContentArr['search'] . '%');
                })->orWhere(function($query) use ($dataContentArr) {
                    $query->where('type', $dataContentArr['type'])->where('service_code', 'like', '%' . $dataContentArr['search'] . '%');
                })->offset($offset)->limit($limit)->get();
            }

        } else if (isset($dataContentArr['token'])) {
            if(isset($dataContentArr['time'])) {
                $timeEnd = $dataContentArr['time'] + 24*60*60*1000;
                $count = Services::orderBy('_id', 'DESC')->where('token', $dataContentArr['token'])->where('type', $dataContentArr['type'])->where('created_at', '>' , $dataContentArr['time'])->where('created_at', '<' , $timeEnd)->count();
                $ListService = Services::orderBy('_id', 'DESC')->where('token', $dataContentArr['token'])->where('type', $dataContentArr['type'])->where('created_at', '>' , $dataContentArr['time'])->where('created_at', '<' , $timeEnd)->offset($offset)->limit($limit)->get();
            } else {
                $count = Services::orderBy('_id', 'DESC')->where('token', $dataContentArr['token'])->where('type', $dataContentArr['type'])->count();
                $ListService = Services::orderBy('_id', 'DESC')->where('token', $dataContentArr['token'])->where('type', $dataContentArr['type'])->offset($offset)->limit($limit)->get();
            }

        } else {
            if (isset($dataContentArr['action'])) {
                if ($dataContentArr['action'] === 'active') {
                    $count = Services::orderBy('createdAt', 'DESC')->where('status', 'Active')->where('type', $dataContentArr['type'])->count();
                    $ListService = Services::orderBy('createdAt', 'DESC')->where('status', 'Active')->where('type', $dataContentArr['type'])->offset($offset)->limit($limit)->get();
                } else if ($dataContentArr['action'] === 'time') {
                    $count = Services::orderBy('TimeSuccess', 'DESC')->where('status', 'Active')->where('type', $dataContentArr['type'])->count();
                    $ListService = Services::orderBy('TimeSuccess', 'DESC')->where('status', 'Active')->where('type', $dataContentArr['type'])->offset($offset)->limit($limit)->get();
                } else if ($dataContentArr['action'] === 'success') {
                    $count = Services::orderBy('TimeSuccess', 'DESC')->where('status', 'Success')->where('type', $dataContentArr['type'])->count();
                    $ListService = Services::orderBy('TimeSuccess', 'DESC')->where('status', 'Success')->where('type', $dataContentArr['type'])->offset($offset)->limit($limit)->get();
                } else if ($dataContentArr['action'] === 'cancel') {
                    $count = Services::orderBy('createdAt', 'DESC')->where('status', 'pause')->where('type', $dataContentArr['type'])->count();
                    $ListService = Services::orderBy('createdAt', 'DESC')->where('status', 'pause')->where('type', $dataContentArr['type'])->offset($offset)->limit($limit)->get();
                } else {
                    $count = Services::orderBy('createdAt', 'DESC')->where('type', $dataContentArr['type'])->count();
                    $ListService = Services::orderBy('createdAt', 'DESC')->where('type', $dataContentArr['type'])->offset($offset)->limit($limit)->get();
                }
            } else {
                if(isset($dataContentArr['time'])) {
                    $timeEnd = $dataContentArr['time'] + 24*60*60*1000;
                    $count = Services::orderBy('_id', 'DESC')->where('type', $dataContentArr['type'])->where('created_at', '>' , $dataContentArr['time'])->where('created_at', '<' , $timeEnd)->count();
                    $ListService = Services::orderBy('_id', 'DESC')->where('type', $dataContentArr['type'])->where('created_at', '>' , $dataContentArr['time'])->where('created_at', '<' , $timeEnd)->offset($offset)->limit($limit)->get();
                } else {
                    $count = Services::orderBy('_id', 'DESC')->where('type', $dataContentArr['type'])->count();
                    $ListService = Services::orderBy('_id', 'DESC')->where('type', $dataContentArr['type'])->offset($offset)->limit($limit)->get();
                }
            }
        }
        if (!empty($ListService)) {
            for ($i = 0; $i < count($ListService); $i++) {
                if (isset($ListService[$i]['updatedDateTime'])) {
                    if (isset($ListService[$i]['updatedDateTime']) && is_numeric($ListService[$i]['updatedDateTime'])) {
                        $ListService[$i]['updatedDateTime'] = isset($ListService[$i]['updatedDateTime']) ? date('Y-m-d H:i:s', $ListService[$i]['updatedDateTime'] / 1000) : '';
                    }
                }
                $ListService[$i]['TimeSuccess'] = date('Y-m-d H:i:s', (int)$ListService[$i]['TimeSuccess'] / 1000);
            }
        }
        return json_encode(['success' => true, 'total' => $count, 'data' => $ListService]);
    }

    public function ApiService(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $dataService = [];
        $limit = isset($dataContentArr['limit']) ? $dataContentArr['limit'] : 0;
        if ($dataContentArr['limit'] > 0) {
            $serviceList = Services::orderBy('updateDateTime', 'ASC')->where('status', 'Active')->where('type', $dataContentArr['type'])->limit($limit)->get()->toArray();
            foreach ($serviceList as $item) {
                $dataService[] = [
                    'service_code' => $item['service_code'],
                    'uid' => $item['fanpage_id']
                ];
                Services::where('_id', $item['_id'])->update(['updateDateTime' => date('Y-m-d H:i:s')]);
            }
        }
        return json_encode(['data' => $dataService]);
    }

    public function ApiServiceUpdate(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if ($dataContentArr['service_code']) {
            $serviceItem = Services::where('service_code', $dataContentArr['service_code'])->first();
            $numberDeff = $serviceItem['number_deff'] - 1;
            $numberSuccess = $serviceItem['number_success'] + 1;
            $dataUpdate = [
                'number_deff' => $numberDeff,
                'number_success' => $numberSuccess,
                'updateAt' => time() * 1000,
                'updateDateTime' => date('Y-m-d H:i:s')
            ];
            if ($numberDeff <= 0) {
                $dataUpdate['status'] = 'Success';
            }
            ServiceLog::insert($dataContentArr);
            Services::where('_id', $serviceItem['_id'])->update($dataUpdate);
            return json_encode(['success' => true, 'data' => $dataContentArr]);
        } else {
            return json_encode(['success' => false]);
        }

    }

    public function getReport(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if ($dataContentArr['date']) {
            $timeStart =  strtotime($dataContentArr['date'])*1000;
            $timeEnd = $timeStart + 24*60*60*1000 - 1;
            $dataItem = Report::where('date', '>=', $timeStart)->where('date', '<', $timeEnd)->first();
            return json_encode(['success' => true, 'data' => $dataItem, 'timeStart' => $timeStart]);
        } else {
            return json_encode(['success' => false]);
        }

    }

    public function updateAllService()
    {
        $ListService = Services::orderBy('updateDateTime', 'ASC')->limit(1000)->get()->toArray();
        foreach ($ListService as $item) {
            $date = date('d-m-Y',strtotime($item['createdAt']));
            Services::where('_id', $item['_id'])->update(['date' => $date, 'updateDateTime' => date('Y-m-d H:i:s')]);
        }
        return json_encode(['success' => true]);
    }

    public function updateServiceDay() {
        // $ListService = Services::where('cron_check', 1)->orderBy('date', 'DESC')->limit(100)->get()->toArray();
        // foreach ($ListService as $item) {
        //     $date = isset($item['date']) ? $item['date'] : '';
        //     $checkExist = Report::where('date', $date)->first();
        //     $number = $item['number'];
        //     $feild = 'number_'.$item['type'];
        //     if(!empty($checkExist)) {
        //         $number = isset($checkExist[$feild]) ? $number + $checkExist[$feild] : $number;
        //     } 
        //     $dataUpdate = [
        //         'date' => $date,
        //         $feild => $number
        //     ];
        //     if(!empty($checkExist)) {
        //         Report::where('_id', $checkExist['_id'])->update($dataUpdate);
        //     } else {
        //         Report::insert($dataUpdate);
        //     }
        //     Services::where('_id', $item['_id'])->update(['cron_check' => 2]);
        // }
        // return json_encode(['success' => true]);
    }

    public function updateServiceSuccessDay() {
        // $ListService = Services::orderBy('date', 'DESC')->where('cron_check', 2)->where('status', 'Success')->limit(100)->get()->toArray();
        // foreach ($ListService as $item) {
        //     if(isset($item['TimeSuccess']) && is_numeric($item['TimeSuccess']) && isset($item['price'])) {
        //         $date =  date('d-m-Y', (int)$item['TimeSuccess'] / 1000);
        //         $checkExist = Report::where('date', $date)->first();
        //         $number = 1;
        //         $total_proceed = $item['price']*$item['number'];
        //         $feild = 'number_success_'.$item['type'];
        //         if(!empty($checkExist)) {
        //             $number = isset($checkExist[$feild]) ? $number + $checkExist[$feild] : $number;
        //             $total_proceed = isset($checkExist['total_proceed']) ? $checkExist['total_proceed'] + $total_proceed : $total_proceed;
        //         } 
        //         $dataUpdate = [
        //             'date' => $date,
        //             $feild => $number,
        //             'total_proceed' => $total_proceed
        //         ];
        //         if(!empty($checkExist)) {
        //             Report::where('_id', $checkExist['_id'])->update($dataUpdate);
        //             Services::where('_id', $item['_id'])->update(['cron_check' => 3]);
        //         }
        //     }
        //     Services::where('_id', $item['_id'])->update(['DateTimeCron' => date('Y-m-d H:i:s')]);
        // }
        // return json_encode(['success' => true]);
    }

    public function updateTotalDeposit() {
        // $listTransaction = Transaction::where('cron_check', 1)->where('status', 'Active')->limit(100)->get()->toArray();
        // foreach ($listTransaction as $transaction) {
        //     if(isset($transaction['date'])) {
        //         $checkExist = Report::where('date', $transaction['date'])->first();
        //         if(!empty($checkExist)) {
        //             $total_deposit = isset($checkExist['total_deposit']) ? $checkExist['total_deposit'] + $transaction['value'] : $transaction['value'];
        //         } else {
        //             $total_deposit = $transaction['value'];
        //         }
        //         $dataUpdate = [
        //             'total_deposit' => $total_deposit,
        //             'date' => $transaction['date']
        //         ];
        //         if(!empty($checkExist)) {
        //             Report::where('_id', $checkExist['_id'])->update($dataUpdate);
        //         } else {
        //             Report::insert($dataUpdate);
        //         }
        //     }
        //     Transaction::where('_id', $transaction['_id'])->update(['cron_check' => 2]);
        // }
    }

    public function InsertComment(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if ($dataContentArr['token'] && $dataContentArr['contentArr'] && isset($dataContentArr['name'])) {
            if(empty($dataContentArr['name']) || empty($dataContentArr['contentArr'])) {
                return json_encode(['success' => false, 'message' => 'các trường không được phép để trống']);
            }
            $userItem = User::where('token', $dataContentArr['token'])->first();
            if(empty($userItem)) {
                return json_encode(['success' => false, 'message' => 'user không tồn tại']);
            }

            if(count($dataContentArr['contentArr']) < 20) {
                return json_encode(['success' => false, 'message' => 'Yêu cầu tối thiếu 20 comment']);
            }
            $dataComment = [
                'name' =>  $dataContentArr['name'],
                'createdAt' => date('Y-m-d H:i:s'),
                'token' => $dataContentArr['token'],
                'username' => $userItem['username'],
                'fullname' => $userItem['fullname'],
                'status' => 'Pending'
            ];
            $result = Comment::create($dataComment);
            foreach ($dataContentArr['contentArr'] as $key => $value) {
                if(!empty($value)) {
                    $dataSave = [
                        'content' => $value,
                        'comment_id' => $result['_id'],
                        'token' => $dataContentArr['token'],
                        'createdAt' => date('Y-m-d H:i:s')
                    ];
                    CommentContent::insert($dataSave);
                }  
            }
            return json_encode(['success' => true]);
        } else {
            return json_encode(['success' => false]);
        }
    }

    public function GetComment(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $limit = $dataContentArr['limit'] ? (int)$dataContentArr['limit'] : 100;
        $page = $dataContentArr['page'] ? (int)$dataContentArr['page'] : 1;
        $offset = ($page - 1) * $limit;
        if (isset($dataContentArr['token'])) {
            if(isset($dataContentArr['status'])) {
                $count = Comment::orderBy('_id', 'DESC')->where('status', 'Active')->where('token', $dataContentArr['token'])->count();
                $ListComment = Comment::orderBy('_id', 'DESC')->where('status', 'Active')->where('token', $dataContentArr['token'])->offset($offset)->limit($limit)->get();
            } else {
                $count = Comment::orderBy('_id', 'DESC')->where('token', $dataContentArr['token'])->count();
                $ListComment = Comment::orderBy('_id', 'DESC')->where('token', $dataContentArr['token'])->offset($offset)->limit($limit)->get();
            }
        } else {
            $count = Comment::orderBy('_id', 'DESC')->count();
            $ListComment = Comment::orderBy('_id', 'DESC')->offset($offset)->limit($limit)->get();
        }
        return json_encode(['success' => true, 'total' => $count, 'data' => $ListComment]);
    }

    public function GetCommentContent(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        $limit = $dataContentArr['limit'] ? (int)$dataContentArr['limit'] : 100;
        $page = $dataContentArr['page'] ? (int)$dataContentArr['page'] : 1;
        $offset = ($page - 1) * $limit;
        $count = 0;
        $ListComment = [];
        if (isset($dataContentArr['_id'])) {
            $count = CommentContent::orderBy('_id', 'DESC')->where('comment_id', $dataContentArr['_id'])->count();
            $ListComment = CommentContent::orderBy('_id', 'DESC')->where('comment_id', $dataContentArr['_id'])->offset($offset)->limit($limit)->get();
        }
        return json_encode(['success' => true, 'total' => $count, 'data' => $ListComment]);
    }

    public function UpdateComment(Request $request)
    {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if(isset($dataContentArr['_id']) && isset($dataContentArr['status'])) {
            Comment::where('_id', $dataContentArr['_id'])->update(['status' => $dataContentArr['status']]);
        }
        return json_encode(['success' => true]);
    }

    public function AddVipikeService(Request $request) {
        $dataContent = $request->getContent();
        $dataContentArr = json_decode($dataContent, TRUE);
        if(isset($dataContentArr['uids'])) {
            $uids = $dataContentArr['uids'];
            foreach($uids as $key => $value) {
                $dataService['service_code'] = StringHelper::generateCode(6);
                $dataService['status'] = 'Active';
                $dataService['fanpage_id'] = $value;
                $dataService['createdAt'] = date('Y-m-d H:i:s');
                $dataService['date'] = date('d-m-Y');
                $dataService['cron_check'] = 1;
                $dataService['updated_at'] = time() * 1000;
                $dataService['created_at'] = time() * 1000;
                Viplikes::insert($dataService);
            }
        }
        return json_encode(['success' => true]);
    }
}