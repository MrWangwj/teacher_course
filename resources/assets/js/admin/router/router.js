import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        {
            path: '/',
            component: resolve => void(require(['../components/Main.vue'], resolve)),
        },

        {
            path: '/teacher/info',
            component: resolve => void(require(['../components/teacher/Info.vue'], resolve)),
        },

        {
            path: '/teacher/course',
            component: resolve => void(require(['../components/teacher/Course.vue'], resolve)),
            children: [
                {
                    path: 'type/one/:id',
                    component: resolve => void(require(['../components/teacher/Course1.vue'], resolve)),
                    props: true,
                },
                {
                    path: 'type/two/:id',
                    component: resolve => void(require(['../components/teacher/Course2.vue'], resolve)),
                    props: true,
                }
            ]
        },

        {
            path: '/course/info',
            component: resolve => void(require(['../components/teacher/Info.vue'], resolve)),
        },







        {
            path: '/setting/staffroom',
            component: resolve => void(require(['../components/setting/Staffroom.vue'], resolve)),
        },
        {
            path: '/setting/joblevel',
            component: resolve => void(require(['../components/setting/Joblevel.vue'], resolve)),
        },
        {
            path: '/setting/title',
            component: resolve => void(require(['../components/setting/Title.vue'], resolve)),
        },
        {
            path: '/setting/jobtype',
            component: resolve => void(require(['../components/setting/Jobtype.vue'], resolve)),
        },
        {
            path: '/setting/term',
            component: resolve => void(require(['../components/setting/Term.vue'], resolve)),
        },
    ],
});