import React, { useEffect } from 'react';
import { LayoutProps } from './Layout';
import { CanvasProps } from './Canvas';

interface Props {
    children?: React.ReactElement<LayoutProps|CanvasProps>[];
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

const LayoutRow = (props: Props) => {



    return (
        <div className="editor__layout-row">
            {props.children}
        </div>
    );
};

export default LayoutRow;