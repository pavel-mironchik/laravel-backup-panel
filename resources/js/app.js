import Vue from 'vue'
import axios from 'axios'
import VueRouter from 'vue-router'

require('bootstrap')

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
Vue.prototype.$http = axios.create()

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        redirect: '/files',
        component: require('./components/App').default,
        children: [
            {
                path: 'files',
                component: require('./components/Files').default
            }
        ]
    }
]

const router = new VueRouter({
    mode: 'history',
    base: '/' + window.LaravelBackupPanel.path + '/',
    routes
})

Vue.prototype.$eventHub = new Vue()

new Vue({
    el: '#laravel_backup_panel',
    router
});
