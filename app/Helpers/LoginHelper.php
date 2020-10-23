<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/19/2018
 * Time: 9:46 AM
 */

namespace App\Helpers;


use App\Models\ActionProfile;
use App\Models\Device;
use App\Models\Friend;
use App\Models\GroupProfile;
use App\Models\Page;
use App\Models\User;
use App\Models\PageUid;
use App\Models\ActionProfileNumber;
use Illuminate\Support\Facades\Cache;

class LoginHelper
{
    public function checkSession() {
        $value = session('dataLogin');
        if($value) {
            return true;
        } else {
            return false;
        }
    }

    function getMessageError($device) {
        if (!isset($device['AndroidVersion'])) {
            return 'thiếu param AndroidVersion';
        }

        if (!isset($device['AndroidID'])) {
            return 'thiếu param AndroidID';
        }

        if (!isset($device['IMEI'])) {
            return 'thiếu param IMEI';
        }

        if (!isset($device['IMSI'])) {
            return 'thiếu param IMSI';
        }

        if (!isset($device['SIMCardSerial'])) {
            return 'thiếu param SIMCardSerial';
        }

        if (!isset($device['WifiMacAddress'])) {
            return 'thiếu param WifiMacAddress';
        }

        if ( !isset($device['WifiMacAddress'])) {
            return 'thiếu param WifiMacAddress';
        }

        if (!isset($device['GoogleSF'])) {
            return 'thiếu param GoogleSF';
        }

        if (!isset($device['model'])) {
            return 'thiếu param model';
        }
    }

    public function checkBalance($userDetail) {
        if($userDetail['balance'] < 1000) {
            $listActionProfile = ActionProfile::where('user_id', $userDetail['_id'])->get()->toArray();;
            if(!empty($listActionProfile)) {
                foreach ($listActionProfile as $profile) {
                    $actionProfileId = $profile['_id'];
                    $profileNumber = ActionProfileNumber::where('action_profile_id', $actionProfileId)->get()->toArray();
                    if(!empty($profileNumber)) {
                        foreach ($profileNumber as $action) {
                            $actionList = json_decode($action['action_list']);
                            $data = [1, 16, 17];
                            if(!empty($actionList)) {
                                foreach ($actionList as $key => $value) {
                                    if(!in_array($value, $data)) {
                                        $data[] = $value;
                                    }
                                }
                            }
                            ActionProfileNumber::where('_id', $action['_id'])->update(['action_list' => \GuzzleHttp\json_encode($data)]);
                        }
                    }
                }
            }
        }
    }

    function encrypted($plaintext, $key) {
        $method = 'aes-256-cbc';
        $iv = '0123456789012345';
        $key = 'Congaubeo@123456Congaubeo@123456';
        return base64_encode(openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv));
    }

    function decrypted($encrypted, $check) {
        $method = 'aes-256-cbc';
        $iv = '0123456789012345';
        $key = 'Congaubeo@123456Congaubeo@123456';
        return json_decode(openssl_decrypt(base64_decode($encrypted), $method, $key, OPENSSL_RAW_DATA, $iv), true);
    }

    function decryptedLang($encrypted, $check) {
        $method = 'aes-256-cbc';
        $iv = '0123456789012345';
        $key = 'Congaubeo@123456Congaubeo@123456';
        $decrypted = openssl_decrypt($encrypted, $method, $key, 0, $iv);
        return $decrypted;
    }

    function decryptedNotArr($encrypted, $key) {
        $method = 'aes-256-cbc';
        $iv = '0123456789012345';
        $key = 'Congaubeo@123456Congaubeo@123456';
        return openssl_decrypt(base64_decode($encrypted), $method, $key, OPENSSL_RAW_DATA, $iv);
    }

    //V3
    function encryptedV3($plaintext, $date) {
        $method = 'aes-256-cbc';
        $iv = 'Congaubeo@123560';
        $KeyAll = 'Congaubeo@123'.$date.$date;
        $key = substr($KeyAll, 0, 32);
        return base64_encode(openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv));
    }

    function decryptedV3($encrypted, $date) {
        $method = 'aes-256-cbc';
        $iv = 'Congaubeo@123560';
        $KeyAll = 'Congaubeo@123'.$date.$date;
        $key = substr($KeyAll, 0, 32);
        return json_decode(openssl_decrypt(base64_decode($encrypted), $method, $key, OPENSSL_RAW_DATA, $iv), true);
    }

    function decryptedNotArrV3($encrypted, $date) {
        $KeyAll = 'Congaubeo@123'.$date.$date;
        $key = substr($KeyAll, 0, 32);
        $method = 'aes-256-cbc';
        $iv = 'Congaubeo@123560';
        return openssl_decrypt(base64_decode($encrypted), $method, $key, OPENSSL_RAW_DATA, $iv);
    }
    //End V3

    function saveDevice($device, $token)
    {
        if (!empty($device)) {
            $AndroidID = $device['IMEI'];
        }
        $dataDevice = [
            'clone_name' => 'vn',
            'token' => $token,
            'name' => $AndroidID,
            'device' => $device,
            'reset_3g' => '10',
            'time_out' => '15',
        ];
        $checkDevice = Device::where('name', $AndroidID)->get()->first();
        if ($checkDevice) {
            $deviceId = $checkDevice['_id'];
        } else {
            $reuslt = Device::create($dataDevice);
            if ($reuslt) {
                $deviceId = $reuslt->_id;
            }
        }
        Device::where('_id', $deviceId)->update(['date_create' => date('Y-m-d H:i:s')]);
        return $deviceId;
    }

    function getCloneName($country, $type) {
        $listData = Cache::get('names');
        $dataArr = [];
        if(!empty($listData) && isset($listData[$country])) {
            foreach ($listData[$country] as $key => $data) {
                if(!empty($data)) {
                    $dataArr[$key] = $data[0];
                    shuffle($listData[$country][$key]);
                }
            }
            Cache::put('names', $listData, 1440);
        } else {
            $this->updateCloneInfo();
            $listData = Cache::get('names');
            $dataArr = [];
            if(!empty($listData) && isset($listData[$country])) {
                foreach ($listData[$country] as $key => $data) {
                    if(!empty($data)) {
                        $dataArr[$key] = $data[0];
                        shuffle($listData[$country][$key]);
                    }
                }
                Cache::put('names', $listData, 1440);
            }
        }
        if(isset($dataArr[$type])) {
            return $dataArr[$type];
        } else {
            return '';
        }
    }

    function  handlingApi($url, $bodyData = []) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($bodyData))
        );
        $result = curl_exec($ch);
        return json_decode($result, true);
    }

}