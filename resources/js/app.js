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
Vue.component('view-layout', require('@/views/ViewLayout').default);
Vue.component('view-check-list', require('@/views/ViewCheckList').default);
Vue.component('view-check', require('@/views/ViewCheck').default);
Vue.component('view-index', require('@/views/ViewIndex').default);
Vue.component('view-recognition', require('@/views/ViewRecognition').default);
Vue.component('view-user', require('@/views/ViewUser').default);

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
// Vue.component('check', require('./components/Check'));
Vue.component('check-events', require('./components/CheckEvents').default);
Vue.component('check-info', require('./components/CheckInfo').default);
Vue.component('check-list', require('./components/CheckList').default);
Vue.component('check-uik', require('./components/Check/CheckUik').default);
Vue.component('check-schema', require('./components/Check/CheckSchema').default);
Vue.component('check-settings', require('./components/CheckSettings').default);
Vue.component('leaflet-map', require('./components/LeafletMap').default);
Vue.component('modal', require('./components/Modal').default);
Vue.component('recognition', require('./components/Recognition').default);
Vue.component('sign-type', require('./components/SignType').default);
Vue.component('sign-status', require('./components/SignStatus').default);
Vue.component('user-list', require('./components/UserList').default);


/* TODO переделать в базовые компоненты */
Vue.component('search-checkbox', require('./components/SearchCheckbox').default);
Vue.component('tip-overflow', require('./components/TipOverflow').default);
Vue.component('v-filter', require('./components/VFilter').default);


if (document.querySelectorAll('#app').length) {
  const app = new Vue({
    el: '#app',
    VtTables,
    Union,
    store
  });
}
