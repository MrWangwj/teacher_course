<style>
    .title {
        width: 100%;
        height: 45px;
        font-size: small;
    }

    .title > div {
        width: 335px;
        margin: 5px auto;
        height: 35px;
    }

    .title > div > div {
        float: left;
        height: 100%;
    }

    .title > div:after {
        display: block;
        content: '';
        clear: both;
    }

    .see-type {
        width: 50px;
        margin-right: 5px;
    }

    .now-week-but {
        width: 35px;
        height: 100%;
        margin-right: 5px;
    }

    .weeks {
        width: 200px;
    }

    .count {
        width: 35px;
        margin-left: 5px;
    }

    .see-type, .now-week-but, .count {
        background-color: #227B86;
        border-radius: 5px;
        line-height: 35px;
        text-align: center;
        color: white;
    }
</style>

<template>
    <div class="main">
        <div class="title">
            <div>
                <div class="see-type">
                    <label v-if="!type" @click="type = true">课表</label>
                    <label v-if="type" @click="type = false">统计</label>
                </div>
                <div @click="setNowWeek()" class="now-week-but">本周</div>
                <div class="weeks">
                    <scroller lock-y :scrollbar-x=false>
                        <div class="box1" id="weeks" :style="{width: set.weeks.length*40+'px'}">
                            <div v-for="i in set.weeks" :class="{'box1-item':true, 'test':test, 'now-week': get.nowWeek === i.id, 'sel-week': i.sel }"
                                 @click="addSelWeek(i.id)" >
                                <span>{{' ' + i.id + ' '}}</span>
                            </div>
                        </div>
                    </scroller>
                </div>
                <div @click="count = true" class="count">筛选</div>
            </div>
        </div>

        <div v-transfer-dom>
            <popup v-model="count" position="right">
                <div style="width:330px;">
                    <divider>教研室</divider>
                    <checker
                            v-model="set.selStaffrooms"
                            type="checkbox"
                            default-item-class="group-item"
                            selected-item-class="group-item-selected"
                            @on-change="getSelStudents">
                        <checker-item v-for="i in get.staffrooms" :key="i.id" :value="i.id" class="group-item">{{ i.name }}</checker-item>
                    </checker>

                    <divider>职务级别</divider>
                    <checker
                            v-model="set.selJoblevels"
                            type="checkbox"
                            default-item-class="group-item"
                            selected-item-class="group-item-selected"
                            @on-change="getSelStudents">
                        <checker-item v-for="i in get.joblevels" :key="i.id" :value="i.id" class="group-item">{{ i.name }}</checker-item>
                    </checker>

                    <divider>职务类型</divider>
                    <checker
                            v-model="set.selJobtypes"
                            type="checkbox"
                            default-item-class="group-item"
                            selected-item-class="group-item-selected"
                            @on-change="getSelStudents">
                        <checker-item v-for="i in get.jobtypes" :key="i.id" :value="i.id" class="group-item">{{ i.name }}</checker-item>
                    </checker>

                    <divider>职称</divider>
                    <checker
                            v-model="set.selTitles"
                            type="checkbox"
                            default-item-class="group-item"
                            selected-item-class="group-item-selected"
                            @on-change="getSelStudents">
                        <checker-item v-for="i in get.titles" :key="i.id" :value="i.id" class="group-item">{{ i.name }}</checker-item>
                    </checker>

                    <divider>性别</divider>
                    <checker
                            v-model="set.selSexs"
                            type="checkbox"
                            default-item-class="group-item"
                            selected-item-class="group-item-selected"
                            @on-change="getSelStudents">
                        <checker-item :key="0" :value="'男'" class="group-item">男</checker-item>
                        <checker-item :key="1" :value="'女'" class="group-item">女</checker-item>
                    </checker>

                    <divider>----</divider>
                    <x-button style="background-color:#ff4a00;color: white;"  @click.native="selStuBut">高级筛选</x-button>
                </div>
            </popup>
        </div>

        <carousel-3d v-show="type" display="5" perspective="0" space="50" width="250" height="500"
        :inverseScaling="50" :loop="false" :controlsVisible="true" :minSwipeDistance="50">
            <slide  v-for="(v, i) in week_day" :key="i" :index="i" class="week-day" :style="{backgroundColor: colors[colorIndex[i]].content}">
                <div class="week-title" :style="{backgroundColor: colors[colorIndex[i]].title}">
                {{ v }}
                    （<span @click="haveNoCourseFun(true)">{{ haveNoCourse ? '有课':'无课' }}</span>）
                </div>
                <div class="courseContent">
                    <div style="width: 100%;" class="section" v-for="section in courses[i]">
                        <div>
                            <label :style="{backgroundColor: colors[colorIndex[i]].title}">{{ section.id }}</label>
                        </div>
                        <div>
                            <label :class="{'sel-user': stu.id === selUserId}" v-for="(stu,index) in section.stus"
                                   @click="courseInfo(stu.id, stu.courseInfo)">{{ stu.name }},</label>
                        </div>
                    </div>
                </div>
            </slide>
        </carousel-3d>

        <div v-if="!type" style="padding:15px;">
            <x-table full-bordered style="background-color:#fff;">
                <thead>
                <tr>
                    <th @click="haveNoCourseFun(true)" :style="{backgroundColor: haveNoCourse?'#1AAD19':'#E64340',color: 'white',borderRadius: '10px'}">
                        <span >{{ haveNoCourse ? '有课':'无课' }}</span>
                    </th>
                    <th>周一</th>
                    <th>周二</th>
                    <th>周三</th>
                    <th>周四</th>
                    <th>周五</th>
                    <th>周六</th>
                    <th>周日</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="i in 7">
                    <td>{{ (courses.hasOwnProperty(i-1)&&courses[i- 1].hasOwnProperty(0)&&courses[i - 1][0].hasOwnProperty('id')) ? courses[0][i-1].id : 0 }}节</td>
                    <td v-for="j in 7" @click="selTd(j-1, i-1)">
                        {{ (courses.hasOwnProperty(j-1)&&courses[j - 1].hasOwnProperty(i-1)&&courses[j - 1][i - 1].hasOwnProperty('stus')) ? courses[j - 1][i - 1].stus.length : 0 }}
                    </td>
                </tr>
                </tbody>
            </x-table>


            <div v-transfer-dom>
                <x-dialog v-model="type2Data.showHideOnBlur" class="dialog-demo" hide-on-blur>
                    <div class="img-box">
                        <div style=" margin: 10px auto;height:330px; text-align: center;position: relative;">
                            <div style="width: 100%;height: 40px; line-height: 40px;position: absolute;top: 0;background-color: white;">
                                <p>
                                    {{ type2Data.title }}节
                                    (

                                    <span @click="haveNoCourseFun(true)">{{ haveNoCourse ? '有课':'无课' }}</span>
                                    )
                                </p>
                            </div>
                            <div class="label-stu">
                                <label :class="{'sel-user': stu.id === selUserId}"
                                       v-for="(stu,index) in (courses.hasOwnProperty(type2Data.x) && courses[type2Data.x].hasOwnProperty(type2Data.y) && courses[type2Data.x][type2Data.y].hasOwnProperty('stus'))?courses[type2Data.x][type2Data.y].stus:[]"
                                       @click="courseInfo(stu.id, stu.courseInfo)">{{ stu.name }},</label>
                            </div>
                        </div>
                    </div>
                    <div @click="type2Data.showHideOnBlur=false">
                        <span class="vux-close"></span>
                    </div>
                </x-dialog>
            </div>
        </div>


        <div v-transfer-dom>
            <x-dialog v-model="selTeacher.showHideOnBlur" class="dialog-demo" hide-on-blur>
                <div class="img-box">
                    <div style=" margin: 10px auto;height:330px; text-align: center; ;position: relative;">
                        <div style="width: 100%;height: 40px; line-height: 40px;top: 0;position: absolute;background-color: white;">
                            <p>
                                人员选择<check-icon :value.sync="selTeacher.allType" @click.native="selAllStu">全选</check-icon>
                            </p>
                        </div>
                        <div style="padding-top: 40px;overflow: scroll;height: 300px;">
                            <checker
                                    v-model="selTeacher.teachers"
                                    type="checkbox"
                                    default-item-class="group-item"
                                    selected-item-class="group-item-selected"
                                    @on-change="selStuFun"
                            >
                                <checker-item v-for="(v,i) in get.teachers" :key="i" :value="i" class="group-item">{{ v.name }}</checker-item>
                            </checker>
                        </div>
                    </div>
                </div>
                <div @click="selTeacher.showHideOnBlur=false">
                    <span class="vux-close"></span>
                </div>
            </x-dialog>
        </div>

    </div>
</template>


<script>
    import {Carousel3d, Slide} from 'vue-carousel-3d';
    import {
        Popup,
        TransferDom,
        Checker,
        CheckerItem,
        Divider,
        Scroller,
        ToastPlugin,
        LoadingPlugin,
        XTable,
        XDialog,
        XButton,
        CheckIcon
    } from 'vux';

    Vue.use(ToastPlugin);
    Vue.use(LoadingPlugin);
    export default {
        directives: {
            TransferDom
        },
        components: {
            Carousel3d,
            Slide,
            Popup,
            TransferDom,
            Checker,
            CheckerItem,
            Divider,
            Scroller,
            ToastPlugin,
            XTable,
            XDialog,
            XButton,
            CheckIcon,
        },
        data() {
            return {
                get: {
                    nowWeek: 0,     //当前周
                    joblevels: [],  //职务级别
                    jobtypes: [],   //职务类型
                    staffrooms: [], //教研室
                    titles: [],     //职称
                    teachers: [],   //教师
                },
                set: {
                    weeks: [],          //一共有多少周
                    selJoblevels: [],   //选中的职务级别
                    selSexs: [],        //选中的性别
                    selJobtypes: [],    //选中的职务类型
                    selStaffrooms: [],  //选中的教研室
                    selTitles:[],       //选中的职称
                    selTeachers: [],    //选中的教师
                    selWeek: 1,         //选中的周
                },
                count: false,           //是否打开统计页面
                haveNoCourse: true,     //是否有课
                week_day: [
                    '星期一',
                    '星期二',
                    '星期三',
                    '星期四',
                    '星期五',
                    '星期六',
                    '星期日'
                ],
                courses: [],    //统计的课表人员信息
                haveCourses: [],    //有课人员信息表
                noCourses: [],      // 无课人员信息表
                test: true,
                colorIndex: [0, 1, 2, 3, 4, 5, 6],

                type2Data: {
                    showHideOnBlur: false,
                    title: '',
                    x: 0,
                    y: 0,
                },
                type: false,
                colors: [
                    {
                        title: '#1A9053',
                        content: '#26CDB2',
                    },
                    {
                        title: '#EF3C67',
                        content: '#FD9693',
                    },
                    {
                        title: '#794017',
                        content: '#FEC961',
                    },
                    {
                        title: '#1BAE9A',
                        content: '#91FBC1',
                    },
                    {
                        title: '#D13420',
                        content: '#FD8E6B',
                    },
                    {
                        title: '#82BD4B',
                        content: '#CAF3A3',
                    },
                    {
                        title: '#E23B5B',
                        content: '#FCC5CC',
                    },
                ],
                selTeacherId: 0,
                selTeacher:{
                    showHideOnBlur:false,
                    allType: false,
                    teachers:[],
                },
                selUserId: 0,
            }
        },
        methods: {
            //初始化信息
            info() {
                document.getElementsByTagName('html')[0].style.background = '#89E4DF';

                let maxWeek = 20; // 最大周
                this.$vux.loading.show({
                    text: '正在加载数据',
                });

                axios.get('/wechat/teacher/type').then(res => {
                    let data = res.data;
                    this.get.joblevels = data.joblevels;
                    this.get.jobtypes = data.jobtypes;
                    this.get.staffrooms = data.staffrooms;
                    this.get.titles = data.titles;
                });


                axios.get('/wechat/course/count').then(response => {
                    //设置数据
                    let data = response.data;
                    this.get.nowWeek = data.nowWeek;        //当前周
                    this.get.teachers = data.teachers;      //教师

                    // console.log(data.teachers);
                    if (this.get.nowWeek > 20) maxWeek = this.get.nowWeek;   //判断若用户大于20周，则以当前为最大
                    for (let i = 1; i <= maxWeek; i++) {
                        this.set.weeks.push({
                            id: i,
                            sel: false,
                        });
                    }
                    //返回当前周
                    this.setNowWeek();
                    //选中今天
                    let nowWeekDay = (new Date().getDay() + 6) % 7;
                    this.$children[2].goSlide(nowWeekDay);

                    this.getSelStudents();
                    this.$vux.loading.hide();

                });

                //初始化颜色板
                this.colorIndex.sort(this.randomsort)

            },

            //用户选择周数
            addSelWeek(id) {
                if (this.set.selWeel !== id) {
                    this.test = true;
                    this.set.weeks[this.set.selWeek - 1].sel = false;
                    this.set.selWeek = id;
                    this.set.weeks[id - 1].sel = true;
                    this.test = false;
                    this.getCourses();
                }
            },
            //返回当前周
            setNowWeek() {
                this.set.weeks[this.set.selWeek - 1].sel = false;
                this.set.selWeek = this.get.nowWeek;
                this.set.weeks[this.get.nowWeek - 1].sel = true;
                this.test = !this.test;

                let translate = (this.get.nowWeek - 3) * -40;
                translate = Math.min(0, translate);
                translate = Math.max(((this.set.weeks.length - 5) * -40), translate);
                document.getElementById('weeks').style.transform = 'translate(' + translate + 'px, 0)';
            },

            //获取当前选择的用户
            getSelStudents() {
                this.set.selTeachers = [];
                this.selTeacher.teachers = [];

                let all = this.get.teachers,
                    staffrooms = this.set.selStaffrooms,
                    joblevels = this.set.selJoblevels,
                    jobtypes = this.set.selJobtypes,
                    titles = this.set.selTitles,
                    sexs = this.set.selSexs;

                //当用户没有选择条件是默认为全部人员
                if (staffrooms.length === 0 && joblevels.length === 0 && sexs.length === 0 && jobtypes.length === 0 && titles.length === 0) {
                    for (let index in all) {
                        this.set.selTeachers.push({
                            id: index,
                            name: all[index].name,
                        });
                    }
                } else {
                    for (let index in all) {
                        if ((!staffrooms.length || staffrooms.indexOf(all[index].staffroom_id) !== -1) &&
                            (!sexs.length || sexs.indexOf(all[index].sex) !== -1) &&
                            (!joblevels.length || joblevels.indexOf(all[index].joblevel_id)!== -1) &&
                            (!jobtypes.length || jobtypes.indexOf(all[index].jobtype_id) !== -1) &&
                            (!titles.length || titles.indexOf(all[index].title_id) !== -1)){  //判断用户是否符合选择的条件

                            this.set.selTeachers.push({
                                id: index,
                                name: all[index].name,
                            });
                            this.selTeacher.teachers.push(index);
                        }
                    }
                }

                console.log(this.set.selTeachers);
                this.getCourses();
            },

            getCourses() {
                this.haveCourses = [];
                this.noCourses = [];
                for (let i = 0; i < 7; i++) {
                    this.haveCourses[i] = [
                        {id: '1-2', stus: [],},
                        {id: '3-4', stus: [],},
                        {id: '5', stus: [],},
                        {id: '6-7', stus: [],},
                        {id: '8-9', stus: [],},
                        {id: '10-11', stus: [],},
                        {id: '12', stus: [],},
                    ];

                    this.noCourses[i] = [
                        {id: '1-2', stus: [],},
                        {id: '3-4', stus: [],},
                        {id: '5', stus: [],},
                        {id: '6-7', stus: [],},
                        {id: '8-9', stus: [],},
                        {id: '10-11', stus: [],},
                        {id: '12', stus: [],},
                    ];
                }


                let all = this.get.teachers,        //所有用户信息
                    selTeachers = this.set.selTeachers,   //选中的用户信息
                    selWeek = this.set.selWeek;     //选中的周


                for (let index in selTeachers){
                    for (let courseIndex in all[selTeachers[index].id].courses){
                        let tmpCourse = all[selTeachers[index].id].courses[courseIndex];
                        if((tmpCourse.week_start <= selWeek && selWeek <= tmpCourse.week_end) && (tmpCourse.week_type === 0 || selWeek%2 === tmpCourse.week_type%2)){
                            for (let n = tmpCourse.section_start; n <= tmpCourse.section_end; n++){
                                let sectionNum = -1;
                                switch (n) {
                                    case 1:
                                        sectionNum = 0;
                                        break;
                                    case 3:
                                        sectionNum = 1;
                                        break;
                                    case 5:
                                        sectionNum = 2;
                                        break;
                                    case 6:
                                        sectionNum = 3;
                                        break;
                                    case 8:
                                        sectionNum = 4;
                                        break;
                                    case 10:
                                        sectionNum = 5;
                                        break;
                                    case 12:
                                        sectionNum = 6;
                                        break;
                                }
                                if(sectionNum !== -1){
                                    this.haveCourses[tmpCourse.week_day-1][sectionNum].stus.push({
                                        id: selTeachers[index].id,
                                        name: selTeachers[index].name,
                                        courseInfo: tmpCourse.name+'/'+tmpCourse.location,
                                    });
                                    if(!( sectionNum === 5 || sectionNum === 12))
                                        continue;
                                }
                            }
                        }
                    }
                }

                // console.log(this.haveCourses);

                // 获取用户无课人员信息

                for (let i = 0; i < 7; i++) {
                    for (let j = 0; j < 7; j++) {
                        let tmpTeachers2 = this.haveCourses[i][j].stus;

                        for (let index in selTeachers) {
                            let id = selTeachers[index].id;
                            let status = false;
                            for (let index2 in tmpTeachers2) {
                                if (id === tmpTeachers2[index2].id) {
                                    status = true;
                                    break;
                                }
                            }

                            if (!status) {
                                this.noCourses[i][j].stus.push({
                                    id: selTeachers[index].id,
                                    name: selTeachers[index].name,
                                    courseInfo: '',
                                });
                            }
                        }
                    }
                }



                this.haveNoCourseFun(false);
            },

            //点击用户显示课程
            courseInfo(id, courseInfo) {
                if (this.haveNoCourse) {
                    this.$vux.toast.text(courseInfo, 'top');
                }
                this.selUserId = id;
            },

            //随机排序
            randomsort(a, b) {
                return Math.random() > .5 ? -1 : 1; //通过随机产生0到1的数，然后判断是否大于0.5从而影响排序，产生随机性的效果。
            },

            //单击表格时
            selTd(i, j) {
                let weeks = ['周一', '周二', '周三', '周四', '周五', '周六', '周日'];
                this.type2Data.title = weeks[i] + this.courses[i][j].id;
                this.type2Data.x = i;
                this.type2Data.y = j;
                this.type2Data.showHideOnBlur = true;
            },

            //单击选择人员按钮
            selStuBut(){
                this.count = false;
                this.selTeacher.showHideOnBlur = true;
            },

            //选择人员
            selStuFun(){
                if(this.selTeacher.showHideOnBlur){
                    this.set.selTeachers = [];

                    for( let i in this.selTeacher.teachers){
                        let id = this.selTeacher.teachers[i];
                        this.set.selTeachers.push({
                            id: id,
                            name: this.get.teachers[id].name,
                        });
                    }
                    this.getCourses();
                }
            },

            selAllStu(){
                this.set.selTeachers = [];
                this.selTeacher.teachers = [];
                for( let i in this.get.teachers){
                    this.set.selTeachers.push({
                        id: i,
                        name: this.get.teachers[i].name,
                    });
                    if(this.selTeacher.allType)
                        this.selTeacher.teachers.push(i);
                }
                this.getCourses();
            },

            //有课，无课切换
            haveNoCourseFun(is){
                if(is) this.haveNoCourse = !this.haveNoCourse;
                this.courses = [];
                if(this.haveNoCourse){
                    for (let i = 0; i < this.haveCourses.length; i++){
                        this.courses.push(this.haveCourses[i]);
                    }
                }else{
                    for (let i = 0; i < this.noCourses.length; i++){
                        this.courses.push(this.noCourses[i]);
                    }
                }
            }
        },
        mounted() {

        },
        created(){
            this.info();
        },
        beforeCreate(){

        },
    }
</script>

<style>
    * {
        margin: 0;
        padding: 0;
    }
</style>

<style scoped>

    .week-day {
        border-radius: 25px;

    }

    .week-title {
        height: 40px;
        text-align: center;
        line-height: 40px;
    }

    .courseContent {
        overflow-y: scroll;
        width: 210px;
        height: 420px;
        margin: 10px auto;
    }

    .courseContent label {
        text-align: center;
    }

    .section > div:first-child {
        height: 50px;
    }

    .section > div:first-child label {
        padding: 8px;
        border-radius: 10px;
        margin: 10px auto;
        display: block;
        width: 50px;
        text-align: center;
    }

    .section > div:last-child {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        border-radius: 10px;
        /*border: 1px solid black;*/
    }

    .section > div:last-child label {
        display: block;
        width: 33.3%;
        float: left;

    }

    .section > div:last-child:after {
        display: block;
        content: '';
        clear: both;
    }

    .group-item {
        height: 26px;
        padding: 0 10px 0 10px;
        line-height: 26px;
        text-align: center;
        border-radius: 3px;
        border: 1px solid #ccc;
        background-color: #fff;
        margin: 5px;
        box-sizing: border-box;
    }

    .group-item-selected {
        background: #ffffff url(/images/wechat/sel.png) no-repeat right bottom;
        border-color: #ff4a00;
    }

    .box1-item {
        width: 35px;
        height: 100%;
        background-color: #FDCA62;
        display: inline-block;
        margin-left: 5px;
        float: left;
        text-align: center;
        line-height: 35px;
        border-radius: 5px;
    }

    .box1 {
        position: relative;

    }

    .now-week {
        background-color: #227B86;
        color: white;
    }

    .sel-week {
        color: #DB5061;
        background-color: #F6C1C3;
    }

    .week-day .week-title {
        color: white;

    }

    .week-day > .courseContent > div > div:first-child > label {
        color: white;

    }

    .week-day > .courseContent > div > div:last-child {
        box-shadow: #666 0px 0px 10px;
        background-color: #F9EAC7;
    }

    .main {
        background: url(/images/wechat/cloud.png) no-repeat;
        background-size: 100% 60px;
        background-position: 0 35px;
    }

</style>

<style lang="less" scoped>
    @import '~vux/src/styles/close';

    .dialog-demo {
        .weui-dialog {
            border-radius: 8px;
            padding-bottom: 8px;
        }
        .dialog-title {
            line-height: 30px;
            color: #666;
        }
        .img-box {
            height: 350px;
            overflow: hidden;
        }
        .vux-close {
            margin-top: 8px;
            margin-bottom: 8px;
        }
    }

</style>

<style scoped>
    .label-stu {
        width: 100%;
        padding-top: 40px;
        overflow: scroll;
        height: 300px;
    }

    .label-stu:after {
        display: block;
        content: '';
        clear: both;
    }

    .label-stu > label {
        float: left;
        display: block;
        width: 25%;
        overflow: hidden;
        height: 30px;
        line-height: 30px;
    }

    .sel-user {
        color: #289886;
    }
</style>