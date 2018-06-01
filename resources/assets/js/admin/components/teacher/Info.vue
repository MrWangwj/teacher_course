<template>
    <div>
        <div>
            <el-row>
                <el-col :span="24">
                    <div class="grid-content bg-purple">
                        <el-button type="primary" size="mini" @click="addTeacherBut">添加</el-button>
                        <el-button type="primary" size="mini" @click="code = true">一键导课</el-button>
                        <el-button type="primary" size="mini" @click="showTeacher">显示所有老师</el-button>
                        <!--<el-button type="primary" size="mini">导入</el-button>-->
                        <!--<el-select v-model="value" placeholder="请选择" size="mini" class="">-->
                            <!--<el-option-->
                                    <!--v-for="item in options"-->
                                    <!--:key="item.value"-->
                                    <!--:label="item.label"-->
                                    <!--:value="item.value">-->
                            <!--</el-option>-->
                        <!--</el-select>-->
                        <div class="tercher-search">
                            <div id="teacher-name">
                                教师姓名：
                            </div>
                            <el-input
                                    placeholder="请输入教师姓名"
                                    prefix-icon="el-icon-search"
                                    v-model="teacherInformation"
                                    class="input-teacher">
                            </el-input>
                        </div>
                    </div>
                </el-col>
            </el-row>
            <div style="margin-top: 15px;">
                <el-table
                        :data="teachers"
                        border
                        style="width: 100%">
                    <el-table-column
                            prop="name"
                            label="姓名">
                    </el-table-column>

                    <el-table-column
                            prop="sex"
                            label="性别">
                    </el-table-column>

                    <el-table-column
                            prop="staffroom"
                            label="教研室">
                        <template slot-scope="scope">
                            {{ scope.row.staffroom.name }}
                        </template>
                    </el-table-column>

                    <el-table-column
                            prop="joblevel"
                            label="职务级别">
                        <template slot-scope="scope">
                            {{ scope.row.joblevel.name }}
                        </template>
                    </el-table-column>

                    <el-table-column
                            prop="title"
                            label="职称">
                        <template slot-scope="scope">
                            {{ scope.row.title.name }}
                        </template>
                    </el-table-column>

                    <el-table-column
                            prop="jobtype"
                            label="职务类型">
                        <template slot-scope="scope">
                            {{ scope.row.jobtype.name }}
                        </template>
                    </el-table-column>

                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button-group>
                                <el-button
                                        size="mini"
                                        type="primary"
                                        icon="el-icon-zoom-in"
                                        @click="seeCourseBut(scope.row.id)">
                                </el-button>

                                <el-button
                                        size="mini"
                                        type="primary"
                                        icon="el-icon-edit"
                                        @click="editTeacherBut(scope.$index, scope.row)">

                                </el-button>

                                <el-button
                                        size="mini"
                                        type="danger"
                                        icon="el-icon-delete"
                                        @click="deleteTeacherBut(scope.row.id)">
                                </el-button>
                            </el-button-group>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>


        <el-dialog :title="TeacherDialogType === 0 ? '添加用户':'编辑用户'" :visible.sync="TeacherDialog">
            <el-form :model="teacher">
                <el-form-item label="姓名" label-width="100px">
                    <el-input v-model="teacher.name" style="width: 194px;"></el-input>
                </el-form-item>
                <el-form-item label="性别" label-width="100px">
                    <el-radio v-model="teacher.sex" label="男">男</el-radio>
                    <el-radio v-model="teacher.sex" label="女">女</el-radio>
                </el-form-item>
                <el-form-item label="教研室" label-width="100px">
                    <el-select v-model="teacher.staffroom_id" placeholder="请选择">
                        <el-option
                                v-for="staffroom in data.staffrooms"
                                :key="staffroom.id"
                                :label="staffroom.name"
                                :value="staffroom.id">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="职务级别" label-width="100px">
                    <el-select v-model="teacher.joblevel_id" placeholder="请选择">
                        <el-option
                                v-for="joblevel in data.joblevels"
                                :key="joblevel.id"
                                :label="joblevel.name"
                                :value="joblevel.id">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="职称" label-width="100px">
                    <el-select v-model="teacher.title_id" placeholder="请选择">
                        <el-option
                                v-for="title in data.titles"
                                :key="title.id"
                                :label="title.name"
                                :value="title.id">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="职务类型" label-width="100px">
                    <el-select v-model="teacher.jobtype_id" placeholder="请选择">
                        <el-option
                                v-for="jobtype in data.jobtypes"
                                :key="jobtype.id"
                                :label="jobtype.name"
                                :value="jobtype.id">
                        </el-option>
                    </el-select>
                </el-form-item>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="TeacherDialog = false">取 消</el-button>
                <el-button type="primary" @click="save()">保存</el-button>
            </div>
        </el-dialog>
        <div class="block">
            <el-pagination
                    @current-change="handleCurrentChange"
                    :current-page.sync="currentPage4"
                    :page-size="8"
                    layout="total, prev, pager, next"
                    :total="pageTotal">
            </el-pagination>
        </div>

        <el-dialog title="请输入验证码" :visible.sync="code">
            <el-form ref="form" :model="form" label-width="80px">
                <img src="admin/teacher/course/verification" @click="changeUrl" id="code-img" alt="未加载">
                <div class="code-div">
                    <el-input v-model="form.code" class="code-input"></el-input>
                </div>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="code = false">取 消</el-button>
                <el-button type="primary" @click="courseImport">提 交</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                code:false,
                form:{
                    code:''
                },
                pageTotal:0,
                currentPage4: 0,
                presentPage:1,
                TeacherDialog: false,
                TeacherDialogType: 0,
                teacher: {
                    id: '',
                    name: '',
                    sex: '',
                    staffroom_id: '',
                    joblevel_id: '',
                    title_id: '',
                    jobtype_id: '',
                },
                data: {
                    staffrooms: [],
                    joblevels: [],
                    titles: [],
                    jobtypes: [],
                },
                teachers: [],
                teacherInformation: ''
            }
        },
        methods: {
            getType(){
                axios.get('/admin/teacher/type').then(res => {
                    this.data = res.data;
                });
            },
            getData(){

                axios.get('/admin/teacher/data').then(res => {
                    this.sendPage();
                });
            },

            addTeacherBut(){
                this.teacher ={
                    id: '',
                    name: '',
                    sex: '',
                    staffroom_id: '',
                    joblevel_id: '',
                    title_id: '',
                    jobtype_id: '',
                };
                this.TeacherDialogType = 0;
                this.TeacherDialog = true;
            },
            editTeacherBut(index, row){
                this.teacher ={
                    id: row.id,
                    name: row.name,
                    sex: row.sex,
                    staffroom_id: row.staffroom.id,
                    joblevel_id: row.joblevel.id,
                    title_id: row.title.id,
                    jobtype_id: row.jobtype.id,
                };

                this.TeacherDialogType = 1;
                this.TeacherDialog = true;
            },
            addTeacherFun(){
                axios.post('/admin/teacher/add',this.teacher).then(res => {
                    let data = res.data;
                    if(data.code === 1){
                        this.$message({
                            message: data.msg,
                            type: 'success'
                        });
                        this.TeacherDialog = false;
                        this.getData();
                    }else{
                        this.$message.error(data.msg);
                    }
                });
            },
            editTeacherFun(){
                axios.post('/admin/teacher/edit',this.teacher).then(res => {
                    let data = res.data;
                    if(data.code === 1){
                        this.$message({
                            message: data.msg,
                            type: 'success'
                        });
                        this.TeacherDialog = false;
                        this.getData();
                    }else{
                        this.$message.error(data.msg);
                    }
                });
            },
            save(){
                if(this.TeacherDialogType === 0)
                    this.addTeacherFun();
                else
                    this.editTeacherFun();

            },

            deleteTeacherBut(id){
                this.$confirm('确认要删除吗？')
                    .then(_ => {
                        axios.post('/admin/teacher/delete',{
                            id: id,
                        }).then(res => {
                            let data = res.data;
                            if(data.code === 1){
                                this.$message({
                                    message: data.msg,
                                    type: 'success'
                                });
                                this.TeacherDialog = false;
                                this.getData();
                            }else{
                                this.$message.error(data.msg);
                            }
                        });
                    })
                    .catch(_ => {

                    });
            },

            seeCourseBut(id){
                this.$router.push('/teacher/course/type/one/'+id);
            },

            handleCurrentChange(presentPage) {
                this.presentPage = presentPage;
                this.sendPage();
            },
            showTeacher() {
                this.teacherInformation = '';
                sessionStorage.setItem('name','');
                this.sendPage();
            },

            sendPage() {
                let this_ = this;
                let page = this_.presentPage;
                this_.currentPage4 = page;
                let url;
                if (sessionStorage.getItem('name')==null||sessionStorage.getItem('name')==""){
                    url = '/admin/teacher/data?page='+page;
                } else {
                    url = '/admin/teacher/search/name?page='+page;
                    this_.teacherInformation = sessionStorage.getItem('name');
                }
                axios.post(url,{
                    'name':this_.teacherInformation
                }).then(res=>{
                    let data = res.data;
                    this_.teachers = data.data;
                    this_.pageTotal = data.total;
                });
            },

            courseImport() {
                if (this.form.code != ''){
                    axios.post('/admin/teacher/teachers/import',{
                        'code':this.form.code,
                    }).then(res=>{
                        let data = res.data;
                        console.log(data);
                    });
                }

            },

            changeUrl(){
                document.getElementById("code-img").src="admin/teacher/course/verification?t="+new Date().getMilliseconds();
            }
        },
        mounted(){
            this.getType();
            this.getData();
        },
        watch:{
            teacherInformation: function () {
                let this_ = this;
                document.onkeydown = function (event) {
                    var e = event || window.event || arguments.callee.caller.arguments[0];
                    if (e.keyCode == 13){
                        this_.presentPage = 1;
                        sessionStorage.setItem('name',this_.teacherInformation);
                        this_.sendPage();
                    }
                }
            }
        }
    }
</script>

<style scoped>
    #teacher-name{
        width: 30%;
        display: inline-block;
    }
    .tercher-search{
        display: inline-block;
        width: 300px;
        margin-left: 20px;
    }
    .input-teacher{
        width: 68%;
    }
    .block{
        float: right;
        margin-top: 20px;
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