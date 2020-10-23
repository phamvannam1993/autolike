<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Logs;
use App\Models\LoggingFacebook;
use App\Models\Country;
use Illuminate\Support\Facades\Cache;
use DB;
class SshCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssh:cron';
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
        //Logs::insert(['created_at' => date('Y-m-d H:i:s')]);
        $nowtime = date("Y-m-d H:i:s");
        $date = date('Y-m-d H:i:s', strtotime($nowtime . " -23 hours"));
        $listLogging = LoggingFacebook::where('dateTime', '<' , $date)->orderBy('_id', 'DESC')->limit(1000)->get()->toArray();

        foreach ($listLogging as $logging) {
            if(Cache::get('info'.$logging['_id'])) {
                Cache::forget($logging['_id']);
                Cache::forget('info'.$logging['_id']);
            }
            LoggingFacebook::where('_id', $logging['_id'])->delete();
        }
        //Logs::insert(['created_at' => date('Y-m-d H:i:s')]);
//        @file_get_contents('https://www.autofarmer.xyz/clone/form?action=lang');
        $this->info('Demo:Cron Cummand Run successfully!');
    }
}