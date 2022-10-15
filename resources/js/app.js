/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// require('jquery.easing');

// window.$ = window.jQuery = require('jquery');


window.Vue = require('vue');

import VueRouter from 'vue-router'

Vue.use(VueRouter)
const routes = [
    { path: '/' }
]
var router = new VueRouter({
    routes: routes,
    mode: 'history'
})
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


Vue.config.productionTip = false


Vue.component('dashboard-test', require('./components/dashboard/Test.vue').default);
Vue.component('widget-form', require('./components/WidgetForm.vue').default);
Vue.component('adm-employee', require('./components/admnistration/Employee.vue').default);
Vue.component('clear-log', require('./components/admnistration/auditTrails/clearLog.vue').default);
Vue.component('approval-settings', require('./components/admnistration/approval/ApprovalSettings.vue').default);
Vue.component('reimbursment-form', require('./components/reimbursment/ReimbursmentForm.vue').default);
Vue.component('gl-form', require('./components/finance/GLForm.vue').default);


// Main Component
Vue.component('range-date', require('./components/main/RangeDate.vue').default);
Vue.component('input-currency', require('./components/main/InputCurrency.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const elVue = 'app';
var myEle = document.getElementById(elVue);
if (myEle) {
    const app = new Vue({
        el: '#' + elVue,
        router: router
    });
}