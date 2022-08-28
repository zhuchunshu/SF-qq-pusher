<?php

namespace App\Plugins\QQPusher\src\Jobs;

use App\Model\AdminOption;
use App\Plugins\QQPusher\src\GoCqhttp;
use App\Plugins\QQPusher\src\Models\QQpusher;
use App\Plugins\Topic\src\Models\Topic;
use Hyperf\Crontab\Annotation\Crontab;
use Noodlehaus\Config;

/**
 * @Crontab(name="QQPusherSendMsg", rule="* * * * *", callback="execute", memo="QQPusherSendMsg")
 */
class SendJob
{
    public function execute(){
        if(QQpusher::query()->exists()){
            $groups = Config::load(plugin_path('QQPusher/groups.json'))->all();
            foreach (Topic::query()->where('status','publish')->get() as $topic){
                if(!QQpusher::query()->where('topic_id',$topic->id)->exists()){
                    // 发送
                    foreach ($groups as $group_id){
                        GoCqhttp::post('send_group_msg',[
                            'message' => "--- 论坛新帖通知 --- 
主题： ".$topic->title."\n链接：".$this->url("/".$topic->id.".html")."\n点击上方链接参与探讨。",
                            'group_id' => $group_id
                        ]);
                    }
                    QQpusher::query()->create([
                        'topic_id' => $topic->id
                    ]);
                }
            }
        }
    }

    private function url($path=null){
        $url = $this->get_options("APP_URL","http://127.0.0.1");
        if(!$path){
            return $url;
        }
        return $url.$path;
    }

    private function get_options($name,$default=""){
        if(!cache()->has('admin.options.'.$name)){
            cache()->set("admin.options.".$name,@AdminOption::query()->where("name",$name)->first()->value);
        }
        return $this->core_default(cache()->get("admin.options.".$name),$default);
    }

    private function core_default($string=null,$default=null){
        if($string){
            return $string;
        }
        return $default;
    }
}