import React, { useEffect } from "react";
import { ContainerProps } from "../../Container";
import { useRoot } from "../../RootEditor";
import './BlocksContainer.scss';


/**
 * Blocks Container
 */
export const BlocksContainer = (props: ContainerProps) => {

    const { editor, config } = useRoot();

    config.blockManager = {
        appendTo: '.blocks-container',
        blocks: [
            {
                id: 'image',
                label: 'Image',
                media: `<svg style="width:24px;height:24px" viewBox="0 0 24 24">
                  <path d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z" />
              </svg>`,
                // Use `image` component
                content: { type: 'image' },
                // The component `image` is activatable (shows the Asset Manager).
                // We want to activate it once dropped in the canvas.
                activate: true,
                // select: true, // Default with `activate: true`
            }
        ],
    }

    useEffect(() => {
        if (editor == null) return;

        editor.BlockManager
        editor.BlockManager.add('header', {
            label: 'Header',
            category: 'Basic',
            attributes: { class: 'fa fa-header' },
            content: "<h1>Header</h1>",
            
        });

        editor.Components.addType('header', {
            isComponent: (el) => el.tagName === 'H1' || el.tagName === 'H2' || el.tagName === 'H3' || el.tagName === 'H4' || el.tagName === 'H5' || el.tagName === 'H6',
            model: {
                tagName: 'h1',
                droppable: false,
                attributes: { // Default attributes
                    type: 'text',
                    name: 'default-name',
                    placeholder: 'Insert text here',
                },
            }
        });

    }, [editor]);

    return (
        <div className="blocks-container hide" id={props.id}>
            {props.children}
        </div>
    )
}

export default BlocksContainer;