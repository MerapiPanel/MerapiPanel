import React from "react";


export class BlockRegister extends React.Component {

    public static register(block: any) {
        console.log(block);
    }

    render(): React.ReactNode {
        return (<></>);
    }
}


// export interface BlockRegisterProps {
//     children?: React.ReactElement<BlockProps>[] | React.ReactElement<BlockProps>
// }

// export const BlockRegister = (props: BlockRegisterProps) => {


//     return (<>{props.children}</>);
// }






export interface BlockProps {
    name: string,
    title: string,
    category: string,
    attributes?: {},
    options?: {},
    editScript?: string,
    editStyle?: string,
    save?: string,
    style?: string
}
export const Block = (props: BlockProps) => {
    return (
        <></>
    )
}
