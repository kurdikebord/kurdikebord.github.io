import Vue from 'vue';
import App from '@/App.vue';
import router from '@/router';
import store from '@/store';
import i18n from '@/plugins/i18n';
import M from 'materialize-css/dist/js/materialize';
import 'materialize-css/dist/css/materialize.min.css';
import VuePlyr from 'vue-plyr';
import 'vue-plyr/dist/vue-plyr.css';
import '@/assets/scss/main.scss';

M.AutoInit();

Vue.use(VuePlyr, {
  plyr: {
    fullscreen: { enabled: false },
    // autoplay: true
  },
  emit: ['ended']
});

Vue.config.productionTip = false

new Vue({
  router,
  store,
  i18n,
  render: h => h(App)
}).$mount('#app')
