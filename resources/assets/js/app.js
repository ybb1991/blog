
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');


// element UI
import './element-ui';

Vue.component('button-counter', {
      data: function () {
        return {
          count: 0
        }
      },
      template: '<button v-on:click="count++">You clicked me {{ count }} times.</button>'
    })

/**
 * 接下来，我们将创建一个新的Vue应用程序实例并将其附加到
 * 该页面。然后，您可以开始向这个应用程序添加组件
 * 或者定制JavaScript脚手架以满足您独特的需求
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
// Vue.component('page-app', require('./components/Paging.vue'));
// Vue.component('button-counter', require('./components/ButtonCounter.vue'));


// const app = new Vue({
//     el: '#app'
// });
// var app = new Vue({
//     el: '#app',
//     // template: "<div>这是laravel+vue的项目</div>"
// });
