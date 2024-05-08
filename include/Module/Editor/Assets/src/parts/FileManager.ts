import { __MP } from "../../../../../Buildin/src/main";
const __: __MP = (window as any).__;

var selectCallback: Function | null = null;

export const fileManager = (props) => {
    props.close();
    selectCallback = props.select;
    __.FileManager.select()
        .then((file: any) => {
            if (selectCallback) {
                selectCallback({
                    name: file.name,
                    src: file.path
                });

            }
        })
}