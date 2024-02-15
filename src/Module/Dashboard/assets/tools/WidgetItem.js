import WidgetBox from "./WidgetBox";

export default class WidgetItem extends WidgetBox {

    constructor(args = {}) {

        args.type = "item";
        args.category = "item";
        super(args);
    }
}