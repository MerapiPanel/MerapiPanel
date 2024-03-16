import React, { useState, useEffect } from 'react';
import { PanelProps, EditorProps } from './_define';
import { Device } from './provider/Options';
import { ButtonProps, Editor } from 'grapesjs';





const updateDeviceUI = (editor: Editor) => {

    const devices: Array<Device> = editor.Devices.getDevices();

    editor.Panels.addPanel({
        id: 'panel-devices',
        el: '.panel__devices',
        buttons: devices.map((device) => {

            editor.Commands.add('set-device-' + device.get("name")?.toLowerCase(), {
                run: (editor: Editor) => editor.setDevice(device.get("name") as any),
                stop: () => {}
            });

            return {
                id: 'device-' + device.get("name")?.toLowerCase(),
                label: `<i class="fa-solid ${device.get("icon" as any)}"></i>`,
                command: 'set-device-' + device.get("name")?.toLowerCase(),
                active: (device.get("name")?.toLowerCase() == "desktop" ? true : false),
                togglable: false
            } as ButtonProps


        }),
    });
}


const LayoutTop = ({ editor }: PanelProps) => {



    useEffect(() => {

        if (editor === null) return;

        editor.Panels.addPanel({
            id: 'panel-top',
            el: '.layout__panel-top',
        });
        editor.Panels.addPanel({
            id: 'panel-actions',
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

        updateDeviceUI(editor);

    }, [editor, updateDeviceUI]);

    return (
        <div className="layout__panel-top">
            <div className="panel__actions panel__initial"></div>
            <div className="panel__devices panel__initial"></div>
            <div className="panel__switcher panel__initial"></div>
        </div>
    );
}

export default LayoutTop;