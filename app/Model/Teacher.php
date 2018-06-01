<?php

namespace App\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class Teacher extends Model
{

    //不可被批量复制的字段
    public $guarded = ['id'];

    //关联职务级别表
    public function joblevel(){
        return $this->belongsTo(Joblevel::class, 'joblevel_id', 'id');
    }

    //关联职务类型表
    public function jobtype(){
        return $this->belongsTo(Jobtype::class, 'jobtype_id', 'id');
    }

    //关联教研室表
    public function staffroom(){
        return $this->belongsTo(Staffroom::class, 'staffroom_id', 'id');
    }

    //关联职称表
    public function title(){
        return $this->belongsTo(Title::class, 'title_id', 'id');
    }

    //关联课表
    public function courses(){
        return $this->belongsToMany(Course::class, 'teacher_course', 'teacher_id', 'course_id');
    }

    /*
     * 获取教师的id号
     *
     * */
    static function teacherId($name){
        $client = new Client();
        $name = mb_convert_encoding($name,'gb2312','UTF-8');
        $semester = Term::judgeIterm();
        $request = $client
            ->request('GET',Config::get('course.url.teacher_id').$semester.'&js='.$name, [
                'headers' => [
                    'Referer'=>Config::get('course.headers')
                ]
            ]);
        $teacher = $request->getBody();
        $trans=mb_convert_encoding($teacher,'UTF-8',"gb2312");
        $crawler = new Crawler($trans);
        $crawler = $crawler->filter('script')->text();
        $reg='/(\d{6,7}(\.\d+)?)/is';//匹配数字的正则表达式
        preg_match_all($reg,$crawler,$result);
        if(is_array($result)&&!empty($result)&&!empty($result[1])&&!empty($result[1][0])){
            if ((count($result[1])==2)||count($result[1])==1){
                return $result[1][0];
            }elseif (count($result[1])>2){
                preg_match_all("/\(([\s\S]*?)\)/",$crawler, $result_teacher);
                for ($i = 1 ; $i < count($result_teacher[1]) ;$i++){
                    if ($result_teacher[1][$i] == '&#20449;&#24037;'){
                        return $result[1][$i];
                    }
                }
            }
        }
        return -2;
    }


    /*
    * 获取课表
    * */
    static function getCourse($id,$code){
        $techer_id =Teacher::where('id', $id)->get(['name']);
        $techer_name = $techer_id[0]->name;
        $client = new Client();
        $teacher_id = Teacher::teacherId($techer_name);
        if ($teacher_id == -2){
            return -2;
        }
        $teacher_exit = Teacher::find($id)->courses()->get();
        if (count($teacher_exit)>0){
            Course::romoveCourse($id);
        }
        $jar = cache('jar');
        $semester = Term::judgeIterm();
        try {                                                                                               //发送请求获取含有课表信息的html页面
            $request = $client
                ->request('POST',Config::get('course.url.teacher'),[
                    'form_params' => [
                        'Sel_XNXQ' => $semester,
                        'Sel_JS' => $teacher_id,
                        'type' => '2',
                        'txt_yzm'=>$code,
                        '_token'=>csrf_token()
                    ],
                    'headers' => [
                        'Referer'=>Config::get('course.headers'),
                    ],
                    'cookies'=>$jar
                ]);
        } catch (ClientException $e) {
            echo $e->getRequest();
            echo $e->getResponse();
        }
        $course = $request->getBody();
        $course = mb_convert_encoding($course,'utf-8','gb2312');
        if (strstr($course,'验证码错误')){                                                             //判断验证码是否有误
            return -1;
        }
        $crawler = new Crawler($course);
        $classroom = $crawler->filter('body>table>tr>td>table')->reduce(function (Crawler $node, $i) {
            return ($i == 2);
        });
        $course = $classroom->filter('table > tr')->nextAll();
        $y = 0;
        $x = 0;
        foreach ($course as $domELement){
            if ($x%10 == 0 &&$x!=0){
                $y++;
                $x = 0;
            }
            $course_teacher[$y][$x] = $domELement->nodeValue;
            $x++;
        }
        $teacher = Teacher::handle($course_teacher,$techer_name);
        $data_teacher = Teacher::conversion($teacher);
        $teacher_course =Teacher::find($id)->courses()->get();
        Log::info($id);
        Log::info($data_teacher);
        if(count($teacher_course)>0){
            Course::romoveCourse($id);
        }else{
            DB::beginTransaction();
            try{                                                                                              //进行课表的插入
                $course_id = Course::max('id');
                Course::insert($data_teacher);
                DB::commit();
                if (count($course_id)==0){
                    $course_id = Course::min('id');
                }
                for ($i = 0;$i < count($data_teacher); $i++){
                    $teachers_id[$i] = $course_id+$i;
                }
                Teacher::find($id)->courses()->attach($teachers_id);
                $result[0] = $data_teacher;
                $result[1] = $teacher_id;
                return $result;
            }catch (\Exception $e){
                DB::rollback();
                return -3;
            }
        }
    }

    /*
    * 处理课表数组
    * */
    static function handle($course,$techer){
        $x = 0;
        for ($i = 0 ; $i < count($course) ; $i++){
            if ($course[$i][1] != ""){
                $reg='/(\d{8}(\.\d+)?)/is';                                 //匹配数字的正则表达式
                preg_match_all($reg,$course[$i][1] ,$result);
                $course[$i][0] = $result[1][0];
                $course[$i][1] = explode(']',$course[$i][1])[1];
            }else{
                $course[$i][0] = $course[$i-1][0];
                $course[$i][1] = $course[$i-1][1];
            }
            if ($course[$i][4] == ""){
                $course[$i][4] = $course[$i-1][4];
            }
            $course[$i][2] = $techer;
            $course[$i][3] = Teacher::week($course[$i][8]);
            $course[$i][4] = Teacher::type($course[$i][8]);
            $reg='/(\d{1,2}(\.\d+)?)/is';
            preg_match_all($reg,$course[$i][8],$cour);          //获取课表时间列数字参数
            if (count($cour[1])==4){                                      //当提取出四个参数
                $course[$i][5] = (int)$cour[1][0];
                $course[$i][6] = (int)$cour[1][1];
                $course[$i][7] = (int)$cour[1][2];
                $course[$i][8] = (int)$cour[1][3];
            }elseif(count($cour[1])==3){                                  //当提取出3个参数
                $course[$i][5] = (int)$cour[1][0];
                $course[$i][6] = (int)$cour[1][0];
                $course[$i][7] = (int)$cour[1][1];
                $course[$i][8] = (int)$cour[1][2];
            }else{                                                         //当参数为6个时
                $course[$i][5] = (int)$cour[1][0];
                $course[$i][6] = (int)$cour[1][1];
                $course[$i][7] = (int)$cour[1][4];
                $course[$i][8] = (int)$cour[1][5];
                $number_couser[$x] = $i;                                   //保存其上一列的位置例如1-8,10-18周 一[1-2节]
                $number_couser[$x+1] = (int)$cour[1][2];                   //保存周的后两个参数如 10， 18
                $number_couser[$x+2] = (int)$cour[1][3];
                $x = $x+2;
                $x++;
            }
        }
        if (isset($number_couser)) {                                       //将保存的周的后两个参数加入数组中
            $tatol = count($course);
            $j = 0;
            for ($y = 0;$y < count($number_couser) ; $y = $y+3){
                $course[$tatol+$j][0] = $course[$number_couser[$y]][0];
                $course[$tatol+$j][1] = $course[$number_couser[$y]][1];
                $course[$tatol+$j][2] = $course[$number_couser[$y]][2];
                $course[$tatol+$j][3] = $course[$number_couser[$y]][3];
                $course[$tatol+$j][4] = $course[$number_couser[$y]][4];
                $course[$tatol+$j][5] = $number_couser[$y+1];
                $course[$tatol+$j][6] = $number_couser[$y+2];
                $course[$tatol+$j][7] = $course[$number_couser[$y]][7];
                $course[$tatol+$j][8] = $course[$number_couser[$y]][8];
                $course[$tatol+$j][9] = $course[$number_couser[$y]][9];
                $j++;
            }
        }
        return $course;
    }

    /*
   *固定插入数据中课表的格式
   *
   * */
    static function conversion($course){
        $semester = Setting::max('id');
        for($i = 0; $i<count($course) ; $i++){

            $teacher[$i]['name'] = $course[$i][1];
            $teacher[$i]['location'] = $course[$i][9];
            $teacher[$i]['week_start'] = $course[$i][5];
            $teacher[$i]['week_end'] = $course[$i][6];
            $teacher[$i]['week_type'] = $course[$i][4];
            $teacher[$i]['week_day'] = $course[$i][3];
            $teacher[$i]['section_start'] = $course[$i][7];
            $teacher[$i]['section_end'] = $course[$i][8];
            $teacher[$i]['term_id'] = $semester;
        }
        return $teacher;
    }


    /*
   * 判断单双周
   * */
    static function type($type){
        if (count(explode('单',$type))>1){
            return 1;
        }elseif (count(explode('双',$type))>1){
            return 2;
        }
        return 0;
    }

    /*判断周几
    * */
    static function week($week){
        if (count(explode('一',$week))>1){
            return 1;
        }elseif (count(explode('二',$week))>1){
            return 2;
        }elseif (count(explode('三',$week))>1){
            return 3;
        }elseif (count(explode('四',$week))>1){
            return 4;
        }elseif (count(explode('五',$week))>1){
            return 5;
        }elseif (count(explode('六',$week))>1){
            return 6;
        }elseif (count(explode('日',$week))>1){
            return 7;
        }
    }

}
