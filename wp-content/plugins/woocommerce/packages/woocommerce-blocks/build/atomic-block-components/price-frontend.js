(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[12],{140:function(e,r,c){"use strict";var t=c(4),a=c.n(t),n=c(1),o=c(69),l=c(6),i=c.n(l),u=(c(10),c(58)),s=c(0),p=(c(183),function(e){var r=e.currency,c=e.maxPrice,t=e.minPrice,a=e.priceClassName,l=e.priceStyle;return React.createElement(React.Fragment,null,React.createElement("span",{className:"screen-reader-text"},Object(n.sprintf)(
/* translators: %1$s min price, %2$s max price */
Object(n.__)("Price between %1$s and %2$s",'woocommerce'),Object(u.formatPrice)(t),Object(u.formatPrice)(c))),React.createElement("span",{"aria-hidden":!0},React.createElement(o.a,{className:i()("wc-block-components-product-price__value",a),currency:r,value:t,style:l})," — ",React.createElement(o.a,{className:i()("wc-block-components-product-price__value",a),currency:r,value:c,style:l})))}),m=function(e){var r=e.currency,c=e.regularPriceClassName,t=e.regularPriceStyle,a=e.regularPrice,l=e.priceClassName,u=e.priceStyle,s=e.price;return React.createElement(React.Fragment,null,React.createElement("span",{className:"screen-reader-text"},Object(n.__)("Previous price:",'woocommerce')),React.createElement(o.a,{currency:r,renderText:function(e){return React.createElement("del",{className:i()("wc-block-components-product-price__regular",c),style:t},e)},value:a}),React.createElement("span",{className:"screen-reader-text"},Object(n.__)("Discounted price:",'woocommerce')),React.createElement(o.a,{currency:r,renderText:function(e){return React.createElement("ins",{className:i()("wc-block-components-product-price__value","is-discounted",l),style:u},e)},value:s}))};r.a=function(e){var r=e.align,c=e.className,t=e.currency,n=e.format,l=void 0===n?"<price/>":n,u=e.maxPrice,d=void 0===u?null:u,b=e.minPrice,f=void 0===b?null:b,y=e.price,g=void 0===y?null:y,v=e.priceClassName,_=e.priceStyle,O=e.regularPrice,P=e.regularPriceClassName,N=e.regularPriceStyle,j=i()(c,"price","wc-block-components-product-price",a()({},"wc-block-components-product-price--align-".concat(r),r));l.includes("<price/>")||(l="<price/>",console.error("Price formats need to include the `<price/>` tag."));var w=O&&g!==O,S=React.createElement("span",{className:i()("wc-block-components-product-price__value",v)});return w?S=React.createElement(m,{currency:t,price:g,priceClassName:v,priceStyle:_,regularPrice:O,regularPriceClassName:P,regularPriceStyle:N}):null!==f&&null!==d?S=React.createElement(p,{currency:t,maxPrice:d,minPrice:f,priceClassName:v,priceStyle:_}):null!==g&&(S=React.createElement(o.a,{className:i()("wc-block-components-product-price__value",v),currency:t,value:g,style:_})),React.createElement("span",{className:j},Object(s.createInterpolateElement)(l,{price:S}))}},147:function(e,r){},183:function(e,r){},357:function(e,r,c){"use strict";c.d(r,"a",(function(){return a})),c(275);var t=c(83),a=function(){return t.m>1}},387:function(e,r,c){"use strict";c.r(r);var t=c(4),a=c.n(t),n=(c(10),c(6)),o=c.n(n),l=c(140),i=c(58),u=c(101),s=c(274),p=c(357),m=c(249);r.default=Object(m.withProductDataContext)((function(e){var r,c,t,n,m,d,b,f=e.className,y=e.align,g=e.fontSize,v=e.customFontSize,_=e.saleFontSize,O=e.customSaleFontSize,P=e.color,N=e.customColor,j=e.saleColor,w=e.customSaleColor,S=Object(u.useInnerBlockLayoutContext)().parentClassName,C=Object(u.useProductDataContext)().product,E=o()(f,a()({},"".concat(S,"__product-price"),S));if(!C.id)return React.createElement(l.a,{align:y,className:E});var R=Object(s.getColorClassName)("color",P),x=Object(s.getFontSizeClass)(g),h=Object(s.getColorClassName)("color",j),k=Object(s.getFontSizeClass)(_),z=o()((r={"has-text-color":P||N,"has-font-size":g||v},a()(r,R,R),a()(r,x,x),r)),F=o()((c={"has-text-color":j||w,"has-font-size":_||O},a()(c,h,h),a()(c,k,k),c)),D={color:N,fontSize:v},T={color:w,fontSize:O},V=C.prices,B=Object(i.getCurrencyFromPriceResponse)(V),I=V.price!==V.regular_price,U=I?o()((t={},a()(t,"".concat(S,"__product-price__value"),S),a()(t,F,Object(p.a)()),t)):o()((n={},a()(n,"".concat(S,"__product-price__value"),S),a()(n,z,Object(p.a)()),n)),J=I?T:D;return React.createElement(l.a,{align:y,className:E,currency:B,price:V.price,priceClassName:U,priceStyle:Object(p.a)()?J:{},minPrice:null==V||null===(m=V.price_range)||void 0===m?void 0:m.min_amount,maxPrice:null==V||null===(d=V.price_range)||void 0===d?void 0:d.max_amount,regularPrice:V.regular_price,regularPriceClassName:o()((b={},a()(b,"".concat(S,"__product-price__regular"),S),a()(b,z,Object(p.a)()),b)),regularPriceStyle:Object(p.a)()?D:{}})}))},69:function(e,r,c){"use strict";var t=c(14),a=c.n(t),n=c(4),o=c.n(n),l=c(23),i=c.n(l),u=c(126),s=c(6),p=c.n(s),m=(c(147),["className","value","currency","onValueChange","displayType"]);function d(e,r){var c=Object.keys(e);if(Object.getOwnPropertySymbols){var t=Object.getOwnPropertySymbols(e);r&&(t=t.filter((function(r){return Object.getOwnPropertyDescriptor(e,r).enumerable}))),c.push.apply(c,t)}return c}function b(e){for(var r=1;r<arguments.length;r++){var c=null!=arguments[r]?arguments[r]:{};r%2?d(Object(c),!0).forEach((function(r){o()(e,r,c[r])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(c)):d(Object(c)).forEach((function(r){Object.defineProperty(e,r,Object.getOwnPropertyDescriptor(c,r))}))}return e}r.a=function(e){var r=e.className,c=e.value,t=e.currency,n=e.onValueChange,o=e.displayType,l=void 0===o?"text":o,s=i()(e,m),d="string"==typeof c?parseInt(c,10):c;if(!Number.isFinite(d))return null;var f=d/Math.pow(10,t.minorUnit);if(!Number.isFinite(f))return null;var y=p()("wc-block-formatted-money-amount","wc-block-components-formatted-money-amount",r),g=b(b(b({},s),function(e){return{thousandSeparator:e.thousandSeparator,decimalSeparator:e.decimalSeparator,decimalScale:e.minorUnit,fixedDecimalScale:!0,prefix:e.prefix,suffix:e.suffix,isNumericString:!0}}(t)),{},{value:void 0,currency:void 0,onValueChange:void 0}),v=n?function(e){var r=e.value*Math.pow(10,t.minorUnit);n(r)}:function(){};return React.createElement(u.a,a()({className:y,displayType:l},g,{value:f,onValueChange:v}))}}}]);