(window.webpackJsonp=window.webpackJsonp||[]).push([[14],{2:function(t,e,a){"use strict";e.a={model:{prop:"modelValue",event:"update:modelValue"},props:{modelValue:{type:Object,required:!0},data:{type:Object,required:!0,validator:function(t){return t.hasOwnProperty("type")&&t.hasOwnProperty("name")&&t.hasOwnProperty("label")}}},computed:{computedId:function(){return"form-".concat(this.data.type,"-").concat(this._uid)},computedModelValue:{get:function(){return this.modelValue[this.data.name]},set:function(t){this.$emit("update:modelValue",Object.assign({},function(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}({},this.data.name,t)))}},computedDescription:function(){return this.data.desc},computedLabel:function(){return this.data.label},computedData:function(){return this.data.data}}}},79:function(t,e,a){"use strict";a.r(e);var n={mixins:[a(2).a],props:{data:{type:Array,required:!0},value:{type:Object,required:!1}},methods:{change:function(t,e){this.$emit("input",Object.assign({},t),e,this.name)}}},r=a(0),u=Object(r.a)(n,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"form-group"},[a("label",{staticClass:"form-group__label",attrs:{for:"form-toggle-"+t._uid}},[t._v(t._s(t.label))]),t._v(" "),t.desc?a("div",{staticClass:"form-group__desc"},[t._v(t._s(t.desc))]):t._e(),t._v(" "),a("toggle-list",{attrs:{id:"form-toggle-"+t._uid,data:t.data,value:t.value},on:{input:t.change}},[t._t("default")],2)],1)}),[],!1,null,null,null);e.default=u.exports}}]);
//# sourceMappingURL=14.chunk.js.map