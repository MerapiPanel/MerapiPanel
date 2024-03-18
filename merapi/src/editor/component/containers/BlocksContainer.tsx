import React, { useEffect } from "react";
import { ContainerProps } from "../../Container";
import { useRoot } from "../../RootEditor";
import './BlocksContainer.scss';
import { AddComponentTypeOptions, BlockProperties, ComponentAddType } from "grapesjs";


/**
 * Blocks Container
 */
export const BlocksContainer = (props: ContainerProps) => {

   

    const { editor, config } = useRoot();

    config.blockManager = {
        appendTo: '.blocks-container',
    }


    useEffect(() => {

        if (editor === null) return;

        editor.Components.addType('header', {
            tagName: 'h1',
            isComponent: el => {
                return el.tagName === 'H1' || el.tagName === 'H2' || el.tagName === 'H3' || el.tagName === 'H4' || el.tagName === 'H5' || el.tagName === 'H6'
            },
            model: {
                defaults: {
                    tagName: 'h1',
                    content: 'hello world'
                }
            },
            extend: 'text',

        } as AddComponentTypeOptions);


        editor.BlockManager.add('header', {
            id: 'header',
            label: 'Header',
            category: 'Text',
            content: {
                type: 'header'
            }
        });

        editor.setComponents(editor.getHtml());

    }, [editor]);

    return (
        <div className="blocks-container hide" id={props.id}>
            {props.children}
        </div>
    )
}

export default BlocksContainer;