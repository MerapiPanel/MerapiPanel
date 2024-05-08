/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/huge-uploader/node_modules/event-target-shim/dist/event-target-shim.js":
/*!*********************************************************************************************!*\
  !*** ./node_modules/huge-uploader/node_modules/event-target-shim/dist/event-target-shim.js ***!
  \*********************************************************************************************/
/***/ ((module, exports) => {

/**
 * @author Toru Nagashima <https://github.com/mysticatea>
 * @copyright 2015 Toru Nagashima. All rights reserved.
 * See LICENSE file in root directory for full license.
 */


Object.defineProperty(exports, "__esModule", ({ value: true }));

/**
 * @typedef {object} PrivateData
 * @property {EventTarget} eventTarget The event target.
 * @property {{type:string}} event The original event object.
 * @property {number} eventPhase The current event phase.
 * @property {EventTarget|null} currentTarget The current event target.
 * @property {boolean} canceled The flag to prevent default.
 * @property {boolean} stopped The flag to stop propagation immediately.
 * @property {Function|null} passiveListener The listener if the current listener is passive. Otherwise this is null.
 * @property {number} timeStamp The unix time.
 * @private
 */

/**
 * Private data for event wrappers.
 * @type {WeakMap<Event, PrivateData>}
 * @private
 */
const privateData = new WeakMap();

/**
 * Cache for wrapper classes.
 * @type {WeakMap<Object, Function>}
 * @private
 */
const wrappers = new WeakMap();

/**
 * Get private data.
 * @param {Event} event The event object to get private data.
 * @returns {PrivateData} The private data of the event.
 * @private
 */
function pd(event) {
    const retv = privateData.get(event);
    console.assert(retv != null, "'this' is expected an Event object, but got", event);
    return retv
}

/**
 * @see https://dom.spec.whatwg.org/#interface-event
 * @private
 */
/**
 * The event wrapper.
 * @constructor
 * @param {EventTarget} eventTarget The event target of this dispatching.
 * @param {Event|{type:string}} event The original event to wrap.
 */
function Event(eventTarget, event) {
    privateData.set(this, {
        eventTarget,
        event,
        eventPhase: 2,
        currentTarget: eventTarget,
        canceled: false,
        stopped: false,
        passiveListener: null,
        timeStamp: event.timeStamp || Date.now(),
    });

    // https://heycam.github.io/webidl/#Unforgeable
    Object.defineProperty(this, "isTrusted", { value: false, enumerable: true });

    // Define accessors
    const keys = Object.keys(event);
    for (let i = 0; i < keys.length; ++i) {
        const key = keys[i];
        if (!(key in this)) {
            Object.defineProperty(this, key, defineRedirectDescriptor(key));
        }
    }
}

// Should be enumerable, but class methods are not enumerable.
Event.prototype = {
    /**
     * The type of this event.
     * @type {string}
     */
    get type() {
        return pd(this).event.type
    },

    /**
     * The target of this event.
     * @type {EventTarget}
     */
    get target() {
        return pd(this).eventTarget
    },

    /**
     * The target of this event.
     * @type {EventTarget}
     */
    get currentTarget() {
        return pd(this).currentTarget
    },

    /**
     * @returns {EventTarget[]} The composed path of this event.
     */
    composedPath() {
        const currentTarget = pd(this).currentTarget;
        if (currentTarget == null) {
            return []
        }
        return [currentTarget]
    },

    /**
     * Constant of NONE.
     * @type {number}
     */
    get NONE() {
        return 0
    },

    /**
     * Constant of CAPTURING_PHASE.
     * @type {number}
     */
    get CAPTURING_PHASE() {
        return 1
    },

    /**
     * Constant of AT_TARGET.
     * @type {number}
     */
    get AT_TARGET() {
        return 2
    },

    /**
     * Constant of BUBBLING_PHASE.
     * @type {number}
     */
    get BUBBLING_PHASE() {
        return 3
    },

    /**
     * The target of this event.
     * @type {number}
     */
    get eventPhase() {
        return pd(this).eventPhase
    },

    /**
     * Stop event bubbling.
     * @returns {void}
     */
    stopPropagation() {
        const data = pd(this);
        if (typeof data.event.stopPropagation === "function") {
            data.event.stopPropagation();
        }
    },

    /**
     * Stop event bubbling.
     * @returns {void}
     */
    stopImmediatePropagation() {
        const data = pd(this);

        data.stopped = true;
        if (typeof data.event.stopImmediatePropagation === "function") {
            data.event.stopImmediatePropagation();
        }
    },

    /**
     * The flag to be bubbling.
     * @type {boolean}
     */
    get bubbles() {
        return Boolean(pd(this).event.bubbles)
    },

    /**
     * The flag to be cancelable.
     * @type {boolean}
     */
    get cancelable() {
        return Boolean(pd(this).event.cancelable)
    },

    /**
     * Cancel this event.
     * @returns {void}
     */
    preventDefault() {
        const data = pd(this);
        if (data.passiveListener != null) {
            console.warn("Event#preventDefault() was called from a passive listener:", data.passiveListener);
            return
        }
        if (!data.event.cancelable) {
            return
        }

        data.canceled = true;
        if (typeof data.event.preventDefault === "function") {
            data.event.preventDefault();
        }
    },

    /**
     * The flag to indicate cancellation state.
     * @type {boolean}
     */
    get defaultPrevented() {
        return pd(this).canceled
    },

    /**
     * The flag to be composed.
     * @type {boolean}
     */
    get composed() {
        return Boolean(pd(this).event.composed)
    },

    /**
     * The unix time of this event.
     * @type {number}
     */
    get timeStamp() {
        return pd(this).timeStamp
    },
};

// `constructor` is not enumerable.
Object.defineProperty(Event.prototype, "constructor", { value: Event, configurable: true, writable: true });

// Ensure `event instanceof window.Event` is `true`.
if (typeof window !== "undefined" && typeof window.Event !== "undefined") {
    Object.setPrototypeOf(Event.prototype, window.Event.prototype);

    // Make association for wrappers.
    wrappers.set(window.Event.prototype, Event);
}

/**
 * Get the property descriptor to redirect a given property.
 * @param {string} key Property name to define property descriptor.
 * @returns {PropertyDescriptor} The property descriptor to redirect the property.
 * @private
 */
function defineRedirectDescriptor(key) {
    return {
        get() {
            return pd(this).event[key]
        },
        set(value) {
            pd(this).event[key] = value;
        },
        configurable: true,
        enumerable: true,
    }
}

/**
 * Get the property descriptor to call a given method property.
 * @param {string} key Property name to define property descriptor.
 * @returns {PropertyDescriptor} The property descriptor to call the method property.
 * @private
 */
function defineCallDescriptor(key) {
    return {
        value() {
            const event = pd(this).event;
            return event[key].apply(event, arguments)
        },
        configurable: true,
        enumerable: true,
    }
}

/**
 * Define new wrapper class.
 * @param {Function} BaseEvent The base wrapper class.
 * @param {Object} proto The prototype of the original event.
 * @returns {Function} The defined wrapper class.
 * @private
 */
function defineWrapper(BaseEvent, proto) {
    const keys = Object.keys(proto);
    if (keys.length === 0) {
        return BaseEvent
    }

    /** CustomEvent */
    function CustomEvent(eventTarget, event) {
        BaseEvent.call(this, eventTarget, event);
    }

    CustomEvent.prototype = Object.create(BaseEvent.prototype, {
        constructor: { value: CustomEvent, configurable: true, writable: true },
    });

    // Define accessors.
    for (let i = 0; i < keys.length; ++i) {
        const key = keys[i];
        if (!(key in BaseEvent.prototype)) {
            const descriptor = Object.getOwnPropertyDescriptor(proto, key);
            const isFunc = (typeof descriptor.value === "function");
            Object.defineProperty(
                CustomEvent.prototype,
                key,
                isFunc ? defineCallDescriptor(key) : defineRedirectDescriptor(key)
            );
        }
    }

    return CustomEvent
}

/**
 * Get the wrapper class of a given prototype.
 * @param {Object} proto The prototype of the original event to get its wrapper.
 * @returns {Function} The wrapper class.
 * @private
 */
function getWrapper(proto) {
    if (proto == null || proto === Object.prototype) {
        return Event
    }

    let wrapper = wrappers.get(proto);
    if (wrapper == null) {
        wrapper = defineWrapper(getWrapper(Object.getPrototypeOf(proto)), proto);
        wrappers.set(proto, wrapper);
    }
    return wrapper
}

/**
 * Wrap a given event to management a dispatching.
 * @param {EventTarget} eventTarget The event target of this dispatching.
 * @param {Object} event The event to wrap.
 * @returns {Event} The wrapper instance.
 * @private
 */
function wrapEvent(eventTarget, event) {
    const Wrapper = getWrapper(Object.getPrototypeOf(event));
    return new Wrapper(eventTarget, event)
}

/**
 * Get the stopped flag of a given event.
 * @param {Event} event The event to get.
 * @returns {boolean} The flag to stop propagation immediately.
 * @private
 */
function isStopped(event) {
    return pd(event).stopped
}

/**
 * Set the current event phase of a given event.
 * @param {Event} event The event to set current target.
 * @param {number} eventPhase New event phase.
 * @returns {void}
 * @private
 */
function setEventPhase(event, eventPhase) {
    pd(event).eventPhase = eventPhase;
}

/**
 * Set the current target of a given event.
 * @param {Event} event The event to set current target.
 * @param {EventTarget|null} currentTarget New current target.
 * @returns {void}
 * @private
 */
function setCurrentTarget(event, currentTarget) {
    pd(event).currentTarget = currentTarget;
}

/**
 * Set a passive listener of a given event.
 * @param {Event} event The event to set current target.
 * @param {Function|null} passiveListener New passive listener.
 * @returns {void}
 * @private
 */
function setPassiveListener(event, passiveListener) {
    pd(event).passiveListener = passiveListener;
}

/**
 * @typedef {object} ListenerNode
 * @property {Function} listener
 * @property {1|2|3} listenerType
 * @property {boolean} passive
 * @property {boolean} once
 * @property {ListenerNode|null} next
 * @private
 */

/**
 * @type {WeakMap<object, Map<string, ListenerNode>>}
 * @private
 */
const listenersMap = new WeakMap();

// Listener types
const CAPTURE = 1;
const BUBBLE = 2;
const ATTRIBUTE = 3;

/**
 * Check whether a given value is an object or not.
 * @param {any} x The value to check.
 * @returns {boolean} `true` if the value is an object.
 */
function isObject(x) {
    return x !== null && typeof x === "object" //eslint-disable-line no-restricted-syntax
}

/**
 * Get listeners.
 * @param {EventTarget} eventTarget The event target to get.
 * @returns {Map<string, ListenerNode>} The listeners.
 * @private
 */
function getListeners(eventTarget) {
    const listeners = listenersMap.get(eventTarget);
    if (listeners == null) {
        throw new TypeError("'this' is expected an EventTarget object, but got another value.")
    }
    return listeners
}

/**
 * Get the property descriptor for the event attribute of a given event.
 * @param {string} eventName The event name to get property descriptor.
 * @returns {PropertyDescriptor} The property descriptor.
 * @private
 */
function defineEventAttributeDescriptor(eventName) {
    return {
        get() {
            const listeners = getListeners(this);
            let node = listeners.get(eventName);
            while (node != null) {
                if (node.listenerType === ATTRIBUTE) {
                    return node.listener
                }
                node = node.next;
            }
            return null
        },

        set(listener) {
            if (typeof listener !== "function" && !isObject(listener)) {
                listener = null; // eslint-disable-line no-param-reassign
            }
            const listeners = getListeners(this);

            // Traverse to the tail while removing old value.
            let prev = null;
            let node = listeners.get(eventName);
            while (node != null) {
                if (node.listenerType === ATTRIBUTE) {
                    // Remove old value.
                    if (prev !== null) {
                        prev.next = node.next;
                    }
                    else if (node.next !== null) {
                        listeners.set(eventName, node.next);
                    }
                    else {
                        listeners.delete(eventName);
                    }
                }
                else {
                    prev = node;
                }

                node = node.next;
            }

            // Add new value.
            if (listener !== null) {
                const newNode = {
                    listener,
                    listenerType: ATTRIBUTE,
                    passive: false,
                    once: false,
                    next: null,
                };
                if (prev === null) {
                    listeners.set(eventName, newNode);
                }
                else {
                    prev.next = newNode;
                }
            }
        },
        configurable: true,
        enumerable: true,
    }
}

/**
 * Define an event attribute (e.g. `eventTarget.onclick`).
 * @param {Object} eventTargetPrototype The event target prototype to define an event attrbite.
 * @param {string} eventName The event name to define.
 * @returns {void}
 */
function defineEventAttribute(eventTargetPrototype, eventName) {
    Object.defineProperty(eventTargetPrototype, `on${eventName}`, defineEventAttributeDescriptor(eventName));
}

/**
 * Define a custom EventTarget with event attributes.
 * @param {string[]} eventNames Event names for event attributes.
 * @returns {EventTarget} The custom EventTarget.
 * @private
 */
function defineCustomEventTarget(eventNames) {
    /** CustomEventTarget */
    function CustomEventTarget() {
        EventTarget.call(this);
    }

    CustomEventTarget.prototype = Object.create(EventTarget.prototype, {
        constructor: { value: CustomEventTarget, configurable: true, writable: true },
    });

    for (let i = 0; i < eventNames.length; ++i) {
        defineEventAttribute(CustomEventTarget.prototype, eventNames[i]);
    }

    return CustomEventTarget
}

/**
 * EventTarget.
 *
 * - This is constructor if no arguments.
 * - This is a function which returns a CustomEventTarget constructor if there are arguments.
 *
 * For example:
 *
 *     class A extends EventTarget {}
 *     class B extends EventTarget("message") {}
 *     class C extends EventTarget("message", "error") {}
 *     class D extends EventTarget(["message", "error"]) {}
 */
function EventTarget() {
    /*eslint-disable consistent-return */
    if (this instanceof EventTarget) {
        listenersMap.set(this, new Map());
        return
    }
    if (arguments.length === 1 && Array.isArray(arguments[0])) {
        return defineCustomEventTarget(arguments[0])
    }
    if (arguments.length > 0) {
        const types = new Array(arguments.length);
        for (let i = 0; i < arguments.length; ++i) {
            types[i] = arguments[i];
        }
        return defineCustomEventTarget(types)
    }
    throw new TypeError("Cannot call a class as a function")
    /*eslint-enable consistent-return */
}

// Should be enumerable, but class methods are not enumerable.
EventTarget.prototype = {
    /**
     * Add a given listener to this event target.
     * @param {string} eventName The event name to add.
     * @param {Function} listener The listener to add.
     * @param {boolean|{capture?:boolean,passive?:boolean,once?:boolean}} [options] The options for this listener.
     * @returns {boolean} `true` if the listener was added actually.
     */
    addEventListener(eventName, listener, options) {
        if (listener == null) {
            return false
        }
        if (typeof listener !== "function" && !isObject(listener)) {
            throw new TypeError("'listener' should be a function or an object.")
        }

        const listeners = getListeners(this);
        const optionsIsObj = isObject(options);
        const capture = optionsIsObj ? Boolean(options.capture) : Boolean(options);
        const listenerType = (capture ? CAPTURE : BUBBLE);
        const newNode = {
            listener,
            listenerType,
            passive: optionsIsObj && Boolean(options.passive),
            once: optionsIsObj && Boolean(options.once),
            next: null,
        };

        // Set it as the first node if the first node is null.
        let node = listeners.get(eventName);
        if (node === undefined) {
            listeners.set(eventName, newNode);
            return true
        }

        // Traverse to the tail while checking duplication..
        let prev = null;
        while (node != null) {
            if (node.listener === listener && node.listenerType === listenerType) {
                // Should ignore duplication.
                return false
            }
            prev = node;
            node = node.next;
        }

        // Add it.
        prev.next = newNode;
        return true
    },

    /**
     * Remove a given listener from this event target.
     * @param {string} eventName The event name to remove.
     * @param {Function} listener The listener to remove.
     * @param {boolean|{capture?:boolean,passive?:boolean,once?:boolean}} [options] The options for this listener.
     * @returns {boolean} `true` if the listener was removed actually.
     */
    removeEventListener(eventName, listener, options) {
        if (listener == null) {
            return false
        }

        const listeners = getListeners(this);
        const capture = isObject(options) ? Boolean(options.capture) : Boolean(options);
        const listenerType = (capture ? CAPTURE : BUBBLE);

        let prev = null;
        let node = listeners.get(eventName);
        while (node != null) {
            if (node.listener === listener && node.listenerType === listenerType) {
                if (prev !== null) {
                    prev.next = node.next;
                }
                else if (node.next !== null) {
                    listeners.set(eventName, node.next);
                }
                else {
                    listeners.delete(eventName);
                }
                return true
            }

            prev = node;
            node = node.next;
        }

        return false
    },

    /**
     * Dispatch a given event.
     * @param {Event|{type:string}} event The event to dispatch.
     * @returns {boolean} `false` if canceled.
     */
    dispatchEvent(event) { //eslint-disable-line complexity
        if (event == null || typeof event.type !== "string") {
            throw new TypeError("\"event.type\" should be a string.")
        }

        // If listeners aren't registered, terminate.
        const listeners = getListeners(this);
        const eventName = event.type;
        let node = listeners.get(eventName);
        if (node == null) {
            return true
        }

        // Since we cannot rewrite several properties, so wrap object.
        const wrappedEvent = wrapEvent(this, event);

        // This doesn't process capturing phase and bubbling phase.
        // This isn't participating in a tree.
        let prev = null;
        while (node != null) {
            // Remove this listener if it's once
            if (node.once) {
                if (prev !== null) {
                    prev.next = node.next;
                }
                else if (node.next !== null) {
                    listeners.set(eventName, node.next);
                }
                else {
                    listeners.delete(eventName);
                }
            }
            else {
                prev = node;
            }

            // Call this listener
            setPassiveListener(wrappedEvent, (node.passive ? node.listener : null));
            if (typeof node.listener === "function") {
                try {
                    node.listener.call(this, wrappedEvent);
                }
                catch (err) {
                    /*eslint-disable no-console */
                    if (typeof console !== "undefined" && typeof console.error === "function") {
                        console.error(err);
                    }
                    /*eslint-enable no-console */
                }
            }
            else if (node.listenerType !== ATTRIBUTE && typeof node.listener.handleEvent === "function") {
                node.listener.handleEvent(wrappedEvent);
            }

            // Break if `event.stopImmediatePropagation` was called.
            if (isStopped(wrappedEvent)) {
                break
            }

            node = node.next;
        }
        setPassiveListener(wrappedEvent, null);
        setEventPhase(wrappedEvent, 0);
        setCurrentTarget(wrappedEvent, null);

        return !wrappedEvent.defaultPrevented
    },
};

// `constructor` is not enumerable.
Object.defineProperty(EventTarget.prototype, "constructor", { value: EventTarget, configurable: true, writable: true });

// Ensure `eventTarget instanceof window.EventTarget` is `true`.
if (typeof window !== "undefined" && typeof window.EventTarget !== "undefined") {
    Object.setPrototypeOf(EventTarget.prototype, window.EventTarget.prototype);
}

exports.defineEventAttribute = defineEventAttribute;
exports.EventTarget = EventTarget;
exports["default"] = EventTarget;

module.exports = EventTarget
module.exports.EventTarget = module.exports["default"] = EventTarget
module.exports.defineEventAttribute = defineEventAttribute
//# sourceMappingURL=event-target-shim.js.map


/***/ }),

/***/ "./node_modules/huge-uploader/src/index.js":
/*!*************************************************!*\
  !*** ./node_modules/huge-uploader/src/index.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var event_target_shim__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! event-target-shim */ "./node_modules/huge-uploader/node_modules/event-target-shim/dist/event-target-shim.js");
/* harmony import */ var event_target_shim__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(event_target_shim__WEBPACK_IMPORTED_MODULE_0__);


class HugeUploader {
    constructor(params) {
        this.endpoint = params.endpoint;
        this.file = params.file;
        this.headers = params.headers || {};
        this.postParams = params.postParams;
        this.chunkSize = params.chunkSize || 10;
        this.retries = params.retries || 5;
        this.delayBeforeRetry = params.delayBeforeRetry || 5;

        this.start = 0;
        this.chunk = null;
        this.chunkCount = 0;
        this.totalChunks = Math.ceil(this.file.size / (this.chunkSize * 1000 * 1000));
        this.retriesCount = 0;
        this.offline = false;
        this.paused = false;

        this.headers['uploader-file-id'] = this._uniqid(this.file);
        this.headers['uploader-chunks-total'] = this.totalChunks;

        this._reader = new FileReader();
        this._eventTarget = new event_target_shim__WEBPACK_IMPORTED_MODULE_0__.EventTarget();

        this._validateParams();
        this._sendChunks();

        // restart sync when back online
        // trigger events when offline/back online
        window.addEventListener('online', () => {
            if (!this.offline) return;

            this.offline = false;
            this._eventTarget.dispatchEvent(new Event('online'));
            this._sendChunks();
        });

        window.addEventListener('offline', () => {
            this.offline = true;
            this._eventTarget.dispatchEvent(new Event('offline'));
        });
    }

    /**
     * Subscribe to an event
     */
    on(eType, fn) {
        this._eventTarget.addEventListener(eType, fn);
    }

    /**
     * Validate params and throw error if not of the right type
     */
    _validateParams() {
        if (!this.endpoint || !this.endpoint.length) throw new TypeError('endpoint must be defined');
        if (this.file instanceof File === false) throw new TypeError('file must be a File object');
        if (this.headers && typeof this.headers !== 'object') throw new TypeError('headers must be null or an object');
        if (this.postParams && typeof this.postParams !== 'object') throw new TypeError('postParams must be null or an object');
        if (this.chunkSize && (typeof this.chunkSize !== 'number' || this.chunkSize === 0)) throw new TypeError('chunkSize must be a positive number');
        if (this.retries && (typeof this.retries !== 'number' || this.retries === 0)) throw new TypeError('retries must be a positive number');
        if (this.delayBeforeRetry && (typeof this.delayBeforeRetry !== 'number')) throw new TypeError('delayBeforeRetry must be a positive number');
    }

    /**
     * Generate uniqid based on file size, date & pseudo random number generation
     */
    _uniqid() {
        return Math.floor(Math.random() * 100000000) + Date.now() + this.file.size;
    }

    /**
     * Get portion of the file of x bytes corresponding to chunkSize
     */
    _getChunk() {
        return new Promise((resolve) => {
            const length = this.totalChunks === 1 ? this.file.size : this.chunkSize * 1000 * 1000;
            const start = length * this.chunkCount;

            this._reader.onload = () => {
                this.chunk = new Blob([this._reader.result], { type: 'application/octet-stream' });
                resolve();
            };

            this._reader.readAsArrayBuffer(this.file.slice(start, start + length));
        });
    }

    /**
     * Send chunk of the file with appropriate headers and add post parameters if it's last chunk
     */
    _sendChunk() {
        const form = new FormData();

        // send post fields on last request
        if (this.chunkCount + 1 === this.totalChunks && this.postParams) Object.keys(this.postParams).forEach(key => form.append(key, this.postParams[key]));

        form.append('file', this.chunk);
        this.headers['uploader-chunk-number'] = this.chunkCount;

        return fetch(this.endpoint, { method: 'POST', headers: this.headers, body: form });
    }

    /**
     * Called on net failure. If retry counter !== 0, retry after delayBeforeRetry
     */
    _manageRetries() {
        if (this.retriesCount++ < this.retries) {
            setTimeout(() => this._sendChunks(), this.delayBeforeRetry * 1000);
            this._eventTarget.dispatchEvent(new CustomEvent('fileRetry', { detail: { message: `An error occured uploading chunk ${this.chunkCount}. ${this.retries - this.retriesCount} retries left`, chunk: this.chunkCount, retriesLeft: this.retries - this.retriesCount } }));
            return;
        }

        this._eventTarget.dispatchEvent(new CustomEvent('error', { detail: `An error occured uploading chunk ${this.chunkCount}. No more retries, stopping upload` }));
    }

    /**
     * Manage the whole upload by calling getChunk & sendChunk
     * handle errors & retries and dispatch events
     */
    _sendChunks() {
        if (this.paused || this.offline) return;

        this._getChunk()
        .then(() => this._sendChunk())
        .then((res) => {
            if (res.status === 200 || res.status === 201 || res.status === 204) {
                if (++this.chunkCount < this.totalChunks) this._sendChunks();
                else this._eventTarget.dispatchEvent(new Event('finish'));

                const percentProgress = Math.round((100 / this.totalChunks) * this.chunkCount);
                this._eventTarget.dispatchEvent(new CustomEvent('progress', { detail: percentProgress }));
            }

            // errors that might be temporary, wait a bit then retry
            else if ([408, 502, 503, 504].includes(res.status)) {
                if (this.paused || this.offline) return;
                this._manageRetries();
            }

            else {
                if (this.paused || this.offline) return;
                this._eventTarget.dispatchEvent(new CustomEvent('error', { detail: `Server responded with ${res.status}. Stopping upload` }));
            }
        })
        .catch((err) => {
            if (this.paused || this.offline) return;

            // this type of error can happen after network disconnection on CORS setup
            this._manageRetries();
        });
    }

    togglePause() {
        this.paused = !this.paused;

        if (!this.paused) this._sendChunks();
    }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HugeUploader);


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**************************************************************!*\
  !*** ./include/Module/FileManager/Assets/src/FileManager.ts ***!
  \**************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var huge_uploader__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! huge-uploader */ "./node_modules/huge-uploader/src/index.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }
function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

var __ = window.__;
var FileManager = __.FileManager;
FileManager.Modal = "CreateProperty";
FileManager.AssetView = "CreateProperty";
FileManager.on("set:Modal", function (value) {
  throw new Error("Error: Can't change FileManager object");
});
FileManager.on("set:endpoints", function (value) {
  throw new Error("Error: Can't change FileManager object");
});
FileManager.config = {
  enable_select_folder: true
};
FileManager.filter = {
  extensions: null
};
FileManager.uploadInfo = function (id) {
  return new Promise(function (resolve, reject) {
    __.http.post(FileManager.endpoints.uploadInfo, {
      id: id
    }).then(function (res) {
      resolve(res.data);
    })["catch"](reject);
  });
};
// upload handler
FileManager.uploadHandler = function (files, appendTo) {
  var _FileManager$upload_f;
  FileManager.fire("upload", files);
  (_FileManager$upload_f = FileManager.upload_files).push.apply(_FileManager$upload_f, _toConsumableArray(files));
  window.onbeforeunload = function (e) {
    return "Upload is in progress. Are you sure you want to leave?";
  };
  var _loop = function _loop(i) {
      var file = files[i];
      var component = $("<div class='card rounded-3 shadow-sm border-0'>").append($("<small class=\"text-muted p-1 pb-0 d-inline-block file-name text-break\" >".concat(files[i].name, "</small>")).append($("<span class=\"text-muted p-1 d-inline-block fw-semibold file-size\" >".concat((file.size / 1024 / 1024).toFixed(2), " MB</span>")))).append(file.folder ? "<small class='d-block text-muted p-1 pt-0 mb-1' style=\"font-size: 0.7em\">folder: ".concat(file.folder || "/", "</small>") : "").append($("<div class=\"d-flex align-items-end\">").append($("<div class=\"progress rounded-0 flex-grow-1\" role=\"progressbar\" aria-label=\"Example with label\" aria-valuenow=\"25\" aria-valuemin=\"0\" aria-valuemax=\"100\">").css("height", "19px").append("<div class=\"progress-bar rounded-0\" style=\"width: 5%\">5%</div>"), $("<button type=\"button\" class=\"btn btn-sm btn-warning py-0 px-1 rounded-0 ms-1\">Pause</button>")));
      appendTo.prepend(component);
      var uploadURL = FileManager.endpoints.upload;
      if (!uploadURL) {
        __.toast("Error: Please set FileManager.endpoints.upload", 5, 'text-danger');
        return {
          v: void 0
        };
      }
      var uploader = new huge_uploader__WEBPACK_IMPORTED_MODULE_0__["default"]({
        endpoint: uploadURL,
        file: files[i],
        headers: {
          "uploader-file-name": files[i].name,
          "uploader-file-folder": file.folder,
          "uploader-file-size": files[i].size
        },
        chunkSize: 2
      });
      var $btn = component.find(".btn");
      var $progressBar = component.find(".progress-bar");
      function pauseUpload() {
        uploader.togglePause();
        if (uploader.paused) {
          $btn.text("Resume").removeClass("btn-warning btn-success").addClass("btn-success");
          $progressBar.removeClass("bg-danger bg-success progress-bar-striped progress-bar-animated").addClass("bg-warning");
        } else {
          $btn.text("Pause").removeClass("btn-success btn-danger").addClass("btn-warning");
          $progressBar.removeClass("bg-danger bg-success bg-warning").addClass("bg-primary progress-bar-striped progress-bar-animated");
        }
        $btn.off("click", pauseUpload).off("click", tryAgain).on("click", pauseUpload);
      }
      function tryAgain() {
        if (uploader.paused) {
          pauseUpload();
        } else {
          pauseUpload();
          pauseUpload();
        }
      }
      component.find(".btn").on("click", pauseUpload);

      // subscribe to events
      uploader.on('error', function (err) {
        $progressBar.css("width", "100%").text(err.detail).addClass("bg-danger").removeClass("bg-primary progress-bar-striped progress-bar-animated");
        component.find(".file-name").removeClass("text-muted").addClass("text-danger");
        component.find(".btn").off("click", pauseUpload).text("Try Again").removeClass("btn-warning btn-success").addClass("btn-danger").on("click", tryAgain);
        __.toast(err.detail || file.name + " upload failed", 5, 'text-danger');
      });
      uploader.on('progress', function (progress) {
        $progressBar.removeClass("bg-danger bg-success bg-warning").addClass("bg-primary progress-bar-striped progress-bar-animated").css("width", progress.detail + "%").text(progress.detail + "%");
        component.find(".file-name").removeClass("text-danger text-warning").addClass("text-muted");
        if (uploader.paused) {
          $btn.text("Resume").removeClass("btn-warning btn-success").addClass("btn-success");
          $progressBar.removeClass("bg-danger bg-success progress-bar-striped progress-bar-animated").addClass("bg-warning");
        }
        if (progress.detail == 100) {
          $btn.off("click", pauseUpload).off("click", tryAgain).html("<span class=\"spinner-border spinner-border-sm\" aria-hidden=\"true\"></span> Loading...").removeClass("btn-warning btn-success").addClass("btn-primary");
          $progressBar.html("Processing...").removeClass("bg-danger bg-success bg-warning progress-bar-striped progress-bar-animated").addClass("bg-primary");
        }
      });
      uploader.on('finish', function (body) {
        FileManager.uploadInfo(uploader.headers['uploader-file-id']).then(function (info) {
          FileManager.fire("upload:finish", info);
        })["catch"](function (err) {
          __.toast(err.message || "Something went wrong", 5, 'text-danger');
        });
        setTimeout(function () {
          $progressBar.css("width", "100%").text("100%").addClass("bg-success").removeClass("bg-primary bg-danger bg-warning progress-bar-striped progress-bar-animated");
          component.find(".file-name").removeClass("text-muted").addClass("text-success");
          $btn.off("click", pauseUpload).off("click", tryAgain).text("Remove").removeClass("btn-warning btn-danger btn-success").addClass("btn-primary").on("click", function () {
            component.remove();
            var index = FileManager.upload_files.indexOf(files[i]);
            if (index > -1) {
              FileManager.upload_files.splice(index, 1);
            }
          });
          __.toast(file.name + " uploaded successfully!", 5, 'text-success');
        }, 400);
        setTimeout(function () {
          component.fadeOut({
            duration: 3000,
            complete: function complete() {
              component.remove();
              var index = FileManager.upload_files.indexOf(files[i]);
              if (index > -1) {
                FileManager.upload_files.splice(index, 1);
              }
            }
          });
        }, 60000);
      });
      uploader.on('offline', function () {
        __.toast("Network offline", 5, 'text-danger');
      });
    },
    _ret;
  for (var i = 0; i < files.length; i++) {
    _ret = _loop(i);
    if (_ret) return _ret.v;
  }
};
FileManager.on("upload:finish", function (files) {
  if (files.length > 0) {
    $("#floating_button").find(".badge").text(files.length);
  } else {
    window.onbeforeunload = null;
    $("#floating_button").fadeOut({
      duration: 1000,
      complete: function complete() {
        return $("#floating_button").remove();
      }
    });
  }
});
FileManager.upload_files = [];

// show upload manager
FileManager.upload = function () {
  var files = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  var folder = null;
  if (FileManager.selectedFolder) {
    folder = FileManager.selectedFolder.path || null;
  }
  if (!folder && FileManager.config.enable_select_folder) {
    folder = "/";
  }
  var $content = $("<div>").css({
    display: "flex",
    flexWrap: "wrap",
    flexDirection: "column",
    gap: "1rem"
  }).append($("<div id=\"upload\">").css({
    position: 'relative',
    flex: 1,
    minHeight: "250px",
    minWidth: "250px",
    borderRadius: "5px",
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'center',
    alignItems: 'center',
    padding: "10px",
    background: "#0389fff0",
    color: "#fff",
    opacity: 0.5
  }).append($("<div class=\"w-100\">").css({
    position: "absolute",
    top: "10px",
    left: "10px"
  }).append(folder ? $("<h5 id=\"upload-path\">").text("upload to: " + folder) : ""), $("<div class='text-center'>").on("click", function () {
    $("<input type=\"file\" multiple>").on('input', function (e) {
      return FileManager.upload(e.target.files, $("#upload-content"));
    }).trigger("click");
  }).on("drop", function (e) {
    e.preventDefault();
    console.log(e);
  }).css({
    margin: "auto 0px",
    width: "100%",
    minHeight: "250px",
    display: "flex",
    justifyContent: "center",
    alignContent: "center",
    alignItems: "center",
    flexDirection: "column"
  }).append($("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"35\" height=\"35\" fill=\"currentColor\" class=\"bi bi-upload\" viewBox=\"0 0 16 16\">\n                            <path d=\"M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5\"/>\n                            <path d=\"M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z\"/>\n                        </svg>"), $("<h5 class=\"text-center mt-3\">").text("Click or Drop Files Here")))).append($("<div class=\"d-flex flex-column gap-1\" id=\"upload-content\">"));
  var modal = $("#FileManagerUploadModal").length ? __.modal.from($("#FileManagerUploadModal")) : __.modal.create("Upload Files", $content);
  modal.el.prop("id", "FileManagerUploadModal");
  modal.find(".modal-footer").remove();
  modal.show();
  if (files.length > 0) {
    for (var i = 0; i < files.length; i++) {
      files[i].folder = folder;
    }
    FileManager.uploadHandler(files, $(modal.el).find("#upload-content"));
  }
  function modalHideHandler() {
    $("#floating_button").remove();
    if (FileManager.upload_files.length > 0) {
      var floating_button = $("<button id=\"floating_button\">").append("<i class=\"fa-solid fa-cloud-arrow-up fa-xl\"></i>").append("<span class=\"position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger\">".concat(FileManager.upload_files.length, "</span>")).on("click", function () {
        modal.show();
        floating_button.remove();
      }).css({
        position: "fixed",
        bottom: "20px",
        right: "20px",
        background: "#2685ff",
        color: "#fff",
        border: "none",
        cursor: "pointer",
        width: "30px",
        height: "30px",
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        borderRadius: "2rem",
        padding: "1.5rem",
        boxShadow: "0 0 10px 0 rgba(0, 0, 0, 0.2)",
        zIndex: 9999,
        transition: "1s",
        opacity: 0,
        transform: "scale(5)"
      });
      $(document.body).append(floating_button);
      setTimeout(function () {
        floating_button.css({
          transform: "scale(1)",
          opacity: 1
        });
      }, 100);
    }
  }
  modal.off("modal:hide", modalHideHandler);
  modal.on("modal:hide", modalHideHandler);
};
FileManager["delete"] = function (asset) {
  __.dialog.danger("Are you sure?", "<p>Are you sure you want to delete <span class=\"text-danger\">".concat(asset.path, "</span>?</p>")).then(function () {
    var deleteURL = FileManager.endpoints["delete"];
    if (!deleteURL) {
      __.toast("Error: Please set FileManager.endpoints.delete", 5, 'text-danger');
    }
    __.http.post(deleteURL, {
      path: asset.path
    }).then(function (res) {
      __.toast("File deleted successfully", 5, 'text-success');
      FileManager.fire("delete", asset);
      FileManager.fire("delete:".concat(asset.path), asset);
    })["catch"](function (err) {
      __.toast(err.message, 5, 'text-danger');
    });
  });
};
FileManager.rename = function (asset) {
  var content = $("<div class=\"form-group mb-3\">").append($("<label>File Name</label>"), $("<input type=\"text\" class=\"form-control\" value=\"".concat(asset.name, "\" />")));
  setTimeout(function () {
    content.find("input").focus();
  }, 400);
  __.dialog.confirm("Rename " + asset.name, content).then(function () {
    var name = content.find("input").val();
    if (name && name.length > 0) {
      var renameURL = FileManager.endpoints.rename;
      if (!renameURL) {
        __.toast("Error: Please set FileManager.endpoints.rename", 5, 'text-danger');
      }
      __.http.post(renameURL, {
        path: asset.path,
        name: name
      }).then(function (res) {
        __.toast("File renamed successfully", 5, 'text-success');
        FileManager.fire("rename", asset, name);
        FileManager.fire("rename:".concat(asset.path), asset, name);
      })["catch"](function (err) {
        __.toast(err.message, 5, 'text-danger');
      });
    } else {
      __.toast("File name cannot be empty", 5, "text-danger");
    }
  });
};
FileManager.newFolder = function (asset) {
  var content = $("<div class=\"form-group mb-3\">").append($("<label>Folder Name</label>"), $("<input type=\"text\" class=\"form-control\" />"));
  setTimeout(function () {
    content.find("input").focus();
  }, 400);
  __.dialog.confirm("Create new folder", content).then(function () {
    var name = content.find("input").val();
    if (name && name.length > 0) {
      var newFolderURL = FileManager.endpoints.newFolder;
      if (!newFolderURL) {
        __.toast("Error: Please set FileManager.endpoints.newFolder", 5, 'text-danger');
      }
      __.http.post(newFolderURL, {
        path: asset.path,
        name: name
      }).then(function (res) {
        __.toast("Folder created successfully", 5, 'text-success');
        FileManager.fire("newFolder", asset, name);
        FileManager.fire("newFolder:".concat(asset.path), asset, name);
      })["catch"](function (err) {
        __.toast(err.message, 5, 'text-danger');
      });
    } else {
      __.toast("Folder name cannot be empty", 5, "text-danger");
    }
  });
};
FileManager.assets = {};
function storeAsset(assets) {
  var _iterator = _createForOfIteratorHelper(assets),
    _step;
  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var asset = _step.value;
      var keyPath = (asset.path || "").split("/").filter(function (e) {
        return e !== "";
      });
      if (keyPath.length > 1) {
        var currentAssets = FileManager.assets;
        for (var i = 0; i < keyPath.length; i++) {
          var _key = keyPath[i];
          if (!currentAssets[_key]) {
            currentAssets[_key] = {
              path: keyPath.slice(0, i + 1).join("/"),
              children: {}
            };
          }
          if (i === keyPath.length - 1) {
            // This is the last key in the path, store additional data if needed
            currentAssets[_key] = _objectSpread(_objectSpread({}, {
              children: {}
            }), asset); // Assuming asset contains additional data
          } else {
            // Update the current assets to point to the children for the next iteration
            currentAssets = currentAssets[_key].children;
          }
        }
      }
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
}
function getAsset(path) {
  var result = null;
  var currentAssets = FileManager.assets;
  var keyPath = (path || "").split("/").filter(function (e) {
    return e !== "";
  });
  for (var i = 0; i < keyPath.length; i++) {
    var _key2 = keyPath[i];
    if (currentAssets[_key2]) {
      if (i === keyPath.length - 1) {
        result = currentAssets[_key2];
      } else {
        currentAssets = currentAssets[_key2].children;
      }
    } else {
      break;
    }
  }
  return result;
}
function appendAsset(path, asset) {
  var currentAssets = FileManager.assets;
  var parent = currentAssets;
  var keyPath = (path || "").replace(/^\//, "").split("/").filter(function (e) {
    return e !== "";
  });
  for (var i = 0; i < keyPath.length; i++) {
    var _key3 = keyPath[i];
    if (i === keyPath.length - 1) {
      if (currentAssets[_key3]) {
        var children = currentAssets[_key3].children || {};
        children[keyPath[i]] = asset;
        currentAssets[_key3].children = children;
      } else {
        currentAssets[_key3] = _objectSpread(_objectSpread({}, {
          children: {}
        }), asset);
      }
      parent.children_count += 1;
      // This is the last key in the path, store additional data if needed
    } else {
      if (!currentAssets[_key3]) {
        currentAssets[_key3] = {
          name: _key3,
          path: keyPath.slice(0, i + 1).join("/"),
          type: "folder",
          children: {},
          children_count: 0,
          time: Date.now()
        };
        parent.invalid = true;
      }
      parent = currentAssets[_key3];
      // Update the current assets to point to the children for the next iteration
      currentAssets = currentAssets[_key3].children;
    }
  }
  return true;
}
function replaceAsset(old_asset, new_asset) {
  var status = false;
  var currentAssets = FileManager.assets;
  var keyPath = (old_asset.path || "").replace(/^\//, "").split("/").filter(function (e) {
    return e !== "";
  });
  for (var i = 0; i < keyPath.length; i++) {
    var _key4 = keyPath[i];
    if (currentAssets[_key4]) {
      if (i === keyPath.length - 1) {
        var children = currentAssets[_key4].children || {};
        delete currentAssets[_key4];
        currentAssets[new_asset.name] = _objectSpread(_objectSpread({}, {
          children: children
        }), new_asset);
        status = true;
        break;
      } else {
        currentAssets = currentAssets[_key4].children;
      }
    } else {
      break;
    }
  }
  return status;
}
function deleteAsset(asset) {
  var status = false;
  var currentAssets = FileManager.assets;
  var parent = currentAssets;
  var keyPath = (asset.path || "").replace(/^\//, "").split("/").filter(function (e) {
    return e !== "";
  });
  for (var i = 0; i < keyPath.length; i++) {
    var _key5 = keyPath[i];
    if (currentAssets[_key5]) {
      if (i === keyPath.length - 1) {
        delete currentAssets[_key5];
        if (parent && parent.children_count) {
          parent.children_count -= 1;
        }
        status = true;
        break;
      } else {
        parent = currentAssets[_key5];
        currentAssets = currentAssets[_key5].children;
      }
    } else {
      break;
    }
  }
  return status;
}
FileManager.selectedFolder = null;
FileManager.on("property:Modal", function (e, Modal) {
  var icons = {
    "image": "<i class=\"fa-solid fa-image fa-xl\"></i>",
    "video": "<i class=\"fa-solid fa-video fa-xl\"></i>",
    "audio": "<i class=\"fa-solid fa-music fa-xl\"></i>",
    "document": "<i class=\"fa-solid fa-file fa-xl\"></i>",
    "other": "<i class=\"fa-solid fa-file fa-xl\"></i>",
    "folder": "<i class=\"fa-solid fa-folder fa-xl\"></i>",
    "file": "<i class=\"fa-solid fa-file fa-xl\"></i>"
  };
  function truncateStringFromStart(str, maxLength) {
    if (str.length > maxLength) {
      return "..." + str.substring(str.length - maxLength + 3);
    } else {
      return str;
    }
  }
  function clickOutsideHandler(e) {
    if (!FileManager.config.enable_select_folder) return false;
    if ($(e.target).closest('#assets-content').length > 0 || $(e.target).closest('.file-asset').length > 0 || $(e.target).closest('#upload').length > 0 || $(e.target).closest('#FileManagerUploadModal').length > 0) {
      return false; // if you want to ignore the click completely
      // return; // else
    }
    FileManager.selectedFolder = "/";
    $("#FileManagerModal").find(".file-asset").css({
      backgroundColor: "transparent"
    });
    $("#FileManagerModal").find("#upload-path").text("upload to: /");
  }
  function selectedHandler(e, asset) {
    $("#FileManagerModal").find(".file-asset").css({
      backgroundColor: "transparent"
    });
    $("#FileManagerModal").find("#" + assetId(asset)).css({
      backgroundColor: "rgba(145, 209, 255, 0.1)"
    });
    $("#FileManagerModal").find("#upload-path").text("upload to: ".concat(asset.path.replace(/^[/]content/, '')));
    $("#FileManagerModal").off("click", clickOutsideHandler).on("click", clickOutsideHandler);
  }
  function assetId(asset) {
    return ("asset-" + asset.path.replace(/[^a-zA-Z0-9]/g, "-")).replace(/-+/g, "-");
  }
  function uploadFinishHandler(e, fileInfo) {
    var path = fileInfo.file_folder;
    var name = fileInfo.file_name;
    var type = fileInfo.file_type;
    if (appendAsset(path.replace(/\/$/, "") + "/" + name, {
      name: name,
      path: path.replace(/\/$/, "") + "/" + name,
      type: type,
      children_count: 0,
      time: new Date().getTime()
    })) {
      if (path.replace(/^[/]content\//, '') == "") {
        Modal.renderAsset($("#FileManagerModal").find("#assets-content"), "/");
      } else {
        var _path = path;
        var collapse = $("#collapse-" + ("asset-" + _path.replace(/[^a-zA-Z0-9]/g, "-")).replace(/-+/g, "-"));
        if (collapse.length) {
          Modal.renderAsset(collapse, path);
        } else {
          while (collapse.length == 0) {
            _path = _path.replace(/\/[^\/]+$/, '');
            collapse = $("#collapse-" + ("asset-" + _path.replace(/[^a-zA-Z0-9]/g, "-")).replace(/-+/g, "-"));
          }
          Modal.renderAsset(collapse, _path);
        }
      }
    }
  }
  function showContextMenu(e, asset) {
    function adjustContentPosition(content) {
      var rect = content.getBoundingClientRect();
      var isInViewport = rect.top >= 0 && rect.left >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && rect.right <= (window.innerWidth || document.documentElement.clientWidth);
      if (!isInViewport) {
        var newX = rect.left < 0 ? 0 : rect.right > window.innerWidth ? window.innerWidth - rect.width : rect.left;
        var newY = rect.top < 0 ? 0 : rect.bottom > window.innerHeight ? window.innerHeight - rect.height : rect.top;
        content.style.left = newX + "px";
        content.style.top = newY + "px";
      }
    }
    function clickOutsideHandler(e) {
      if (!$(e.target).closest('#context-menu').length) {
        $("#context-menu").remove();
        $("#".concat(assetId(asset))).removeClass("bg-primary bg-opacity-10");
        $(document).off("click", clickOutsideHandler);
      }
    }
    $(".file-asset").removeClass("bg-primary bg-opacity-10");
    $("#".concat(assetId(asset))).addClass("bg-primary bg-opacity-10");
    $("#context-menu").remove();
    var content = $("<ul class=\"list-group list-group-flush\" id=\"context-menu\">").append($("<li class=\"list-group-item fw-semibold px-3 py-2\">".concat(icons[asset.type], " /").concat(truncateStringFromStart(asset.path.replace(/^[/]content\//, ""), 22).replace(/^\//, ""), "</li>")), asset.type !== "folder" ? $("<li class=\"list-group-item \"><i class=\"fa-regular fa-folder-open\"></i> Open</li>").on("mouseenter", function () {
      $(this).addClass("bg-primary text-white");
    }).on("mouseleave", function () {
      $(this).removeClass("bg-primary text-white");
    }).on("click", function () {
      $("#context-menu").remove();
      FileManager.AssetView.show(asset);
    }) : "", asset.path != "/" ? $("<li class=\"list-group-item\"><i class=\"fa-solid fa-pen-to-square\"></i> Rename</li>").on("mouseenter", function () {
      $(this).addClass("bg-primary text-white");
    }).on("mouseleave", function () {
      $(this).removeClass("bg-primary text-white");
    }).on("click", function () {
      $("#context-menu").remove();
      FileManager.rename(asset);
    }) : "", asset.type == "folder" ? $("<li class=\"list-group-item\"><i class=\"fa-solid fa-folder-plus\"></i> Folder</li>").on("mouseenter", function () {
      $(this).addClass("bg-primary text-white");
    }).on("mouseleave", function () {
      $(this).removeClass("bg-primary text-white");
    }).on("click", function () {
      $("#context-menu").remove();
      FileManager.newFolder(asset);
    }) : "", asset.type !== "folder" ? $("<li class=\"list-group-item\"><i class=\"fa-solid fa-download\"></i> Download</li>").on("mouseenter", function () {
      $(this).addClass("bg-primary text-white");
    }).on("mouseleave", function () {
      $(this).removeClass("bg-primary text-white");
    }).on("click", function () {
      $("#context-menu").remove();
      var a = $('<a>').prop('download', asset.name).prop('target', '_blank').attr('href', window.location.origin + asset.path);
      a[0].click();
    }) : "", asset.path != "/" ? $("<li class=\"list-group-item\"><i class=\"fa-solid fa-trash\"></i> Delete</li>").on("mouseenter", function () {
      $(this).addClass("bg-danger text-white");
    }).on("mouseleave", function () {
      $(this).removeClass("bg-danger text-white");
    }).on("click", function () {
      $("#context-menu").remove();
      FileManager["delete"](asset);
    }) : "");
    content.css({
      position: 'absolute',
      zIndex: 600,
      left: e.pageX - 200,
      top: e.pageY,
      boxShadow: '0 0 10px 0 rgba(0, 0, 0, 0.1)',
      cursor: 'pointer',
      width: "100%",
      maxWidth: "200px"
    }).appendTo("body");
    adjustContentPosition(content[0]);
    $(document).on("click", clickOutsideHandler);
  }
  function renameHandler(e, asset, name) {
    var paths = asset.path.split("/");
    paths[paths.length - 1] = name;
    if (replaceAsset(asset, {
      name: name,
      path: paths.join("/"),
      type: asset.type,
      children_count: asset.children_count,
      time: asset.time
    })) {
      Modal.renderAsset($("#FileManagerModal").find("#assets-content"), "/");
    }
  }
  function deleteHandler(e, asset) {
    if (deleteAsset(asset)) {
      $("#".concat(assetId(asset))).remove();
    }
  }
  function newFolderHandler(e, asset, name) {
    var newAsset = {
      name: name,
      path: asset.path.replace(/\/$/, "") + "/" + name + "/",
      type: "folder",
      children_count: 0,
      time: Date.now()
    };
    if (appendAsset(asset.path, newAsset)) {
      if (asset.path.replace(/^[/]content\//, '') == "") {
        Modal.renderAsset($("#FileManagerModal").find("#assets-content"), "/");
      } else {
        var id = "#collapse-" + assetId(asset);
        var collapse = $("#FileManagerModal").find(id);
        if (collapse.length) {
          Modal.renderAsset(collapse, asset.path);
        }
      }
    }
  }
  Modal.show = function () {
    var $content = $("<div>").css({
      display: "flex",
      flexWrap: "wrap",
      alignItems: "start",
      gap: "10px",
      minHeight: "400px"
    }).append($("<div id=\"upload\">").css({
      position: 'relative',
      flex: "1 1 300px",
      minHeight: "200px",
      width: "100%",
      border: "1px solid #eee",
      borderRadius: "5px",
      display: 'flex',
      flexDirection: 'column',
      justifyContent: 'center',
      alignItems: 'center',
      padding: "10px",
      cursor: "pointer"
    }).append($("<div class=\"w-100\">").append(FileManager.config.enable_select_folder ? $("<h5 id=\"upload-path\">upload to: /</h5>") : $("<h5 id=\"upload-path\"></h5>")), $("<div class='text-center'>").on("click", function () {
      var input = $("<input type=\"file\" multiple>").on('input', function (e) {
        if (!e.target || !e.target.files) return;
        FileManager.upload(e.target.files);
      });
      if (FileManager.filter.extensions) {
        var extensions = [];
        Object.values(FileManager.filter.extensions).forEach(function (ext) {
          if (typeof ext === 'string' || ext instanceof String) {
            if (ext.startsWith(".")) {
              ext.replace(/^./, "");
            }
            extensions.push(ext);
          }
        });
        input.attr("accept", extensions.join(", "));
      }
      input.trigger("click");
    }).on("drop", function (e) {
      e.preventDefault();
      console.log(e);
    }).css({
      margin: "auto 0px",
      width: "100%",
      display: "flex",
      justifyContent: "center",
      alignContent: "center",
      alignItems: "center",
      flexDirection: "column"
    }).append($("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"35\" height=\"35\" fill=\"currentColor\" class=\"bi bi-upload\" viewBox=\"0 0 16 16\">\n                                    <path d=\"M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5\"/>\n                                    <path d=\"M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z\"/>\n                                </svg>"), $("<h5 class=\"text-center mt-3\">").text("Click or Drop Files Here")))).append($("<div class=\"list-group list-group-flush\" id=\"assets-content\">").css({
      maxHeight: "65vh",
      overflow: "auto",
      flex: "1 1 300px",
      minHeight: "50vh"
    }).on('contextmenu', function (e) {
      e.preventDefault();
      showContextMenu(e, {
        type: "folder",
        path: "/",
        name: "root",
        time: new Date().getTime()
      });
    }));
    var modal = $("#FileManagerModal").length ? __.modal.from($("#FileManagerModal")) : __.modal.create("FileManager", $content);
    modal.el.prop("id", "FileManagerModal");
    modal.el.find(".modal-dialog").css({
      maxWidth: "1024px"
    });
    modal.el.find(".modal-body").css({
      maxWidth: "1024px"
    });
    modal.el.find(".modal-footer").remove();
    FileManager.on("asset:select", function (e) {
      setTimeout(function () {
        $(document).find("#FileManagerModal").remove();
      }, 100);
    });
    modal.show();
    Modal.renderAsset($(modal.el).find("#assets-content"), "/");
    FileManager.off("upload:finish", uploadFinishHandler);
    FileManager.on("upload:finish", uploadFinishHandler);
  };
  Modal.renderAsset = function (container) {
    var folder = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "/";
    container.empty().append("<div class=\"text-center p-5\"><i class=\"fa-solid fa-spinner fa-spin\"></i></div>");
    Modal.fetchAssets(folder).then(function (assets) {
      container.empty().append(assets.map(function (asset) {
        return Modal.createAssetDom(asset);
      }));
      container.parent(".list-group-item").find("small.children-count").first().text(assets.length);
      if (assets.length == 0) {
        container.html("<div class=\"text-center p-4\">No assets found</div>");
      }
    })["catch"](function (err) {
      __.toast(err.message || 'Unknown Error', 5, 'text-danger');
      console.error(err);
    });
    FileManager.off("selectFolder", selectedHandler);
    FileManager.on("selectFolder", selectedHandler);
  };
  Modal.fetchAssets = function () {
    var folder = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "/";
    var fetchURL = FileManager.endpoints.fetch;
    return new Promise(function (resolve, reject) {
      var assets = getAsset(folder == "/" ? "/content" : folder);
      if (assets && assets.children && Object.keys(assets.children).length > 0 && assets.invalid !== true) {
        var result = Object.keys(assets.children).map(function (key) {
          return assets.children[key];
        });
        resolve(result);
        return;
      }
      if (assets !== null && assets !== void 0 && assets.invalid) {
        delete assets.invalid;
      }
      if (!fetchURL) {
        reject("No fetchURL set");
        return;
      }
      var formData = new FormData();
      formData.append("folder", folder);
      if (FileManager.filter.extensions) {
        Object.keys(FileManager.filter.extensions).forEach(function (key) {
          formData.append("extensions[".concat(key, "]"), FileManager.filter.extensions[key]);
        });
      }
      __.http.post(fetchURL, formData).then(function (result) {
        var assets = result.data || [];
        storeAsset(assets);
        resolve(assets);
      })["catch"](function (err) {
        reject(err);
      });
    });
  };
  Modal.createAssetDom = function (asset) {
    function assetClickHandler() {
      if (asset.type == "folder") {
        if (FileManager.config.enable_select_folder) {
          FileManager.selectedFolder = asset;
          FileManager.fire("selectFolder", asset);
        }
        var collapse = $(this).parent(".list-group-item").find("#collapse-".concat(assetId(asset)));
        collapse.toggleClass("show");
        if (collapse.hasClass("show")) {
          $(this).find(".fa-angle-down").removeClass("fa-angle-down").addClass("fa-angle-up");
          Modal.renderAsset($(this).parent(".list-group-item").find(".collapse"), asset.path);
        } else {
          $(this).find(".fa-angle-up").removeClass("fa-angle-up").addClass("fa-angle-down");
        }
      } else {
        FileManager.AssetView.show(asset);
      }
    }
    FileManager.off("delete:".concat(asset.path), deleteHandler);
    FileManager.on("delete:".concat(asset.path), deleteHandler);
    FileManager.off("rename:".concat(asset.path), renameHandler);
    FileManager.on("rename:".concat(asset.path), renameHandler);
    if (asset.type == "folder") {
      FileManager.off("newFolder:".concat(asset.path), newFolderHandler);
      FileManager.on("newFolder:".concat(asset.path), newFolderHandler);
    }
    return $("<li class=\"list-group-item file-asset".concat(asset.time > Math.floor(Date.now() / 1000) - 10 ? ' bg-primary bg-opacity-10' : '', "\" id=\"").concat(assetId(asset), "\">")).css({
      "cursor": "pointer",
      background: "transparent",
      userSelect: "none",
      wordWrap: "anywhere"
    }).append($("<div class=\"d-flex w-100 justify-content-start align-items-center position-relative\">").append($("<div class=\"asset-icon w-100\" style=\"max-width: 45px;height: 30px; display: flex;justify-content: center;align-items: center; overflow: hidden;\">").append(asset.type == "image" ? $("<img src=\"".concat(asset.path, "\" class=\"img-fluid\" style=\"object-fit: cover;\"></div>")) : icons[asset.type])).append($("<div class=\"ms-2 me-1 asset-name\">").append($("<h5 class=\"mb-0\">".concat(asset.name, "</h5>")).append(asset.type == "folder" ? $("<small class=\"text-muted text-thin ms-1 position-absolute top-0 children-count\">".concat(asset.children_count, "</small>")).css({
      fontSize: "0.65em"
    }) : ""))).append(asset.type == "folder" ? $("<i class=\"fa-solid fa-angle-down ms-auto\"></i>") : "").on("click", assetClickHandler), asset.type == "folder" ? $("<div class=\"collapse py-2 list-group list-group-flush\" id=\"collapse-".concat(assetId(asset), "\"></div>")) : "").on("contextmenu", function (e) {
      e.preventDefault();
      e.stopPropagation();
      showContextMenu(e, asset);
    });
  };
  FileManager.on("newFolder:/", function (e, asset, name) {
    console.log(asset, name);
    var newAsset = {
      name: name,
      path: asset.path.replace(/\/$/, "") + "/" + name + "/",
      type: "folder",
      children_count: 0,
      time: Date.now()
    };
    if (appendAsset("/content", newAsset)) {
      Modal.renderAsset($("#FileManagerModal").find("#assets-content"), "/");
    }
  });
});
FileManager.on("property:AssetView", function (e, AssetView) {
  AssetView.show = function (asset) {
    var callback = {
      "video": function video(modal) {
        if ($(document.head).find("link#video-js").length == 0) $(document.head).append("<link id=\"video-js\" href=\"https://vjs.zencdn.net/8.10.0/video-js.css\" rel=\"stylesheet\">");
        $.when($.getScript("https://vjs.zencdn.net/8.10.0/video.min.js")).done(function () {
          modal.el.find(".asset-view").empty().append($("<video class='video-js position-absolute w-100 h-100 object-fit-contain' controls preload='auto' id='video'></video>").attr("src", asset.path));
          window.videojs("video", {
            controls: true,
            controlBar: {
              pictureInPictureToggle: false
            }
          });
          function onModalHide() {
            try {
              window.videojs("video").dispose();
            } catch (error) {
              // console.log(error);
            }
            modal.off("modal:hide", onModalHide);
          }
          modal.on("modal:hide", onModalHide);
        });
      },
      "audio": function audio(modal) {},
      "image": function image(modal) {
        modal.el.find(".asset-view").empty().append($("<img class='w-100 h-100 object-fit-contain' src='" + asset.path + "' />"));
      },
      "pdf": function pdf(modal) {
        $.when($.getScript("https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js")).done(function () {
          var pdfjsLib = window.pdfjsLib;
          var container = $("<div class=\"page-container\" style=\"max-height: 65vh; overflow: auto;\"></div>").append("<canvas class='d-none'></canvas>");
          modal.el.find(".asset-view").empty().append(container);

          // PDF.js worker location
          var pdfjsWorkerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

          // Initialize PDF.js
          pdfjsLib.GlobalWorkerOptions.workerSrc = pdfjsWorkerSrc;

          // Load PDF file
          var pdfUrl = window.location.origin + asset.path;

          // Asynchronous download PDF
          pdfjsLib.getDocument(pdfUrl).promise.then(function (pdfDoc) {
            var _loop2 = function _loop2() {
              // Create a container for each page
              var pageContainer = document.createElement('div');
              pageContainer.className = 'page-container';
              container.append(pageContainer);

              // Get the page
              pdfDoc.getPage(pageNum).then(function (page) {
                // Create a canvas for the page
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                pageContainer.appendChild(canvas);

                // Set canvas dimensions
                var viewport = page.getViewport({
                  scale: 2
                });
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                $(canvas).css({
                  width: "100%"
                });

                // Render the page onto the canvas
                page.render({
                  canvasContext: ctx,
                  viewport: viewport
                });
              });
            };
            // Iterate over each page of the PDF
            for (var pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
              _loop2();
            }
            $('.loding-container').remove();
          });
        });
      }
    };
    var content = $("<div class=\"ratio ratio-16x9 asset-view\" style=\"max-height: 65vh; height: 100%;overflow: hidden;\"></div><small class=\"text-muted text-semibold text-break\">".concat(asset.path, "</small>"));
    var modal = $("#FileManagerModal_AssetView").length > 0 ? __.modal.from($("#FileManagerModal_AssetView")) : __.modal.create("FileManagerModal_AssetView", content);
    modal.el.prop("id", "FileManagerModal_AssetView");
    modal.el.find(".modal-footer").empty();
    modal.el.find(".modal-title").text(asset.name);
    modal.el.find(".modal-dialog").css({
      maxWidth: "1024px"
    });
    modal.el.find(".modal-body").css({
      maxWidth: "1024px"
    });
    modal.el.find(".asset-view").empty().append($("<div class='w-100 h-100 d-flex justify-content-center align-items-center'>").append($("<i class='fa-solid fa-spinner fa-pulse fa-lg'></i>"), $("<span class='ms-2'>Loading...</span>")));
    modal.show();
    $(modal.el).find(".modal-footer").empty().addClass("p-3").append(FileManager.is_pick ? $("<button type=\"button\" class=\"btn btn-primary ms-2\">Select</button>").on("click", function () {
      FileManager.fire("asset:select", asset);
      modal.el.remove();
    }) : "");
    if (callback[asset.type]) {
      callback[asset.type](modal);
    } else {
      if (/\.pdf$/i.test(asset.name)) {
        callback['pdf'](modal);
        return;
      }
      $(modal.el).find(".asset-view").empty().append("<div class=\"object-container\"><object data=\"".concat(asset.path, "\" type=\"text/html\" width=\"100%\" height=\"100%\"></object></div>"));
    }
  };
});
FileManager.is_pick = false;
FileManager.select = function () {
  FileManager.is_pick = true;
  return new Promise(function (resolve, reject) {
    function pickFileHandler(e, asset) {
      FileManager.is_pick = false;
      resolve(asset);
    }
    FileManager.off("asset:select", pickFileHandler);
    FileManager.on("asset:select", pickFileHandler);
    FileManager.Modal.show();
  });
};
})();

/******/ })()
;