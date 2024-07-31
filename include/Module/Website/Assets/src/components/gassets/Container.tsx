import React, { useState, useEffect, useContext } from 'react';
import { AppContext, TAsset } from '../gassets';
import { __MP } from "../../../../../../Buildin/src/main";
import { editAssets } from '../../partials/edit';
import { Modal } from '@il4mb/merapipanel/modal';
const __: __MP = (window as any).__;



const AssetItem = ({ asset }: { asset: any }) => {

    const { refresh, setRefresh, setChanged } = useContext(AppContext) as any;


    const icons = {
        "style": '<i class="fa-brands fa-css3-alt fa-2x"></i>',
        "stylesheet": '<i class="fa-brands fa-css3-alt fa-2x"></i>',
        "script": '<i class="fa-brands fa-js fa-2x"></i>',
        'link': '<i class="fa-solid fa-link fa-xl"></i>'
    };

    const getIconHtml = (type: string) => {
        if (Object.keys(icons).includes(type)) {
            return { __html: icons[type] };
        }
        return { __html: '<i class="fa-regular fa-circle-question"></i>' };
    };


    function handleSave(asset: TAsset, modal: Modal) {
        const copyof = JSON.parse(JSON.stringify(asset));
        if (copyof.attributes) {
            copyof.attributes = JSON.stringify(copyof.attributes);
        }

        __.http.post((window as any).access_path("api/Website/Assets/savepop"), copyof)
            .then(e => {
                if ((e as any).status) {
                    __.toast("Success update asset", 5, "text-success");
                    setRefresh(true);
                    setTimeout(() => {
                        setChanged(asset);
                    }, 100);
                } else throw new Error(e.message);
            })
            .catch(err => {
                __.toast(err.message || "Unknown error", 5, "text-danger")
            });
    }


    function handleDelete(asset: TAsset) {
        __.dialog.danger("Are you sure?", `are you sure you want to delete the asset <b>${asset.name}</b>, this action cannot be restored!.`)
            .then(() => {
                __.http.post((window as any).access_path("api/Website/Assets/rmpop"), asset.id)
                    .then(e => {
                        if ((e as any).status) {
                            __.toast("Success remove asset", 5, "text-success");
                            setRefresh(true);
                        } else throw new Error(e.message);
                    })
                    .catch(err => {
                        __.toast(err.message || "Unknown error", 5, "text-danger")
                    });
            })
    }

    return (
        <div className='mb-2 rounded-2 bg-light py-1 px-2 d-flex align-items-center'>
            <span className='px-2 text-center' style={{ width: '52px' }} dangerouslySetInnerHTML={getIconHtml(asset.type)}></span>
            <div style={{ lineHeight: "1em" }}>
                <div className='fw-semibold'>{asset.name}</div>
                <code>{asset.type}</code>
            </div>
            <div className="dropdown ms-auto">
                <button className="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i className="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul className="dropdown-menu">
                    <li>
                        <a className="dropdown-item" href="#" onClick={() => editAssets(asset).onSave(handleSave)}>
                            <i className="fa-solid fa-code"></i> Edit
                        </a>
                    </li>
                    <li>
                        <a className="dropdown-item" href="#" onClick={() => handleDelete(asset)}>
                            <i className="fa-solid fa-ban text-danger"></i> Remove
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    );
};


const AssetsContainer = () => {

    const { refresh, setRefresh } = useContext(AppContext) as any;
    const [assets, setAssets] = useState<any[]>([]);

    useEffect(() => {
        if (refresh) {
            loadAssets();
            setRefresh(false);
        }
    }, [refresh]);


    let timeOut: any;
    function loadAssets() {
        if (timeOut) clearTimeout(timeOut);
        timeOut = setTimeout(() => {
            fetch((window as any).access_path("api/Website/Assets/listpops"))
                .then(response => response.json())
                .then(data => {
                    setAssets(data.data);
                });
        }, 400);
    }

    return (
        <div className="assets-container">
            {assets.map((asset) => (
                <AssetItem key={asset.id} asset={asset} />
            ))}
        </div>
    );
};

export default AssetsContainer;
