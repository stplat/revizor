(window.webpackJsonp=window.webpackJsonp||[]).push([[20],{2:function(e,t,a){"use strict";t.a={model:{prop:"modelValue",event:"update:modelValue"},props:{modelValue:{type:Object,required:!0},data:{type:Object,required:!0,validator:function(e){return e.hasOwnProperty("type")&&e.hasOwnProperty("name")&&e.hasOwnProperty("label")}}},computed:{computedId:function(){return"form-".concat(this.data.type,"-").concat(this._uid)},computedModelValue:{get:function(){return this.modelValue[this.data.name]},set:function(e){this.$emit("update:modelValue",Object.assign({},function(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}({},this.data.name,e)))}},computedDescription:function(){return this.data.desc},computedLabel:function(){return this.data.label},computedData:function(){return this.data.data}}}},85:function(e,t,a){"use strict";a.r(t);var n={mixins:[a(2).a],props:{data:{type:Array,required:!0}}},r=a(0),u=Object(r.a)(n,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"form-group"},[a("label",{staticClass:"form-group__label",attrs:{for:"form-select-"+e._uid}},[e._v(e._s(e.label))]),e._v(" "),e.desc?a("div",{staticClass:"form-group__desc"},[e._v(e._s(e.desc))]):e._e(),e._v(" "),a("input-select",{attrs:{id:"form-select-"+e._uid,data:e.data,value:e.value,name:e.name},on:{change:e.change}})],1)}),[],!1,null,null,null);t.default=u.exports}}]);
//# sourceMappingURL=20.chunk.js.map