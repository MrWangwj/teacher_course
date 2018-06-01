import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        {
            path: '/course/count',
            component: resolve => void(require(['../components/course/Count.vue'], resolve)),

        },
        {
            path: '/teacher/course',
            component: resolve => void(require(['../components/course/Teacher.vue'], resolve)),
        }
    ],
});