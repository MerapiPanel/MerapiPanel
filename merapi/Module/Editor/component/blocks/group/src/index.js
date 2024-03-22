import block from "./block.json";
import { registerBlock } from "@il4mb/merapipanel/block";



registerBlock(block.name, {
    edit: () => import("./edit.js"),
    view: () => import("./view.js")
});
