import Vue from 'vue'
import axios from 'axios'
import Routes from './routes'
import VueRouter from 'vue-router'
import State from './store/state'
import Mutations from './store/mutations'
import Vuex from 'vuex'
import Toasted from 'vue-toasted'

require('bootstrap')
window.$ = window.jQuery = require('jquery')

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
Vue.prototype.$http = axios.create({
    baseURL: LaravelBackupPanel.path
})

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    base: '/' + LaravelBackupPanel.path + '/',
    routes: Routes
})

Vue.use(Vuex)

const store = new Vuex.Store({
    state: State,
    mutations: Mutations,
})

Vue.use(Toasted)

Vue.prototype.$eventHub = new Vue()

new Vue({
    el: '#laravel_backup_panel',
    store,
    router,
});
