/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************************!*\
  !*** ./include/Module/contact/Blocks/btn/src/index.js ***!
  \********************************************************/
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }
function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
BlockRegister({
  init: function init() {
    this.on('change:attributes:contact-type', this.handleTypeChange);
    this.on('change:attributes:use_template', this.handleUseTemplateChange);
    this.on('change', this.handleUpdate);
    this.handleUseTemplateChange.call(this);
  },
  updated: function updated(property, value, prevValue) {
    if (value == "selected") {
      this.handleSelected.call(this);
    }
  },
  handleSelected: function handleSelected(e) {
    this.updateTraits.call(this);
  },
  handleTypeChange: function handleTypeChange() {
    this.updateTraits.call(this);
  },
  updateTraits: function updateTraits() {
    var fetchURL = this.defaults.fetchURL;
    var attributes = this.getAttributes();
    var contactType = attributes['contact-type'];
    var contactTrait = this.getTrait("contact");
    if (!fetchURL || !contactTrait) {
      return;
    }
    __.http.post(fetchURL, {
      'contact-type': contactType
    }).then(function (response) {
      if (!response.data || response.data.length === 0) {
        contactTrait.set("value", '');
        contactTrait.set("options", [{
          id: '',
          name: '-- Select Contact --'
        }]);
        return;
      }
      contactTrait.set("options", [{
        id: '',
        name: '-- Select Contact --'
      }].concat(_toConsumableArray(response.data.map(function (item) {
        return {
          id: "CT-".concat(item.id),
          name: item.name
        };
      }))));
    })["catch"](function (error) {
      __.toast(error.message || "Something went wrong", 5, 'text-danger');
    });
  },
  handleUseTemplateChange: function handleUseTemplateChange() {
    var templateFetchURL = this.defaults.templateFetchURL;
    var attributes = this.getAttributes();
    var useTemplate = attributes.use_template || false;
    if (useTemplate) {
      this.removeTrait("contact-type");
      this.removeTrait("contact");
      this.addTrait({
        name: "template",
        type: "select",
        label: "Template",
        options: [{
          id: '',
          name: '-- Select Template --'
        }]
      });
      var templateTrait = this.getTrait("template");
      if (!templateFetchURL || !templateTrait) return;
      __.http.get(templateFetchURL).then(function (response) {
        if (!response.data || response.data.length === 0) {
          return;
        }
        templateTrait.set("options", [{
          id: '',
          name: '-- Select Template --'
        }].concat(_toConsumableArray((response.data || []).map(function (item) {
          return {
            id: "TP-".concat(item.id),
            name: item.name
          };
        }))));
      })["catch"](function (error) {
        __.toast(error.message || "Something went wrong", 5, 'text-danger');
      });
    } else {
      this.removeTrait("template");
      this.addTrait({
        type: "select",
        name: "contact-type",
        label: "Contact Type",
        options: [{
          id: 'phone',
          label: 'Phone'
        }, {
          id: 'email',
          label: 'Email'
        }, {
          id: 'whatsapp',
          label: 'Whatsapp'
        }]
      });
      this.addTrait({
        type: "select",
        name: "contact",
        label: "Contact",
        options: [{
          id: '',
          name: '-- Select Contact --'
        }]
      });
      this.updateTraits();
    }
  }
});
/******/ })()
;