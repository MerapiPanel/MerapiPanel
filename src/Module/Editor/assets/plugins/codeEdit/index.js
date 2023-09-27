
export default (editor, opts = {}) => {
    
    const codeMirror = editor.CodeManager.defViewers.CodeMirror;
    codeMirror.attributes.readOnly = false;
}