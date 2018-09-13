<?php

namespace App\Console\Commands\cron;

use App\Facades\RssUtil;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Mail\SendRssMail;
use Illuminate\Support\Facades\Mail;
use Swift_RfcComplianceException;


class GetRss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getrss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GetRSS';

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
        $users = DB::table('users')
            ->orderBy('id')
            ->get();
        foreach ($users as $user) {
            //配信対象RSSデータ取得
            $send_rss_data_contents = RssUtil::RssProsessing($user);
//dump($send_rss_data_contents);
            //メール送信
            if (count($send_rss_data_contents) > 0) {
                echo "メール送信！！";
                $mailto = $user->email;
                $subject = "RSSマッチングレポート(" . date('Ymd') . ")";
                $contents = $send_rss_data_contents;
                $mailfrom = "info@exsample.com";
                //dump($content);
                try {
                    Mail::to($mailto)->send(new SendRssMail($subject, $contents, $mailfrom));
                } catch (Swift_RfcComplianceException $e) {
                    dump("RFC違反のメールです。:" . $mailto);
                }
            }
        }
        return;
    }
}
