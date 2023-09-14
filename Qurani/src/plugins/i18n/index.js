import Vue from 'vue';
import VueI18n from 'vue-i18n';
import ar from './ar.js';
import en from './en.js';

Vue.use(VueI18n);

export default new VueI18n({
  locale: 'ar',
  fallbackLocale: 'en',
  messages: { ar, en }
});