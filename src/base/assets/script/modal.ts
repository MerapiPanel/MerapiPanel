
var isHTML = RegExp.prototype.test.bind(/(<([^>]+)>)/i);

abstract class UnitModel {
    #element: JQuery<HTMLElement>;

    constructor(element: JQuery) {
        this.#element = element;
    }
    getElement(): JQuery<HTMLElement> {
        return this.#element;
    }
}


class Header extends UnitModel {

    public title: JQuery;

    constructor(dialog: Dialog) {
        let _el = $(dialog.getElement()).find('.modal-header');
        if (_el.length <= 0) {
            _el = $('<div class="modal-header"></div>');
            dialog.getElement().html(_el[0]);
        }

        super(_el);

        this.title = this.getElement().find(".modal-title");
    }


    setTitle(title: string | HTMLElement | JQuery) {

        if(!this.title) {
            this.title = $('<div class="modal-title"></div>');
        }

        if (this.getElement().find('.modal-title').length <= 0) {
            this.getElement().prepend(this.title);
        }

        if ($(title as any).length > 0) {

            this.title.html($(title as any).html());
            this.title.addClass("modal-title");
        } else {
            this.title.text(title as any);
        }
    }


}


class Dialog extends UnitModel {

    public modal: Modal;
    public header: Header;
    public body: JQuery;
    public footer: JQuery;

    constructor(modal: Modal) {
        let _el = $(modal.getElement()).find('.modal-dialog');
        if (_el.length <= 0) {
            _el = $('<div class="modal-dialog"></div>');
            modal.getElement().html(_el[0]);
        }

        super(_el);
        this.modal = modal;

        this.header = new Header(this);
        console.log(this.getElement());
    }
}


class Modal extends UnitModel {

    public dialog: Dialog;

    constructor(el: HTMLElement | JQuery) {
        super($(el));
        this.dialog = new Dialog(this);

    }


    toggle() {
        if ($(this.getElement()).hasClass('show')) {
            this.hide();
        } else {
            this.show();
        }
    }

    hide() {
        $(this.getElement()).removeClass('show');
        $(this.getElement()).addClass('hiding');
        setTimeout(() => {
            $(this.getElement()).addClass('hide');
            $(this.getElement()).removeClass('hiding');
        }, 400)
    }

    show() {
        $(this.getElement()).removeClass('hide');
        $(this.getElement()).addClass('showing');
        setTimeout(() => {
            $(this.getElement()).addClass('show');
            $(this.getElement()).removeClass('showing');
        }, 400);
    }


    on(event: string, callback: Function) {
        $(this.getElement()).on(event as any, callback);
    }


    static from(target: HTMLElement | JQuery) {

        return new Modal(target);
    }
}

export default Modal;