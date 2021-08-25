import Vue from 'vue';
import Vuex from 'vuex';

require('./assets/bootstrap');
require('./assets/modernizr');

/* Vuex */
Vue.use(Vuex);
const store = new Vuex.Store({
  modules: {},
  state: {
    // requestPath: 'http://elect.optimagp66.ru'
    requestPath: ''
  }
});

/* Mixins */
Vue.mixin(require('./mixins/variables').default);
Vue.mixin(require('./mixins/helpers').default);

/* Plugins */
import VtTables from './plugins/VtTables';
import Union from './plugins/Unicon';

require('./plugins/Leaflet');
require('./plugins/Chart');

/* Views */
Vue.component('view-layout', () => import('@/views/ViewLayout'));
Vue.component('view-check-list', () => import('@/views/ViewCheckList'));
Vue.component('view-check', () => import('@/views/ViewCheck'));
Vue.component('view-index', () => import('@/views/ViewIndex'));
Vue.component('view-recognition', () => import('@/views/ViewRecognition'));
Vue.component('view-user', () => import('@/views/ViewUser'));

/* Plugin Components */
Vue.component('swiper', require('./components/Swiper').default);

/* TODO V.3 */
Vue.component('s-checkbox', require('./components/__base/SingleCheckbox').default);
Vue.component('s-radio', require('./components/__base/SingleRadio').default);
Vue.component('s-radio-toggle', require('./components/__base/SingleRadioToggle').default);
Vue.component('m-checkbox', require('./components/__base/MultiCheckbox').default);
Vue.component('m-checkbox-xls', require('./components/__base/MultiCheckboxXls').default);
Vue.component('m-checkbox-dropdown', require('./components/__base/MultiCheckboxDropdown').default);
Vue.component('m-file', require('./components/__base/MultiFile').default);
Vue.component('m-number', require('./components/__base/MultiNumber').default);
Vue.component('m-radio', require('./components/__base/MultiRadio').default);
Vue.component('m-radio-dropdown', require('./components/__base/MultiRadioDropdown').default);
Vue.component('m-select', require('./components/__base/MultiSelect').default);
Vue.component('m-textbox', require('./components/__base/MultiTextbox').default);
Vue.component('btn-dropdown', require('./components/__base/ButtonDropdown').default);
Vue.component('search-dropdown', require('./components/__base/SearchDropdown').default);
Vue.component('image-editor', require("./components/ImageEditor").default);
Vue.component('v-form-new', require('./components/__base/VForm').default);

/* Base Components */
Vue.component('alert', require('./components/_base/Alert').default);
Vue.component('card', require('./components/_base/Card').default);
Vue.component('multiple-checkbox', require('./components/_base/MultipleCheckbox').default);
Vue.component('multiple-radio', require('./components/_base/MultipleRadio').default);
Vue.component('multiple-select', require('./components/_base/MultipleSelect').default);
Vue.component('dropdown', require('./components/_base/Dropdown').default);
Vue.component('textbox-multiple', require('./components/_base/TextboxMultiple').default);
Vue.component('input-checkbox', require('./components/_base/InputCheckbox').default);
Vue.component('input-radio', require('./components/_base/InputRadio').default);
Vue.component('input-select', require('./components/_base/InputSelect').default);
Vue.component('input-text', require('./components/_base/InputText').default);
Vue.component('input-textarea', require('./components/_base/InputTextarea').default);
Vue.component('input-toggle', require('./components/_base/InputToggle').default);
Vue.component('input-toggle-radio', require('./components/_base/InputToggleRadio').default);
Vue.component('file', require('./components/_base/File').default);
Vue.component('hamburger', require('./components/_base/Hamburger').default);
Vue.component('popup', require('./components/_base/Popup').default);
Vue.component('preloader', require('./components/_base/Preloader').default);
Vue.component('tip', require('./components/_base/Tip').default);
Vue.component('toggle', require('./components/_base/Toggle').default);
Vue.component('toggle-checkbox', require('./components/_base/ToggleCheckbox').default);
Vue.component('toggle-list', require('./components/_base/ToggleList').default);
Vue.component('toggle-checkbox-list', require('./components/_base/ToggleCheckboxList').default);
Vue.component('v-form', require('./components/_base/VForm').default);
Vue.component('v-number', require('./components/_base/VNumber').default);

/* Custom Components */
// Vue.component('check', () => import('./components/Check'));
Vue.component('check-events', () => import('./components/CheckEvents'));
Vue.component('check-info', () => import('./components/CheckInfo'));
Vue.component('check-list', () => import('./components/CheckList'));
Vue.component('check-uik', () => import('./components/Check/CheckUik'));
Vue.component('check-schema', () => import('./components/Check/CheckSchema'));
Vue.component('check-settings', () => import('./components/CheckSettings'));
Vue.component('leaflet-map', () => import('./components/LeafletMap'));
Vue.component('modal', () => import('./components/Modal'));
Vue.component('recognition', () => import('./components/Recognition'));
Vue.component('sign-type', () => import('./components/SignType'));
Vue.component('sign-status', () => import('./components/SignStatus'));
Vue.component('user-list', () => import('./components/UserList'));


/* TODO переделать в базовые компоненты */
Vue.component('search-checkbox', () => import('./components/SearchCheckbox'));
Vue.component('tip-overflow', () => import('./components/TipOverflow'));
Vue.component('v-filter', () => import('./components/VFilter'));


if (document.querySelectorAll('#app').length) {
  const app = new Vue({
    el: '#app',
    VtTables,
    Union,
    store
  });
}
