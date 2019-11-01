require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router';
import VueRouterMultiguard from 'vue-router-multiguard';

Vue.use(VueRouter);

import App from './components/App.vue';
import LoginComponent from './components/LoginComponent.vue';
import RegisterComponent from './components/RegisterComponent.vue';
import HomeComponent from './components/HomeComponent.vue';
import EditTeamComponent from './components/EditTeamComponent.vue';


function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
  }

const checkToken = (to, from, next) => {
    let token = getCookie('access_token')

    let config = {
        headers: {'Authorization': "Bearer " + token}
    };
    axios.get("/api/user", config)
    .then(response => {
        next()
    })
    .catch(error => {
        next('/')
    })
}
const routes =
[

    {
        name: 'login',
        path: '/',
        component: LoginComponent,
        // beforeEnter: VueRouterMultiguard([guard1])
    },
    {
        name: 'register',
        path: '/register',
        component: RegisterComponent
    },
    {
        name: 'home',
        path: '/home',
        component: HomeComponent,
        beforeEnter: VueRouterMultiguard([checkToken])
    },
    {
        name: 'edit',
        path: '/edit_team',
        component: EditTeamComponent,
        beforeEnter: VueRouterMultiguard([checkToken])
    },
]

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);


const router = new VueRouter({
    mode: 'history',
    hash: false,
    routes
})

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
