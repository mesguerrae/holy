this.wc=this.wc||{},this.wc.blocks=this.wc.blocks||{},this.wc.blocks["stock-filter"]=function(e){function t(t){for(var c,u,a=t[0],l=t[1],i=t[2],b=0,f=[];b<a.length;b++)u=a[b],Object.prototype.hasOwnProperty.call(r,u)&&r[u]&&f.push(r[u][0]),r[u]=0;for(c in l)Object.prototype.hasOwnProperty.call(l,c)&&(e[c]=l[c]);for(s&&s(t);f.length;)f.shift()();return o.push.apply(o,i||[]),n()}function n(){for(var e,t=0;t<o.length;t++){for(var n=o[t],c=!0,a=1;a<n.length;a++){var l=n[a];0!==r[l]&&(c=!1)}c&&(o.splice(t--,1),e=u(u.s=n[0]))}return e}var c={},r={40:0},o=[];function u(t){if(c[t])return c[t].exports;var n=c[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,u),n.l=!0,n.exports}u.m=e,u.c=c,u.d=function(e,t,n){u.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},u.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},u.t=function(e,t){if(1&t&&(e=u(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(u.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var c in e)u.d(n,c,function(t){return e[t]}.bind(null,c));return n},u.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return u.d(t,"a",t),t},u.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},u.p="";var a=window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[],l=a.push.bind(a);a.push=t,a=a.slice();for(var i=0;i<a.length;i++)t(a[i]);var s=l;return o.push([792,0]),n()}({0:function(e,t){e.exports=window.wp.element},1:function(e,t){e.exports=window.wp.i18n},104:function(e,t,n){"use strict";n.d(t,"a",(function(){return p})),n.d(t,"b",(function(){return d})),n.d(t,"c",(function(){return O}));var c=n(8),r=n.n(c),o=n(25),u=n(14),a=n(0),l=n(36),i=n.n(l),s=n(58),b=n(143),f=n(71),p=function(e){var t=Object(f.a)();e=e||t;var n=Object(u.useSelect)((function(t){return t(o.QUERY_STATE_STORE_KEY).getValueForQueryContext(e,void 0)}),[e]),c=Object(u.useDispatch)(o.QUERY_STATE_STORE_KEY).setValueForQueryContext;return[n,Object(a.useCallback)((function(t){c(e,t)}),[e,c])]},d=function(e,t,n){var c=Object(f.a)();n=n||c;var r=Object(u.useSelect)((function(c){return c(o.QUERY_STATE_STORE_KEY).getValueForQueryKey(n,e,t)}),[n,e]),l=Object(u.useDispatch)(o.QUERY_STATE_STORE_KEY).setQueryValue;return[r,Object(a.useCallback)((function(t){l(n,e,t)}),[n,e,l])]},O=function(e,t){var n=Object(f.a)(),c=p(t=t||n),o=r()(c,2),u=o[0],l=o[1],d=Object(s.a)(u),O=Object(s.a)(e),v=Object(b.a)(O),j=Object(a.useRef)(!1);return Object(a.useEffect)((function(){i()(v,O)||(l(Object.assign({},d,O)),j.current=!0)}),[d,O,v,l]),j.current?[u,l]:[e,l]}},11:function(e,t){e.exports=window.React},12:function(e,t){e.exports=window.wp.blockEditor},121:function(e,t,n){"use strict";var c=n(16),r=n.n(c),o=n(17),u=n.n(o),a=n(18),l=n.n(a),i=n(19),s=n.n(i),b=n(9),f=n.n(b),p=n(0),d=n(7),O=n(1),v=n(3);function j(e){var t=e.level,n={1:"M9 5h2v10H9v-4H5v4H3V5h2v4h4V5zm6.6 0c-.6.9-1.5 1.7-2.6 2v1h2v7h2V5h-1.4z",2:"M7 5h2v10H7v-4H3v4H1V5h2v4h4V5zm8 8c.5-.4.6-.6 1.1-1.1.4-.4.8-.8 1.2-1.3.3-.4.6-.8.9-1.3.2-.4.3-.8.3-1.3 0-.4-.1-.9-.3-1.3-.2-.4-.4-.7-.8-1-.3-.3-.7-.5-1.2-.6-.5-.2-1-.2-1.5-.2-.4 0-.7 0-1.1.1-.3.1-.7.2-1 .3-.3.1-.6.3-.9.5-.3.2-.6.4-.8.7l1.2 1.2c.3-.3.6-.5 1-.7.4-.2.7-.3 1.2-.3s.9.1 1.3.4c.3.3.5.7.5 1.1 0 .4-.1.8-.4 1.1-.3.5-.6.9-1 1.2-.4.4-1 .9-1.6 1.4-.6.5-1.4 1.1-2.2 1.6V15h8v-2H15z",3:"M12.1 12.2c.4.3.8.5 1.2.7.4.2.9.3 1.4.3.5 0 1-.1 1.4-.3.3-.1.5-.5.5-.8 0-.2 0-.4-.1-.6-.1-.2-.3-.3-.5-.4-.3-.1-.7-.2-1-.3-.5-.1-1-.1-1.5-.1V9.1c.7.1 1.5-.1 2.2-.4.4-.2.6-.5.6-.9 0-.3-.1-.6-.4-.8-.3-.2-.7-.3-1.1-.3-.4 0-.8.1-1.1.3-.4.2-.7.4-1.1.6l-1.2-1.4c.5-.4 1.1-.7 1.6-.9.5-.2 1.2-.3 1.8-.3.5 0 1 .1 1.6.2.4.1.8.3 1.2.5.3.2.6.5.8.8.2.3.3.7.3 1.1 0 .5-.2.9-.5 1.3-.4.4-.9.7-1.5.9v.1c.6.1 1.2.4 1.6.8.4.4.7.9.7 1.5 0 .4-.1.8-.3 1.2-.2.4-.5.7-.9.9-.4.3-.9.4-1.3.5-.5.1-1 .2-1.6.2-.8 0-1.6-.1-2.3-.4-.6-.2-1.1-.6-1.6-1l1.1-1.4zM7 9H3V5H1v10h2v-4h4v4h2V5H7v4z",4:"M9 15H7v-4H3v4H1V5h2v4h4V5h2v10zm10-2h-1v2h-2v-2h-5v-2l4-6h3v6h1v2zm-3-2V7l-2.8 4H16z",5:"M12.1 12.2c.4.3.7.5 1.1.7.4.2.9.3 1.3.3.5 0 1-.1 1.4-.4.4-.3.6-.7.6-1.1 0-.4-.2-.9-.6-1.1-.4-.3-.9-.4-1.4-.4H14c-.1 0-.3 0-.4.1l-.4.1-.5.2-1-.6.3-5h6.4v1.9h-4.3L14 8.8c.2-.1.5-.1.7-.2.2 0 .5-.1.7-.1.5 0 .9.1 1.4.2.4.1.8.3 1.1.6.3.2.6.6.8.9.2.4.3.9.3 1.4 0 .5-.1 1-.3 1.4-.2.4-.5.8-.9 1.1-.4.3-.8.5-1.3.7-.5.2-1 .3-1.5.3-.8 0-1.6-.1-2.3-.4-.6-.2-1.1-.6-1.6-1-.1-.1 1-1.5 1-1.5zM9 15H7v-4H3v4H1V5h2v4h4V5h2v10z",6:"M9 15H7v-4H3v4H1V5h2v4h4V5h2v10zm8.6-7.5c-.2-.2-.5-.4-.8-.5-.6-.2-1.3-.2-1.9 0-.3.1-.6.3-.8.5l-.6.9c-.2.5-.2.9-.2 1.4.4-.3.8-.6 1.2-.8.4-.2.8-.3 1.3-.3.4 0 .8 0 1.2.2.4.1.7.3 1 .6.3.3.5.6.7.9.2.4.3.8.3 1.3s-.1.9-.3 1.4c-.2.4-.5.7-.8 1-.4.3-.8.5-1.2.6-1 .3-2 .3-3 0-.5-.2-1-.5-1.4-.9-.4-.4-.8-.9-1-1.5-.2-.6-.3-1.3-.3-2.1s.1-1.6.4-2.3c.2-.6.6-1.2 1-1.6.4-.4.9-.7 1.4-.9.6-.3 1.1-.4 1.7-.4.7 0 1.4.1 2 .3.5.2 1 .5 1.4.8 0 .1-1.3 1.4-1.3 1.4zm-2.4 5.8c.2 0 .4 0 .6-.1.2 0 .4-.1.5-.2.1-.1.3-.3.4-.5.1-.2.1-.5.1-.7 0-.4-.1-.8-.4-1.1-.3-.2-.7-.3-1.1-.3-.3 0-.7.1-1 .2-.4.2-.7.4-1 .7 0 .3.1.7.3 1 .1.2.3.4.4.6.2.1.3.3.5.3.2.1.5.2.7.1z"};return n.hasOwnProperty(t)?Object(p.createElement)(v.SVG,{width:"20",height:"20",viewBox:"0 0 20 20",xmlns:"http://www.w3.org/2000/svg"},Object(p.createElement)(v.Path,{d:n[t]})):null}var m=function(e){l()(o,e);var t,n,c=(t=o,n=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}(),function(){var e,c=f()(t);if(n){var r=f()(this).constructor;e=Reflect.construct(c,arguments,r)}else e=c.apply(this,arguments);return s()(this,e)});function o(){return r()(this,o),c.apply(this,arguments)}return u()(o,[{key:"createLevelControl",value:function(e,t,n){var c=e===t;return{icon:Object(p.createElement)(j,{level:e}),title:Object(O.sprintf)(
/* translators: %s: heading level e.g: "2", "3", "4" */
Object(O.__)("Heading %d","woo-gutenberg-products-block"),e),isActive:c,onClick:function(){return n(e)}}}},{key:"render",value:function(){var e=this,t=this.props,n=t.isCollapsed,c=void 0===n||n,r=t.minLevel,o=t.maxLevel,u=t.selectedLevel,a=t.onChange;return Object(p.createElement)(v.ToolbarGroup,{isCollapsed:c,icon:Object(p.createElement)(j,{level:u}),controls:Object(d.range)(r,o).map((function(t){return e.createLevelControl(t,u,a)}))})}}]),o}(p.Component);t.a=m},127:function(e,t,n){"use strict";n.d(t,"a",(function(){return u}));var c=n(8),r=n.n(c),o=n(0),u=function(){var e=Object(o.useState)(),t=r()(e,2)[1];return Object(o.useCallback)((function(e){t((function(){throw e}))}),[])}},129:function(e,t,n){"use strict";var c=n(0),r=n(1),o=n(42);n(329),t.a=function(e){var t=e.name,n=e.count;return Object(c.createElement)(c.Fragment,null,t,Number.isFinite(n)&&Object(c.createElement)(o.a,{label:n,screenReaderLabel:Object(r.sprintf)(
/* translators: %s number of products. */
Object(r._n)("%s product","%s products",n,"woo-gutenberg-products-block"),n),wrapperElement:"span",wrapperProps:{className:"wc-filter-element-label-list-count"}}))}},14:function(e,t){e.exports=window.wp.data},141:function(e,t,n){"use strict";var c=n(0),r=(n(2),n(12)),o=n(20),u=n(1);n(194),t.a=Object(o.withInstanceId)((function(e){var t=e.className,n=e.headingLevel,o=e.onChange,a=e.heading,l=e.instanceId,i="h".concat(n);return Object(c.createElement)(i,{className:t},Object(c.createElement)("label",{className:"screen-reader-text",htmlFor:"block-title-".concat(l)},Object(u.__)("Block title","woo-gutenberg-products-block")),Object(c.createElement)(r.PlainText,{id:"block-title-".concat(l),className:"wc-block-editor-components-title",value:a,onChange:o}))}))},143:function(e,t,n){"use strict";n.d(t,"a",(function(){return r}));var c=n(11);function r(e,t){var n=Object(c.useRef)();return Object(c.useEffect)((function(){n.current===e||t&&!t(e,n.current)||(n.current=e)}),[e,t]),n.current}},149:function(e,t,n){"use strict";n.d(t,"a",(function(){return l}));var c=n(25),r=n(14),o=n(0),u=n(58),a=n(127),l=function(e){var t=e.namespace,n=e.resourceName,l=e.resourceValues,i=void 0===l?[]:l,s=e.query,b=void 0===s?{}:s,f=e.shouldSelect,p=void 0===f||f;if(!t||!n)throw new Error("The options object must have valid values for the namespace and the resource properties.");var d=Object(o.useRef)({results:[],isLoading:!0}),O=Object(u.a)(b),v=Object(u.a)(i),j=Object(a.a)(),m=Object(r.useSelect)((function(e){if(!p)return null;var r=e(c.COLLECTIONS_STORE_KEY),o=[t,n,O,v],u=r.getCollectionError.apply(r,o);return u&&j(u),{results:r.getCollection.apply(r,o),isLoading:!r.hasFinishedResolution("getCollection",o)}}),[t,n,v,O,p]);return null!==m&&(d.current=m),d.current}},179:function(e,t,n){"use strict";var c=n(0),r=n(1),o=(n(2),n(6)),u=n.n(o),a=n(42),l=(n(241),function(e){var t=e.className,n=e.disabled,o=e.label,l=void 0===o?Object(r.__)("Go","woo-gutenberg-products-block"):o,i=e.onClick,s=e.screenReaderLabel,b=void 0===s?Object(r.__)("Apply filter","woo-gutenberg-products-block"):s;return Object(c.createElement)("button",{type:"submit",className:u()("wc-block-filter-submit-button","wc-block-components-filter-submit-button",t),disabled:n,onClick:i},Object(c.createElement)(a.a,{label:l,screenReaderLabel:b}))});l.defaultProps={disabled:!1},t.a=l},194:function(e,t){},20:function(e,t){e.exports=window.wp.compose},24:function(e,t){e.exports=window.wp.blocks},241:function(e,t){},247:function(e,t,n){"use strict";var c=n(34),r=n.n(c),o=n(8),u=n.n(o),a=n(0),l=n(1),i=(n(2),n(6)),s=n.n(i);n(331),t.a=function(e){var t=e.className,n=e.onChange,c=void 0===n?function(){}:n,o=e.options,i=void 0===o?[]:o,b=e.checked,f=void 0===b?[]:b,p=e.isLoading,d=void 0!==p&&p,O=e.isDisabled,v=void 0!==O&&O,j=e.limit,m=void 0===j?10:j,h=Object(a.useState)(!1),w=u()(h,2),g=w[0],y=w[1],k=Object(a.useMemo)((function(){return r()(Array(5)).map((function(e,t){return Object(a.createElement)("li",{key:t,style:{width:Math.floor(75*Math.random())+25+"%"}})}))}),[]),E=Object(a.useMemo)((function(){var e=i.length-m;return!g&&Object(a.createElement)("li",{key:"show-more",className:"show-more"},Object(a.createElement)("button",{onClick:function(){y(!0)},"aria-expanded":!1,"aria-label":Object(l.sprintf)(
/* translators: %s is referring the remaining count of options */
Object(l._n)("Show %s more option","Show %s more options",e,"woo-gutenberg-products-block"),e)},Object(l.sprintf)(
/* translators: %s number of options to reveal. */
Object(l._n)("Show %s more","Show %s more",e,"woo-gutenberg-products-block"),e)))}),[i,m,g]),_=Object(a.useMemo)((function(){return g&&Object(a.createElement)("li",{key:"show-less",className:"show-less"},Object(a.createElement)("button",{onClick:function(){y(!1)},"aria-expanded":!0,"aria-label":Object(l.__)("Show less options","woo-gutenberg-products-block")},Object(l.__)("Show less","woo-gutenberg-products-block")))}),[g]),P=Object(a.useMemo)((function(){var e=i.length>m+5;return Object(a.createElement)(a.Fragment,null,i.map((function(t,n){return Object(a.createElement)(a.Fragment,{key:t.value},Object(a.createElement)("li",e&&!g&&n>=m&&{hidden:!0},Object(a.createElement)("input",{type:"checkbox",id:t.value,value:t.value,onChange:function(e){c(e.target.value)},checked:f.includes(t.value),disabled:v}),Object(a.createElement)("label",{htmlFor:t.value},t.label)),e&&n===m-1&&E)})),e&&_)}),[i,c,f,g,m,_,E,v]),S=s()("wc-block-checkbox-list","wc-block-components-checkbox-list",{"is-loading":d},t);return Object(a.createElement)("ul",{className:S},d?k:P)}},25:function(e,t){e.exports=window.wc.wcBlocksData},26:function(e,t){e.exports=window.wp.htmlEntities},27:function(e,t){e.exports=window.wp.primitives},3:function(e,t){e.exports=window.wp.components},329:function(e,t){},331:function(e,t){},36:function(e,t){e.exports=window.wp.isShallowEqual},4:function(e,t){e.exports=window.wc.wcSettings},42:function(e,t,n){"use strict";var c=n(5),r=n.n(c),o=n(0),u=n(6),a=n.n(u);function l(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,c)}return n}function i(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?l(Object(n),!0).forEach((function(t){r()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):l(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}t.a=function(e){var t,n=e.label,c=e.screenReaderLabel,r=e.wrapperElement,u=e.wrapperProps,l=void 0===u?{}:u,s=null!=n,b=null!=c;return!s&&b?(t=r||"span",l=i(i({},l),{},{className:a()(l.className,"screen-reader-text")}),Object(o.createElement)(t,l,c)):(t=r||o.Fragment,s&&b&&n!==c?Object(o.createElement)(t,l,Object(o.createElement)("span",{"aria-hidden":"true"},n),Object(o.createElement)("span",{className:"screen-reader-text"},c)):Object(o.createElement)(t,l,n))}},487:function(e,t,n){"use strict";n.d(t,"a",(function(){return w}));var c=n(5),r=n.n(c),o=n(34),u=n.n(o),a=n(43),l=n.n(a),i=n(8),s=n.n(i),b=n(0),f=n(364),p=n(7),d=n(58),O=n(104),v=n(149),j=n(71);function m(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,c)}return n}function h(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?m(Object(n),!0).forEach((function(t){r()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):m(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var w=function(e){var t=e.queryAttribute,n=e.queryPrices,c=e.queryStock,r=e.queryState,o=Object(j.a)();o="".concat(o,"-collection-data");var a=Object(O.a)(o),i=s()(a,1)[0],m=Object(O.b)("calculate_attribute_counts",[],o),w=s()(m,2),g=w[0],y=w[1],k=Object(O.b)("calculate_price_range",null,o),E=s()(k,2),_=E[0],P=E[1],S=Object(O.b)("calculate_stock_status_counts",null,o),C=s()(S,2),x=C[0],N=C[1],H=Object(d.a)(t||{}),L=Object(d.a)(n),V=Object(d.a)(c);Object(b.useEffect)((function(){"object"===l()(H)&&Object.keys(H).length&&(g.find((function(e){return e.taxonomy===H.taxonomy}))||y([].concat(u()(g),[H])))}),[H,g,y]),Object(b.useEffect)((function(){_!==L&&void 0!==L&&P(L)}),[L,P,_]),Object(b.useEffect)((function(){x!==V&&void 0!==V&&N(V)}),[V,N,x]);var M=Object(b.useState)(!1),R=s()(M,2),F=R[0],T=R[1],D=Object(f.a)(F,200),z=s()(D,1)[0];F||T(!0);var B=Object(b.useMemo)((function(){return function(e){var t=e;return e.calculate_attribute_counts&&(t.calculate_attribute_counts=Object(p.sortBy)(e.calculate_attribute_counts.map((function(e){return{taxonomy:e.taxonomy,query_type:e.queryType}})),["taxonomy","query_type"])),t}(i)}),[i]);return Object(v.a)({namespace:"/wc/store",resourceName:"products/collection-data",query:h(h({},r),{},{page:void 0,per_page:void 0,orderby:void 0,order:void 0},B),shouldSelect:z})}},58:function(e,t,n){"use strict";n.d(t,"a",(function(){return u}));var c=n(0),r=n(36),o=n.n(r);function u(e){var t=Object(c.useRef)(e);return o()(e,t.current)||(t.current=e),t.current}},61:function(e,t,n){"use strict";var c=n(5),r=n.n(c),o=n(21),u=n.n(o),a=n(0),l=["srcElement","size"];function i(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,c)}return n}t.a=function(e){var t=e.srcElement,n=e.size,c=void 0===n?24:n,o=u()(e,l);return Object(a.isValidElement)(t)?Object(a.cloneElement)(t,function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?i(Object(n),!0).forEach((function(t){r()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):i(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({width:c,height:c},o)):null}},66:function(e,t){e.exports=window.wp.a11y},7:function(e,t){e.exports=window.lodash},71:function(e,t,n){"use strict";n.d(t,"a",(function(){return o}));var c=n(0),r=Object(c.createContext)("page"),o=function(){return Object(c.useContext)(r)};r.Provider},787:function(e,t,n){"use strict";var c=n(0),r=n(27),o=Object(c.createElement)(r.SVG,{xmlns:"http://www.w3.org/2000/SVG",viewBox:"0 0 24 24"},Object(c.createElement)("path",{fill:"none",d:"M0 0h24v24H0V0z"}),Object(c.createElement)("path",{d:"M19 15v4H5v-4h14m1-2H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zM7 18.5c-.82 0-1.5-.67-1.5-1.5s.68-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM19 5v4H5V5h14m1-2H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zM7 8.5c-.82 0-1.5-.67-1.5-1.5S6.18 5.5 7 5.5s1.5.68 1.5 1.5S7.83 8.5 7 8.5z"}));t.a=o},792:function(e,t,n){e.exports=n(862)},793:function(e,t){},794:function(e,t){},862:function(e,t,n){"use strict";n.r(t);var c=n(10),r=n.n(c),o=n(0),u=n(1),a=n(24),l=n(61),i=n(787),s=n(6),b=n.n(s),f=n(12),p=n(3),d=n(121),O=n(141),v=n(5),j=n.n(v),m=n(21),h=n.n(m),w=n(8),g=n.n(w),y=n(66),k=n(58),E=n(143),_=n(104),P=n(487),S=n(4),C=n(247),x=n(179),N=n(129),H=n(36),L=n.n(H),V=n(26),M=[{value:"preview-1",name:"In Stock",label:Object(o.createElement)(N.a,{name:"In Stock",count:3})},{value:"preview-2",name:"Out of sotck",label:Object(o.createElement)(N.a,{name:"Out of stock",count:3})},{value:"preview-3",name:"On backorder",label:Object(o.createElement)(N.a,{name:"On backorder",count:2})}],R=(n(794),["outofstock"]);function F(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,c)}return n}var T=function(e){var t=e.attributes,n=e.isEditor,c=void 0!==n&&n,r=Object(o.useState)(Object(S.getSetting)("hideOutOfStockItems",!1)),a=g()(r,1)[0],l=Object(o.useState)(Object(S.getSetting)("stockStatusOptions",{})),i=g()(l,1)[0],s=i.outofstock,b=h()(i,R),f=Object(o.useState)(a?b:function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?F(Object(n),!0).forEach((function(t){j()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):F(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({outofstock:s},b)),p=g()(f,1)[0],d=Object(o.useState)([]),O=g()(d,2),v=O[0],m=O[1],w=Object(o.useState)(t.isPreview?M:[]),H=g()(w,2),T=H[0],D=H[1],z=Object(o.useState)(Object.entries(p).map((function(e){var t=g()(e,2);return{slug:t[0],name:t[1]}})).filter((function(e){return!!e.name})).sort((function(e,t){return e.slug.localeCompare(t.slug)}))),B=g()(z,1)[0],A=Object(_.a)(),q=g()(A,1)[0],Y=Object(_.b)("stock_status",[]),I=g()(Y,2),Q=I[0],K=I[1],G=Object(P.a)({queryStock:!0,queryState:q}),U=G.results,W=G.isLoading,J=Object(o.useCallback)((function(e){return U.stock_status_counts?U.stock_status_counts.find((function(t){var n=t.status,c=t.count;return n===e&&0!==Number(c)})):null}),[U]);Object(o.useEffect)((function(){if(!W&&!t.isPreview){var e=B.map((function(e){var n,c=J(e.slug);if(!(c||v.includes(e.slug)||(n=e.slug,null!=q&&q.stock_status&&q.stock_status.some((function(e){var t=e.status;return(void 0===t?[]:t).includes(n)})))))return null;var r=c?Number(c.count):0;return{value:e.slug,name:Object(V.decodeEntities)(e.name),label:Object(o.createElement)(N.a,{name:Object(V.decodeEntities)(e.name),count:t.showCounts?r:null})}})).filter(Boolean);D(e)}}),[t.showCounts,t.isPreview,W,J,v,q.stock_status,B]);var X=Object(o.useCallback)((function(e){c||e&&K(v)}),[c,K,v]);Object(o.useEffect)((function(){t.showFilterButton||X(v)}),[t.showFilterButton,v,X]);var Z=Object(o.useMemo)((function(){return Q}),[Q]),$=Object(k.a)(Z),ee=Object(E.a)($);Object(o.useEffect)((function(){L()(ee,$)||L()(v,$)||m($)}),[v,$,ee]);var te=Object(o.useCallback)((function(e){var t=function(e){return T.find((function(t){return t.value===e})).name},n=function(e){var n=e.filterAdded,c=e.filterRemoved,r=n?t(n):null,o=c?t(c):null;r?Object(y.speak)(Object(u.sprintf)(
/* translators: %s stock statuses (for example: 'instock'...) */
Object(u.__)("%s filter added.","woo-gutenberg-products-block"),r)):o&&Object(y.speak)(Object(u.sprintf)(
/* translators: %s stock statuses (for example:'instock'...) */
Object(u.__)("%s filter removed.","woo-gutenberg-products-block"),o))},c=v.includes(e),r=v.filter((function(t){return t!==e}));c?n({filterRemoved:e}):(r.push(e),r.sort(),n({filterAdded:e})),m(r)}),[v,T]);if(0===T.length)return null;var ne="h".concat(t.headingLevel),ce=!t.isPreview&&!p,re=!t.isPreview&&W;return Object(o.createElement)(o.Fragment,null,!c&&t.heading&&Object(o.createElement)(ne,{className:"wc-block-stock-filter__title"},t.heading),Object(o.createElement)("div",{className:"wc-block-stock-filter"},Object(o.createElement)(C.a,{className:"wc-block-stock-filter-list",options:T,checked:v,onChange:te,isLoading:ce,isDisabled:re}),t.showFilterButton&&Object(o.createElement)(x.a,{className:"wc-block-stock-filter__button",disabled:ce||re,onClick:function(){return X(v)}})))},D=(n(793),Object(p.withSpokenMessages)((function(e){var t=e.attributes,n=e.setAttributes,c=t.className,r=t.heading,a=t.headingLevel,l=t.showCounts,i=t.showFilterButton;return Object(o.createElement)(o.Fragment,null,Object(o.createElement)(f.InspectorControls,{key:"inspector"},Object(o.createElement)(p.PanelBody,{title:Object(u.__)("Content","woo-gutenberg-products-block")},Object(o.createElement)(p.ToggleControl,{label:Object(u.__)("Product count","woo-gutenberg-products-block"),help:l?Object(u.__)("Product count is visible.","woo-gutenberg-products-block"):Object(u.__)("Product count is hidden.","woo-gutenberg-products-block"),checked:l,onChange:function(){return n({showCounts:!l})}}),Object(o.createElement)("p",null,Object(u.__)("Heading Level","woo-gutenberg-products-block")),Object(o.createElement)(d.a,{isCollapsed:!1,minLevel:2,maxLevel:7,selectedLevel:a,onChange:function(e){return n({headingLevel:e})}})),Object(o.createElement)(p.PanelBody,{title:Object(u.__)("Block Settings","woo-gutenberg-products-block")},Object(o.createElement)(p.ToggleControl,{label:Object(u.__)("Filter button","woo-gutenberg-products-block"),help:i?Object(u.__)("Products will only update when the button is pressed.","woo-gutenberg-products-block"):Object(u.__)("Products will update as options are selected.","woo-gutenberg-products-block"),checked:i,onChange:function(e){return n({showFilterButton:e})}}))),Object(o.createElement)("div",{className:b()("wc-block-stock-filter",c)},Object(o.createElement)(O.a,{className:"wc-block-stock-filter__title",headingLevel:a,heading:r,onChange:function(e){return n({heading:e})}}),Object(o.createElement)(p.Disabled,null,Object(o.createElement)(T,{attributes:t,isEditor:!0}))))})));Object(a.registerBlockType)("woocommerce/stock-filter",{title:Object(u.__)("Filter Products by Stock","woo-gutenberg-products-block"),icon:{src:Object(o.createElement)(l.a,{srcElement:i.a}),foreground:"#7f54b3"},category:"woocommerce",keywords:[Object(u.__)("WooCommerce","woo-gutenberg-products-block")],description:Object(u.__)("Allow customers to filter the grid by products stock status. Works in combination with the All Products block.","woo-gutenberg-products-block"),supports:{html:!1,multiple:!1},example:{attributes:{isPreview:!0}},attributes:{heading:{type:"string",default:Object(u.__)("Filter by stock status","woo-gutenberg-products-block")},headingLevel:{type:"number",default:3},showCounts:{type:"boolean",default:!0},showFilterButton:{type:"boolean",default:!1},isPreview:{type:"boolean",default:!1}},edit:D,save:function(e){var t=e.attributes,n=t.className,c=t.showCounts,u=t.heading,a=t.headingLevel,l=t.showFilterButton,i={"data-show-counts":c,"data-heading":u,"data-heading-level":a};return l&&(i["data-show-filter-button"]=l),Object(o.createElement)("div",r()({className:b()("is-loading",n)},i),Object(o.createElement)("span",{"aria-hidden":!0,className:"wc-block-product-stock-filter__placeholder"}))}})}});