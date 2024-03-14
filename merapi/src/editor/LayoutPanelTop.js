import React, { useState, useEffect } from 'react';

const LayoutPanelTop = ({ editor, options }) => {

    Object.assign(options, {
        panels: [
            {
                default: [
                    {
                        id: 'panel-top',
                        el: ".layout__panel-top",
                    }
                ]
            }
        ]
    });

    useEffect(() => {

        if(editor === null) return;
        console.log(editor)

        editor.Panels.addPanel({
            id: 'basic-actions',
            el: '.panel__basic-actions',
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
    })

    return (
        <div className="layout__panel-top">
            <div className="panel__basic-actions"></div>
            <div className="panel__devices"></div>
            <div className="panel__switcher"></div>
        </div>
    )
}

export default LayoutPanelTop;