
abstract class UnitModel {
    #element: JQuery<HTMLElement>;

    constructor(element: JQuery) {
        this.#element = element;
    }
    getElement(): JQuery<HTMLElement> {
        return this.#element;
    }
}



class ActionUnit {

    #text: string;
    #class: string = "btn-disabled";
    #callback: Function;

    constructor(text: string, classs: string, callback: Function) {
        this.#text = text;
        this.#class = classs;
        this.#callback = callback;

        Object.defineProperty(this, 'text', {
            get: () => { return this.#text; },
            set: (value) => {
                if (value && typeof value === 'string') {
                    this.#text = value;
                }
            }
        });
        Object.defineProperty(this, 'class', {
            get: () => { return this.#class; },
            set: (value) => {
                if (value && typeof value === 'string') {
                    this.#class = value;
                }
            }
        });
        Object.defineProperty(this, 'callback', {
            get: () => { return this.#callback; },
            set: (value) => {
                if (value && typeof value === 'function') {
                    this.#callback = value;
                }
            }
        })
    }

    render() {
        let button = $(`<button type="button" class="btn ${this.#class ?? 'btn-disabled'}">${this.#text}</button>`)
        button.on('click', () => {
            this.#callback();
        })
        return button;
    }
}




class Action {

    #positive: ActionUnit | null;
    #negative: ActionUnit | null;

    constructor(modal: Modal, { positive, negative }: { positive: ActionUnit, negative: ActionUnit }) {

        this.#positive = positive;
        this.#negative = negative;

        if (positive && !(positive instanceof ActionUnit)) {
            this.#positive = new ActionUnit((positive as any).text, (positive as any).class, (positive as any).callback);
        }


        if (negative && !(negative instanceof ActionUnit)) {
            this.#negative = new ActionUnit((negative as any).text, (negative as any).class, (negative as any).callback);
        }



        Object.defineProperty(this, 'positive', {
            get: () => { return this.#positive; },
            set: (value) => {
                if (typeof value == 'function') {
                    (this.#positive as any).callback = value;
                } else if (value && typeof value == 'object') {
                    if (!this.#positive) this.#positive = new ActionUnit('confirm', 'btn-primary', () => { modal.hide(); });
                    if (value.text && value.class && value.callback) {
                        this.#positive = new ActionUnit((value as any).text, (value as any).class, (value as any).callback);
                    } else {
                        let attr = ['text', 'class', 'callback'];
                        for (let i = 0; i < attr.length; i++) {
                            if (value[attr[i]]) {
                                (this.#positive as any)[attr[i]] = value[attr[i]];
                            }
                        }
                    }
                } else {
                    this.#positive = value;
                }

                modal.render();
            }
        })
        Object.defineProperty(this, 'negative', {
            get: () => { return this.#negative; },
            set: (value) => {

                if (typeof value == 'function') {
                    (this.#negative as any).callback = value;
                } else if (typeof value == 'object') {
                    if (!this.#negative) this.#negative = new ActionUnit('cancel', 'btn-default', () => { modal.hide(); });
                    if (value.text && value.class && value.callback) {
                        this.#negative = new ActionUnit((value as any).text, (value as any).class, (value as any).callback);
                    } else {
                        let attr = ['text', 'class', 'callback'];
                        for (let i = 0; i < attr.length; i++) {
                            if (value[attr[i]]) {
                                (this.#negative as any)[attr[i]] = value[attr[i]];
                            }
                        }
                    }
                } else {
                    this.#negative = value;
                }
                modal.render();
            }
        })
    }


    render(): JQuery[] {
        let _positive = this.#positive?.render();
        let _negative = this.#negative?.render();

        return ([_positive, _negative].filter(x => x) as JQuery[]);
    }
}




class Modal extends UnitModel {

    #title: string | JQuery | HTMLElement = "";
    #content: string | JQuery<HTMLElement> | HTMLElement = "";
    #dismiss: ActionUnit | null;
    #action: Action;
    #clickOut: boolean = true;


    constructor(el: HTMLElement | JQuery) {
        super($(el));

        this.#action = new Action(this, {
            positive: new ActionUnit('confirm', 'btn-primary', () => { this.hide(); }),
            negative: new ActionUnit('cancel', 'btn-secondary', () => { this.hide(); })
        });

        Object.defineProperty(this, 'action', {
            get: () => { return this.#action; }
        })

        Object.defineProperty(this, 'title', {
            get: () => { return this.#title; },
            set: (value) => { this.#title = value; this.render(); }
        });
        Object.defineProperty(this, 'content', {
            get: () => { return this.#content; },
            set: (value) => { this.#content = value; this.render(); }
        });
        Object.defineProperty(this, 'dismiss', {
            get: () => {

                if (!this.#dismiss) return null;

                let dismiss = {}
                Object.defineProperty(dismiss, 'class', {
                    get: () => { return (this.#dismiss as any).class; },
                    set: (value) => {
                        (this.#dismiss as any).class = value;
                        this.render();
                    }
                })
                Object.defineProperty(dismiss, 'callback', {
                    get: () => { return (this.#dismiss as any).callback; },
                    set: (value) => {
                        if (!(typeof value === 'function')) return;
                        (this.#dismiss as any).callback = value;
                        this.render();
                    }
                })

                return dismiss;
            },
            set: (value) => { this.#dismiss = value; this.render(); }
        });

        Object.defineProperty(this, 'clickOut', {
            get: () => { return this.#clickOut; },
            set: (value) => {
                if (typeof value !== 'boolean') { console.error('clickOut must be a boolean'); return; }
                this.#clickOut = value;
            }
        })
    }


    toggle() {

        this.fire('modal:toggle', this);
        if ($(this.getElement()).hasClass('show')) {
            this.hide();
        } else {
            this.show();
        }
        this.fire('modal:toggle:complete', this);
    }

    hide() {

        this.fire('modal:hide', this);

        $(this.getElement()).removeClass('show showing hide');
        $(this.getElement()).addClass('hiding');

        setTimeout(() => {
            $(this.getElement()).addClass('hide');
            $(this.getElement()).removeClass('hiding showing show');
            this.fire('modal:hide:complete', this);
        }, 400);
    }

    show() {

        this.fire('modal:show', this);

        // if modal is not in the dom, append it
        if (this.getElement().parent().length <= 0) $(document.body).append(this.getElement());

        // add backdrop
        if ($(this.getElement()).find('.modal-backdrop').length <= 0) $(this.getElement()).append($(`<div class="modal-backdrop"></div>`));

        $(this.getElement()).removeClass('hide hiding show');
        $(this.getElement()).addClass('showing');
        setTimeout(() => {
            $(this.getElement()).addClass('show');
            $(this.getElement()).removeClass('showing hiding hide');
            this.fire('modal:show:complete', this);
        }, 400);

        if (this.#clickOut) {
            $(this.getElement()).find('.modal-backdrop').on('click', this.hide.bind(this));
        } else {
            $(this.getElement()).find('.modal-backdrop').off('click', this.hide.bind(this));
        }

        let modal_dialog = $(this.getElement()).find('.modal-dialog');

        modal_dialog.on("scroll", (e) => {

            const dialog = modal_dialog[0];
            const parent = modal_dialog.parent();
            const scrollHeight = dialog.scrollHeight;
            const scrollTop  = (modal_dialog.scrollTop() as any) + ((modal_dialog.outerHeight() as any) / 2);
            const percentage = (scrollTop / scrollHeight) * 100;
            const shadowOffsetTop = parent.find(".shadow-offset-top");
            const shadowOffsetBottom = parent.find(".shadow-offset-bottom");

            if (shadowOffsetTop.length === 0) {
                parent.append(`<div class="shadow-offset-top"></div>`);
            }

            if (shadowOffsetBottom.length === 0) {
                parent.append(`<div class="shadow-offset-bottom"></div>`);
            }

            shadowOffsetTop.css("--offset-percentage", (percentage < 50 ? 0 : Math.min(100, percentage)) + "%");
            shadowOffsetBottom.css("--offset-percentage", (100 - percentage) < 50 ? 0 : Math.min(100 - percentage) + "%");
        });

    }


    isVisible() {
        return $(this.getElement()).is(':visible') && !$(this.getElement()).hasClass('hide') && !$(this.getElement()).hasClass('hiding') && (this.getElement().height() as any) > 0 && (this.getElement().width() as any) > 0;
    }

    find(selector: string) {
        return $(this.getElement()).find(selector);
    }

    parent() {
        return $(this.getElement()).parent();
    }

    on(event: string, callback: Function) {
        $(this.getElement()).on(event as any, callback);
    }

    off(event: string, callback: Function | boolean = false) {
        $(this.getElement()).off(event as any, callback as any);
    }

    fire(event: string, params: any) {
        $(this.getElement()).trigger(event, params);
    }

    render() {

        this.fire('modal:render', {
            element: this.getElement(),
            title: this.#title,
            content: this.#content
        });

        let modal = $(this.getElement());
        let modal_title = modal.find('.modal-header .modal-title');

        if ($(this.#title as any).length == 1) {
            let title = $(this.#title as any);
            title.addClass('modal-title');
            modal_title.replaceWith(title);
        } else {
            modal_title.html(this.#title as any);
        }
        modal_title.addClass('modal-title');

        if (this.#dismiss) {
            let btn = this.#dismiss.render();
            btn.html('');
            if (modal.find('.modal-header .btn-close').length == 0) {
                modal.find('.modal-header').append(btn);
            } else {
                modal.find('.modal-header .btn-close').replaceWith(btn);
                modal.find('.modal-header .btn-close').addClass('btn-close');
            }
        } else {
            modal.find('.modal-header .btn-close').remove();
        }

        modal.find('.modal-content').html("").append(this.#content as any);
        modal.find('.modal-footer').html("").append(this.#action.render());
    }


    static create(title: string | JQuery, content: string | JQuery) {

        // create minimal modal structure
        let element = $(`<div class='modal' aria-modal="true">\n
        \t<div class='modal-dialog'>\n
            \t\t<div class='modal-header'>\n
                    \t\t\t<h5 class='modal-title'>Modal Title</h5>\n
                    \t\t\t<button class='btn btn-close'></button>\n
            \t\t</div>\n
            \t\t<div class='modal-content'></div>\n
            \t\t<div class='modal-footer'></div>\n
        \t</div>
        </div>`);

        let modal = (new Modal(element) as any);
        modal.title = title;
        modal.content = content;
        modal.dismiss = new ActionUnit('cancel', 'btn-close', () => { modal.hide(); });

        return modal;
    }

    static from(target: HTMLElement | JQuery) {

        let el = $(target);

        let title = el.find('.modal-title');
        let content = el.find('.modal-content').children();
        let dismiss = el.find('.btn-close');
        let act_positive = el.find('.modal-footer button')[1] ?? null;
        let act_negative = el.find('.modal-footer button')[0] ?? null;

        let act = {} as any;
        if (act_positive) {
            act.positive = {
                text: $(act_positive).html(),
                class: $(act_positive).attr('class'),
                callback: () => { modal.hide(); }
            };
        }
        if (act_negative) {
            act.negative = {
                text: $(act_negative).html(),
                class: $(act_negative).attr('class'),
                callback: () => { modal.hide(); }
            }
        }

        let modal = new Modal(target) as any;
        modal.title = title;
        modal.content = content;
        if (dismiss.length > 0) {
            modal.dismiss = new ActionUnit('cancel', 'btn-close', () => { modal.hide(); });
        }
        modal.action.positive = act.positive;
        modal.action.negative = act.negative;

        return modal;
    }
}

export default Modal;