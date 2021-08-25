(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{31:function(t,e,a){"use strict";a.r(e);var i={data:function(){return{check:"",violation:"",uik:"",camera:"",shown:!1}},props:{},mounted:function(){var t=this,e=String(this.$store.getters["ViewCheck/getCheck"].check_id);setInterval((function(){t.$store.dispatch("ViewCheck/showViolations",{check:e})}),3e4)},methods:{clickModal:function(t){this.check=String(this.$store.getters["ViewCheck/getCheck"].check_id),this.violation=Array.isArray(t.key)?t.key.map((function(t){return String(t)})).sort((function(t,e){return e-t}))[0]:String(t.key),this.uik=String(t.uik.id),this.camera=String(t.camera.id),this.shown=!0}},computed:{table:function(){var t=this,e={key:"#",date:"Дата",time:"Время",region:"Регион",uik:"Участок",camera:"Камера",type:"Вид",status:"Статус",actions:"Детали",indicator:""},a=this.$store.getters["ViewCheck/getViolationsForEventTable"].map((function(e,a){var i,n,s,r,o,c;return Array.isArray(e.id)?(i=e.date_start.sort((function(t,e){return new Date(t)-new Date(e)})),n=t.formatDateHelper(i[0]),s=t.formatDateHelper(i[i.length-1]),o=t.formatTimeHelper(i[0]),c=t.formatTimeHelper(i[i.length-1]),n===s?(i=n,r=o):(i="с ".concat(n," по ").concat(s),r="с ".concat(o," по ").concat(c)),r=o===c?o:"с ".concat(o," по ").concat(c)):(i=t.formatDateHelper(e.date_start),r=t.formatTimeHelper(e.date_start)),{key:Array.isArray(e.id)?e.id.sort()[0]:e.id,indicator:e.blocked,date:i,time:r,region:e.region_number,uik:{id:e.uik_id,number:e.uik_number},camera:{id:e.cam_numeric_id,number:e.cam_num},type:String(e.violation_type_id),status:!!e.status_id&&String(e.status_id),actions:e}}));return{data:a,options:{headings:e,_data:a,sortByColumn:!0,sortable:["key","date"]},columns:Object.keys(e)}}}},n=a(0),s=Object(n.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("v-client-table",{attrs:{data:t.table.data,columns:t.table.columns,options:t.table.options},scopedSlots:t._u([{key:"indicator",fn:function(t){return[a("div",{staticClass:"indicator",class:{"indicator--red":t.row.indicator},attrs:{title:t.row.indicator?"Недоступно":"Доступно"}})]}},{key:"key",fn:function(e){return[a("ul",{staticClass:"uik-type-list"},[a("li",{staticClass:"uik-type-list__item"},[t._v("\n          "+t._s(Array.isArray(e.row.key)?e.row.key.join(", "):e.row.key)+"\n        ")])])]}},{key:"camera",fn:function(e){return[t._v("\n      "+t._s(e.row.camera.number)+"\n    ")]}},{key:"uik",fn:function(e){return[t._v("\n      "+t._s(e.row.uik.number)+"\n    ")]}},{key:"type",fn:function(t){return[a("ul",{staticClass:"uik-type-list"},[a("li",{staticClass:"uik-type-list__item"},[a("sign-type",{attrs:{type:t.row.type}})],1)])]}},{key:"status",fn:function(e){return[a("ul",{staticClass:"uik-type-list"},[a("li",{staticClass:"uik-type-list__item"},[e.row.status?a("sign-status",{attrs:{status:e.row.status}}):t._e()],1)])]}},{key:"actions",fn:function(e){return[a("div",{staticClass:"vuetable__icons"},[a("a",{attrs:{href:"",title:"Информация о событии"},on:{click:function(a){return a.preventDefault(),t.clickModal(e.row)}}},[a("unicon",{attrs:{name:"presentation-check",fill:"royalblue"}})],1)])]}}])}),t._v(" "),a("modal",{attrs:{props:{check:t.check,camera:t.camera,uik:t.uik,violation:t.violation},shown:t.shown},on:{close:function(e){t.shown=!1}}})],1)}),[],!1,null,null,null);e.default=s.exports},32:function(t,e,a){"use strict";a.r(e);var i={computed:{computedWheelChart:function(){var t=this.$store.getters["ViewCheck/getViolationsGroupByName"],e={labels:t.map((function(t){return t.name})),datasets:[{data:t.map((function(t){return t.count}))}]};t.length||this.$emit("empty");return{chartdata:e,options:{scales:{yAxes:[{ticks:{beginAtZero:!0,stepSize:1},display:!1,scaleLabel:{display:!0,labelString:""}}],xAxes:[{display:!1,scaleLabel:{display:!0,labelString:""}}]},legend:{display:!0}}}}}},n=a(0),s=Object(n.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("chart-wheel",{attrs:{"initial-data":this.computedWheelChart}})}),[],!1,null,null,null);e.default=s.exports},62:function(t,e,a){"use strict";a.r(e);var i={components:{EventTable:a(31).default,EventChart:a(32).default},data:function(){return{data:{check_id:null,event:!1,petition:!1,answer:!1},eventChart:!0,isLoading:!1}},methods:{changeData:function(){var t=this;this.isLoading=!0;var e=this.data,a=e.check_id,i=e.event,n=e.petition,s=e.answer,r=[];i&&r.push(1),n&&r.push(3),s&&r.push(5),this.$store.dispatch("ViewCheck/showViolations",{check:a,statuses:r}).then((function(e){e.hasOwnProperty("errors")&&(t.errors=e.errors),t.isLoading=!1}))}},computed:{computedTile:function(){var t=this.$store.getters["ViewCheck/getCheck"];return[{title:"Сбой трансляции",value:t.violation_type_id_1_datetime_null_count,class:""},{title:"Не хватает видео",value:t.violation_type_id_1_datetime_not_null_count,class:"event-tile__item--video"},{title:"Расположение ящиков",value:t.violation_type_id_4_5_count,class:"event-tile__item--area"},{title:"Отклонение явки",value:t.violation_type_id_6_7_count,class:"event-tile__item--offset"}]}},mounted:function(){this.data.check_id=this.$store.getters["ViewCheck/getCheck"].check_id}},n=a(0),s=Object(n.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"event"},[t.isLoading?a("preloader"):t._e(),t._v(" "),a("div",{staticClass:"event__tile"},[a("ul",{staticClass:"event-tile"},t._l(t.computedTile,(function(e,i){return e.value?a("li",{key:i,staticClass:"event-tile__item",class:e.class},[a("div",{staticClass:"event-tile__title"},[t._v(t._s(e.title))]),t._v(" "),a("div",{staticClass:"event-tile__value"},[t._v(t._s(e.value))])]):t._e()})),0)]),t._v(" "),a("div",{staticClass:"event__container mt-4"},[a("div",{staticClass:"event__left-col"},[a("div",{staticClass:"event__title"},[t._v("\n        Список событий\n        "),a("div",{staticClass:"event__tip"},[a("tip",{attrs:{"button-type":"question",title:"Подсказка"}})],1)]),t._v(" "),a("div",{staticClass:"event__toggle",attrs:{"data-key":"Новое событие:"}},[a("input-toggle",{on:{change:t.changeData},model:{value:t.data.event,callback:function(e){t.$set(t.data,"event",e)},expression:"data.event"}})],1),t._v(" "),a("div",{staticClass:"event__toggle",attrs:{"data-key":"Сформирован жалоба:"}},[a("input-toggle",{on:{change:t.changeData},model:{value:t.data.petition,callback:function(e){t.$set(t.data,"petition",e)},expression:"data.petition"}})],1),t._v(" "),a("div",{staticClass:"event__toggle",attrs:{"data-key":"Пришёл ответ:"}},[a("input-toggle",{on:{change:t.changeData},model:{value:t.data.answer,callback:function(e){t.$set(t.data,"answer",e)},expression:"data.answer"}})],1),t._v(" "),a("div",{staticClass:"event__table mt-4"},[a("event-table")],1)]),t._v(" "),t.eventChart?a("div",{staticClass:"event__right-col"},[a("div",{staticClass:"uik__title mb-2"},[t._v("Распределение событий")]),t._v(" "),a("div",{staticClass:"event__chart"},[a("event-chart",{on:{empty:function(e){t.eventChart=!1}}})],1)]):t._e()])],1)}),[],!1,null,null,null);e.default=s.exports}}]);
//# sourceMappingURL=6.chunk.js.map