!function(e){var t={};function r(n){if(t[n])return t[n].exports;var o=t[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(n,o,function(t){return e[t]}.bind(null,o));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=314)}({0:function(e,t){e.exports=window.wp.element},1:function(e,t){e.exports=window.wp.i18n},10:function(e,t,r){e.exports=r(79)()},107:function(e,t,r){"use strict";r.d(t,"a",(function(){return f}));var n=r(14),o=r.n(n),a=r(4),i=r.n(a),c=r(0),s=r(44);function l(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function u(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?l(Object(r),!0).forEach((function(t){i()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):l(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var p=[".wp-block-woocommerce-cart"],d=function(e){var t=e.Block,r=e.containers,n=e.getProps,a=void 0===n?function(){return{}}:n,i=e.getErrorBoundaryProps,l=void 0===i?function(){return{}}:i;0!==r.length&&Array.prototype.forEach.call(r,(function(e,r){var n=a(e,r),i=l(e,r),p=u(u({},e.dataset),n.attributes||{});e.classList.remove("is-loading"),Object(c.render)(React.createElement(s.a,i,React.createElement(c.Suspense,{fallback:React.createElement("div",{className:"wc-block-placeholder"})},React.createElement(t,o()({},n,{attributes:p})))),e)}))},f=function(e){var t,r,n,o,a,i,c,s=document.body.querySelectorAll(p.join(","));t=u(u({},e),{},{wrappers:s}),r=t.Block,n=t.getProps,o=t.getErrorBoundaryProps,a=t.selector,i=t.wrappers,c=document.body.querySelectorAll(a),i.length>0&&Array.prototype.filter.call(c,(function(e){return!function(e,t){return Array.prototype.some.call(t,(function(t){return t.contains(e)&&!t.isSameNode(e)}))}(e,i)})),d({Block:r,containers:c,getProps:n,getErrorBoundaryProps:o}),Array.prototype.forEach.call(s,(function(t){t.addEventListener("wc-blocks_render_blocks_frontend",(function(){var r,n,o,a,i,c;n=(r=u(u({},e),{},{wrapper:t})).Block,o=r.getProps,a=r.getErrorBoundaryProps,i=r.selector,c=r.wrapper.querySelectorAll(i),d({Block:n,containers:c,getProps:o,getErrorBoundaryProps:a})}))}))}},132:function(e,t,r){"use strict";r.d(t,"a",(function(){return s})),r.d(t,"b",(function(){return l}));var n=r(39),o=r.n(n),a=r(27),i=r.n(a),c=r(1),s=function(){var e=o()(i.a.mark((function e(t){var r;return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if("function"!=typeof t.json){e.next=11;break}return e.prev=1,e.next=4,t.json();case 4:return r=e.sent,e.abrupt("return",{message:r.message,type:r.type||"api"});case 8:return e.prev=8,e.t0=e.catch(1),e.abrupt("return",{message:e.t0.message,type:"general"});case 11:return e.abrupt("return",{message:t.message,type:t.type||"general"});case 12:case"end":return e.stop()}}),e,null,[[1,8]])})));return function(_x){return e.apply(this,arguments)}}(),l=function(e){if(e.data&&"rest_invalid_param"===e.code){var t=Object.values(e.data.params);if(t[0])return t[0]}return(null==e?void 0:e.message)||Object(c.__)("Something went wrong. Please contact us to get assistance.",'woocommerce')}},14:function(e,t){function r(){return e.exports=r=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e},e.exports.default=e.exports,e.exports.__esModule=!0,r.apply(this,arguments)}e.exports=r,e.exports.default=e.exports,e.exports.__esModule=!0},168:function(e,t,r){"use strict";r(10);var n=r(6),o=r.n(n),a=r(51),i=r(24);r(196),t.a=Object(i.withInstanceId)((function(e){var t=e.className,r=e.instanceId,n=e.label,i=e.onChange,c=e.options,s=e.screenReaderLabel,l=e.readOnly,u=e.value,p="wc-block-components-sort-select__select-".concat(r);return React.createElement("div",{className:o()("wc-block-sort-select","wc-block-components-sort-select",t)},React.createElement(a.a,{label:n,screenReaderLabel:s,wrapperElement:"label",wrapperProps:{className:"wc-block-sort-select__label wc-block-components-sort-select__label",htmlFor:p}}),React.createElement("select",{id:p,className:"wc-block-sort-select__select wc-block-components-sort-select__select",onChange:i,readOnly:l,value:u},c.map((function(e){return React.createElement("option",{key:e.key,value:e.key},e.label)}))))}))},196:function(e,t){},2:function(e,t){e.exports=window.wc.wcSettings},21:function(e,t){function r(t){return"function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?(e.exports=r=function(e){return typeof e},e.exports.default=e.exports,e.exports.__esModule=!0):(e.exports=r=function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e.exports.default=e.exports,e.exports.__esModule=!0),r(t)}e.exports=r,e.exports.default=e.exports,e.exports.__esModule=!0},227:function(e,t,r){function n(e){for(var t,r,n=[],o=0;o<rowCut.length;o++)(t=rowCut.substring(o).match(/^&[a-z0-9#]+;/))?(r=t[0],n.push(r),o+=r.length-1):n.push(rowCut[o]);return n}e.exports&&(e.exports=function(e,t){for(var r,o,a,i,c,s=(t=t||{}).limit||100,l=void 0===t.preserveTags||t.preserveTags,u=void 0!==t.wordBreak&&t.wordBreak,p=t.suffix||"...",d=t.moreLink||"",f=t.moreText||"»",v=t.preserveWhiteSpace||!1,w=e.replace(/</g,"\n<").replace(/>/g,">\n").replace(/\n\n/g,"\n").replace(/^\n/g,"").replace(/\n$/g,"").split("\n"),b=0,m=[],g=!1,h=0;h<w.length;h++)if(r=w[h],rowCut=v?r:r.replace(/[ ]+/g," "),r.length){var y=n(rowCut);if("<"!==r[0])if(b>=s)r="";else if(b+y.length>=s){if(" "===y[(o=s-b)-1])for(;o&&" "===y[(o-=1)-1];);else a=y.slice(o).indexOf(" "),u||(-1!==a?o+=a:o=r.length);r=y.slice(0,o).join("")+p,d&&(r+='<a href="'+d+'" style="display:inline">'+f+"</a>"),b=s,g=!0}else b+=y.length;else if(l){if(b>=s)if(c=(i=r.match(/[a-zA-Z]+/))?i[0]:"")if("</"!==r.substring(0,2))m.push(c),r="";else{for(;m[m.length-1]!==c&&m.length;)m.pop();m.length&&(r=""),m.pop()}else r=""}else r="";w[h]=r}return{html:w.join("\n").replace(/\n/g,""),more:g}})},24:function(e,t){e.exports=window.wp.compose},25:function(e,t){e.exports=window.wp.isShallowEqual},27:function(e,t){e.exports=window.regeneratorRuntime},280:function(e,t){},281:function(e,t){},282:function(e,t){},283:function(e,t){},29:function(e,t){e.exports=function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e},e.exports.default=e.exports,e.exports.__esModule=!0},3:function(e,t){e.exports=window.React},30:function(e,t){function r(t){return e.exports=r=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},e.exports.default=e.exports,e.exports.__esModule=!0,r(t)}e.exports=r,e.exports.default=e.exports,e.exports.__esModule=!0},314:function(e,t,r){"use strict";r.r(t);var n=r(107),o=r(40),a=r.n(o),i=r(41),c=r.n(i),s=r(29),l=r.n(s),u=r(42),p=r.n(u),d=r(43),f=r.n(d),v=r(30),w=r.n(v),b=r(1),m=r(33),g=r(3),h=r.n(g),y=(r(10),r(61)),_=r.n(y),R=r(6),O=r.n(R),k=r(2),x=function(e){return _()({path:"/wc/store/products/reviews?"+Object.entries(e).map((function(e){return e.join("=")})).join("&"),parse:!1}).then((function(e){return e.json().then((function(t){return{reviews:t,totalReviews:parseInt(e.headers.get("x-wp-total"),10)}}))}))},E=r(51),j=(r(283),function(e){var t=e.onClick,r=e.label,n=e.screenReaderLabel;return React.createElement("div",{className:"wp-block-button wc-block-load-more wc-block-components-load-more"},React.createElement("button",{className:"wp-block-button__link",onClick:t},React.createElement(E.a,{label:r,screenReaderLabel:n})))});j.defaultProps={label:Object(b.__)("Load more",'woocommerce')};var P=j,S=r(168),T=(r(280),function(e){var t=e.onChange,r=e.readOnly,n=e.value;return React.createElement(S.a,{className:"wc-block-review-sort-select wc-block-components-review-sort-select",label:Object(b.__)("Order by",'woocommerce'),onChange:t,options:[{key:"most-recent",label:Object(b.__)("Most recent",'woocommerce')},{key:"highest-rating",label:Object(b.__)("Highest rating",'woocommerce')},{key:"lowest-rating",label:Object(b.__)("Lowest rating",'woocommerce')}],readOnly:r,screenReaderLabel:Object(b.__)("Order reviews by",'woocommerce'),value:n})}),M=r(4),A=r.n(M),C=r(227),N=r.n(C),L=function(e,t){var r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"...",n=N()(e,{suffix:r,limit:t});return n.html},D=function(e,t,r,n){var o=I(e,t,r);return L(e,o-n.length,n)},I=function(e,t,r){for(var n={start:0,middle:0,end:e.length};n.start<=n.end;)n.middle=Math.floor((n.start+n.end)/2),t.innerHTML=L(e,n.middle),n=B(n,t.clientHeight,r);return n.middle},B=function(e,t,r){return t<=r?e.start=e.middle+1:e.end=e.middle-1,e};var G=function(e){p()(o,e);var t,r,n=(t=o,r=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}(),function(){var e,n=w()(t);if(r){var o=w()(this).constructor;e=Reflect.construct(n,arguments,o)}else e=n.apply(this,arguments);return f()(this,e)});function o(e){var t;return a()(this,o),(t=n.apply(this,arguments)).state={isExpanded:!1,clampEnabled:null,content:e.children,summary:"."},t.reviewSummary=Object(g.createRef)(),t.reviewContent=Object(g.createRef)(),t.getButton=t.getButton.bind(l()(t)),t.onClick=t.onClick.bind(l()(t)),t}return c()(o,[{key:"componentDidMount",value:function(){if(this.props.children){var e=this.props,t=e.maxLines,r=e.ellipsis,n=(this.reviewSummary.current.clientHeight+1)*t+1,o=this.reviewContent.current.clientHeight+1>n;this.setState({clampEnabled:o}),o&&this.setState({summary:D(this.reviewContent.current.innerHTML,this.reviewSummary.current,n,r)})}}},{key:"getButton",value:function(){var e=this.state.isExpanded,t=this.props,r=t.className,n=t.lessText,o=t.moreText,a=e?n:o;if(a)return h.a.createElement("a",{href:"#more",className:r+"__read_more",onClick:this.onClick,"aria-expanded":!e,role:"button"},a)}},{key:"onClick",value:function(e){e.preventDefault();var t=this.state.isExpanded;this.setState({isExpanded:!t})}},{key:"render",value:function(){var e=this.props.className,t=this.state,r=t.content,n=t.summary,o=t.clampEnabled,a=t.isExpanded;return r?!1===o?h.a.createElement("div",{className:e},h.a.createElement("div",{ref:this.reviewContent},r)):h.a.createElement("div",{className:e},(!a||null===o)&&h.a.createElement("div",{ref:this.reviewSummary,"aria-hidden":a,dangerouslySetInnerHTML:{__html:n}}),(a||null===o)&&h.a.createElement("div",{ref:this.reviewContent,"aria-hidden":!a},r),this.getButton()):null}}]),o}(g.Component);G.defaultProps={maxLines:3,ellipsis:"&hellip;",moreText:Object(b.__)("Read more",'woocommerce'),lessText:Object(b.__)("Read less",'woocommerce'),className:"read-more-content"};var H=G;r(282);var U=function(e){var t=e.attributes,r=e.review,n=void 0===r?{}:r,o=t.imageType,a=t.showReviewDate,i=t.showReviewerName,c=t.showReviewImage,s=t.showReviewRating,l=t.showReviewContent,u=t.showProductName,p=n.rating,d=!Object.keys(n).length>0,f=Number.isFinite(p)&&s;return React.createElement("li",{className:O()("wc-block-review-list-item__item","wc-block-components-review-list-item__item",{"is-loading":d,"wc-block-components-review-list-item__item--has-image":c}),"aria-hidden":d},(u||a||i||c||f)&&React.createElement("div",{className:"wc-block-review-list-item__info wc-block-components-review-list-item__info"},c&&function(e,t,r){var n,o;return r||!e?React.createElement("div",{className:"wc-block-review-list-item__image wc-block-components-review-list-item__image"}):React.createElement("div",{className:"wc-block-review-list-item__image wc-block-components-review-list-item__image"},"product"===t?React.createElement("img",{"aria-hidden":"true",alt:(null===(n=e.product_image)||void 0===n?void 0:n.alt)||"",src:(null===(o=e.product_image)||void 0===o?void 0:o.thumbnail)||""}):React.createElement("img",{"aria-hidden":"true",alt:"",src:e.reviewer_avatar_urls[96]||""}),e.verified&&React.createElement("div",{className:"wc-block-review-list-item__verified wc-block-components-review-list-item__verified",title:Object(b.__)("Verified buyer",'woocommerce')},Object(b.__)("Verified buyer",'woocommerce')))}(n,o,d),(u||i||f||a)&&React.createElement("div",{className:"wc-block-review-list-item__meta wc-block-components-review-list-item__meta"},f&&function(e){var t=e.rating,r={width:t/5*100+"%"},n=Object(b.sprintf)(
/* translators: %f is referring to the average rating value */
Object(b.__)("Rated %f out of 5",'woocommerce'),t);return React.createElement("div",{className:"wc-block-review-list-item__rating wc-block-components-review-list-item__rating"},React.createElement("div",{className:"wc-block-review-list-item__rating__stars wc-block-components-review-list-item__rating__stars",role:"img","aria-label":n},React.createElement("span",{style:r},n)))}(n),u&&function(e){return React.createElement("div",{className:"wc-block-review-list-item__product wc-block-components-review-list-item__product"},React.createElement("a",{href:e.product_permalink,dangerouslySetInnerHTML:{__html:e.product_name}}))}(n),i&&function(e){var t=e.reviewer,r=void 0===t?"":t;return React.createElement("div",{className:"wc-block-review-list-item__author wc-block-components-review-list-item__author"},r)}(n),a&&function(e){var t=e.date_created,r=e.formatted_date_created;return React.createElement("time",{className:"wc-block-review-list-item__published-date wc-block-components-review-list-item__published-date",dateTime:t},r)}(n))),l&&function(e){return React.createElement(H,{maxLines:10,moreText:Object(b.__)("Read full review",'woocommerce'),lessText:Object(b.__)("Hide full review",'woocommerce'),className:"wc-block-review-list-item__text wc-block-components-review-list-item__text"},React.createElement("div",{dangerouslySetInnerHTML:{__html:e.review||""}}))}(n))};function F(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function W(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?F(Object(r),!0).forEach((function(t){A()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):F(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}r(281);var q=function(e){var t=e.attributes,r=e.reviews,n=Object(k.getSetting)("showAvatars",!0),o=Object(k.getSetting)("reviewRatingsEnabled",!0),a=(n||"product"===t.imageType)&&t.showReviewImage,i=o&&t.showReviewRating,c=W(W({},t),{},{showReviewImage:a,showReviewRating:i});return React.createElement("ul",{className:"wc-block-review-list wc-block-components-review-list"},0===r.length?React.createElement(U,{attributes:c}):r.map((function(e,t){return React.createElement(U,{key:e.id||t,attributes:c,review:e})})))},V=r(14),z=r.n(V),Y=r(39),Z=r.n(Y),$=r(27),J=r.n($),K=r(25),Q=r.n(K),X=r(132);var ee=function(e){var t=function(t){p()(i,t);var r,n,o=(r=i,n=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}(),function(){var e,t=w()(r);if(n){var o=w()(this).constructor;e=Reflect.construct(t,arguments,o)}else e=t.apply(this,arguments);return f()(this,e)});function i(){var e;a()(this,i);for(var t=arguments.length,r=new Array(t),n=0;n<t;n++)r[n]=arguments[n];return e=o.call.apply(o,[this].concat(r)),A()(l()(e),"isPreview",!!e.props.attributes.previewReviews),A()(l()(e),"delayedAppendReviews",e.props.delayFunction(e.appendReviews)),A()(l()(e),"isMounted",!1),A()(l()(e),"state",{error:null,loading:!0,reviews:e.isPreview?e.props.attributes.previewReviews:[],totalReviews:e.isPreview?e.props.attributes.previewReviews.length:0}),A()(l()(e),"setError",function(){var t=Z()(J.a.mark((function t(r){var n,o;return J.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(e.isMounted){t.next=2;break}return t.abrupt("return");case 2:return n=e.props.onReviewsLoadError,t.next=5,Object(X.a)(r);case 5:o=t.sent,e.setState({reviews:[],loading:!1,error:o}),n(o);case 8:case"end":return t.stop()}}),t)})));return function(_x){return t.apply(this,arguments)}}()),e}return c()(i,[{key:"componentDidMount",value:function(){this.isMounted=!0,this.replaceReviews()}},{key:"componentDidUpdate",value:function(e){e.reviewsToDisplay<this.props.reviewsToDisplay?this.delayedAppendReviews():this.shouldReplaceReviews(e,this.props)&&this.replaceReviews()}},{key:"shouldReplaceReviews",value:function(e,t){return e.orderby!==t.orderby||e.order!==t.order||e.productId!==t.productId||!Q()(e.categoryIds,t.categoryIds)}},{key:"componentWillUnmount",value:function(){this.isMounted=!1,this.delayedAppendReviews.cancel&&this.delayedAppendReviews.cancel()}},{key:"getArgs",value:function(e){var t=this.props,r=t.categoryIds,n=t.order,o=t.orderby,a=t.productId,i={order:n,orderby:o,per_page:t.reviewsToDisplay-e,offset:e};return r&&r.length&&(i.category_id=Array.isArray(r)?r.join(","):r),a&&(i.product_id=a),i}},{key:"replaceReviews",value:function(){if(!this.isPreview){var e=this.props.onReviewsReplaced;this.updateListOfReviews().then(e)}}},{key:"appendReviews",value:function(){if(!this.isPreview){var e=this.props,t=e.onReviewsAppended,r=e.reviewsToDisplay,n=this.state.reviews;r<=n.length||this.updateListOfReviews(n).then(t)}}},{key:"updateListOfReviews",value:function(){var e=this,t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],r=this.props.reviewsToDisplay,n=this.state.totalReviews,o=Math.min(n,r)-t.length;return this.setState({loading:!0,reviews:t.concat(Array(o).fill({}))}),x(this.getArgs(t.length)).then((function(r){var n=r.reviews,o=r.totalReviews;return e.isMounted&&e.setState({reviews:t.filter((function(e){return Object.keys(e).length})).concat(n),totalReviews:o,loading:!1,error:null}),{newReviews:n}})).catch(this.setError)}},{key:"render",value:function(){var t=this.props.reviewsToDisplay,r=this.state,n=r.error,o=r.loading,a=r.reviews,i=r.totalReviews;return React.createElement(e,z()({},this.props,{error:n,isLoading:o,reviews:a.slice(0,t),totalReviews:i}))}}]),i}(g.Component);A()(t,"defaultProps",{delayFunction:function(e){return e},onReviewsAppended:function(){},onReviewsLoadError:function(){},onReviewsReplaced:function(){}});var r=e.displayName,n=void 0===r?e.name||"Component":r;return t.displayName="WithReviews( ".concat(n," )"),t}((function(e){var t=e.attributes,r=e.onAppendReviews,n=e.onChangeOrderby,o=e.reviews,a=e.sortSelectValue,i=e.totalReviews;if(0===o.length)return null;var c=Object(k.getSetting)("reviewRatingsEnabled",!0);return React.createElement(React.Fragment,null,"false"!==t.showOrderby&&c&&React.createElement(T,{value:a,onChange:n}),React.createElement(q,{attributes:t,reviews:o}),"false"!==t.showLoadMore&&i>o.length&&React.createElement(P,{onClick:r,screenReaderLabel:Object(b.__)("Load more reviews",'woocommerce')}))}));var te=function(e){p()(o,e);var t,r,n=(t=o,r=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}(),function(){var e,n=w()(t);if(r){var o=w()(this).constructor;e=Reflect.construct(n,arguments,o)}else e=n.apply(this,arguments);return f()(this,e)});function o(){var e;a()(this,o);var t=(e=n.apply(this,arguments)).props.attributes;return e.state={orderby:t.orderby,reviewsToDisplay:parseInt(t.reviewsOnPageLoad,10)},e.onAppendReviews=e.onAppendReviews.bind(l()(e)),e.onChangeOrderby=e.onChangeOrderby.bind(l()(e)),e}return c()(o,[{key:"onAppendReviews",value:function(){var e=this.props.attributes,t=this.state.reviewsToDisplay;this.setState({reviewsToDisplay:t+parseInt(e.reviewsOnLoadMore,10)})}},{key:"onChangeOrderby",value:function(e){var t=this.props.attributes;this.setState({orderby:e.target.value,reviewsToDisplay:parseInt(t.reviewsOnPageLoad,10)})}},{key:"onReviewsAppended",value:function(e){var t=e.newReviews;Object(m.speak)(Object(b.sprintf)(
/* translators: %d is the count of reviews loaded. */
Object(b._n)("%d review loaded.","%d reviews loaded.",t.length,'woocommerce'),t.length))}},{key:"onReviewsReplaced",value:function(){Object(m.speak)(Object(b.__)("Reviews list updated.",'woocommerce'))}},{key:"onReviewsLoadError",value:function(){Object(m.speak)(Object(b.__)("There was an error loading the reviews.",'woocommerce'))}},{key:"render",value:function(){var e=this.props.attributes,t=e.categoryIds,r=e.productId,n=this.state.reviewsToDisplay,o=function(e){if(Object(k.getSetting)("reviewRatingsEnabled",!0)){if("lowest-rating"===e)return{order:"asc",orderby:"rating"};if("highest-rating"===e)return{order:"desc",orderby:"rating"}}return{order:"desc",orderby:"date_gmt"}}(this.state.orderby),a=o.order,i=o.orderby;return React.createElement(ee,{attributes:e,categoryIds:t,onAppendReviews:this.onAppendReviews,onChangeOrderby:this.onChangeOrderby,onReviewsAppended:this.onReviewsAppended,onReviewsLoadError:this.onReviewsLoadError,onReviewsReplaced:this.onReviewsReplaced,order:a,orderby:i,productId:r,reviewsToDisplay:n,sortSelectValue:this.state.orderby})}}]),o}(g.Component);Object(n.a)({selector:"\n\t.wp-block-woocommerce-all-reviews,\n\t.wp-block-woocommerce-reviews-by-product,\n\t.wp-block-woocommerce-reviews-by-category\n",Block:te,getProps:function(e){return{attributes:{showReviewDate:e.classList.contains("has-date"),showReviewerName:e.classList.contains("has-name"),showReviewImage:e.classList.contains("has-image"),showReviewRating:e.classList.contains("has-rating"),showReviewContent:e.classList.contains("has-content"),showProductName:e.classList.contains("has-product-name")}}}})},33:function(e,t){e.exports=window.wp.a11y},39:function(e,t){function r(e,t,r,n,o,a,i){try{var c=e[a](i),s=c.value}catch(e){return void r(e)}c.done?t(s):Promise.resolve(s).then(n,o)}e.exports=function(e){return function(){var t=this,n=arguments;return new Promise((function(o,a){var i=e.apply(t,n);function c(e){r(i,o,a,c,s,"next",e)}function s(e){r(i,o,a,c,s,"throw",e)}c(void 0)}))}},e.exports.default=e.exports,e.exports.__esModule=!0},4:function(e,t){e.exports=function(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e},e.exports.default=e.exports,e.exports.__esModule=!0},40:function(e,t){e.exports=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")},e.exports.default=e.exports,e.exports.__esModule=!0},41:function(e,t){function r(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}e.exports=function(e,t,n){return t&&r(e.prototype,t),n&&r(e,n),e},e.exports.default=e.exports,e.exports.__esModule=!0},42:function(e,t,r){var n=r(71);e.exports=function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&n(e,t)},e.exports.default=e.exports,e.exports.__esModule=!0},43:function(e,t,r){var n=r(21).default,o=r(29);e.exports=function(e,t){return!t||"object"!==n(t)&&"function"!=typeof t?o(e):t},e.exports.default=e.exports,e.exports.__esModule=!0},44:function(e,t,r){"use strict";var n=r(40),o=r.n(n),a=r(41),i=r.n(a),c=r(29),s=r.n(c),l=r(42),u=r.n(l),p=r(43),d=r.n(p),f=r(30),v=r.n(f),w=r(4),b=r.n(w),m=(r(10),r(3)),g=r(1),h=r(83),y=function(e){var t=e.imageUrl,r=void 0===t?"".concat(h.l,"/block-error.svg"):t,n=e.header,o=void 0===n?Object(g.__)("Oops!",'woocommerce'):n,a=e.text,i=void 0===a?Object(g.__)("There was an error loading the content.",'woocommerce'):a,c=e.errorMessage,s=e.errorMessagePrefix,l=void 0===s?Object(g.__)("Error:",'woocommerce'):s,u=e.button;return React.createElement("div",{className:"wc-block-error wc-block-components-error"},r&&React.createElement("img",{className:"wc-block-error__image wc-block-components-error__image",src:r,alt:""}),React.createElement("div",{className:"wc-block-error__content wc-block-components-error__content"},o&&React.createElement("p",{className:"wc-block-error__header wc-block-components-error__header"},o),i&&React.createElement("p",{className:"wc-block-error__text wc-block-components-error__text"},i),c&&React.createElement("p",{className:"wc-block-error__message wc-block-components-error__message"},l?l+" ":"",c),u&&React.createElement("p",{className:"wc-block-error__button wc-block-components-error__button"},u)))};r(81);var _=function(e){u()(a,e);var t,r,n=(t=a,r=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}(),function(){var e,n=v()(t);if(r){var o=v()(this).constructor;e=Reflect.construct(n,arguments,o)}else e=n.apply(this,arguments);return d()(this,e)});function a(){var e;o()(this,a);for(var t=arguments.length,r=new Array(t),i=0;i<t;i++)r[i]=arguments[i];return e=n.call.apply(n,[this].concat(r)),b()(s()(e),"state",{errorMessage:"",hasError:!1}),e}return i()(a,[{key:"render",value:function(){var e=this.props,t=e.header,r=e.imageUrl,n=e.showErrorMessage,o=e.text,a=e.errorMessagePrefix,i=e.renderError,c=e.button,s=this.state,l=s.errorMessage;return s.hasError?"function"==typeof i?i({errorMessage:l}):React.createElement(y,{errorMessage:n?l:null,header:t,imageUrl:r,text:o,errorMessagePrefix:a,button:c}):this.props.children}}],[{key:"getDerivedStateFromError",value:function(e){return void 0!==e.statusText&&void 0!==e.status?{errorMessage:React.createElement(React.Fragment,null,React.createElement("strong",null,e.status),": ",e.statusText),hasError:!0}:{errorMessage:e.message,hasError:!0}}}]),a}(m.Component);_.defaultProps={showErrorMessage:!0},t.a=_},51:function(e,t,r){"use strict";var n=r(4),o=r.n(n),a=r(0),i=r(6),c=r.n(i);function s(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function l(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?s(Object(r),!0).forEach((function(t){o()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):s(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}t.a=function(e){var t,r=e.label,n=e.screenReaderLabel,o=e.wrapperElement,i=e.wrapperProps,s=void 0===i?{}:i,u=null!=r,p=null!=n;return!u&&p?(t=o||"span",s=l(l({},s),{},{className:c()(s.className,"screen-reader-text")}),React.createElement(t,s,n)):(t=o||a.Fragment,u&&p&&r!==n?React.createElement(t,s,React.createElement("span",{"aria-hidden":"true"},r),React.createElement("span",{className:"screen-reader-text"},n)):React.createElement(t,s,r))}},6:function(e,t,r){var n;!function(){"use strict";var r={}.hasOwnProperty;function o(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var a=typeof n;if("string"===a||"number"===a)e.push(n);else if(Array.isArray(n)){if(n.length){var i=o.apply(null,n);i&&e.push(i)}}else if("object"===a)if(n.toString===Object.prototype.toString)for(var c in n)r.call(n,c)&&n[c]&&e.push(c);else e.push(n.toString())}}return e.join(" ")}e.exports?(o.default=o,e.exports=o):void 0===(n=function(){return o}.apply(t,[]))||(e.exports=n)}()},61:function(e,t){e.exports=window.wp.apiFetch},71:function(e,t){function r(t,n){return e.exports=r=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},e.exports.default=e.exports,e.exports.__esModule=!0,r(t,n)}e.exports=r,e.exports.default=e.exports,e.exports.__esModule=!0},79:function(e,t,r){"use strict";var n=r(80);function o(){}function a(){}a.resetWarningCache=o,e.exports=function(){function e(e,t,r,o,a,i){if(i!==n){var c=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw c.name="Invariant Violation",c}}function t(){return e}e.isRequired=e;var r={array:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:t,element:e,elementType:e,instanceOf:t,node:e,objectOf:t,oneOf:t,oneOfType:t,shape:t,exact:t,checkPropTypes:a,resetWarningCache:o};return r.PropTypes=r,r}},80:function(e,t,r){"use strict";e.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},81:function(e,t){},83:function(e,t,r){"use strict";r.d(t,"n",(function(){return a})),r.d(t,"l",(function(){return i})),r.d(t,"k",(function(){return c})),r.d(t,"m",(function(){return s})),r.d(t,"i",(function(){return l})),r.d(t,"d",(function(){return u})),r.d(t,"f",(function(){return p})),r.d(t,"j",(function(){return d})),r.d(t,"c",(function(){return f})),r.d(t,"e",(function(){return v})),r.d(t,"g",(function(){return w})),r.d(t,"a",(function(){return b})),r.d(t,"h",(function(){return m})),r.d(t,"b",(function(){return g}));var n,o=r(2),a=Object(o.getSetting)("wcBlocksConfig",{buildPhase:1,pluginUrl:"",productCount:0,defaultAvatar:"",restApiRoutes:{},wordCountType:"words"}),i=a.pluginUrl+"images/",c=a.pluginUrl+"build/",s=a.buildPhase,l=null===(n=o.STORE_PAGES.shop)||void 0===n?void 0:n.permalink,u=(o.STORE_PAGES.checkout.id,o.STORE_PAGES.checkout.permalink),p=o.STORE_PAGES.privacy.permalink,d=(o.STORE_PAGES.privacy.title,o.STORE_PAGES.terms.permalink),f=(o.STORE_PAGES.terms.title,o.STORE_PAGES.cart.id,o.STORE_PAGES.cart.permalink),v=o.STORE_PAGES.myaccount.permalink?o.STORE_PAGES.myaccount.permalink:Object(o.getSetting)("wpLoginUrl","/wp-login.php"),w=Object(o.getSetting)("shippingCountries",{}),b=Object(o.getSetting)("allowedCountries",{}),m=Object(o.getSetting)("shippingStates",{}),g=Object(o.getSetting)("allowedStates",{})}});