// modules are defined as an array
// [ module function, map of requires ]
//
// map of requires is short require name -> numeric require
//
// anything defined in a previous bundle is accessed via the
// orig method which is the require for previous bundles
parcelRequire = (function (modules, cache, entry, globalName) {
  // Save the require from previous bundle to this closure if any
  var previousRequire = typeof parcelRequire === 'function' && parcelRequire;
  var nodeRequire = typeof require === 'function' && require;

  function newRequire(name, jumped) {
    if (!cache[name]) {
      if (!modules[name]) {
        // if we cannot find the module within our internal map or
        // cache jump to the current global require ie. the last bundle
        // that was added to the page.
        var currentRequire = typeof parcelRequire === 'function' && parcelRequire;
        if (!jumped && currentRequire) {
          return currentRequire(name, true);
        }

        // If there are other bundles on this page the require from the
        // previous one is saved to 'previousRequire'. Repeat this as
        // many times as there are bundles until the module is found or
        // we exhaust the require chain.
        if (previousRequire) {
          return previousRequire(name, true);
        }

        // Try the node require function if it exists.
        if (nodeRequire && typeof name === 'string') {
          return nodeRequire(name);
        }

        var err = new Error('Cannot find module \'' + name + '\'');
        err.code = 'MODULE_NOT_FOUND';
        throw err;
      }

      localRequire.resolve = resolve;
      localRequire.cache = {};

      var module = cache[name] = new newRequire.Module(name);

      modules[name][0].call(module.exports, localRequire, module, module.exports, this);
    }

    return cache[name].exports;

    function localRequire(x){
      return newRequire(localRequire.resolve(x));
    }

    function resolve(x){
      return modules[name][1][x] || x;
    }
  }

  function Module(moduleName) {
    this.id = moduleName;
    this.bundle = newRequire;
    this.exports = {};
  }

  newRequire.isParcelRequire = true;
  newRequire.Module = Module;
  newRequire.modules = modules;
  newRequire.cache = cache;
  newRequire.parent = previousRequire;
  newRequire.register = function (id, exports) {
    modules[id] = [function (require, module) {
      module.exports = exports;
    }, {}];
  };

  var error;
  for (var i = 0; i < entry.length; i++) {
    try {
      newRequire(entry[i]);
    } catch (e) {
      // Save first error but execute all entries
      if (!error) {
        error = e;
      }
    }
  }

  if (entry.length) {
    // Expose entry point to Node, AMD or browser globals
    // Based on https://github.com/ForbesLindesay/umd/blob/master/template.js
    var mainExports = newRequire(entry[entry.length - 1]);

    // CommonJS
    if (typeof exports === "object" && typeof module !== "undefined") {
      module.exports = mainExports;

    // RequireJS
    } else if (typeof define === "function" && define.amd) {
     define(function () {
       return mainExports;
     });

    // <script>
    } else if (globalName) {
      this[globalName] = mainExports;
    }
  }

  // Override the current require with this new one
  parcelRequire = newRequire;

  if (error) {
    // throw error from earlier, _after updating parcelRequire_
    throw error;
  }

  return newRequire;
})({"scss/index.scss":[function(require,module,exports) {

},{}],"js/helpers/instagram-feed-patch.js":[function(require,module,exports) {
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

jQuery(function ($) {
  var Patch =
  /*#__PURE__*/
  function () {
    function Patch($widget) {
      _classCallCheck(this, Patch);

      this.$widget = jQuery('[data-elfsight-instagram-feed-options]');

      if (!this.$widget.length) {
        return;
      }

      this.poll();
    }

    _createClass(Patch, [{
      key: "poll",
      value: function poll() {
        var _this = this;

        if (this.$widget.data('EappsInstagramFeed')) {
          this.ready();
        } else {
          setTimeout(function () {
            return _this.poll();
          }, 10);
        }
      }
    }, {
      key: "ready",
      value: function ready() {
        var widget = this.$widget.data('EappsInstagramFeed');

        if (!widget || !widget.layout || !widget.layout.popup) {
          return;
        }

        var popup = widget.layout.popup;
        var open = popup.open;
        var close = popup.close;

        popup.open = function () {
          popup.$element.show();
          open.apply(popup, arguments);
        };

        popup.close = function () {
          popup.$element.hide();
          close.apply(popup, arguments);
        };
      }
    }]);

    return Patch;
  }();

  new Patch();
});
},{}],"js/helpers/cookies.js":[function(require,module,exports) {
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;
var Cookies = {
  get: function get(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    var value = v ? v[2] : null;

    try {
      return JSON.parse(decodeURIComponent(value));
    } catch (e) {
      return value;
    }
  },
  set: function set(name, value, days) {
    var d = new Date();
    value = encodeURIComponent(JSON.stringify(value));
    d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
  },
  delete: function _delete(name) {
    this.set(name, '', -1);
  }
};
var _default = Cookies;
exports.default = _default;
},{}],"js/helpers/geo.js":[function(require,module,exports) {
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = distance;

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::                                                                         :::
//:::  This routine calculates the distance between two points (given the     :::
//:::  latitude/longitude of those points). It is being used to calculate     :::
//:::  the distance between two locations using GeoDataSource (TM) prodducts  :::
//:::                                                                         :::
//:::  Definitions:                                                           :::
//:::    South latitudes are negative, east longitudes are positive           :::
//:::                                                                         :::
//:::  Passed to function:                                                    :::
//:::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :::
//:::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :::
//:::    unit = the unit you desire for results                               :::
//:::           where: 'M' is statute miles (default)                         :::
//:::                  'K' is kilometers                                      :::
//:::                  'N' is nautical miles                                  :::
//:::                                                                         :::
//:::  Worldwide cities and other features databases with latitude longitude  :::
//:::  are available at https://www.geodatasource.com                         :::
//:::                                                                         :::
//:::  For enquiries, please contact sales@geodatasource.com                  :::
//:::                                                                         :::
//:::  Official Web site: https://www.geodatasource.com                       :::
//:::                                                                         :::
//:::               GeoDataSource.com (C) All Rights Reserved 2018            :::
//:::                                                                         :::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function distance(lat1, lon1, lat2, lon2, unit) {
  if (lat1 == lat2 && lon1 == lon2) {
    return 0;
  } else {
    var radlat1 = Math.PI * lat1 / 180;
    var radlat2 = Math.PI * lat2 / 180;
    var theta = lon1 - lon2;
    var radtheta = Math.PI * theta / 180;
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);

    if (dist > 1) {
      dist = 1;
    }

    dist = Math.acos(dist);
    dist = dist * 180 / Math.PI;
    dist = dist * 60 * 1.1515;

    if (unit == "K") {
      dist = dist * 1.609344;
    }

    if (unit == "N") {
      dist = dist * 0.8684;
    }

    return dist;
  }
}
},{}],"js/helpers/user-location.js":[function(require,module,exports) {
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;

var _cookies = _interopRequireDefault(require("./cookies"));

var _geo = _interopRequireDefault(require("./geo"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var $ = jQuery;

var UserLocation =
/*#__PURE__*/
function () {
  function UserLocation() {
    _classCallCheck(this, UserLocation);

    this.stores = cabothouse_config.locations;
    this.initLocation();
    this.initEvents();
    this.dropdown = false;
  }

  _createClass(UserLocation, [{
    key: "initLocation",
    value: function initLocation() {
      var _this = this;

      this.ipinfo = _cookies.default.get('ipinfo');

      var location = _cookies.default.get('location');

      if (location && location.id) {
        // get the users preferred store
        this.setLocation(location);
        return;
      }

      if (this.ipinfo && this.ipinfo.loc && cabothouse_config.user_ip === this.ipinfo.ip) {
        return this.lookupLocation();
      } else {
        $.ajax({
          url: 'https://ipinfo.io',
          headers: {
            'Authorization': 'Bearer ' + cabothouse_config.ipinfo_token,
            'Accept': 'application/json'
          }
        }).done(function (res) {
          _cookies.default.set('ipinfo', res);

          _this.ipinfo = res;

          _this.lookupLocation();
        });
      }
    }
  }, {
    key: "initEvents",
    value: function initEvents() {
      $(document).on('click', '[data-toggle="location-dropdown"]', this.toggleDropdown.bind(this));
      $(window).on('resize orientationchange', this.onResize.bind(this));
      $(document).on('click', '.location-dropdown__background', this.backgroundClick.bind(this));
      $(document).on('click', '[data-action="set-location"]', this.setLocationClick.bind(this));
      $(document).on('click', '[data-action="contact-store"]', this.contactLocationClick.bind(this));
    }
  }, {
    key: "lookupLocation",
    value: function lookupLocation() {
      if (!this.ipinfo) {
        return;
      } // lets get the closest location


      var coords = this.ipinfo.loc.split(',');
      this.stores.forEach(function (store) {
        store.distance = (0, _geo.default)(store.location.lat, store.location.lng, Number(coords[0]), Number(coords[1]));
      });
      this.stores.sort(function (a, b) {
        return a.distance - b.distance;
      });
      this.setLocation(this.stores[0]);
      this.location = this.stores[0];
    }
  }, {
    key: "getCoords",
    value: function getCoords() {
      if (this.location) {
        return [this.location.location.lat, this.location.location.lng];
      }

      if (this.ipinfo) {
        return this.ipinfo.loc.split(',');
      }

      return false;
    }
  }, {
    key: "sortStores",
    value: function sortStores() {
      var _this2 = this;

      // lets get the closest location
      var coords = this.getCoords();

      if (!coords) {
        return this.stores;
      }

      this.stores.forEach(function (store) {
        store.distance = (0, _geo.default)(store.location.lat, store.location.lng, Number(coords[0]), Number(coords[1]));
      });
      this.stores.sort(function (a, b) {
        if (_this2.location) {
          return _this2.location.id === a.id ? -1 : _this2.location.id === b.id ? 1 : a.distance - b.distance;
        }

        return a.distance - b.distance;
      });
      return this.stores;
    }
  }, {
    key: "onChange",
    value: function onChange(callback) {
      if (this.location) {
        callback(this.location);
      }

      this.updateUI();
      $(document).on('userlocation', function (e, location) {
        callback(location);
      });
    }
  }, {
    key: "setLocation",
    value: function setLocation(location) {
      this.location = location;

      _cookies.default.set('location', location, 365);

      $(document).trigger('userlocation', [location]);
      this.updateUI();
    }
  }, {
    key: "setLocationClick",
    value: function setLocationClick(e) {
      var id = Number($(e.target).data('id'));
      var store = this.stores.find(function (s) {
        return s.id === id;
      });

      if (store) {
        this.setLocation(store);
      }

      this.hideDropdown();
    }
  }, {
    key: "contactLocationClick",
    value: function contactLocationClick(e) {
      var id = Number($(e.target).data('id'));
      var store = this.stores.find(function (s) {
        return s.id === id;
      });
      console.log(e.target, id, store);

      if (store) {
        $(document).trigger('contact-store', [store]);
      }
    }
  }, {
    key: "updateUI",
    value: function updateUI() {
      var _this3 = this;

      // make sure the dom is ready before updating ui
      $(function () {
        if (!_this3.location) {
          var $container = $('#et-top-navigation');
          $container.find('#user-location').remove();
          return;
        } else {
          var triggerClass = _this3.dropdown ? ' -open' : '';

          if (!_this3.$trigger) {
            _this3.$trigger = $("<div id=\"user-location\" class=\"user-location".concat(triggerClass, "\">\n\t\t\t\t\t\t\t<div class=\"user-location__label\">\n\t\t\t\t\t\t\t\tYour Store:\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"user-location__value\" data-toggle=\"location-dropdown\">\n\t\t\t\t\t\t\t\t<span data-name=\"store-name\">").concat(_this3.location.name, "</span>\n\t\t\t\t\t\t\t\t<span class=\"fa fa-angle-down\"></span>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t</div>"));

            _this3.$trigger.insertBefore('#et_mobile_nav_menu');
          } else {
            _this3.$trigger.find('[data-name="store-name"]').html(_this3.location.name);
          }

          if (!_this3.$dropdown) {
            // lets also create the dropdown
            _this3.$dropdown = $("\n\t\t\t\t\t\t<div id=\"user-location-dropdown\" class=\"location-dropdown\">\n\t\t\t\t\t\t\t<div class=\"location-dropdown__background\"></div>\n\t\t\t\t\t\t\t<ul class=\"location-dropdown__items\"></ul>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t");

            _this3.updateDropdownList();
          }
        }
      });
    }
  }, {
    key: "updateDropdownList",
    value: function updateDropdownList() {
      var _this4 = this;

      var $ul = this.$dropdown.find('ul');
      $ul.empty();
      this.sortStores().forEach(function (store) {
        var classes = ['location-dropdown__item'];
        var isUserLocation = false;

        if (_this4.location && _this4.location.id === store.id) {
          classes.push('location-dropdown__item--selected');
          isUserLocation = true;
        }

        var address = store.address.trim().split('\n')[0];
        classes = classes.join(' ');
        var phoneLink = store.phone.replace(/[^\d]/g, '');
        var $li = $("\n\t\t\t\t<li class=\"".concat(classes, "\">\n\t\t\t\t\t<div class=\"location-dropdown__item-info\">\n\t\t\t\t\t\t<div class=\"location-dropdown__item-name\">\n\t\t\t\t\t\t\t").concat(store.name, "\n\t\t\t\t\t\t</div>\n\t\t\t\t\t\t<div class=\"location-dropdown__item-address\">\n\t\t\t\t\t\t\t").concat(address, "\n\t\t\t\t\t\t</div>\n\t\t\t\t\t\t<div class=\"location-dropdown__item-phone\">\n\t\t\t\t\t\t\t<a href=\"tel:").concat(phoneLink, "\">\n\t\t\t\t\t\t\t\t").concat(store.phone, "\n\t\t\t\t\t\t\t</a>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"location-dropdown__item-button location-dropdown__button-container\">\n\t\t\t\t\t</div>\n\t\t\t\t</li>\n\t\t\t"));
        var $btn_container = $li.find('.location-dropdown__button-container');

        if (isUserLocation) {
          var $btn = $("\n\t\t\t\t\t<button class=\"et_pb_button location-dropdown__button location-dropdown__button-selected\" data-action=\"set-location\" data-id=\"".concat(store.id, "\">\n\t\t\t\t\t\tMy store\n\t\t\t\t\t</button>\n\t\t\t\t"));
          $btn_container.append($btn);
        } else {
          var _$btn = $("\n\t\t\t\t\t<button class=\"et_pb_button location-dropdown__button\" type=\"button\" data-id=\"".concat(store.id, "\" data-action=\"set-location\">\n\t\t\t\t\t\tSet as my store\n\t\t\t\t\t</button>\n\t\t\t\t"));

          $btn_container.append(_$btn);
        }

        $ul.append($li);
      });
    }
  }, {
    key: "toggleDropdown",
    value: function toggleDropdown() {
      if (this.dropdown) {
        this.hideDropdown();
      } else {
        this.showDropdown($(window).width() < 981 ? 'mobile' : 'desktop');
      }
    }
  }, {
    key: "hideDropdown",
    value: function hideDropdown() {
      var _this5 = this;

      this.$trigger.removeClass('-open');

      if (this.dropdown == 'mobile') {
        this.$dropdown.css({
          'height': this.$dropdown.outerHeight()
        }).animate({
          height: 0
        }, 300, function () {
          _this5.$dropdown.remove();

          setTimeout(function () {
            return $(window).trigger('resize');
          }, 100);
        });
      } else {
        this.$dropdown.animate({
          'opacity': 0
        }, 300, function () {
          _this5.$dropdown.remove();

          $(window).trigger('resize');
        });
      }

      this.dropdown = false;
    }
  }, {
    key: "showDropdown",
    value: function showDropdown(style) {
      var _this6 = this;

      this.updateUI();

      if (!this.$dropdown) {
        return;
      }

      if (this.dropdown === style) {
        return;
      }

      this.updateDropdownList();
      this.$trigger.addClass('-open');

      if (style == 'mobile') {
        this.$dropdown.insertAfter(this.$trigger);

        if (!this.dropdown) {
          this.$dropdown.css({
            'height': '0',
            'top': 'auto',
            'opacity': 1
          }).animate({
            height: this.$dropdown[0].scrollHeight
          }, 300, function () {
            _this6.$dropdown.css({
              'height': 'auto'
            });

            $(window).trigger('resize');
          });
        } else {
          this.$dropdown.css({
            'height': 'auto',
            'top': 'auto',
            'opacity': 1
          });
        }
      } else {
        this.$dropdown.css({
          'opacity': 0
        });
        this.$dropdown.appendTo('body');
        this.$dropdown.animate({
          'opacity': 1
        }, 300);
        this.dropdown = style;
        this.onResize();
      }

      this.dropdown = style;
    }
  }, {
    key: "backgroundClick",
    value: function backgroundClick(e) {
      this.toggleDropdown();
    }
  }, {
    key: "onResize",
    value: function onResize() {
      if (!this.dropdown) {
        return;
      }

      if ($(window).width() < 981 && this.dropdown !== 'mobile') {
        // move to mobile view
        this.showDropdown('mobile');
      } else if ($(window).width() > 980) {
        if (this.dropdown !== 'desktop') {
          // move to mobile view
          this.showDropdown('desktop');
        }

        var offset = $('#main-header').outerHeight() + $('body').offset().top;
        console.log(offset);
        this.$dropdown.css({
          top: offset,
          height: $(window).height() - offset
        });
      }
    }
  }]);

  return UserLocation;
}();

window.UserLocation = new UserLocation();
var _default = window.UserLocation;
exports.default = _default;
},{"./cookies":"js/helpers/cookies.js","./geo":"js/helpers/geo.js"}],"js/helpers/design-survey.js":[function(require,module,exports) {
"use strict";

var _userLocation = _interopRequireDefault(require("./user-location"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

(function ($) {
  var location;

  function setLocation(_location) {
    location = _location;
    updateLocation();
  }

  function updateLocation(_location) {
    location = _location ? _location : location;

    if (location) {
      $('.design-survey .chx-store select option[value="' + location.id + '"]').prop('selected', true);
    }
  }

  function pageRender(e, i) {
    var $wrapper = $('#gform_wrapper_' + i);

    if (!$wrapper.find('form').hasClass('design-survey')) {
      return;
    }

    var page = 1;
    var $active = $wrapper.find('.gform_page').filter(function (i, el) {
      if ($(el).is(':visible')) {
        page = i;
        return true;
      }

      return false;
    });
    var total = $wrapper.find('.gform_page').length - 1;

    if ($active.length && !$active.hasClass('no-paging')) {
      var $toolbar = $('<div />');
      var title = '';

      if ($active.hasClass('about-your-room')) {
        title = 'About Your Room';
      } else if ($active.hasClass('wrapping-up')) {
        title = 'Wrapping Up';
      } else {
        title = 'Getting to Know You';
      }

      $wrapper.find('form').prepend($toolbar);
      $toolbar.addClass('paging-toolbar');
      $toolbar.html("\n\t\t\t\t<span class=\"label\">".concat(title, "</span>\n\t\t\t\t<span class=\"pages\">Question ").concat(page, " of ").concat(total, "</span>\n\t\t\t"));
    } // check for the image column


    var $image = $active.find('.image-right');

    if ($image.length) {
      var $fields = $active.find('.gform_page_fields');
      var $footer = $active.find('.gform_page_footer');
      var $ct = $('<div />').addClass('flex-row');
      $ct.insertBefore($fields);
      var $div = $('<div />');
      $fields.appendTo($div);
      $footer.appendTo($div);
      $div.appendTo($ct);
      var $col = $('<div />');
      $col.appendTo($ct);
      $col.html($image.html());
      $image.remove();
    }

    updateLocation();
  }

  _userLocation.default.onChange(setLocation);

  $(document).on('gform_post_render', pageRender);
})(jQuery);
},{"./user-location":"js/helpers/user-location.js"}],"js/helpers/gallery.js":[function(require,module,exports) {
(function ($) {
  $(document).on('et_pb_after_init_modules', function () {
    jQuery('.et_post_gallery').each(function () {
      $(this).data().magnificPopup.image = {
        titleSrc: function titleSrc(item) {
          var title = item.el.attr('title');
          var image = item.el.attr('href');
          var $data = $('<div />');
          $('<h4 />').html(title).appendTo($data);
          return $data.html();
        },
        markup: '<div class="mfp-figure">' + '<div class="mfp-close"></div>' + '<div class="mfp-top-bar">' + '<div class="mfp-title"></div>' + '</div>' + '<div class="mfp-img"></div>' + '<div class="mfp-bottom-bar">' + '<div class="mfp-details">' + '<div class="mfp-details-buttons">' + '<button class="mfp-info" data-toggle="mfp-caption">' + '<span class="fas fa-angle-up"></span> ' + 'Details' + '</button>' + '<button class="mfp-info" data-action="inquire">' + '<span class="fas fa-comment-alt"></span> ' + 'Like What You see? Get this look' + '</button>' + '</div>' + '<div class="mfp-caption-container">' + '<div class="mfp-caption"></div>' + '</div>' + '</div>' + //'<div class="inquire-button"></div>'+
        '<div class="mfp-counter"></div>' + '</div>' + '</div>' // Popup HTML markup. `.mfp-img` div will be replaced with img tag, `.mfp-close` by close button

      };
      $(this).on('mfpMarkupParse', function (event, template, values, item) {
        var title = item.el.attr('title');
        var image = item.el.attr('href'); // caption?

        var caption = item.el.closest('.et_pb_gallery_item').find('.et_pb_gallery_caption').html();
        if (caption) caption = caption.trim();
        setTimeout(function () {
          // add our stuff to the top bar
          if (caption) {
            $('.mfp-caption').html(caption);
            $('[data-toggle="mfp-caption"]').show();
          } else {
            $('.mfp-caption').html('');
            $('[data-toggle="mfp-caption"]').hide();
          }

          $('[data-action="inquire"]').data('title', title).data('image', image);
        }, 0);
      });
    });
    $(document).on('click', '[data-toggle="mfp-caption"]', function (e) {
      var $btn = $(e.target);
      var $ct = $('.mfp-caption-container');

      if (!$ct.height()) {
        $btn.addClass('-active');
        $ct.animate({
          height: $ct[0].scrollHeight
        }, 200, function () {
          $ct.css({
            height: 'auto'
          });
        });
      } else {
        $btn.removeClass('-active');
        $ct.css({
          height: $ct.outerHeight()
        });
        $ct.animate({
          height: 0
        }, 200);
      }
    });
    $(document).on('click', '[data-action="inquire"]', function (e) {
      var $btn = $(e.target);
      $(document).trigger('inquire.contact-widget', [$btn.data('title'), $btn.data('image')]);
    });
  });
})(jQuery);
},{}],"js/helpers/contact-widget.js":[function(require,module,exports) {
"use strict";

var _userLocation = _interopRequireDefault(require("./user-location"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

(function ($) {
  var ContactWidget =
  /*#__PURE__*/
  function () {
    function ContactWidget() {
      _classCallCheck(this, ContactWidget);

      _userLocation.default.onChange(this.setLocation.bind(this));

      $(this.init.bind(this));
      $(this.fixMagnificPopup.bind(this));
    }

    _createClass(ContactWidget, [{
      key: "init",
      value: function init() {
        this.initElements();
        this.initEvents();
      }
    }, {
      key: "fixMagnificPopup",
      value: function fixMagnificPopup() {
        if (!$.magnificPopup || !$.magnificPopup.instance) {
          return;
        }

        $.magnificPopup.instance._onFocusIn = function (e) {
          var $target = $(e.target);

          if (!$target.is('.mfp-content button')) {
            $.magnificPopup.proto._onFocusIn.call(this, e);
          }
        };
      }
    }, {
      key: "initElements",
      value: function initElements() {
        this.$el = $('.contact-widget');
        this.$trigger = this.$el.find('.contact-widget__trigger');
        this.$content = this.$el.find('.contact-widget__content');
        this.$block = this.$el.find('.contact-widget__block');
        this.formId = Number(this.$el.find('form').attr('id').replace(/^gform_/, ''));
        this.formHTML = this.$el.find('.contact-widget__form').html();
      }
    }, {
      key: "initEvents",
      value: function initEvents() {
        this.$trigger.on('click', this.toggle.bind(this));
        this.$el.on('focusin', this.cancelFocusBubble.bind(this));
        this.$el.on('keyup keydown keypress', this.stopPropagation.bind(this));
        $(document).on('inquire.contact-widget', this.inquire.bind(this));
        $(document).on('contact-store', this.contactStore.bind(this));
        $(document).on('gform_confirmation_loaded', this.onConfirmation.bind(this));
      }
    }, {
      key: "toggle",
      value: function toggle() {
        this.$el.hasClass('open') ? this.close() : this.open();
      }
    }, {
      key: "open",
      value: function open(title, image) {
        var _this = this;

        if (!this.$el.find('form').length) {
          this.$el.find('.contact-widget__form').html(this.formHTML);
          this.initForm();
        }

        this.$el.find('.contact-widget__inquiry').remove();

        if (title && image) {
          this.addImage(title, image);
        } else {
          this.clearImage();
        }

        var isOpen = this.$el.hasClass('open');
        this.$el.addClass('open');

        if (isOpen) {
          console.log(isOpen);
          this.$el.animate({
            'margin-bottom': 20
          }, 150, function () {
            _this.$el.animate({
              'margin-bottom': 0
            }, 130, function () {
              _this.$el.animate({
                'margin-bottom': 13
              }, 120, function () {
                _this.$el.animate({
                  'margin-bottom': 0
                }, 100, function () {// this.$el.animate({
                  // 	'margin-bottom':7
                  // },100,()=>{
                  // 	this.$el.animate({
                  // 		'margin-bottom':0
                  // 	},100,()=>{
                  //
                  // 	});
                  // });
                });
              });
            });
          });
        }

        this.initForm();
      }
    }, {
      key: "initForm",
      value: function initForm() {
        var _this2 = this;

        this.$el.find('[aria-required]').attr('required', 'required');
        this.updateLocation();
        setTimeout(function () {
          return _this2.$el.find('input').first().focus();
        }, 300);
      }
    }, {
      key: "addImage",
      value: function addImage(title, image) {
        this.$el.find('[data-input-name="collection_image"]').val(image);
        this.$el.find('[data-input-name="collection_item"]').val(title);
        this.$el.find('.gform_wrapper').prepend($("\n\t\t\t\t<div class=\"contact-widget__inquiry\">\n\t\t\t\t\t<img class=\"contact-widget__inquiry-image\" src=\"".concat(image, "\" />\n\t\t\t\t\t<div class=\"contact-widget__inquiry-title\">Inquiring about <strong>").concat(title, "</strong></div>\n\t\t\t\t</div>\n\t\t\t")));
      }
    }, {
      key: "clearImage",
      value: function clearImage() {
        this.$el.find('[data-input-name="collection_image"]').val('');
        this.$el.find('[data-input-name="collection_item"]').val('');
      }
    }, {
      key: "updateLocation",
      value: function updateLocation(location) {
        location = location ? location : this.location;

        if (location && this.$el) {
          this.$el.find('.chx-store select option[value="' + location.id + '"]').prop('selected', true);
        }
      }
    }, {
      key: "close",
      value: function close() {
        this.$el.removeClass('open');
      }
    }, {
      key: "cancelFocusBubble",
      value: function cancelFocusBubble(e) {
        e.stopPropagation();
        e.stopImmediatePropagation();
      }
    }, {
      key: "stopPropagation",
      value: function stopPropagation(e) {
        e.stopPropagation();
        e.stopImmediatePropagation();
      }
    }, {
      key: "inquire",
      value: function inquire(e, title, image) {
        this.open(title, image);
      }
    }, {
      key: "contactStore",
      value: function contactStore(e, store) {
        this.open();
        this.updateLocation(store);
      }
    }, {
      key: "setLocation",
      value: function setLocation(location) {
        this.location = location;
        this.updateLocation();
      }
    }, {
      key: "onConfirmation",
      value: function onConfirmation(e, formId) {
        if (this.formId !== formId) return;
      }
    }]);

    return ContactWidget;
  }();

  window.contactWidget = new ContactWidget();
})(jQuery);
},{"./user-location":"js/helpers/user-location.js"}],"js/helpers/izimodal.js":[function(require,module,exports) {
(function ($) {
  $(document).on('click', 'a.modal, li.modal a', function (e) {
    e.preventDefault();
    var $target = $(e.target);

    if (!$target.is('a')) {
      $target = $target.find('a');
    }

    console.log(e);
    $("#modal-iframe").iziModal({
      title: $target.text(),
      headerColor: '#111111',
      overlayClose: true,
      iframe: true,
      openFullscreen: false,
      borderBottom: false,
      zindex: 999999,
      iframeHeight: $target.data('height') || 350
    });
    $('#modal-iframe').iziModal('open', e);
  });
})(jQuery);
},{}],"js/helpers/map-pins.js":[function(require,module,exports) {
jQuery(function ($) {
  setTimeout(function () {
    $('.et_pb_map_container').each(function () {
      $this_map_container = $(this);
      var markers = [];
      $this_map_container.find('.et_pb_map_pin[data-initial="open"]').each(function () {
        var $this_marker = $(this);
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(parseFloat($this_marker.attr('data-lat')), parseFloat($this_marker.attr('data-lng'))),
          map: $this_map_container.data('map'),
          title: $this_marker.attr('data-title'),
          icon: {
            url: et_pb_custom.builder_images_uri + '/marker.png',
            size: new google.maps.Size(46, 43),
            anchor: new google.maps.Point(16, 43)
          },
          shape: {
            coord: [1, 1, 46, 43],
            type: 'rect'
          },
          anchorPoint: new google.maps.Point(0, -45),
          opacity: 0
        });

        if ($this_marker.find('.infowindow').length) {
          var infowindow = new google.maps.InfoWindow({
            content: $this_marker.html()
          });
          infowindow.open($this_map_container.data('map'), marker);
          google.maps.event.addListener($this_map_container.data('map'), 'click', function () {
            infowindow.close();
            marker.setMap(null);
          });
          google.maps.event.addListener(infowindow, 'closeclick', function () {
            infowindow.close();
            marker.setMap(null);
          });
        }

        markers.push(marker);
      });

      if ($this_map_container.find('.et_pb_map_pin').length > 1) {
        var map = $this_map_container.data('map');
        var bounds = new google.maps.LatLngBounds();
        $this_map_container.find('.et_pb_map_pin').each(function () {
          var $this_marker = $(this);
          var point = new google.maps.LatLng(parseFloat($this_marker.attr('data-lat')), parseFloat($this_marker.attr('data-lng')));

          if (point.lat() > 30) {
            bounds.extend(point);
          }
        });
        map.setCenter(bounds.getCenter());
        map.fitBounds(bounds);
      }
    });
  }, 1500);
});
},{}],"js/index.js":[function(require,module,exports) {
"use strict";

require("./helpers/instagram-feed-patch");

require("./helpers/design-survey");

require("./helpers/gallery");

require("./helpers/user-location");

require("./helpers/contact-widget");

require("./helpers/izimodal");

require("./helpers/map-pins");
},{"./helpers/instagram-feed-patch":"js/helpers/instagram-feed-patch.js","./helpers/design-survey":"js/helpers/design-survey.js","./helpers/gallery":"js/helpers/gallery.js","./helpers/user-location":"js/helpers/user-location.js","./helpers/contact-widget":"js/helpers/contact-widget.js","./helpers/izimodal":"js/helpers/izimodal.js","./helpers/map-pins":"js/helpers/map-pins.js"}],"cabothouse.js":[function(require,module,exports) {
"use strict";

require("./scss/index.scss");

require("./js/index");
},{"./scss/index.scss":"scss/index.scss","./js/index":"js/index.js"}]},{},["cabothouse.js"], null)
//# sourceMappingURL=/cabothouse.js.map