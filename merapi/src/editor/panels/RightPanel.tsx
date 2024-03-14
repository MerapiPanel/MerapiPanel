import React, { useEffect } from "react";
import { PanelProps } from "../_define";
import { setOptions } from "./EditorPanel";
import StyleContainer from "./containers/StylesContainer";
import TraitsContainer from "./containers/TraitsContainer";
import { Editor } from "grapesjs";



const RightPanel = ({ editor }: PanelProps) => {



    useEffect(() => {
        if (editor === null) return;

        editor.Panels.addPanel({
            id: 'panel-right',
            el: '.layout__panel-right',
        });
        editor.Panels.addPanel({

            id: 'panel-switcher',
            el: '.panel__switcher',
            buttons: [{
                id: 'show-style',
                active: true,
                label: '<i class="fa-solid fa-paint-roller"></i>',
                command: 'show-styles',
                togglable: false
            },
            {
                id: 'show-traits',
                active: false,
                label: '<i class="fa-solid fa-gears"></i>',
                command: 'show-traits',
                togglable: false,
            }],
        });


        editor.Commands.add('show-styles', {

           getEl(editor: Editor): HTMLElement|null|undefined {
               return editor?.getContainer()?.closest('.layout__panel-row')?.querySelector('.styles-container');
           },

            run(editor, sender) {
                const smEl = this.getEl(editor);
                smEl.style.display = '';
            },
            stop(editor, sender) {
                const smEl = this.getEl(editor);
                smEl.style.display = 'none';
            },
        });

        editor.Commands.add('show-traits', {
            getEl(editor: Editor): HTMLElement|null|undefined {
                return editor?.getContainer()?.closest('.layout__panel-row')?.querySelector('.traits-container');
            },
            run(editor: Editor, sender) {
                this.getEl(editor).style.display = '';
            },
            stop(editor, sender) {
                this.getEl(editor).style.display = 'none';
            },
        });

    })


    return (
        <div className="layout__panel-right">
            <StyleContainer editor={editor} />
            <TraitsContainer editor={editor} />
        </div>
    )
}


export default RightPanel;
