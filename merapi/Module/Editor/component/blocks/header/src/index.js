import block from "./block.json";
import { registerBlock } from "@il4mb/merapipanel/block";
import { Edit } from "./edit.js";
import { View } from "./view.js";

registerBlock(block.name, {
    ...block,
    
    isComponent: (el) => {
        console.log(el.tagName);
        return el.tagName === 'H1' || el.tagName === 'H2' || el.tagName === 'H3' || el.tagName === 'H4' || el.tagName === 'H5' || el.tagName === 'H6';
    },
    edit: Edit,
    view: View
})