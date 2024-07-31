import { EditorView, basicSetup } from 'codemirror';
import { javascript } from "@codemirror/lang-javascript";
import { css } from "@codemirror/lang-css";
import { autocompletion } from "@codemirror/autocomplete";
import { EditorState } from "@codemirror/state";
import { html_beautify, css_beautify, js_beautify } from "js-beautify";
import { __MP } from "../../../../../Buildin/src/main";
import { TAsset } from '../components/gassets';
const __: __MP = (window as any).__;

const beautyOptions = {
    "arrowParens": "always",
    "bracketSameLine": false,
    "bracketSpacing": true,
    "semi": true,
    "experimentalTernaries": false,
    "singleQuote": false,
    "jsxSingleQuote": false,
    "quoteProps": "as-needed",
    "trailingComma": "all",
    "singleAttributePerLine": false,
    "htmlWhitespaceSensitivity": "css",
    "vueIndentScriptAndStyle": false,
    "proseWrap": "preserve",
    "insertPragma": false,
    "printWidth": 80,
    "requirePragma": false,
    "tabWidth": 0,
    "useTabs": false,
    "embeddedLanguageFormatting": "auto",
    "indent_size": 2
} as js_beautify.CSSBeautifyOptions;

// Function to initialize the editor
function createEditor(holder: HTMLElement, asset: TAsset) {
    const content = (asset.type == "style" ? css_beautify : js_beautify)(asset.content || "", beautyOptions);
    const extensions = {
        script: [
            basicSetup,
            javascript(),
            autocompletion(),
            EditorView.updateListener.of((update) => {
                if (update.docChanged) {
                    // Assuming 'asset' is a global or higher scope variable
                    asset.content = update.state.doc.toString();
                }
            })
        ],
        style: [
            basicSetup,
            css(),
            autocompletion(),
            EditorView.updateListener.of((update) => {
                if (update.docChanged) {
                    // Assuming 'asset' is a global or higher scope variable
                    asset.content = update.state.doc.toString();
                }
            })
        ]
    }

    const editor = new EditorView({
        state: EditorState.create({
            doc: content,
            extensions: extensions[asset.type]
        }),
        parent: holder
    } as any);

    return editor;
}


function createAttrEditor(holder: HTMLElement, asset: TAsset) {
    render();
    function render() {

        $(holder).html("").append(
            ((asset.attributes || []).length ?
                $(`<table class="w-100 mb-3">`)
                    .append(
                        `<colgroup><col style="width: 100px;"/><col/></colgroup>`,
                        (asset.attributes || []).map((attr, i) => {
                            return $(`<tr>`)
                                .append(
                                    $(`<td>`).append(
                                        $(`<input class="form-control fw-bold" placeholder="attr key">`).val(attr.key)
                                            .on("keyup", function (e: any) {
                                                const v = $(this).val() as any;
                                                if (e.originalEvent.keyCode == 8 && (v.length <= 0 && attr.key.length <= 0)) {
                                                    if (asset.attributes) {
                                                        asset.attributes.splice(i, 1);
                                                        render();
                                                    }
                                                }
                                                attr.key = v;
                                            })
                                    ),
                                    $(`<td colspan="2">`).append(
                                        $(`<input class="form-control text-primary" placeholder="attr value">`).val(attr.value)
                                            .on("input", function () {
                                                attr.value = $(this).val() as string;
                                            })
                                    )
                                )
                        })
                    )
                :
                $(`<div class="py-5 fw-semibold text-center">No attributes yet</div>`)
            ),
            $(`<button class="btn btn-sm btn-primary rounded-pill px-5 position-absolute bottom-0 start-0 mb-3 ms-3">Add <i class="fa-solid fa-plus"></i></button>`)
                .on("click", () => {
                    asset.attributes?.push({
                        key: "",
                        value: ""
                    });
                    render();
                })
        )
        setTimeout(() => {
            holder.scrollTop = holder.scrollHeight;
        }, 200);
    }
}


export const editAssets = (asset: TAsset) => {

    const oldAsset = JSON.parse(JSON.stringify(asset));
    let onSave: Function = () => { }

    const codeContainer = $(`<div id="code-edit" class="px-1 pt-1" style="max-height: 50vh; overflow: auto; padding-bottom: 1.5rem;">`);
    const attrContainer = $(`<div id="attr-edit" class="px-1 pt-1" style="max-height: 50vh; overflow: auto; padding-bottom: 1.5rem;">`);

    const modal = $("#edit-assets-modal").length ? __.modal.from($("#edit-assets-modal")) : __.modal.create("Edit Asset", $("<div>"));
    modal.el.attr("id", "edit-assets-modal");
    modal.show();
    modal.action.positive = function () {
        onSave(asset, modal);
    }
    modal.action.negative = function () {
        Object.keys(oldAsset).forEach(key => {
            asset[key] = oldAsset[key];
        });
        modal.hide();
    }
    modal.el.find(".modal-dialog").css("max-width", "860px");
    const body = modal.el.find(".modal-body").css("max-width", "unset");
    body.html("").append(
        $(`<div class="pb-3">`).append(
            $(`<input class="form-control" placeholder="Asset name" value="${asset.name}">`).on("input", function () {
                let v = $(this).val() as string;
                if (v.length) {
                    asset.name = $(this).val() as string;
                }
            })
        ),
        $(`<div class="row align-items-start p-2" id="edit-container">`)
            .append(
                $(`<ul class="col-12 col-lg-3 nav flex-lg-column nav-pills mb-3" role="tablist">`)
                    .append(
                        $(`<li class="nav-item" role="presentation">
                                <button class="nav-link w-100 active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-tab-pane" type="button" role="tab" aria-controls="content-tab-pane">Contents</button>
                            <li>`),
                        $(`<li class="nav-item" role="presentation">
                                <button class="nav-link w-100" id="attribute-tab" data-bs-toggle="tab" data-bs-target="#attribute-tab-pane" type="button" role="tab" aria-controls="attribute-tab-pane">Attributes</button>
                            <li>`)
                    ),
                $(`<div class="col-12 col-lg-9 tab-content position-relative" id="v-pills-tabContent" style="min-height: 40vh;">`)
                    .append(
                        $(`<div class="tab-pane fade show active" id="content-tab-pane" role="tabpanel" aria-labelledby="content-tab-pane-tab" tabindex="0">`)
                            .append(
                                codeContainer
                            ),
                        $(`<div class="tab-pane fade pb-5" id="attribute-tab-pane" role="tabpanel" aria-labelledby="attribute-tab-tab" tabindex="1">`)
                            .append(
                                attrContainer
                            )

                    )
            ),

    )

    if (Object.keys(asset).includes("attributes") && Object.keys(asset).includes("content")) {
        createEditor(codeContainer[0], asset);
        createAttrEditor(attrContainer[0], asset);
    } else if (Object.keys(asset).includes("attributes")) {
        body.find("#edit-container").html("").append(attrContainer);
        createAttrEditor(attrContainer[0], asset);
    } else if (Object.keys(asset).includes("content")) {
        body.find("#edit-container").html("").append(codeContainer);
        createEditor(codeContainer[0], asset);
    } else {
        body.find("#edit-container").html("");
    }

    return {
        onSave: (callback: Function) => {
            onSave = callback;
        }
    }

}