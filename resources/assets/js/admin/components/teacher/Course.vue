<template>
    <div >
        <div style="margin-top: 20px;">
            <label >用户：</label>
            <span>{{ teacher.name }}</span>
        </div>

        <div>
            <div>
                <el-button v-if="$route.path === '/teacher/course/type/two/'+$route.params.id" type="primary" @click="changeType('/teacher/course/type/one/'+$route.params.id)">格式一</el-button>
                <el-button v-if="$route.path === '/teacher/course/type/one/'+$route.params.id || $route.path === '/teacher/course'" type="primary" @click="changeType('/teacher/course/type/two/'+$route.params.id)">格式二</el-button>
                <el-button type="primary" @click="addCourseVisible = true">添加课程</el-button>
                <el-button type="primary" @click="clearAllCourse">清除所有课表</el-button>
                <el-button type="primary" @click="code = true">一键导入</el-button>
            </div>
        </div>


        <router-view @courseInfo="userInfo" :userId="userId" :courseInfos="courseInfos">

        </router-view>

        <el-dialog title="添加课程" :visible.sync="addCourseVisible" class="dialog-width">
            <el-form :model="newCourse" label-width="80px" :rules="rules" ref="newCourse" style="width: 460px;" >
                <el-form-item label="课程名称" prop="name">
                    <el-input v-model="newCourse.name">

                    </el-input>
                </el-form-item>
                <el-form-item label="地点" prop="location">
                    <el-input v-model="newCourse.location">

                    </el-input>
                </el-form-item>

                <el-form-item label="星期" prop="week_day">
                    <el-select v-model="newCourse.week_day" placeholder="请选择" style="width: 100%;">
                        <el-option :key="1" label="星期一" :value="1">

                        </el-option>
                        <el-option :key="2" label="星期二" :value="2">

                        </el-option>
                        <el-option :key="3" label="星期三" :value="3">

                        </el-option>
                        <el-option :key="4" label="星期四" :value="4">

                        </el-option>
                        <el-option :key="5" label="星期五" :value="5">

                        </el-option>
                        <el-option :key="6" label="星期六" :value="6">

                        </el-option>
                        <el-option :key="7" label="星期日" :value="7">

                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="节数">
                    <el-col :span="11">
                        <el-form-item prop="start_section">
                            <el-select v-model="newCourse.start_section"  placeholder="开始"  @change="startSectionChange(0)">
                                <el-option :key="1" label="第1节" :value="1"></el-option>
                                <el-option :key="3" label="第3节" :value="3"></el-option>
                                <el-option :key="6" label="第6节" :value="6"></el-option>
                                <el-option :key="8" label="第8节" :value="8"></el-option>
                                <el-option :key="10" label="第10节" :value="10"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="2" style="text-align: center;">—</el-col>
                    <el-col :span="11">
                        <el-form-item prop="end_section">
                            <el-select v-model="newCourse.end_section" placeholder="结束">
                                <el-option v-for="section in end_sections"
                                           :key="section"
                                           :label="'第'+section+'节'"
                                           :value="section" v-if="section >= newCourse.start_section">

                                </el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-form-item>

                <el-form-item label="周数">
                    <el-col :span="6">
                        <el-form-item prop="start_week">
                            <el-select v-model="newCourse.start_week" placeholder="开始" @change="startWeekChange(0)">
                                <el-option v-for="n in 20" :key="n" :label="'第'+n+'周'" :value="n">

                                </el-option>
                            </el-select>
                        </el-form-item>

                    </el-col>
                    <el-col :span="2" style="text-align: center;">—</el-col>
                    <el-col :span="6">
                        <el-form-item prop="end_week">
                            <el-select v-model="newCourse.end_week" placeholder="结束">
                                <el-option v-for="week in end_weeks"
                                           :key="week"
                                           :label="'第'+week+'周'"
                                           :value="week" v-if="week >= newCourse.start_week">

                                </el-option>
                            </el-select>
                        </el-form-item>

                    </el-col>
                    <el-col :span="4" style="text-align: right;padding-right: 3px;">类型:</el-col>
                    <el-col :span="6">
                        <el-form-item prop="status">
                            <el-select v-model="newCourse.status" placeholder="类型">
                                <el-option key="0" label="全部" :value="0">

                                </el-option>
                                <el-option key="1" label="单周" :value="1">

                                </el-option>
                                <el-option key="2" label="双周" :value="2">

                                </el-option>
                            </el-select>
                        </el-form-item>

                    </el-col>
                </el-form-item>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="addCourseVisible = false">取 消</el-button>
                <el-button @click="resetForm('newCourse')">重 置</el-button>
                <el-button type="primary" @click="addSubmit('newCourse')">添 加</el-button>
            </div>
        </el-dialog>
        <el-dialog title="请输入验证码" :visible.sync="code">
            <el-form ref="form" :model="form" label-width="80px">
                <img src="admin/teacher/course/verification" @click="changeUrl" id="code-img" alt="未加载">
                <div class="code-div">
                    <el-input v-model="form.code" class="code-input"></el-input>
                </div>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="code = false">取 消</el-button>
                <el-button type="primary" @click="importCourse">提 交</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>


    export default {

        data() {
            return {
                userId: 0,
                teacher:{
                    name:'',
                },

                addCourseVisible: false,
                code:false,

                courseInfos: [],
                newCourse: {
                    name: '',
                    location: '',
                    week_day:1,
                    start_week:1,
                    end_week:1,
                    status: 0,
                    start_section: 1,
                    end_section: '',
                },

                form:{
                    code:''
                },

                end_weeks: [
                    1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,
                ],
                end_sections: [
                    2,4,5,7,9,11,12
                ],




                rules: {
                    name: [
                        { required: true, message: '请输入课程名称', trigger: 'blur' },
                        { max: 50, message: '长度在50个字符内', trigger: 'blur' }
                    ],
                    location: [
                        { required: true, message: '请输入上课地点', trigger: 'blur' },
                        { max: 50, message: '长度在50个字符内', trigger: 'blur' }
                    ],
                    week_day: [
                        { required: true, message: '请选择星期' }
                    ],
                    start_week: [
                        { required: true, message: '请选择开始周' }
                    ],
                    end_week: [
                        { required: true, message: '请选择结束周' }
                    ],
                    start_section: [
                        { required: true, message: '请选择开始节数' }
                    ],
                    end_section: [
                        { required: true, message: '请选择结束节数' }
                    ],
                    status: [
                        { required: true, message: '请选择周类型' }
                    ]
                },

            }
        },
        methods: {
            userInfo(){
                this.userId = this.$route.params.id;
                axios.post('/admin/teacher/course/get', {
                    id: this.userId,
                }).then(res => {
                    let data = res.data;
                    if(data.code === 1){
                        this.teacher = data.data;
                        this.courseInfos = this.teacher.courses;
                        console.log(data);
                    }else{
                        this.$message.error(data.msg);
                    }
                });
            },
            changeType(url) {
                this.$router.push(url);
                // this.userInfo();
            },

            startSectionChange(type){

                if(type === 0){
                    if(this.newCourse.end_section < this.newCourse.start_section) this.newCourse.end_section = '';
                }else{
                    if(this.editCourse.end_section < this.editCourse.start_section) this.editCourse.end_section = '';
                }


            },

            startWeekChange(type){
                if(type === 0){
                    if(this.newCourse.end_week < this.newCourse.start_week) this.newCourse.end_week = '';
                }else{
                    if(this.editCourse.end_week < this.editCourse.start_week) this.editCourse.end_week = '';
                }
            },

            resetForm(formName) {
                this.$refs[formName].resetFields();
            },

            addSubmit(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        axios.post('/admin/teacher/course/add',{
                            id:  this.userId,
                            name:       this.newCourse.name,
                            location:   this.newCourse.location,
                            week_day:   this.newCourse.week_day,
                            week_start: this.newCourse.start_week,
                            week_end:   this.newCourse.end_week,
                            week_type:     this.newCourse.status,
                            section_start:  this.newCourse.start_section,
                            section_end:    this.newCourse.end_section,
                        }).then(response => {
//                            console.log(response.data);
                            let data = response.data;
                            if(parseInt(data.code) === 0){
                                this.$message.error(data.msg);
                            }else{
                                this.$message({
                                    message: '添加成功',
                                    type: 'success'
                                });

                                this.userInfo();
                                this.addCourseVisible = false;
                                this.$refs[formName].resetFields();
                            }
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },


            clearAllCourse(){
                this.$confirm('此操作将清除所有课程, 是否继续?', '提示', {
                    confirmButtonText: '继续',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('/admin/teacher/course/clear',{
                        'id': this.userId,
                    }).then(response => {
//                            console.log(response.data);
                        let data = response.data;
                        if(parseInt(data.code) === 0){
                            this.$message.error(data.msg);
                        }else{
                            this.$message({
                                message: '清除成功',
                                type: 'success'
                            });
                            this.userInfo();
                        }
                    });
                });
            },
            importCourse(){
                // const id = this.$route.params.id;
                // console.log(this.form.code);
                if (this.form.code != ""){
                    axios.post('/admin/teacher/code',{
                        'code':this.form.code,
                        'id':this.$route.params.id
                    }).then(response=>{
                        let data = response.data;
                        console.log(data);
                        if(data.code == -2||data.code == 1){
                            // this.changeUrl();
                            this.$message({
                                type: 'warm',
                                message: data.msg
                            });

                        }else if (data.code == 3){
                            this.$message({
                                type: 'success',
                                message: '添加课表成功'
                            });
                            this.courseInfos = data.msg;
                            this.userId = data.data;
                        }
                    });
                }else {
                    this.$message({
                        type: 'warm',
                        message: '您未输入验证码！'
                    });
                }
            },
            changeUrl(){
                document.getElementById("code-img").src="admin/teacher/course/verification?t="+new Date().getMilliseconds();
            }
        },

        mounted(){
            this.userInfo();
        }

    }
</script>

<style>
    .code-div{
        width: 50%;
        display: inline-block;
    }
    .dialog-width>div{
        min-width: 520px;
    }
    .code-div{
        width: 100%;
    }
    #code-img{
        display: inline-block;
        width: 92px;
        height: 42px;
    }
    .code-div{
        display: inline-block;
        width: 76%;
        float: right;
        margin: 1px 20px;
    }
</style>