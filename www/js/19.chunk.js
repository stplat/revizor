(window.webpackJsonp=window.webpackJsonp||[]).push([[19],{2:function(e,t,a){"use strict";t.a={model:{prop:"modelValue",event:"update:modelValue"},props:{modelValue:{type:Object,required:!0},data:{type:Object,required:!0,validator:function(e){return e.hasOwnProperty("type")&&e.hasOwnProperty("name")&&e.hasOwnProperty("label")}}},computed:{computedId:function(){return"form-".concat(this.data.type,"-").concat(this._uid)},computedModelValue:{get:function(){return this.modelValue[this.data.name]},set:function(e){this.$emit("update:modelValue",Object.assign({},function(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}({},this.data.name,e)))}},computedDescription:function(){return this.data.desc},computedLabel:function(){return this.data.label},computedData:function(){return this.data.data}}}},87:function(e,t,a){"use strict";a.r(t);var n={mixins:[a(2).a],props:{value:{type:Number,required:!1}}},u=a(0),r=Object(u.a)(n,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"form-group"},[a("label",{staticClass:"form-group__label",attrs:{for:"form-input-"+e._uid}},[e._v(e._s(e.label))]),e._v(" "),e.desc?a("div",{staticClass:"form-group__desc"},[e._v(e._s(e.desc))]):e._e(),e._v(" "),a("v-number",{attrs:{id:"form-input-"+e._uid,value:e.value,name:e.name},on:{input:e.change}})],1)}),[],!1,null,null,null);t.default=r.exports}}]);
//# sourceMappingURL=19.chunk.js.map