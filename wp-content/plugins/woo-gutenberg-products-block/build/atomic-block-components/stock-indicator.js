(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[14],{484:function(o,c,t){"use strict";t.r(c);var n=t(5),e=t.n(n),r=t(0),s=t(1),a=(t(2),t(6)),i=t.n(a),u=t(41),k=t(81),b=(t(548),function(o){return Object(s.sprintf)(
/* translators: %d stock amount (number of items in stock for product) */
Object(s.__)("%d left in stock","woo-gutenberg-products-block"),o)});c.default=Object(k.withProductDataContext)((function(o){var c,t=o.className,n=Object(u.useInnerBlockLayoutContext)().parentClassName,a=Object(u.useProductDataContext)().product;if(!a.id||!a.is_purchasable)return null;var k=!!a.is_in_stock,d=a.low_stock_remaining,l=a.is_on_backorder;return Object(r.createElement)("div",{className:i()(t,"wc-block-components-product-stock-indicator",(c={},e()(c,"".concat(n,"__stock-indicator"),n),e()(c,"wc-block-components-product-stock-indicator--in-stock",k),e()(c,"wc-block-components-product-stock-indicator--out-of-stock",!k),e()(c,"wc-block-components-product-stock-indicator--low-stock",!!d),e()(c,"wc-block-components-product-stock-indicator--available-on-backorder",!!l),c))},d?b(d):function(o,c){return c?Object(s.__)("Available on backorder","woo-gutenberg-products-block"):o?Object(s.__)("In Stock","woo-gutenberg-products-block"):Object(s.__)("Out of Stock","woo-gutenberg-products-block")}(k,l))}))},548:function(o,c){}}]);