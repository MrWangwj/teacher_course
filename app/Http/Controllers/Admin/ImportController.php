<?php

namespace App\Http\Controllers\Admin;

use App\Model\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;
use Symfony\Component\DomCrawler\Crawler;
use App\Model\Teacher;
use App\Model\Teachercourse;

class ImportController extends Controller
{
    private $semester;
    public function __construct(){
        $months = date('m',time());
        if ($months < 8){
            $kind = 1;
            $year = date('Y',time());
            $year = $year-1;
            $this->semester = $year.$kind;
        }else{
            $kind = 0;
            $year = date('Y',time());
            $this->semester = $year.$kind;
        }
    }

    /*
     * 获取教师的id号
     *
     * */
    public function teacherId($name){
        $client = new Client(['base_uri'=>'http://jwgl.hist.edu.cn/jwweb/']);
        $name = mb_convert_encoding($name,'gb2312','UTF-8');
        $request = $client
            ->request('GET','ZNPK/Private/List_JS.aspx?xnxq='.$this->semester.'&js='.$name, [
                'headers' => [
                    'Referer'=>'ZNPK/TeacherKBFB.aspx'
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
     * 返回验证码的视图
     * */
    public function sendCode(){
        $jar = new CookieJar;
        $client = new Client(['base_uri'=>'http://jwgl.hist.edu.cn/jwweb/']);
        $request = $client
            ->request('GET','sys/ValidateCode.aspx', [
                'headers' => [
                    'Referer'=>'ZNPK/TeacherKBFB.aspx'
                ],
                'cookies' => $jar,
            ]);
         Session::put('jar',$jar);
        return $request->getBody();
    }

    /*
     * 教课表组成二位数组
     * */
    public function code(Request $request){
        $code = $request->all();
        $techer_id =Teacher::where('id', $code['id'])->get(['name']);
        $techer_name = $techer_id[0]->name;
        $client = new Client(['base_uri' => 'http://jwgl.hist.edu.cn/jwweb/']);
        $teacher_id = $this->teacherId($techer_name);
        if ($teacher_id == -2){
            return $this->returnJSON(-2, '无该教师课表信息');
        }
        $teacher_exit = Teachercourse::where('teacher_id',$code['id'])->get();
        if (count($teacher_exit)>0){
            return $this->returnJSON('-2','该教师的信息已存在，请清除后再添加！');
        }
        $jar = Session::get('jar');

        try {
            $request = $client
                ->request('POST','ZNPK/TeacherKBFB_rpt.aspx',[
                    'form_params' => [
                        'Sel_XNXQ' => $this->semester,
                        'Sel_JS' => $teacher_id,
                        'type' => '2',
                        'txt_yzm'=>$code['code'],
                        '_token'=>csrf_token()
                    ],
                    'headers' => [
                        'Referer'=>'ZNPK/TeacherKBFB.aspx',
                    ],
                    'cookies'=>$jar
                ]);
        } catch (ClientException $e) {
            echo $e->getRequest();
            echo $e->getResponse();
        }
        $course = $request->getBody();
        $course = mb_convert_encoding($course,'utf-8','gb2312');
        if (strstr($course,'验证码错误')){
            return $this->returnJSON(1, '验证码错误');
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
        $teacher = $this->handle($course_teacher,$techer_name);
        $data_teacher = $course_class = $this->conversion($teacher,$this->semester);
        $teacher_course =Teachercourse::where('id', $code['id'])->get();
        if(count($teacher_course)>0){
            return $this->returnJSON(-2, '教师课表信息已存在，请先清除所有课表');
        }else{
            DB::beginTransaction();
            try{
                $course_id = Course::max('id');
                Course::insert($data_teacher);
                DB::commit();
                if (count($course_id)==0){
                    $course_id = Course::min('id');
                }

                for ($i = 0;$i < count($data_teacher); $i++){
                    $teachers_id[$i]['teacher_id'] = $code['id'];
                    $teachers_id[$i]['course_id'] = $course_id+1+$i;
                }
                Teachercourse::insert($teachers_id);
                return $this->returnJSON('3',$data_teacher,$teacher_id);
            }catch (\Exception $e){
                DB::rollback();
                return $this->returnJSON('-2','插入课表失败');
            }

        }
    }

    /*判断周几
     * */
    public function week($week){
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

    /*
     * 判断单双周
     * */
    public function type($type){
        if (count(explode('单',$type))>1){
            return 1;
        }elseif (count(explode('双',$type))>1){
            return 2;
        }
        return 3;
    }

    /*
     * 处理课表数组
     * */
    public function handle($course,$techer){
        $x = 0;
        for ($i = 0 ; $i < count($course) ; $i++){
            if ($course[$i][1] != ""){
                $reg='/(\d{8}(\.\d+)?)/is';//匹配数字的正则表达式
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
            $course[$i][3] = $this->week($course[$i][8]);
            $course[$i][4] = $this->type($course[$i][8]);
            $reg='/(\d{1,2}(\.\d+)?)/is';
            preg_match_all($reg,$course[$i][8],$cour);
            if (count($cour[1])==4){
                $course[$i][5] = (int)$cour[1][0];
                $course[$i][6] = (int)$cour[1][1];
                $course[$i][7] = (int)$cour[1][2];
                $course[$i][8] = (int)$cour[1][3];
            }elseif(count($cour[1])==3){
                $course[$i][5] = (int)$cour[1][0];
                $course[$i][6] = (int)$cour[1][0];
                $course[$i][7] = (int)$cour[1][1];
                $course[$i][8] = (int)$cour[1][2];                
            }else{
                $course[$i][5] = (int)$cour[1][0];
                $course[$i][6] = (int)$cour[1][1];
                $course[$i][7] = (int)$cour[1][4];
                $course[$i][8] = (int)$cour[1][5];
                $number_couser[$x] = $i;
                $number_couser[$x+1] = (int)$cour[1][2];
                $number_couser[$x+2] = (int)$cour[1][3];
                $x++;
            }
        }
        if (isset($number_couser)) {
            $tatol = count($course);
            for ($y = 0;$y < count($number_couser) ; $y = $y+3){
                $course[$tatol][0] = $course[$number_couser[$y]][0];
                $course[$tatol][1] = $course[$number_couser[$y]][1];
                $course[$tatol][2] = $course[$number_couser[$y]][2];
                $course[$tatol][3] = $course[$number_couser[$y]][3];
                $course[$tatol][4] = $course[$number_couser[$y]][4];
                $course[$tatol][5] = $number_couser[$y+1];
                $course[$tatol][6] = $number_couser[$y+2];
                $course[$tatol][7] = $course[$number_couser[$y]][7];
                $course[$tatol][8] = $course[$number_couser[$y]][8];
                $course[$tatol][9] = $course[$number_couser[$y]][9];
            }            
        }
        return $course;
    }

    public function conversion($course,$semester){
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


    public function s(){
        $id = Course::max('id');
        return $id;
    }
}
