<template>
    <div>
        <div>
            <el-button type="primary" size="mini" @click="showTermDialog(1)">添加学期</el-button>
        </div>
        <el-table
                :data="terms"
                border
                style="width: 100%">
            <el-table-column
                    prop="name"
                    label="名称">
            </el-table-column>
            <el-table-column
                    prop="start_school"
                    label="开学时间">
            </el-table-column>
            <el-table-column
                    label="操作">
                <template slot-scope="scope">
                    <el-button-group>
                        <el-button type="primary" icon="el-icon-edit" size="mini" @click="showTermDialog(2, scope.row)"></el-button>
                        <el-button type="danger" icon="el-icon-delete" size="mini" @click="deleteTerm(scope.row.id)"></el-button>
                        <el-button type="success" icon="el-icon-check" size="mini" :disabled="scope.row.id === nowTermId" @click="setNowTerm(scope.row.id)"></el-button>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>

        <el-dialog title="添加学期" :visible.sync="termDialogStatus">
            <el-form :model="term">
                <el-form-item label="学期名称" label-width="180">
                    <el-input v-model="term.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="开学时间" label-width="180">
                    <!--<el-input v-model="term.start_school" auto-complete="off"></el-input>-->
                    <el-date-picker
                            v-model="term.start_school"
                            type="date"
                            placeholder="选择日期"
                            auto-complete="off"
                            style="width: 100%;">
                    </el-date-picker>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="termDialogStatus = false">取 消</el-button>
                <el-button type="primary" @click="save(termDialogType)">保 存</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "term",
        data(){
            return {
                terms:[],
                nowTermId: '',
                termDialogStatus: false,
                termDialogType: 1,
                term:{
                    id: '',
                    name: '',
                    start_school: ''
                },
            }
        },
        methods:{
            info(){
                axios.get('/admin/setting/terms').then(res => {
                    this.terms = res.data.data;
                    this.nowTermId = res.data.nowTermId;
                    console.log(this.terms);
                });
            },
            showTermDialog(type, term){
                this.termDialogType = type;
                if(type === 1){
                    this.term = {
                        id: '',
                        name: '',
                        start_school: ''
                    };
                }else{
                    this.term = term;
                }
                this.termDialogStatus = true;
            },
            save(type){
                let url = '/admin/setting/term/edit';
                if(type === 1)
                    url = '/admin/setting/term/create';
                axios.post(url, this.term).then(res => {
                    let data = res.data;
                    if(data.code === 1){
                        this.$message({
                            type: 'success',
                            message: '保存成功'
                        });
                        this.info();
                        this.termDialogStatus = false;
                    }else{
                        this.$message({
                            type: 'warning',
                            message: data.msg
                        });
                    }

                });
            },
            deleteTerm(id){
                this.$confirm('此操作将永久删除该学期和教师当前学期课表?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('/admin/setting/term/delete', {
                        id: id,
                    }).then(res => {

                        let data = res.data;
                        if(data.code === 1){
                            this.$message({
                                type: 'success',
                                message: '删除成功'
                            });
                            this.info();
                        }else{
                            this.$message({
                                type: 'warning',
                                message: data.msg
                            });
                        }
                    });
                });

            },
            setNowTerm(id){
                this.$confirm('确定要设置该学期为当前学期吗？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('/admin/setting/term/now/set', {
                        id: id
                    }).then(res => {
                        let data = res.data;
                        if(data.code === 1){
                            this.$message({
                                type: 'success',
                                message: '修改成功'
                            });
                            this.info();
                        }else{
                            this.$message({
                                type: 'warning',
                                message: data.msg
                            });
                        }
                    });
                });
            }
        },
        mounted(){
            this.info();
        }
    }
</script>

<style scoped>

</style>