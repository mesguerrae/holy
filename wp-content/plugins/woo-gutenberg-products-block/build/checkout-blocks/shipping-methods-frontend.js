(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[41],{126:function(e,t,n){"use strict";n(155),t.a=function(){return React.createElement("span",{className:"wc-block-components-spinner","aria-hidden":"true"})}},155:function(e,t){},156:function(e,t){},170:function(e,t,n){"use strict";var c=n(1),a=(n(8),n(7)),r=n.n(a),o=(n(205),n(126));t.a=function(e){var t=e.children,n=e.className,a=e.screenReaderLabel,i=e.showSpinner,s=void 0!==i&&i,l=e.isLoading,p=void 0===l||l;return React.createElement("div",{className:r()(n,{"wc-block-components-loading-mask":p})},p&&s&&React.createElement(o.a,null),React.createElement("div",{className:r()({"wc-block-components-loading-mask__children":p}),"aria-hidden":p},t),p&&React.createElement("span",{className:"screen-reader-text"},a||Object(c.__)("Loading…","woo-gutenberg-products-block")))}},205:function(e,t){},276:function(e,t,n){"use strict";var c=n(20),a=n.n(c),r=n(26),o=n.n(r),i=n(7),s=n.n(i),l=(n(8),n(277),["children","className","headingLevel"]);t.a=function(e){var t=e.children,n=e.className,c=e.headingLevel,r=o()(e,l),i=s()("wc-block-components-title",n),p="h".concat(c);return React.createElement(p,a()({className:i},r),t)}},277:function(e,t){},278:function(e,t,n){"use strict";t.a=function(e){var t=e.label,n=e.secondaryLabel,c=e.description,a=e.secondaryDescription,r=e.id;return React.createElement("div",{className:"wc-block-components-radio-control__option-layout"},React.createElement("div",{className:"wc-block-components-radio-control__label-group"},t&&React.createElement("span",{id:r&&"".concat(r,"__label"),className:"wc-block-components-radio-control__label"},t),n&&React.createElement("span",{id:r&&"".concat(r,"__secondary-label"),className:"wc-block-components-radio-control__secondary-label"},n)),React.createElement("div",{className:"wc-block-components-radio-control__description-group"},c&&React.createElement("span",{id:r&&"".concat(r,"__description"),className:"wc-block-components-radio-control__description"},c),a&&React.createElement("span",{id:r&&"".concat(r,"__secondary-description"),className:"wc-block-components-radio-control__secondary-description"},a)))}},279:function(e,t,n){"use strict";var c=n(5),a=n.n(c),r=n(7),o=n.n(r),i=n(278);t.a=function(e){var t,n=e.checked,c=e.name,r=e.onChange,s=e.option,l=s.value,p=s.label,u=s.description,d=s.secondaryLabel,b=s.secondaryDescription;return React.createElement("label",{className:o()("wc-block-components-radio-control__option",{"wc-block-components-radio-control__option-checked":n}),htmlFor:"".concat(c,"-").concat(l)},React.createElement("input",{id:"".concat(c,"-").concat(l),className:"wc-block-components-radio-control__input",type:"radio",name:c,value:l,onChange:function(e){return r(e.target.value)},checked:n,"aria-describedby":o()((t={},a()(t,"".concat(c,"-").concat(l,"__label"),p),a()(t,"".concat(c,"-").concat(l,"__secondary-label"),d),a()(t,"".concat(c,"-").concat(l,"__description"),u),a()(t,"".concat(c,"-").concat(l,"__secondary-description"),b),t))}),React.createElement(i.a,{id:"".concat(c,"-").concat(l),label:p,secondaryLabel:d,description:u,secondaryDescription:b}))}},280:function(e,t){},282:function(e,t,n){"use strict";var c=n(1);t.a=function(e){var t=e.defaultTitle,n=void 0===t?Object(c.__)("Step","woo-gutenberg-products-block"):t,a=e.defaultDescription,r=void 0===a?Object(c.__)("Step description text.","woo-gutenberg-products-block"):a,o=e.defaultShowStepNumber;return{title:{type:"string",default:n},description:{type:"string",default:r},showStepNumber:{type:"boolean",default:void 0===o||o}}}},285:function(e,t,n){"use strict";var c=n(7),a=n.n(c),r=n(25),o=n(279);n(286),t.a=Object(r.withInstanceId)((function(e){var t=e.className,n=e.instanceId,c=e.id,r=e.selected,i=e.onChange,s=e.options,l=void 0===s?[]:s,p=c||n;return l.length&&React.createElement("div",{className:a()("wc-block-components-radio-control",t)},l.map((function(e){return React.createElement(o.a,{key:"".concat(p,"-").concat(e.value),name:"radio-control-".concat(p),checked:e.value===r,option:e,onChange:function(t){i(t),"function"==typeof e.onChange&&e.onChange(t)}})})))}))},286:function(e,t){},289:function(e,t){},290:function(e,t,n){"use strict";var c=n(7),a=n.n(c),r=(n(8),n(276)),o=(n(280),function(e){var t=e.title,n=e.stepHeadingContent;return React.createElement("div",{className:"wc-block-components-checkout-step__heading"},React.createElement(r.a,{"aria-hidden":"true",className:"wc-block-components-checkout-step__title",headingLevel:"2"},t),!!n&&React.createElement("span",{className:"wc-block-components-checkout-step__heading-content"},n))});t.a=function(e){var t=e.id,n=e.className,c=e.title,r=e.legend,i=e.description,s=e.children,l=e.disabled,p=void 0!==l&&l,u=e.showStepNumber,d=void 0===u||u,b=e.stepHeadingContent,m=void 0===b?function(){}:b,g=r||c?"fieldset":"div";return React.createElement(g,{className:a()(n,"wc-block-components-checkout-step",{"wc-block-components-checkout-step--with-step-number":d,"wc-block-components-checkout-step--disabled":p}),id:t,disabled:p},!(!r&&!c)&&React.createElement("legend",{className:"screen-reader-text"},r||c),!!c&&React.createElement(o,{title:c,stepHeadingContent:m()}),React.createElement("div",{className:"wc-block-components-checkout-step__container"},!!i&&React.createElement("p",{className:"wc-block-components-checkout-step__description"},i),React.createElement("div",{className:"wc-block-components-checkout-step__content"},s)))}},293:function(e,t,n){"use strict";var c=n(26),a=n.n(c),r=n(1),o=n(0),i=n(34),s=n(170),l=n(28),p=n(319),u=n(33),d=n(36),b=n(7),m=n.n(b),g=n(32),f=n(49),h=n(4),O=n.n(h),v=n(19),w=n.n(v),j=n(115),_=n(52),R=function(e){var t;return null===(t=e.find((function(e){return e.selected})))||void 0===t?void 0:t.rate_id},k=n(285),y=n(278),E=n(60),N=n(85),S=n(2),P=function(e){var t=Object(S.getSetting)("displayCartPricesIncludingTax",!1)?parseInt(e.price,10)+parseInt(e.taxes,10):parseInt(e.price,10);return{label:Object(g.decodeEntities)(e.name),value:e.rate_id,description:React.createElement(React.Fragment,null,Number.isFinite(t)&&React.createElement(N.a,{currency:Object(E.getCurrencyFromPriceResponse)(e),value:t}),Number.isFinite(t)&&e.delivery_time?" — ":null,Object(g.decodeEntities)(e.delivery_time))}},C=function(e){var t=e.className,n=e.noResultsMessage,c=e.onSelectRate,a=e.rates,r=e.renderOption,o=void 0===r?P:r,i=e.selected;if(0===a.length)return n;if(a.length>1)return React.createElement(k.a,{className:t,onChange:function(e){c(e)},selected:i,options:a.map(o)});var s=o(a[0]),l=s.label,p=s.secondaryLabel,u=s.description,d=s.secondaryDescription;return React.createElement(y.a,{label:l,secondaryLabel:p,description:u,secondaryDescription:d})},D=(n(289),function(e){var t=e.packageId,n=e.className,c=e.noResultsMessage,a=e.renderOption,i=e.packageData,s=e.collapsible,p=void 0!==s&&s,u=e.collapse,d=void 0!==u&&u,b=e.showItems,h=void 0!==b&&b,v=function(e,t){var n=Object(_.a)().dispatchCheckoutEvent,c=Object(j.a)(),a=c.selectShippingRate,r=c.isSelectingRate,i=Object(o.useState)((function(){return R(t)})),s=O()(i,2),l=s[0],p=s[1],u=Object(o.useRef)(t);return Object(o.useEffect)((function(){w()(u.current,t)||(u.current=t,p(R(t)))}),[t]),{selectShippingRate:Object(o.useCallback)((function(t){p(t),a(t,e),n("set-selected-shipping-rate",{shippingRateId:t})}),[e,a,n]),selectedShippingRate:l,isSelectingRate:r}}(t,i.shipping_rates),k=v.selectShippingRate,y=v.selectedShippingRate,E=React.createElement(React.Fragment,null,(h||p)&&React.createElement("div",{className:"wc-block-components-shipping-rates-control__package-title"},i.name),h&&React.createElement("ul",{className:"wc-block-components-shipping-rates-control__package-items"},Object.values(i.items).map((function(e){var t=Object(g.decodeEntities)(e.name),n=e.quantity;return React.createElement("li",{key:e.key,className:"wc-block-components-shipping-rates-control__package-item"},React.createElement(f.a,{label:n>1?"".concat(t," × ").concat(n):"".concat(t),screenReaderLabel:Object(r.sprintf)(
/* translators: %1$s name of the product (ie: Sunglasses), %2$d number of units in the current cart package */
Object(r._n)("%1$s (%2$d unit)","%1$s (%2$d units)",n,"woo-gutenberg-products-block"),t,n)}))})))),N=React.createElement(C,{className:n,noResultsMessage:c,rates:i.shipping_rates,onSelectRate:k,selected:y,renderOption:a});return p?React.createElement(l.Panel,{className:"wc-block-components-shipping-rates-control__package",initialOpen:!d,title:E},N):React.createElement("div",{className:m()("wc-block-components-shipping-rates-control__package",n)},E,N)}),F=["package_id"],L=["extensions","receiveCart"],x=function(e){var t=e.packages,n=e.collapse,c=e.showItems,r=e.collapsible,o=e.noResultsMessage,i=e.renderOption;return t.length?React.createElement(React.Fragment,null,t.map((function(e){var t=e.package_id,s=a()(e,F);return React.createElement(D,{key:t,packageId:t,packageData:s,collapsible:r,collapse:n,showItems:c,noResultsMessage:o,renderOption:i})}))):null};t.a=function(e){var t=e.shippingRates,n=e.shippingRatesLoading,c=e.className,b=e.collapsible,m=void 0!==b&&b,g=e.noResultsMessage,f=e.renderOption;Object(o.useEffect)((function(){if(!n){var e=Object(p.a)(t),c=Object(p.b)(t);1===e?Object(i.speak)(Object(r.sprintf)(
/* translators: %d number of shipping options found. */
Object(r._n)("%d shipping option was found.","%d shipping options were found.",c,"woo-gutenberg-products-block"),c)):Object(i.speak)(Object(r.sprintf)(
/* translators: %d number of shipping packages packages. */
Object(r._n)("Shipping option searched for %d package.","Shipping options searched for %d packages.",e,"woo-gutenberg-products-block"),e)+" "+Object(r.sprintf)(
/* translators: %d number of shipping options available. */
Object(r._n)("%d shipping option was found","%d shipping options were found",c,"woo-gutenberg-products-block"),c))}}),[n,t]);var h=Object(u.a)(),O=h.extensions,v=(h.receiveCart,{className:c,collapsible:m,noResultsMessage:g,renderOption:f,extensions:O,cart:a()(h,L),components:{ShippingRatesControlPackage:D}}),w=Object(d.a)().isEditor;return React.createElement(s.a,{isLoading:n,screenReaderLabel:Object(r.__)("Loading shipping rates…","woo-gutenberg-products-block"),showSpinner:!0},w?React.createElement(x,{packages:t,noResultsMessage:g,renderOption:f}):React.createElement(React.Fragment,null,React.createElement(l.ExperimentalOrderShippingPackages.Slot,v),React.createElement(l.ExperimentalOrderShippingPackages,null,React.createElement(x,{packages:t,noResultsMessage:g,renderOption:f}))))}},308:function(e,t,n){"use strict";n.d(t,"a",(function(){return b}));var c=n(5),a=n.n(c),r=n(26),o=n.n(r),i=n(2),s=n(0),l=n(65),p=n(57),u=["email"];function d(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,c)}return n}var b=function(){var e=Object(l.b)().needsShipping,t=Object(p.b)(),n=t.billingData,c=t.setBillingData,r=t.shippingAddress,b=t.setShippingAddress,m=t.shippingAsBilling,g=t.setShippingAsBilling,f=Object(s.useRef)(m),h=Object(s.useRef)(n),O=Object(s.useCallback)((function(e){b(e),m&&c(e)}),[m,b,c]),v=Object(s.useCallback)((function(t){c(t),e||b(t)}),[e,b,c]);Object(s.useEffect)((function(){if(f.current!==m){if(m)h.current=n,c(r);else{var e=h.current,t=(e.email,o()(e,u));c(function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?d(Object(n),!0).forEach((function(t){a()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):d(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({},t))}f.current=m}}),[m,c,r,n]);var w=Object(s.useCallback)((function(e){c({email:e})}),[c]),j=Object(s.useCallback)((function(e){c({phone:e})}),[c]),_=Object(s.useCallback)((function(e){O({phone:e})}),[O]);return{defaultAddressFields:i.defaultAddressFields,shippingFields:r,setShippingFields:O,billingFields:n,setBillingFields:v,setEmail:w,setPhone:j,setShippingPhone:_,shippingAsBilling:m,setShippingAsBilling:g,showShippingFields:e,showBillingFields:!e||!f.current}}},317:function(e,t,n){"use strict";var c=n(13),a=n(23),r=n(18),o=n(0),i=n(7),s=n.n(i),l=n(25),p=n(86);t.a=function(e){var t,n=e.icon,i=e.children,u=e.label,d=e.instructions,b=e.className,m=e.notices,g=e.preview,f=e.isColumnLayout,h=Object(r.a)(e,["icon","children","label","instructions","className","notices","preview","isColumnLayout"]),O=Object(l.useResizeObserver)(),v=Object(a.a)(O,2),w=v[0],j=v[1].width;"number"==typeof j&&(t={"is-large":j>=320,"is-medium":j>=160&&j<320,"is-small":j<160});var _=s()("components-placeholder",b,t),R=s()("components-placeholder__fieldset",{"is-column-layout":f});return Object(o.createElement)("div",Object(c.a)({},h,{className:_}),w,m,g&&Object(o.createElement)("div",{className:"components-placeholder__preview"},g),Object(o.createElement)("div",{className:"components-placeholder__label"},Object(o.createElement)(p.a,{icon:n}),u),!!d&&Object(o.createElement)("div",{className:"components-placeholder__instructions"},d),Object(o.createElement)("div",{className:R},i))}},319:function(e,t,n){"use strict";n.d(t,"a",(function(){return c})),n.d(t,"b",(function(){return a}));var c=function(e){return e.length},a=function(e){return e.reduce((function(e,t){return e+t.shipping_rates.length}),0)}},359:function(e,t){},360:function(e,t){},395:function(e,t,n){"use strict";n.r(t);var c=n(7),a=n.n(c),r=n(154),o=n(290),i=n(54),s=n(308),l=n(1),p=n(293),u=n(319),d=n(60),b=n(85),m=n(36),g=n(65),f=n(32),h=n(152),O=n(2),v=n(317),w=n(81),j=n(128),_=n(44),R=React.createElement(_.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 17 13"},React.createElement("path",{fill:"currentColor",fillRule:"evenodd",d:"M11.667 2.5h1.666l3.334 3.333V10H15a2.5 2.5 0 11-5 0H6.667a2.5 2.5 0 11-5 0H0V1.667C0 .746.746 0 1.667 0H10c.92 0 1.667.746 1.667 1.667V2.5zM2.917 10a1.25 1.25 0 102.5 0 1.25 1.25 0 00-2.5 0zm-1.25-2.5V1.667H10V7.5H1.667zM11.25 10a1.25 1.25 0 102.5 0 1.25 1.25 0 00-2.5 0z"})),k=(n(360),function(){return React.createElement(v.a,{icon:React.createElement(j.a,{srcElement:R}),label:Object(l.__)("Shipping options","woo-gutenberg-products-block"),className:"wc-block-checkout__no-shipping-placeholder"},React.createElement("span",{className:"wc-block-checkout__no-shipping-placeholder-description"},Object(l.__)("Your store does not have any Shipping Options configured. Once you have added your Shipping Options they will appear here.","woo-gutenberg-products-block")),React.createElement(w.a,{isSecondary:!0,href:"".concat(O.ADMIN_URL,"admin.php?page=wc-settings&tab=shipping"),target:"_blank",rel:"noopener noreferrer"},Object(l.__)("Configure Shipping Options","woo-gutenberg-products-block")))}),y=(n(359),function(e){var t=Object(O.getSetting)("displayCartPricesIncludingTax",!1)?parseInt(e.price,10)+parseInt(e.taxes,10):parseInt(e.price,10);return{label:Object(f.decodeEntities)(e.name),value:e.rate_id,description:Object(f.decodeEntities)(e.description),secondaryLabel:React.createElement(b.a,{currency:Object(d.getCurrencyFromPriceResponse)(e),value:t}),secondaryDescription:Object(f.decodeEntities)(e.delivery_time)}}),E=function(){var e=Object(m.a)().isEditor,t=Object(g.b)(),n=t.shippingRates,c=t.shippingRatesLoading,r=t.needsShipping,o=t.hasCalculatedShipping;if(!r)return null;var i=Object(u.a)(n);return e||o||i?React.createElement(React.Fragment,null,e&&!i?React.createElement(k,null):React.createElement(p.a,{noResultsMessage:React.createElement(h.a,{isDismissible:!1,className:a()("wc-block-components-shipping-rates-control__no-results-notice","woocommerce-error")},Object(l.__)("There are no shipping options available. Please check your shipping address.","woo-gutenberg-products-block")),renderOption:y,shippingRates:n,shippingRatesLoading:c})):React.createElement("p",null,Object(l.__)("Shipping options will be displayed here after entering your full shipping address.","woo-gutenberg-products-block"))},N=n(5),S=n.n(N),P=n(282);function C(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,c)}return n}function D(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?C(Object(n),!0).forEach((function(t){S()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):C(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var F=D(D({},Object(P.a)({defaultTitle:Object(l.__)("Shipping options","woo-gutenberg-products-block"),defaultDescription:""})),{},{allowCreateAccount:{type:"boolean",default:!1},className:{type:"string",default:""},lock:{type:"object",default:{move:!0,remove:!0}}});t.default=Object(r.withFilteredAttributes)(F)((function(e){var t=e.title,n=e.description,c=e.showStepNumber,r=e.children,l=e.className,p=Object(i.b)().isProcessing;return Object(s.a)().showShippingFields?React.createElement(o.a,{id:"shipping-option",disabled:p,className:a()("wc-block-checkout__shipping-option",l),title:t,description:n,showStepNumber:c},React.createElement(E,null),r):null}))},49:function(e,t,n){"use strict";var c=n(5),a=n.n(c),r=n(0),o=n(7),i=n.n(o);function s(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,c)}return n}function l(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?s(Object(n),!0).forEach((function(t){a()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):s(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}t.a=function(e){var t,n=e.label,c=e.screenReaderLabel,a=e.wrapperElement,o=e.wrapperProps,s=void 0===o?{}:o,p=null!=n,u=null!=c;return!p&&u?(t=a||"span",s=l(l({},s),{},{className:i()(s.className,"screen-reader-text")}),React.createElement(t,s,c)):(t=a||r.Fragment,p&&u&&n!==c?React.createElement(t,s,React.createElement("span",{"aria-hidden":"true"},n),React.createElement("span",{className:"screen-reader-text"},c)):React.createElement(t,s,n))}},85:function(e,t,n){"use strict";var c=n(20),a=n.n(c),r=n(5),o=n.n(r),i=n(26),s=n.n(i),l=n(127),p=n(7),u=n.n(p),d=(n(156),["className","value","currency","onValueChange","displayType"]);function b(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,c)}return n}function m(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?b(Object(n),!0).forEach((function(t){o()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):b(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}t.a=function(e){var t=e.className,n=e.value,c=e.currency,r=e.onValueChange,o=e.displayType,i=void 0===o?"text":o,p=s()(e,d),b="string"==typeof n?parseInt(n,10):n;if(!Number.isFinite(b))return null;var g=b/Math.pow(10,c.minorUnit);if(!Number.isFinite(g))return null;var f=u()("wc-block-formatted-money-amount","wc-block-components-formatted-money-amount",t),h=m(m(m({},p),function(e){return{thousandSeparator:e.thousandSeparator,decimalSeparator:e.decimalSeparator,decimalScale:e.minorUnit,fixedDecimalScale:!0,prefix:e.prefix,suffix:e.suffix,isNumericString:!0}}(c)),{},{value:void 0,currency:void 0,onValueChange:void 0}),O=r?function(e){var t=e.value*Math.pow(10,c.minorUnit);r(t)}:function(){};return React.createElement(l.a,a()({className:f,displayType:i},h,{value:g,onValueChange:O}))}}}]);