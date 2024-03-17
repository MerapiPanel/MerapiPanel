import React, { useEffect } from 'react';
import { LayoutProps } from './Layout';
import { CanvasProps } from './Canvas';

interface LayoutRowProps {
    children?: React.ReactElement<LayoutProps | CanvasProps>[]
    clasaName?: string
}


// const LayoutRow = ({ onReady }: EditorProps) => {

//     const [editor, setEditor] = useState<EditorState>(null);

//     function handleOnReady(editor: Editor) {
//         onReady(editor);
//         setEditor(editor);
//     }


//     return (
//         <div className="layout__panel-row">
//             <LeftPanel editor={editor} />
//             <EditorPanel onReady={handleOnReady} />
//             <RightPanel editor={editor} />
//         </div>
//     );
// };

const LayoutRow = (props: LayoutRowProps) => {



    return (
        <div className={"merapi__editor--layout-row" + (props.clasaName ? " " + props.clasaName : "")}>
            {props.children}
        </div>
    );
};

export default LayoutRow;