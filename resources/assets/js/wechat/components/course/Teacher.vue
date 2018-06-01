<template>
    <div>
        <div class="title">
            <div class="weeken" @click="choice">第{{week}}周<i class="el-icon-arrow-down el-icon--right"></i></div>
            <div class="leave" @click="cancellation">
                <i class="el-icon-plus"></i>
            </div>
        </div>
        <transition name="el-zoom-in-top">
            <div class="show-week transition-box" v-show="!choiceCourse">
                <div class="modify">
                    本周
                    <br/>
                    ({{ this.weekT.weekTthis }}周)
                </div>
                <div class="weeks">
                    <div class="show-div">
                        <div class="everyweek" v-for="n in 25" @click="checked(n)">
                            第<span class="number-week">{{n}}</span>周
                        </div>
                    </div>
                </div>
            </div>

        </transition>

        <transition name="el-fade-in-linear">
            <div v-show="!plus" class="leave-div" @click="cancel">注销</div>
        </transition>
        <div class="course">
            <el-table
                    :data="userCourse"
                    :span-method="objectSpanMethod"
                    highlight-current-row
                    border
                    style="width: 100%;margin: auto"
                    class="course"
                    :render-header="rowStyle">
                <el-table-column
                        prop="section"
                        label=""
                        width="26"
                        style="font-size: 5px;text-align: center">
                </el-table-column>

                <el-table-column
                        label=""
                        width="55%">
                    <template slot-scope="scope">
                        <div v-for="course in scope.row.mon" class="week-clown" @click="editCourseFun(course.id)">
                            <div class="course-style color-back" v-if="course.course.name!=''">
                                {{ course.course.name }}
                                <br/>
                                <span class="class-week div-table">{{ course.course.week_start+'-'+course.course.week_end }}</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 1">单</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 2">双</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 0"></span>
                                {{ course.course.location }}
                            </div>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column
                        label=""
                        width="55%">
                    <template slot-scope="scope">
                        <div v-for="course in scope.row.tue" class="color-back week-clown" @click="editCourseFun(course.id)">
                            <div class="course-style color-back">
                                {{ course.course.name }}
                                <br/>
                                <span class="class-week div-table">{{ course.course.week_start+'-'+course.course.week_end }}</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 1">单</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 2">双</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 0"></span>
                                {{ course.course.location }}
                            </div>
                        </div>
                    </template>
                </el-table-column>


                <el-table-column
                        label=""
                        width="55%">
                    <template slot-scope="scope">
                        <div v-for="course in scope.row.wed" class="color-back week-clown" style="height: 120px;padding: 9px 1px" @click="editCourseFun(course.id)">
                            <div class="course-style color-back">
                                {{ course.course.name }}
                                <br/>
                                <span class="class-week div-table">{{ course.course.week_start+'-'+course.course.week_end }}</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 1">单</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 2">双</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 0"></span>
                                {{ course.course.location }}
                            </div>
                        </div>
                    </template>
                </el-table-column>


                <el-table-column
                        label=""
                        width="55%">
                    <template slot-scope="scope">
                        <div v-for="course in scope.row.thu" class="color-back week-clown" style="height: 120px;padding: 9px 1px" @click="editCourseFun(course.id)">
                            <div class="course-style color-back">
                                {{ course.course.name }}
                                <br/>
                                <span class="class-week div-table">{{ course.course.week_start+'-'+course.course.week_end }}</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 1">单</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 2">双</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 0"></span>
                                {{ course.course.location }}
                            </div>
                        </div>
                    </template>
                </el-table-column>


                <el-table-column
                        label=""
                        width="55%">
                    <template slot-scope="scope">
                        <div v-for="course in scope.row.fri" class="color-back week-clown" style="height: 120px;padding: 9px 1px" @click="editCourseFun(course.id)">
                            <div class="course-style color-back">
                                {{ course.course.name }}
                                <br/>
                                <span class="class-week div-table">{{ course.course.week_start+'-'+course.course.week_end }}</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 1">单</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 2">双</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 0"></span>
                                {{ course.course.location }}
                            </div>
                        </div>
                    </template>
                </el-table-column>


                <el-table-column
                        label=""
                        width="55%">
                    <template slot-scope="scope">
                        <div v-for="course in scope.row.sat" class="color-back week-clown" style="height: 120px;padding: 9px 1px" @click="editCourseFun(course.id)" >
                            <div class="course-style color-back">
                                {{ course.course.name }}
                                <br/>
                                <span class="class-week div-table">{{ course.course.week_start+'-'+course.course.week_end }}</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 1">单</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 2">双</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 0"></span>
                                {{ course.course.location }}
                            </div>
                        </div>
                    </template>
                </el-table-column>


                <el-table-column
                        label=""
                        width="55%">
                    <template slot-scope="scope">
                        <div v-for="course in scope.row.sun" class="color-back week-clown" style="height: 120px;padding: 9px 1px" @click="editCourseFun(course.id)">
                            <div class="course-style color-back">
                                {{ course.course.name }}
                                <br/>
                                <span class="class-week div-table">{{ course.course.week_start+'-'+course.course.week_end }}</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 1">单</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 2">双</span>
                                <span class="judge-weeks div-table" v-if="course.course.week_type === 0"></span>
                                {{ course.course.location }}
                            </div>
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <el-dialog
                :visible.sync="editCourseVisible"
                width="70%"
                :model="editCourse"
                :title="editCourse.name">
            <div>
                <span>教室:&nbsp;&nbsp;&nbsp;</span><span>{{ editCourse.location }}</span>
            </div>
            <div>
                <span>
                    单双周:&nbsp;&nbsp;&nbsp;
                </span>
                <span v-if="editCourse.week_type === 1">单周</span>
                <span v-if="editCourse.week_type === 2">双周</span>
                <span v-if="editCourse.week_type === 0">全周</span>
            </div>
            <div>
                <span>周数:&nbsp;&nbsp;&nbsp;</span><span>第{{ editCourse.week_start }}-{{ editCourse.week_end }}周</span>
            </div>
            <div>
                <span>节数:&nbsp;&nbsp;&nbsp;</span><span>第{{ editCourse.section_start }}-{{ editCourse.section_end }}节</span>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                weekT:{
                    weekTthis:'',
                    weekB:true
                },
                startIterm:'',
                months:'',
                week:1,
                plus:true,
                circle:[],
                choiceCourse:true,
                circleJudge:true,
                userCourse:[],
                teacherName:'',
                courseInfos:[],
                editCourse: {
                    id: 0,
                    name: '',
                    location: '',
                    week_day: 1,
                    week_start: 1,
                    week_type: 0,
                    week_end: 1,
                    section_start: 1,
                    section_end: '',
                },
                editCourseVisible: false,
                formInline: {
                    user:''
                }
            }
        },
        methods: {
            teacherNames() {
                let name = sessionStorage.getItem('wechat');
                if (name == ''){
                    window.location = "/wechat/login";
                }else{
                    this.teacherName = name;
                    this.formInline.user=name;
                }
            },
            info(){
                this.userCourse = [];
                let sectionsName = ['1', '2', '3', '4', '5', '6', '7' ,'8' ,'9' , '10','11','12'];
                for(let i = 0; i < sectionsName.length; i++){
                    this.userCourse[i] = {
                        section: sectionsName[i],
                        mon: [],
                        tue: [],
                        wed: [],
                        thu: [],
                        fri: [],
                        sat: [],
                        sun: []
                    }
                }

                let weekDay = ['','mon','tue','wed','thu','fri','sat','sun'];
                for(let i in this.courseInfos){
                    let course = {
                        id:i,
                        course: this.courseInfos[i],
                    };
                    for (let j = course.course.section_start; j <= course.course.section_end; j++){
                        switch (j) {
                            case 1:{
                                this.userCourse[0][weekDay[course.course.week_day]].push(course);
                                continue;
                                break;
                            }
                            case 3:{
                                this.userCourse[2][weekDay[course.course.week_day]].push(course);
                                continue;
                                break;
                            }
                            case 5:{
                                this.userCourse[4][weekDay[course.course.week_day]].push(course);
                                break;
                            }
                            case 6:{
                                this.userCourse[5][weekDay[course.course.week_day]].push(course);
                                continue;
                                break;
                            }
                            case 8:{
                                this.userCourse[7][weekDay[course.course.week_day]].push(course);
                                continue;
                                break;
                            }
                            case 10:{
                                this.userCourse[9][weekDay[course.course.week_day]].push(course);
                                continue;
                                break;
                            }
                            case 12:{
                                this.userCourse[11][weekDay[course.course.week_day]].push(course);
                                break;
                            }
                        }
                    }
                }
                this.color();
            },

            getCourse () {
                axios.post('/wechat/show/course',{
                    teacher:this.teacherName
                }).then(response=>{
                    this.courseInfos = response.data[0];
                    this.startIterm = response.data[1];
                    this.judge();
                });
            },
            rowStyle({ row, rowIndex}){
                if (rowIndex === 0) {
                    return 'color:green'
                } else {
                    return ''
                }
            },
            color(weekChoice) {
                weekChoice = this.week || 1;
                let number = this.courseInfos.length;
                let this_ =this;
                setTimeout(function () {
                    this_.changeDate(weekChoice);
                    let week = new Array();
                    let divs = $(".color-back");
                    for (let j = 0 ; j < divs.length ; j++){
                        let weekNumber = $(divs[j]).find('.class-week').html();
                        let weekId = $(divs[j]).find('.judge-weeks').html();
                        if (weekId == ""){
                            weekId = 0;
                        } else if (weekId == "单") {
                            weekId = 1;
                        } else if (weekId == "双"){
                            weekId = 2;
                        }
                        week[j] =[weekNumber.split('-')[0],weekNumber.split('-')[1],weekId]
                    }
                    let color = new Array("#6A5ACD","#CD5C5C","#9370DB","#FFA500","#EE00EE","#1874CD","#228B22");
                    for (let i = 0 ; i < divs.length ; i++){
                        if (weekChoice >= week[i][0]&& weekChoice <= week[i][1]){
                            if (week[i][2] == 0){
                                $(divs[i]).parents(".cell").css("background-color",color[this_.ran("0","6")]);
                            } else if (week[i][2] == 1&& weekChoice%2==1){
                                $(divs[i]).parents(".cell").css("background-color",color[this_.ran("0","6")]);
                            } else if (week[i][2] == 2&& weekChoice%2==0){
                                $(divs[i]).parents(".cell").css("background-color",color[this_.ran("0","6")]);
                            }else {
                                $(divs[i]).parents(".cell").css("background-color","#A9A9A9");
                            }
                        }else {
                            $(divs[i]).parents(".cell").css("background-color","#A9A9A9");
                        }

                    }
                },140*number);
            },
            ran(n, m){
                var random = Math.floor(Math.random()*(m-n+1)+n);
                return random;
            },
            editCourseFun(id){
                this.editCourse = this.courseInfos[id];
                this.editCourseVisible = true;
            },
            cancellation(){
                if (this.plus){
                    $(".leave").empty();
                    $(".leave").append('<i class="el-icon-close"></i>');
                    this.plus = !this.plus;
                } else {
                    $(".leave").empty();
                    $(".leave").append('<i class="el-icon-plus"></i>');
                    this.plus = !this.plus;
                }

            },
            cancel(){
                axios.post("/wechat/leave/course").then(response=>{
                    window.location = "/wechat/login";
                });
            },
            choice(){
                if (this.choiceCourse){
                    $("i").removeClass("el-icon-arrow-down");
                    $(".weeken i").attr("class","el-icon-arrow-up");
                    this.choiceCourse = !this.choiceCourse;
                }else {
                    $("i").removeClass("el-icon-arrow-up");
                    $(".weeken i").attr("class","el-icon-arrow-down");
                    this.choiceCourse = !this.choiceCourse;
                }
            },

            checked(n){
                this.week = n;
                $(".everyweek").removeClass('everyweek-chicked');
                $($(".everyweek")[n-1]).addClass('everyweek-chicked');
                this.circleJudge = false;
                this.color(n);
                let dateDs = new Date();
                let number = dateDs.getDay();
                if (number ==0 ){
                    number = 7;
                }
                if (n!=this.weekT.weekTthis){
                    $($(".cell")[number]).parents('th').attr('class','other-color');
                } else {
                    $($(".cell")[number]).parents('th').attr('class','week-color');
                }
            },

            judge(){
                let date = this.startIterm;
                let regexp = /[0-9]+/g ;
                let dateArray = date.match(regexp);
                let tatolDays;
                let dateD = new Date();
                if (dateArray == null){
                    return ;
                }
                tatolDays =this.monthonsDays(dateArray[1])- dateArray[2]+1;

                let month = dateD.getMonth();

                for (let i = dateArray[1]; i <month ; i++ ){
                    tatolDays += this.monthonsDays(parseInt(i)+1);
                }

                tatolDays = tatolDays+dateD.getDate();
                let n =parseInt(tatolDays/7)+1;
                let dateDs = new Date();
                if (this.circleJudge){
                    $($(".everyweek")[n-1]).addClass('everyweek-chicked');
                    this.week = n;
                    if (this.weekT.weekB){
                        this.weekT.weekTthis = n;
                        this.weekT.weekB = !this.weekT.weekB;
                    }

                }
                let number = dateDs.getDay();
                if (number ==0 ){
                    number = 7;
                }
                $($(".cell")[number]).parents('th').attr('class','week-color');
            },

            tableHead(month,mon,tue,wed,thu,fri,sat,sun) {
                if (month!=""){
                    $($(".cell")[0]).html(month);
                }
                if (mon!=""){
                    $($(".cell")[1]).html(mon);
                }
                if (tue!=""){
                    $($(".cell")[2]).html(tue);
                }
                if (wed!=""){
                    $($(".cell")[3]).html(wed);
                }
                if (thu!=""){
                    $($(".cell")[4]).html(thu);
                }
                if (fri!=""){
                    $($(".cell")[5]).html(fri);
                }
                if (sat!=""){
                    $($(".cell")[6]).html(sat);
                }
                if (sun!=""){
                    $($(".cell")[7]).html(sun);
                }
            },

            changeDate(n) {
                let date = this.startIterm;
                let regexp = /[0-9]+/g ;
                let dateArray = date.match(regexp);
                let fullday = n * 7;
                if (dateArray==null){
                    return ;
                }
                let day = this.monthonsDays(dateArray[1])-dateArray[2]+1;
                let dates = new Date();
                let newMonths;
                let week = new Array();
                if (fullday<= day){
                    for (let j = 0 ; j < 7 ; j++){
                        if (fullday<=(this.monthonsDays(dateArray[1])-dateArray[2]+1)){  //加入天数在2月8日后再三月之前
                            newMonths = dateArray[1];
                            week[j] = (parseInt(dateArray[2])+fullday)-7+j;
                        }
                    }
                } else {
                    for (let i = parseInt(dateArray[1]) ; i < 12 ; i++){
                        if ((day<fullday)){                     //天数不在二月的
                            fullday = fullday-day;
                            day = this.monthonsDays(parseInt(i)+1);
                        }else {
                            newMonths = parseInt(i);
                            if (fullday<7){
                                let lastDay = this.monthonsDays(parseInt(i-1));
                                for (let j = 0 ; j < 7 ; j++){
                                    if (j<7-fullday){           //加入天数在2月8日后再三月之前
                                        week[j] = lastDay-(7-fullday)+j+1;
                                    }else {                                                         //不在二月份
                                        week[j] = j-(7-fullday)+1;
                                    }
                                }
                                break;
                            } else {
                                for (let j = 0 ; j < 7 ; j++) {
                                    week[j] = fullday-6+j;
                                }
                                break;
                            }
                        }
                    }

                }
                if (n==this.weekT.weekTthis){
                    newMonths = dates.getMonth()+1;
                }
                this.tableHead(newMonths+"月","周一</br>"+week[0]+"日","周二</br>"+week[1]+"日","周三</br>"+week[2]+"日","周四</br>"+week[3]+"日","周五</br>"+week[4]+"日","周六</br>"+week[5]+"日","周日</br>"+week[6]+"日");
            },

            monthonsDays (month) {
                if (month == 1||month == 3||month == 5||month == 7||month == 8||month == 10||month == 12) {
                    return 31;
                }else if (month == 4||month ==6||month ==9||month==11){
                    return 30
                } else {
                    let year = new Date();
                    if (year.getFullYear()%400==0||(year.getFullYear()%100 != 0&& year.getFullYear()%4==0)){
                        return 29;
                    } else {
                        return 28;
                    }
                }
            },

            objectSpanMethod({ row, column, rowIndex, columnIndex }) {
                if (rowIndex === (row.section-1)&&(rowIndex !== 4)&&(rowIndex !== 11)){
                    if (row.mon.length>0){
                        if (columnIndex === 1){
                            return [2, 1];
                        }else if (columnIndex === 1) {
                            return [0, 0];
                        }
                    }
                    if (row.tue.length>0){
                        if (columnIndex === 2){
                            return [2, 1];
                        }else if (columnIndex === 2) {
                            return [0, 0];
                        }
                    }
                    if (row.wed.length>0){
                        if (columnIndex === 3){
                            return [2, 1];
                        }else if (columnIndex === 3) {
                            return [0, 0];
                        }
                    }
                    if (row.thu.length>0){
                        if (columnIndex === 4){
                            return [2, 1];
                        }else if (columnIndex === 4) {
                            return [0, 0];
                        }
                    }
                    if (row.fri.length>0){
                        if (columnIndex === 5){
                            return [2, 1];
                        }else if (columnIndex === 5) {
                            return [0, 0];
                        }
                    }
                    if (row.sat.length>0){
                        if (columnIndex === 6){
                            return [2, 1];
                        }else if (columnIndex === 6) {
                            return [0, 0];
                        }
                    }
                    if (row.sun.length>0){
                        if (columnIndex === 7){
                            return [2, 1];
                        }else if (columnIndex === 7) {
                            return [0, 0];
                        }
                    }
                }
                if ((row.section === 5)||((row.section === 12))){
                    if (row.mon.length>0){
                        if (rowIndex=2||rowIndex==9){
                            if (columnIndex === 1){
                                return [3, 1];
                            }else if (columnIndex === 1) {
                                return [0, 0];
                            }
                        }

                    }
                    if (row.tue.length>0){
                        if (rowIndex=2||rowIndex==9){
                            if (columnIndex === 2){
                                return [3, 1];
                            }else if (columnIndex === 2) {
                                return [0, 0];
                            }
                        }
                    }
                    if (row.wed.length>0){
                        if (rowIndex=2||rowIndex==9){
                            if (columnIndex === 3){
                                return [3, 1];
                            }else if (columnIndex === 3) {
                                return [0, 0];
                            }
                        }
                    }
                    if (row.thu.length>0){
                        if (rowIndex=2||rowIndex==9){
                            if (columnIndex === 4){
                                return [3, 1];
                            }else if (columnIndex === 4) {
                                return [0, 0];
                            }
                        }
                    }
                    if (row.fri.length>0){
                        if (rowIndex=2||rowIndex==9){
                            if (columnIndex === 5){
                                return [3, 1];
                            }else if (columnIndex === 5) {
                                return [0, 0];
                            }
                        }
                    }
                    if (row.sat.length>0){
                        if (rowIndex=2||rowIndex==9){
                            if (columnIndex === 6){
                                return [3, 1];
                            }else if (columnIndex === 6) {
                                return [0, 0];
                            }
                        }
                    }
                    if (row.sun.length>0){
                        if (rowIndex=2||rowIndex==9){
                            if (columnIndex === 7){
                                return [3, 1];
                            }else if (columnIndex === 7) {
                                return [0, 0];
                            }
                        }
                    }
                }

            }
        },
        mounted() {
            this.teacherNames();
            this.info();
            this.getCourse();


        },
        watch:{
            courseInfos:{
                handler(curVal,oldVal){
                    this.info();
                },
                deep:true
            }
        },

    }
</script>

<style>
    body{
        padding: 0px;
        margin: 0px;
    }
    .title{
        width: 100%;
        height: 54px;
        text-align: center;
        font-size: 16px;
        font-family: 'Source Sans Pro', 'Helvetica Neue', Arial, sans-serif;
        line-height: 54px;
        color: dodgerblue;
        font-weight: bolder;
        background-color: #ffffff;
    }
    .course{
        width: 100%;
        font-size: 11px;
    }
    table{
        margin: auto;
    }
    .course label {
        color: #99a9bf;
        background-color: #636b6f;
    }
    .el-table th{
        /*background-color:#f4f4f4 !important;*/
    }
    .course-style{
        width: 100%;
        height: 94%;
        overflow: hidden;
    }
    .cell{
        overflow: hidden !important;
    }

    .el-table__body tr{
        color:#ffffff;
        height:50px;
    }

    .el-table__body tr td:first-of-type{
        background-color: #f4f4f4 !important;
        color: #909399;
    }
    .table-fixed .el-table__fixed-right{
        height: 100% !important;
    }
    .course{
        background-color: #f4f4f4 !important;
    }
    .el-table__body, .el-table__footer, .el-table__header{
        background-image: url("../../../../img/key.jpg");
    }
    .el-table th, .el-table tr{
        background-color: rgba(0,0,4,0);
    }
    .search-teacher{
        padding-left: 9px;
    }
    .first-column{
        font-size: 7px;
    }
    .el-table--border td{
        border: 0px solid #ffffff;
    }
    .el-table td{
        border-bottom: 1px dotted #35394a;
        /*background-color: #35394a;*/
    }

    .el-table td:first-of-type{
        border: 0px solid #ffffff;
        border-radius: 0px;
    }
    .leave{
        display: inline-block;
        width: 59px;
        text-align: center;
        height: 100%;
        line-height: 54px;
        float: right;
        font-size: 24px;
    }
    .leave-div{
        position: absolute;
        right: 1px;
        top: 54px;
        width: 61px;
        height: 42px;
        background-color: #ffffff;
        z-index: 999;
        line-height: 39px;
        font-size: 14px;
        text-align: center;
    }
    .weeken{
        width: 117px;
        margin: auto;
        display: inline-block;
        text-align: right;
    }
    .show-week{
        width: 100%;
        height: 68px;
        background-color: #E1FFFF;
    }
    .modify{
        width: 80px;
        height: 78%;
        text-align: center;
        color: #53868B;
        font-size: 14px;
        background-color: #BBFFFF;
        padding-top: 17px;
        float: left;
    }
    .weeks{
        float: right;
        width: 326px;
        height: 100%;
        /*position: relative;*/
        /*overflow: auto;*/
        overflow-y:scroll;
    }
    .show-div{
        height: 100%;
        width: 1750px;
        overflow: auto;
        /*left: 0px;*/
        /*position: absolute;*/
    }
    .everyweek{
        margin-top: 4px;
        background-color: #E1FFFF;
        width: 60px;
        height: 89%;
        text-align: center;
        font-size: 11px;
        color: #53868B;
        float: left;
        margin-right: 9px;
        line-height: 59px;
    }

    .number-week{
        font-size: 19px;
    }

    .everyweek-chicked{
        background-color: #ffffff;
        border-radius: 4px;
    }

    ::-webkit-scrollbar{
        display:none;
    }
    .el-table th{
        color: #35394a;
        font-weight: bolder;
    }

    .el-table tr{
        height: 50px;
    }
    .el-table__body, .el-table__footer, .el-table__header{
        table-layout: auto;
    }
    .el-table__body tr.current-row>td{
        background-color: rgba(0,0,0,0);
    }
    .el-table--enable-row-hover .el-table__body tr:hover>td{
        background-color: rgba(0,0,0,0);
    }
    .el-table .cell{
        padding: 0px;
        width: 96%;
        margin: auto;
        border-radius: 6px;
    }

    .el-table td{
        text-align: center;
        padding: 0px;
    }
    /*.el-table--border td:first-child .cell{*/
        /*padding: 10px;*/
    /*}*/
    .el-table--border td:first-child .cell{
        padding: 0px;
    }
    .el-table th>.cell{
        text-align: center;
    }
    .el-table td{
        height: 61px;
    }
    .div-table{
        display: none;
    }
    .week-clown{
        height: 120px;
        padding: 9px 1px;
        text-align: center;
    }
    .week-color{
        background-color: mediumturquoise !important;
    }
    .other-color{
        background-color: #f4f4f4 !important;
    }
    .el-table__header tr:first-of-type{
        background-color:#f4f4f4;
    }
</style>