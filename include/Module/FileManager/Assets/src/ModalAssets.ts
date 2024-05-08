import { __MP } from "../../../../Buildin/src/main";
import HugeUploader from 'huge-uploader';

const __: __MP = (window as any).__;
const FileManager = {
    endpoints: {
        upload: null,
        fetch: null,
        delete: null
    },
};
__.FileManager = new Proxy(FileManager, {
    get: function (target, name) {
        if (target[name]) {
            return target[name];
        }
        return null;
    },

    set: function (target, name, value) {
        throw new Error("Error: Can't change FileManager object");
    }
})