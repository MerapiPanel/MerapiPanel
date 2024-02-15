
const WidgetParser = function (data) {

    if (data.type === "box") {
        return new WidgetBox(data);
    } else if (data.type === "item") {
        return new WidgetItem(data);
    }
}