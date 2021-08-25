(window.webpackJsonp=window.webpackJsonp||[]).push([[32],{64:function(t,i,n){"use strict";n.r(i);var e={components:{CheckRemove:function(){return n.e(28).then(n.bind(null,90))},UikUpload:function(){return n.e(31).then(n.bind(null,91))},OfficialVoteUpload:function(){return n.e(30).then(n.bind(null,92))},IntermediateVoteUpload:function(){return n.e(29).then(n.bind(null,93))}},props:{},data:function(){return{data:{id:null,cam:"",reviewUiks:[],countingUiks:[]},modal:{remove:!1,uploadUik:!1,uploadVote:!1,uploadIntermediateVote:!1},isLoading:!1}},computed:{computedCheck:function(){return this.$store.getters["ViewCheck/getCheck"]},computedUiks:function(){return this.$store.getters["ViewCheck/getUikList"]}},methods:{changeStatus:function(t){var i=this,n=this.computedCheck.check_id;this.isLoading=!0,this.$store.dispatch("ViewCheck/editCheck",{id:n,active:t}).then((function(t){t.hasOwnProperty("errors"),i.isLoading=!1}))},reviewUik:function(){var t=this,i=this.computedCheck.check_id;this.isLoading=!0,this.$store.dispatch("ViewCheck/reviewUik",{check_id:i,uiks:this.data.reviewUiks}).then((function(i){i.hasOwnProperty("errors"),t.isLoading=!1}))},compareTurnout:function(){var t=this,i=this.computedCheck.check_id;this.isLoading=!0,this.$store.dispatch("ViewCheck/compareTurnout",{check_id:i}).then((function(i){i.hasOwnProperty("errors"),t.isLoading=!1}))},compareIntermediateTurnout:function(){var t=this,i=this.computedCheck.check_id;this.isLoading=!0,this.$store.dispatch("ViewCheck/compareIntermediateTurnout",{check_id:i}).then((function(i){i.hasOwnProperty("errors"),t.isLoading=!1}))},countingUik:function(){var t=this,i=this.computedCheck.check_id;this.isLoading=!0,this.$store.dispatch("ViewCheck/countingUik",{check_id:i,uiks:this.data.countingUiks}).then((function(i){i.hasOwnProperty("errors"),t.isLoading=!1}))}},created:function(){}},o=n(0),s=Object(o.a)(e,(function(){var t=this,i=t.$createElement,n=t._self._c||i;return n("tip",{attrs:{"button-type":"settings",title:"Настройки проверки"},scopedSlots:t._u([{key:"outer",fn:function(){return[n("check-remove",{attrs:{shown:t.modal.remove},on:{close:function(i){t.modal.remove=!1}}}),t._v(" "),n("uik-upload",{attrs:{shown:t.modal.uploadUik},on:{close:function(i){t.modal.uploadUik=!1}}}),t._v(" "),n("official-vote-upload",{attrs:{shown:t.modal.uploadVote},on:{close:function(i){t.modal.uploadVote=!1}}}),t._v(" "),n("intermediate-vote-upload",{attrs:{shown:t.modal.uploadIntermediateVote},on:{close:function(i){t.modal.uploadIntermediateVote=!1}}})]},proxy:!0}])},[n("div",{staticClass:"tip-container"},[t.isLoading?n("preloader"):t._e(),t._v(" "),n("div",{staticClass:"tip-container__half"},[n("div",{staticClass:"tip-dropdown__title"},[t._v("Управление проверкой")]),t._v(" "),n("div",{staticClass:"tip-dropdown__content"},[t.computedCheck.active?n("button",{staticClass:"btn btn--warning btn--small",on:{click:function(i){return t.changeStatus(!1)}}},[t._v("\n          Остановить\n        ")]):t._e(),t._v(" "),t.computedCheck.active?t._e():n("button",{staticClass:"btn btn--success btn--small",on:{click:function(i){return t.changeStatus(!0)}}},[t._v("\n          Продолжить\n        ")]),t._v(" "),n("button",{staticClass:"btn btn--danger btn--small ml-1",on:{click:function(i){t.modal.remove=!0}}},[t._v("\n          Удалить\n        ")])]),t._v(" "),n("div",{staticClass:"tip-dropdown__title"},[t._v("Участки")]),t._v(" "),n("div",{staticClass:"tip-dropdown__text"},[t.computedCheck.uiks_count?[t._v("\n          Загружено "+t._s(t.computedCheck.uiks_count)+" участка (-ов)\n        ")]:[t._v("\n          Данные не загружены\n        ")]],2),t._v(" "),n("div",{staticClass:"tip-dropdown__content"},[n("div",{staticClass:"btn btn--primary btn--small",on:{click:function(i){t.modal.uploadUik=!0}}},[t._v("\n          Загрузить новые\n        ")])]),t._v(" "),n("div",{staticClass:"tip-dropdown__title"},[t._v("Проверка работы Ревизора")]),t._v(" "),n("ul",{staticClass:"tip-dropdown__list tip-dropdown__list---light"},[n("li",{attrs:{"data-key":"Участки:"}},[n("div",{staticClass:"tip-dropdown__input"},[n("search-checkbox",{attrs:{data:t.computedUiks},model:{value:t.data.reviewUiks,callback:function(i){t.$set(t.data,"reviewUiks",i)},expression:"data.reviewUiks"}})],1)])]),t._v(" "),n("button",{staticClass:"btn btn--primary btn--small",attrs:{disabled:!t.data.reviewUiks.length},on:{click:t.reviewUik}},[t._v("\n        Отправить участки на проверку\n      ")])]),t._v(" "),n("div",{staticClass:"tip-container__half"},[n("div",{staticClass:"tip-dropdown__title"},[t._v("Официальная явка")]),t._v(" "),t.computedCheck.official_votes?[n("div",{staticClass:"tip-dropdown__text"},[t._v("\n          Загружено "+t._s(t.computedCheck.official_votes)+" участка (-ов)\n        ")])]:[n("div",{staticClass:"tip-dropdown__text"},[t._v("Данные не загружены")])],t._v(" "),n("div",{staticClass:"tip-dropdown__content"},[n("button",{staticClass:"btn btn--primary btn--small",on:{click:function(i){t.modal.uploadVote=!0}}},[t._v("\n          Загрузить\n        ")]),t._v(" "),t.computedCheck.official_votes?n("button",{staticClass:"btn btn--success btn--small ml-1",on:{click:t.compareTurnout}},[t._v("\n          Сравнить явку\n        ")]):t._e()]),t._v(" "),n("div",{staticClass:"tip-dropdown__title"},[t._v("Промежуточная явка")]),t._v(" "),t.computedCheck.inter_official_votes?[n("div",{staticClass:"tip-dropdown__text"},[t._v("\n          Загружено "+t._s(t.computedCheck.inter_official_votes)+" участка (-ов)\n        ")])]:[n("div",{staticClass:"tip-dropdown__text"},[t._v("Данные не загружены")])],t._v(" "),n("div",{staticClass:"tip-dropdown__content"},[n("button",{staticClass:"btn btn--primary btn--small",on:{click:function(i){t.modal.uploadIntermediateVote=!0}}},[t._v("\n          Загрузить\n        ")]),t._v(" "),t.computedCheck.inter_official_votes?n("button",{staticClass:"btn btn--success btn--small ml-1",on:{click:t.compareIntermediateTurnout}},[t._v("\n          Сравнить явку\n        ")]):t._e()]),t._v(" "),n("div",{staticClass:"tip-dropdown__title"},[t._v("Ручной подсчет явки")]),t._v(" "),n("ul",{staticClass:"tip-dropdown__list tip-dropdown__list---light"},[n("li",{attrs:{"data-key":"Участки:"}},[n("div",{staticClass:"tip-dropdown__input"},[n("search-checkbox",{attrs:{data:t.computedUiks},model:{value:t.data.countingUiks,callback:function(i){t.$set(t.data,"countingUiks",i)},expression:"data.countingUiks"}})],1)])]),t._v(" "),n("button",{staticClass:"btn btn--primary btn--small",attrs:{disabled:!t.data.countingUiks.length},on:{click:t.countingUik}},[t._v("\n        Отправить участки на подсчёт\n      ")])],2)],1)])}),[],!1,null,null,null);i.default=s.exports}}]);
//# sourceMappingURL=32.chunk.js.map