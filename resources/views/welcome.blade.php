<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <el-select v-model="value" placeholder="请选择">
                <el-option
                    v-for="item in options"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                </el-option>
            </el-select>
            <p>
                Ask a yes/no question:
                <input v-model="question">
            </p>
            <p>@{{ answer }}</p>
            <counter heading="likes" color="green"></counter>
            <counter heading="dislikes" color="red"></counter>
            <page-app></page-app>
        </div>

        <template id="counter-template">
            <div>
                <h1>@{{ heading }}</h1>
                <button  @click="count += 1" v-bind:style="{background:color}">submit @{{ count }}</button>
            </div>
        </template>

        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            Vue.component('counter', {
                template: "#counter-template",
                props: ['heading', 'color'],
                data: function(){
                    return { count:0 };
                }
            });

            new Vue({
                el:'#app',
                data:{
                    options: [{
                        value: '选项1',
                        label: '黄金糕'
                        }, {
                        value: '选项2',
                        label: '双皮奶'
                        }, {
                        value: '选项3',
                        label: '蚵仔煎'
                        }, {
                        value: '选项4',
                        label: '龙须面'
                        }, {
                        value: '选项5',
                        label: '北京烤鸭'
                    }],
                    value: '双皮奶',
                    question: '',
                    answer: 'I cannot give you an answer until you ask a question!',
                },
                watch: {
                    // 如果 `question` 发生改变，这个函数就会运行
                    question: function (newQuestion, oldQuestion) {
                    this.answer = 'Waiting for you to stop typing...'
                    this.debouncedGetAnswer()
                    }
                },
                created: function () {
                    // `_.debounce` 是一个通过 Lodash 限制操作频率的函数。
                    // 在这个例子中，我们希望限制访问 yesno.wtf/api 的频率
                    // AJAX 请求直到用户输入完毕才会发出。想要了解更多关于
                    // `_.debounce` 函数 (及其近亲 `_.throttle`) 的知识，
                    // 请参考：https://lodash.com/docs#debounce
                    this.debouncedGetAnswer = _.debounce(this.getAnswer, 500)
                },
                methods: {
                    doSomething: function(){
                        console.log(this.value);
                    },
                    getAnswer: function () {
                        if (this.question.indexOf('?') === -1) {
                            this.answer = 'Questions usually contain a question mark. ;-)'
                            return
                        }
                        this.answer = 'Thinking...'
                        var vm = this
                        axios.get('https://yesno.wtf/api')
                            .then(function (response) {
                            vm.answer = _.capitalize(response.data.answer)
                            })
                            .catch(function (error) {
                            vm.answer = 'Error! Could not reach the API. ' + error
                            })
                        }
                },
            });
        </script>
    </body>
</html>
