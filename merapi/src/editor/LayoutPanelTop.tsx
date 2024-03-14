import React, { useState, useEffect } from 'react';
import { Editor, EditorConfig } from 'grapesjs';
import { PanelProps , EditorProps} from './_define';



const LayoutPanelTop = ({ editor }: PanelProps) => {



    useEffect(() => {

        if (editor === null) return;

        editor.Panels.addPanel({
            id: 'panel-top',
            el: '.layout__panel-top',
        });
        editor.Panels.addPanel({
            id: 'basic-actions',
            el: '.panel__actions',
            buttons: [
                {
                    id: 'visibility',
                    active: true, // active by default
                    className: 'btn-toggle-borders',
                    label: '<i class="fa-solid fa-border-top-left"></i>',
                    command: 'sw-visibility', // Built-in command
                }, {
                    id: 'export',
                    className: 'btn-open-export',
                    label: '<i class="fa-solid fa-code"></i>',
                    command: 'export-template',
                    context: 'export-template', // For grouping context of buttons from the same panel
                }, {
                    id: 'show-json',
                    className: 'btn-show-json',
                    label: 'JSON',
                    context: 'show-json',
                    command(editor) {
                        editor.Modal.setTitle('Components JSON')
                            .setContent(`<textarea style="width:100%; height: 250px;">
                      ${JSON.stringify(editor.getComponents())}
                    </textarea>`)
                            .open();
                    },
                }
            ],
        });

    });

    return (
        <div className="layout__panel-top">
            <div className="panel__actions"></div>
            <div className="panel__devices"></div>
            <div className="panel__switcher"></div>
        </div>
    );
}

export default LayoutPanelTop;