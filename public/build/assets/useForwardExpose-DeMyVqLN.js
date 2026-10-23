import{a0 as g,n as b,c as w,a as u,d,u as c,b as v,q as x,ab as C,o as O,ac as j,ad as z,l as A,r as B,a1 as E}from"./app-B_-7MekF.js";import{c as _,u as P}from"./index-CGkf5bHx.js";/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const M=t=>{for(const e in t)if(e.startsWith("aria-")||e==="role"||e==="title")return!0;return!1};/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const y=t=>t==="";/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const L=(...t)=>t.filter((e,r,n)=>!!e&&e.trim()!==""&&n.indexOf(e)===r).join(" ").trim();/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const k=t=>t.replace(/([a-z0-9])([A-Z])/g,"$1-$2").toLowerCase();/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const $=t=>t.replace(/^([A-Z])|[\s-_]+(\w)/g,(e,r,n)=>n?n.toUpperCase():r.toLowerCase());/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const I=t=>{const e=$(t);return e.charAt(0).toUpperCase()+e.slice(1)};/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */var m={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor","stroke-width":2,"stroke-linecap":"round","stroke-linejoin":"round"};/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const q=({name:t,iconNode:e,absoluteStrokeWidth:r,"absolute-stroke-width":n,strokeWidth:s,"stroke-width":a,size:f=m.width,color:o=m.stroke,...i},{slots:l})=>g("svg",{...m,...i,width:f,height:f,stroke:o,"stroke-width":y(r)||y(n)||r===!0||n===!0?Number(s||a||m["stroke-width"])*24/Number(f):s||a||m["stroke-width"],class:L("lucide",i.class,...t?[`lucide-${k(I(t))}-icon`,`lucide-${k(t)}`]:["lucide-icon"]),...!l.default&&!M(i)&&{"aria-hidden":"true"}},[...e.map(h=>g(...h)),...l.default?[l.default()]:[]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const p=(t,e)=>(r,{slots:n,attrs:s})=>g(q,{...s,...r,iconNode:e,name:t},n);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const N=p("circle-check",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"m9 12 2 2 4-4",key:"dzmm74"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const R=p("info",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"M12 16v-4",key:"1dtifu"}],["path",{d:"M12 8h.01",key:"e9boi3"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const T=p("loader-circle",[["path",{d:"M21 12a9 9 0 1 1-6.219-8.56",key:"13zald"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const U=p("octagon-x",[["path",{d:"m15 9-6 6",key:"1uzhvr"}],["path",{d:"M2.586 16.726A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2h6.624a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586z",key:"2d38gg"}],["path",{d:"m9 9 6 6",key:"z0biqf"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const V=p("triangle-alert",[["path",{d:"m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3",key:"wmoenq"}],["path",{d:"M12 9v4",key:"juzpu7"}],["path",{d:"M12 17h.01",key:"p32p05"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const X=p("x",[["path",{d:"M18 6 6 18",key:"1bl5f8"}],["path",{d:"m6 6 12 12",key:"d8bk6v"}]]),F=b({__name:"Sonner",props:{id:{},invert:{type:Boolean},theme:{},position:{},closeButtonPosition:{},hotkey:{},richColors:{type:Boolean},expand:{type:Boolean},duration:{},gap:{},visibleToasts:{},closeButton:{type:Boolean},toastOptions:{},class:{},style:{},offset:{},mobileOffset:{},dir:{},swipeDirections:{},icons:{},containerAriaLabel:{}},setup(t){const e=t;return(r,n)=>(O(),w(c(C),x({class:c(_)("toaster group",e.class)},e),{"success-icon":u(()=>[d(c(N),{class:"size-4"})]),"info-icon":u(()=>[d(c(R),{class:"size-4"})]),"warning-icon":u(()=>[d(c(V),{class:"size-4"})]),"error-icon":u(()=>[d(c(U),{class:"size-4"})]),"loading-icon":u(()=>[v("div",null,[d(c(T),{class:"size-4 animate-spin"})])]),"close-icon":u(()=>[d(c(X),{class:"size-4"})]),_:1},16,["class"]))}});function H(){const t=E(),e=B(),r=A(()=>n());j(()=>{r.value!==n()&&z(e)});function n(){return e.value&&"$el"in e.value&&["#text","#comment"].includes(e.value.$el.nodeName)?e.value.$el.nextElementSibling:P(e)}const s=Object.assign({},t.exposed),a={};for(const o in t.props)Object.defineProperty(a,o,{enumerable:!0,configurable:!0,get:()=>t.props[o]});if(Object.keys(s).length>0)for(const o in s)Object.defineProperty(a,o,{enumerable:!0,configurable:!0,get:()=>s[o]});Object.defineProperty(a,"$el",{enumerable:!0,configurable:!0,get:()=>t.vnode.el}),t.exposed=a;function f(o){if(e.value=o,!!o&&(Object.defineProperty(a,"$el",{enumerable:!0,configurable:!0,get:()=>o instanceof Element?o:o.$el}),!(o instanceof Element)&&!Object.hasOwn(o,"$el"))){const i=o.$.exposed,l=Object.assign({},a);for(const h in i)Object.defineProperty(l,h,{enumerable:!0,configurable:!0,get:()=>i[h]});t.exposed=l}}return{forwardRef:f,currentRef:e,currentElement:r}}export{X,F as _,p as c,H as u};
