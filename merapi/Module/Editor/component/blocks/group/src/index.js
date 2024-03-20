import block from "./block.json";



registerBlock(block.name, {
    edit: () => import("./edit.js"),
    view: () => import("./view.js")
});
