/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
window.VueRouter = require('vue-router').default;
window.axios = require('axios');
window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
Vue.use(VueRouter);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('navbar', require('./components/Nav.vue').default);
Vue.component('app', require('./components/App.vue').default);
Vue.component('Login', require('./components/Login.vue').default);
Vue.component('Home', require('./components/Home.vue').default);
Vue.component('sales-count-line-chart', require('./components/SalesCountLineChart.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 const routes = [
    {
      path: '/',
      name: 'Home',
      component: require('./components/Home.vue').default
    },
    {
      path: '/login',
      name: 'Login',
      component: require('./components/Login.vue').default
    },
    {
      path: '/datagrid',
      name: 'DataGrid',
      component: require('./components/DataGrid.vue').default
    },
    {
      path: '/salesform',
      name: 'SalesForm',
      component: require('./components/SalesForm.vue').default
    },
    {
      path: '/customerform',
      name: 'CustomerForm',
      component: require('./components/CustomerForm.vue').default
    },
    {
      path: '/myfolder',
      name: 'MyFolder',
      component: require('./components/MyFolder.vue').default
    },
    {
      path: '/employeeladder',
      name: 'EmployeeLadder',
      component: require('./components/EmployeeLadder.vue').default
    }
  ];

  const router = new VueRouter({
    mode: 'history',
    routes
  })

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('user-token')
  if (token) {
      axios.defaults.headers.common['Authorization'] = 'Bearer ' +token
  }
  if (to.name !== 'Login' && !token) next({ name: 'Login' })
  else next()
})

const app = new Vue({
    el: '#app',
    router
});
