import { Editor } from "grapesjs";



type onReady = (editor: Editor) => void

type EditorState = Editor | null;


interface PanelProps {
    editor: Editor | null
}

interface EditorProps {
    onReady: onReady
}





export type {
    onReady,
    EditorState,
    PanelProps,
    EditorProps
}