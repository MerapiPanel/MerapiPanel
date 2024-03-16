import React from "react";

interface LayoutProps {
    children?: React.ReactElement[]
}

const Layout = (props: LayoutProps) => {
    return (
        <div className="editor__layout">
            {props.children}
        </div>
    )
}

export default Layout;
export type { LayoutProps }