import React, { useContext } from "react";
import { __MP } from "../../../../../../Buildin/src/main";
const __: __MP = (window as any).__;
import { AppContext } from "../gassets";

export default () => {

    const { refresh, setRefresh } = useContext(AppContext) as any;

    function showAddModal() {

        let [name, type] = ["", "style"];
        const content = $("<div>")
            .append(
                $(`<div class="form-group mb-3">`)
                    .append(
                        `<label for="asset-name">Name</label>`,
                        $(`<input id="asset-name" class='form-control' placeholder='Give a name for an asset'/>`)
                            .on("input change", function () {
                                name = $(this).val() as string
                            }),
                    ),
                $(`<div class="form-group mb-3">`)
                    .append(
                        `<label for="asset-type">Type</label>`,
                        $(`<select id="asset-type" class="form-select">`)
                            .append(
                                `<option value="style">Style</option>`,
                                `<option value="script">Script</option>`,
                                `<option value="link">Link</option>`,
                                `<option value="link:style">Link:style</option>`,
                            )
                            .on("change", function () {
                                type = $(this).val() as string
                            })
                    )
            )
        const modal = __.modal.create("Add Asset", content);
        modal.el.attr("id", "add-assets-modal");
        modal.el.find("#asset-name").val("");
        modal.show();
        modal.action.positive = function () {
            addAsset(name, type);
            modal.hide();
        }
        modal.on("modal:hide", () => modal.el.remove());
    }

    function addAsset(name: string, type: string) {
        __.http.post((window as any).access_path("api/Website/Assets/addpop"), { name, type })
            .then(e => {
                if ((e as any).status) {
                    __.toast("Success add new asset", 5, "text-success");
                    setRefresh(true);
                } else throw new Error(e.message);
            })
            .catch(err => {
                __.toast(err.message || "Unknown error", 5, "text-danger")
            });
    }

    return (<button className="btn btn-primary" onClick={showAddModal}>Add Assets</button>)
}