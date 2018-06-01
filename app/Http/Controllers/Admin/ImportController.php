<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\ObtainCourse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Log;
use App\Model\Teacher;
use App\Model\Teachercourse;

class ImportController extends Controller
{
    /*
     * 返回验证码的视图
     * */
    public function sendCode(){
        $jar = new CookieJar;
        $client = new Client();
        $request = $client
            ->request('GET',Config::get('course.url.code'), [
                'headers' => [
                    'Referer'=>Config::get('course.headers')
                ],
                'cookies' => $jar,
            ]);
        Cache::put('jar', $jar, 100);
        return $request->getBody();
    }

    /*
     * 教课表组成二位数组
     * */
    public function code(Request $request){
        $code = $request->all();
        $information = Teacher::getCourse($code['id'],$code['code']);
        if (is_array( $information ) ){
            return $this->returnJSON(3,$information[0],$information[1]);
        }else{
            if ($information == -2){
                return $this->returnJSON(-2, '无该教师课表信息');
            }elseif ($information == -1){
                return $this->returnJSON(-1, '验证码错误');
            }elseif ($information == -3){
                return $this->returnJSON(-2,'插入课表失败');
            }
        }
    }

    /*
     * 导入所有老师的课程
     * */
    public function teachersCourse(){
        $code = Input::get('code');
        $id = Teacher::get(['id']);
        for ($i = 0 ; $i < count($id) ; $i++){
            Log::info($i);
            ImportController::dispatch(new ObtainCourse($id[$i]->id,$code));
        }

    }

    public function s(){
        return Config::get('course.url.teacher_id');

    }
}
