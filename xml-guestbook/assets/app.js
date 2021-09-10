import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'

Object.defineProperty(Vue.prototype, '$bus', {
    get() {
        return this.$root.bus;
    }
});
const bus = new Vue({})

new Vue({
    vuetify,
    data() {
        return {
            bus: bus,
        }
    },
    render: h => h(App)
}).$mount('#app')