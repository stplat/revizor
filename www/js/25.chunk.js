(window.webpackJsonp=window.webpackJsonp||[]).push([[25],{54:function(e,t,n){"use strict";n.r(t);var a={mixins:[n(7).a],data:function(){return{data:{check_type:"",name:"",date:{from:"",to:""},auth_needed:!1,auth_type:"",auth_link:[],auth_login:"",auth_password:"",csv:"",decree:""},rules:{check_type:["required"],name:["required"],date:["required"],auth_link:["required"],csv:["required"]},labels:{check_type:"Тип проверки",name:"Название",date:"Дата голосования",auth_link:"Ссылка(и) на каталоги видео",csv:"Загрузка участков"},isLoading:!1,shown:!1}},mounted:function(){var e=this;setTimeout((function(){return e.shown=!0}))},methods:{create:function(){var e=this;if(this.validate()){this.isLoading=!0;var t=this.data.date.from,n=this.data.date.to,a=Object.assign({},this.data,{date_start:t,date_end:n,auth_needed:!!this.data.auth_needed||""});this.$store.dispatch("ViewCheckList/createCheck",a).then((function(t){t.hasOwnProperty("errors")?e.errors=t.errors:(e.$emit("result",{text:"Проверка ".concat(e.data.name," успешно создана!"),className:"alert--success"}),e.$emit("close")),e.isLoading=!1}))}}},computed:{computedForm:function(){var e=this;return[{type:"select",name:"check_type",label:"Вид проверки:",data:[{value:"3",label:"Онлайн"},{value:"2",label:"Оффлайн"}]},{type:"text",name:"name",label:"Название:"},{type:"date-period",name:"date",label:"Дата голосования:"},{type:"multi-textbox",name:"auth_link",label:"Ссылка(и) на каталоги видео:"},{type:"toggle",name:"auth_needed",label:"Необходима авторизация:"},{type:"select",name:"auth_type",label:"Вид авторизации:",data:this.$store.getters["ViewCheckList/getAuthTypeList"].map((function(e){return{value:String(e.id),label:e.name}}))},{type:"text",name:"auth_login",label:"Логин:"},{type:"password",name:"auth_password",label:"Пароль:"},{type:"file",name:"csv",label:"Загрузка участков:",linkToExample:this.asset("storage/upload_layouts/stations_example.csv")},{type:"text",name:"decree",label:"Постановление:",desc:"Введите название постановления, на которое ссылаться при формировании протоколов нарушений"}].filter((function(t){return!(["auth_type","auth_login","auth_password"].find((function(e){return e===t.name}))&&!e.data.auth_needed)}))}}},r=n(0),s=Object(r.a)(a,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("popup",{attrs:{isLoading:e.isLoading,shown:e.shown},on:{close:function(t){return e.$emit("close")}},scopedSlots:e._u([{key:"header",fn:function(){return[e._v("Создание новой проверки")]},proxy:!0},{key:"body",fn:function(){return[n("alert",{attrs:{messages:e.errors}}),e._v(" "),n("v-form-new",{attrs:{data:e.computedForm},model:{value:e.data,callback:function(t){e.data=t},expression:"data"}})]},proxy:!0},{key:"footer",fn:function(){return[n("button",{staticClass:"btn btn--primary ml-auto",on:{click:e.create}},[e._v("Добавить")])]},proxy:!0}])})}),[],!1,null,null,null).exports,o={data:function(){return{isLoading:!1,shown:!1,errors:[]}},props:{id:{type:Number,required:!0}},mounted:function(){var e=this;setTimeout((function(){return e.shown=!0}))},computed:{computedMessage:function(){return[{text:"ID: <strong>".concat(this.id,"</strong>"),className:"alert--info"}]}},methods:{remove:function(){var e=this;this.isLoading=!0,this.$store.dispatch("ViewCheckList/deleteCheck",{id:this.id}).then((function(t){t.hasOwnProperty("errors")?e.errors=t.errors:(e.errors=[],e.$emit("result",{text:"Проверка с ID: <strong>".concat(e.id,"</strong> успешно удалена!"),className:"alert--success"}),e.$emit("close")),e.isLoading=!1}))}}},i={components:{CheckCreate:s,CheckDelete:Object(r.a)(o,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("popup",{attrs:{isLoading:e.isLoading,shown:e.shown},on:{close:function(t){return e.$emit("close")}},scopedSlots:e._u([{key:"header",fn:function(){return[e._v("Удаление проверки")]},proxy:!0},{key:"body",fn:function(){return[n("alert",{attrs:{messages:e.errors.length?e.errors:e.computedMessage}}),e._v(" "),n("h4",[e._v("Вы уверены, что хотите удалить этого пользователя?")])]},proxy:!0},{key:"footer",fn:function(){return[n("button",{staticClass:"btn btn--light ml-auto",on:{click:function(t){return e.$emit("close")}}},[e._v("Отменить")]),e._v(" "),n("button",{staticClass:"btn btn--danger ml-1",on:{click:e.remove}},[e._v("Удалить")])]},proxy:!0}])})}),[],!1,null,null,null).exports},data:function(){return{modal:{create:!1,delete:{id:null,name:"",show:!1}},result:[]}},methods:{getResult:function(e){this.result=[e]},modalRemoveCheck:function(e){this.modal.delete=Object.assign({},{id:e.id,name:e.name,show:!0})}},computed:{table:function(){var e={key:"№",name:"Название",uiks:"Участки",cameras:"Камеры",status:"Статус",type:"Вид проверки",actions:"Действия"},t=this.$store.getters["ViewCheckList/getCheckList"].map((function(e,t){return{key:t+1,id:e.check_id,name:e.check_name,uiks:e.uiks_count,cameras:e.cameras_count,status:e.data_type_name,type:e.type_name,actions:"Действия"}}));return{data:t,options:{headings:e,_data:t},columns:Object.keys(e)}}}},c=Object(r.a)(i,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"layout__container"},[n("alert",{attrs:{messages:e.result}}),e._v(" "),n("card",{scopedSlots:e._u([{key:"title",fn:function(){return[e._v("Список проверок")]},proxy:!0}])},[e._v(" "),n("v-client-table",{attrs:{data:e.table.data,columns:e.table.columns,options:e.table.options},scopedSlots:e._u([{key:"afterLimit",fn:function(){return[n("button",{staticClass:"btn btn--success",on:{click:function(t){e.modal.create=!0}}},[e._v("\n          Создать проверку\n        ")])]},proxy:!0},{key:"actions",fn:function(t){return[n("div",{staticClass:"vuetable__icons"},[n("a",{attrs:{href:/checks/+t.row.id,title:"Открыть проверку"}},[n("unicon",{attrs:{name:"presentation-check",fill:"royalblue"}})],1),e._v(" "),n("a",{attrs:{href:"",title:"Удалить проверку"},on:{click:function(n){return n.preventDefault(),e.modalRemoveCheck(t.row)}}},[n("unicon",{attrs:{name:"trash-alt",fill:"royalblue"}})],1)])]}}])})],1),e._v(" "),e.modal.create?n("check-create",{on:{close:function(t){e.modal.create=!1},result:e.getResult}}):e._e(),e._v(" "),e.modal.delete.show?n("check-delete",{attrs:{id:e.modal.delete.id},on:{close:function(t){e.modal.delete.show=!1},result:e.getResult}}):e._e()],1)}),[],!1,null,null,null);t.default=c.exports},7:function(e,t,n){"use strict";t.a={data:function(){return{errorMessages:{required:"Поле <strong></strong> обязательно для заполнения"},errors:[],rules:[],labels:[]}},methods:{generateMessage:function(e,t){if(this.errorMessages.hasOwnProperty(e)){var n=this.labels.hasOwnProperty(t)?this.labels[t]:t;return this.errorMessages[e].replace("<strong></strong>","<strong>".concat(n,"</strong>"))}},sendMessage:function(e,t){var n=this.generateMessage(e,t),a=this.errors.findIndex((function(e){return e===n}));this.$set(this.errors,-1!==a?a:this.errors.length,n)},deleteMessage:function(e,t){var n=this.generateMessage(e,t),a=this.errors.findIndex((function(e){return e===n}));-1!==a&&this.errors.splice(a,1)},validate:function(){var e=this,t=!0,n=function(n){if(e.rules.hasOwnProperty(n)&&e.data.hasOwnProperty(n)){var a=e.data[n];e.rules[n].forEach((function(r){"function"==typeof r&&!1===r(a,e.data,e,n)&&(t=!1),e.ruleHandler.hasOwnProperty(r)&&!1===e.ruleHandler[r](a,e.data,e,n)&&(t=!1)}))}};for(var a in this.rules)n(a);return t}},computed:{ruleHandler:function(){return{required:function(e,t,n){var a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"";if(Array.isArray(e)&&!e.length||!e)return n.sendMessage("required",a),!1;e===Object(e)||n.deleteMessage("required",a)},callback:function(e,t,n){var a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"";console.log(e,t,n,a)}}}}}}}]);
//# sourceMappingURL=25.chunk.js.map