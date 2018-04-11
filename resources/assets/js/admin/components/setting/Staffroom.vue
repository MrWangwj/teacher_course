<template>
    <div>
        <div>
            <el-button type="primary" size="small" @click="add">添加教研室</el-button>
        </div>

        <el-table
                :data="staffrooms"
                border
                style="width: 100%">
            <el-table-column
                    prop="name"
                    label="教研室名称"
                    width="100">
            </el-table-column>
            <el-table-column
                    label="人员">
                <template slot-scope="scope">
                    <el-tag v-for="teacher in scope.row.teachers" :key="teacher.id" style="margin-right: 5px;">{{teacher.name}}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="操作"
                    width="200">
                <template slot-scope="scope">
                    <el-button-group>

                        <el-button type="primary" size="small" icon="el-icon-edit" @click="editBut(scope.$index)"></el-button>
                        <el-button type="primary" size="small" icon="el-icon-delete" @click="deleteFun(scope.row.id)"></el-button>

                    </el-button-group>
                    <el-button-group>
                        <el-button type="primary" size="small" icon="el-icon-arrow-up" @click="sorting(scope.$index, -1)"></el-button>
                        <el-button type="primary" size="small" icon="el-icon-arrow-down" @click="sorting(scope.$index, 1)"></el-button>
                    </el-button-group>
                </template>

            </el-table-column>
        </el-table>

        <el-dialog title="编辑" :visible.sync="editDialog">
            <el-form :model="staffroom"  :ref="staffroom">
                <el-form-item label="名称" :label-width="'60px'">
                    <el-input v-model="staffroom.name" auto-complete="off"></el-input>
                </el-form-item>

                <el-form-item label="人员" :label-width="'60px'">
                    <el-select
                            v-model="staffroom.teachers"
                            multiple
                            filterable
                            placeholder="请选择文章标签"
                            style="width: 100%;">
                        <el-option
                                v-for="item in teachers"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>


            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="editDialog = false">取 消</el-button>
                <el-button type="primary" @click="edit">保 存</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "staffroom",
        data(){
            return {
                staffrooms:[],
                editDialog: false,
                staffroom:{
                    id: '',
                    name:'',
                    teachers: []
                },
                teachers:[],
            }
        },
        methods:{
            info(){
                axios.get('/admin/setting/staffroom').then(res => {
                    this.staffrooms = res.data;
                });

                axios.get('/admin/setting/teacher/names').then(res => {
                    this.teachers = res.data;
                });
            },
            add(){
                this.$prompt('请输入教研室名称', '添加教研室', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    inputPattern: /\S{1,20}/,
                    inputErrorMessage: '请输入的内容不超过20个字符'
                }).then(({ value }) => {
                    axios.post('/admin/setting/staffroom/create', {
                        name: value
                    }).then(res => {
                        let data = res.data;
                        if(data.code === 1){
                            this.$message({
                                type: 'success',
                                message: '添加成功'
                            });
                            this.info();
                        }else{
                            this.$message({
                                type: 'warning',
                                message: data.msg
                            });
                        }
                    });


                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '取消输入'
                    });
                });
            },
            editBut(index){
                // console.log(this.$refs);
                this.staffroom.teachers = [];
                this.staffroom.id = this.staffrooms[index].id;
                this.staffroom.name = this.staffrooms[index].name;

                for (let id in this.staffrooms[index].teachers){
                    this.staffroom.teachers.push(this.staffrooms[index].teachers[id].id);
                }
                this.editDialog = true;
            },
            edit(){
                // console.log(this.staffroom);
                
                axios.post('/admin/setting/staffroom/edit', this.staffroom).then(res => {
                    let data = res.data;
                    // console.log(data);
                    if(data.code === 1){
                        this.$message({
                            type: 'success',
                            message: '修改成功'
                        });
                        this.editDialog = false;
                        this.info();
                    }else{
                        this.$message({
                            type: 'warning',
                            message: data.msg
                        });
                    }
                })
            },
            deleteFun(id){
                this.$confirm('将删除该分组并将人员分配到其他分组, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('/admin/setting/staffroom/delete',{
                        id: id
                    }).then(res => {
                        let data = res.data;
                        // console.log(data);
                        if(data.code === 1){
                            this.$message({
                                type: 'success',
                                message: '删除成功'
                            });
                            this.editDialog = false;
                            this.info();
                        }else{
                            this.$message({
                                type: 'warning',
                                message: data.msg
                            });
                        }
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });

            },
            sorting(index, direction){
                if(index+direction>=0 && index+direction < this.staffrooms.length){
                    axios.post('/admin/setting/staffroom/sorting', {
                        id1: this.staffrooms[index].id,
                        id2: this.staffrooms[index+direction].id
                    }).then(res => {
                        let data = res.data;
                        // console.log(data);
                        if(data.code === 1){
                            this.$message({
                                type: 'success',
                                message: '排序成功'
                            });
                            this.info();
                        }else{
                            this.$message({
                                type: 'warning',
                                message: data.msg
                            });
                        }
                    })
                }
            }
        },
        mounted(){
            this.info();
        }
    }
</script>

<style scoped>

</style>