<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\SettingDomain;
use Illuminate\Support\Facades\Cache;
use DB;
class ViplikeCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'viplike:cron';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $listProxy = file_get_contents('http://list.didsoft.com/get?email=congaubeo76@gmail.com&pass=nie26e&pid=httppremium&showcountry=yes&https=yes');
        $listProxyArr =   $skuList = preg_split("/\\r\\n|\\r|\\n/", $listProxy);
        $dataAll = [];

        if(!empty($listProxyArr)) {
            foreach ($listProxyArr as $proxy) {
                $data1 = explode(':', $proxy);
                if(isset($data1[1])) {
                    $data2 = explode('#', $data1[1]);
                    if(isset($data2[1])) {
                        $dataAll['all'][] = [
                            'ip' => $data1[0],
                            'country' => strtolower($data2[1]),
                            'port' => $data2[0],
                        ];
                    }
                }
            }
        }
        $proxyArrJson = Cache::get('proxyList');
        $setting = SettingDomain::where('type', 'domain')->get()->first();
        $countProxy = isset($setting['proxy_queue']) ? $setting['proxy_queue'] : 2000;
        if(count($proxyArrJson['all']) > $countProxy) {
            for($i = 0; $i < count($dataAll['all']) ; $i++) {
                unset($proxyArrJson['all'][$i]);
            }
        }
        if(!empty($dataAll) && count($proxyArrJson['all']) < $countProxy) {
            if(!empty($dataAll)) {
                foreach ($dataAll as $keyAll => $valueAll) {
                    foreach ($valueAll as $valAll) {
                        $dataArr[$keyAll][] = $valAll;
                    }
                }
            }
            $dataArr = [];
            $dataArr['all'] = array_merge($dataAll['all'], $proxyArrJson['all']);
            Cache::put('proxyList', $dataArr, 1440);
        }
        if('00:00' < date('H:i') == '00:10') {
            file_get_contents('https://admin.autofarmer.xyz/logging/delete');
        }
//        $listSSH = file_get_contents('https://api.autofarmer.xyz/ssh.txt');
//        $listSSHArr =   $skuList = preg_split("/\\r\\n|\\r|\\n/", $listSSH);
//        $dataAll = [];
//        if(!empty($listSSHArr)) {
//            foreach ($listSSHArr as $ssh) {
//                $data1 = explode('|', $ssh);
//                if (isset($data1[0]) && isset($data1[1]) && isset($data1[2]) && isset($data1[5])) {
//                    $dataAll['all'][] = [
//                        'ip' => isset($data1[0]) ? $data1[0] : '',
//                        'username' => isset($data1[1]) ? $data1[1] : '',
//                        'password' => isset($data1[2]) ? $data1[2] : '',
//                        'port' => isset($data1[5]) ? $data1[5] : '',
//                    ];
//                }
//            }
//        }
//        $cache = $dataAll;
//        Cache::put('abc', $cache, 1440);
        $this->info('Demo:Cron Cummand Run successfully!');
    }
}