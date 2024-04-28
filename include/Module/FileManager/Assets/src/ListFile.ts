import * as dialog from "@il4mb/merapipanel/dialog";
import { toast } from "@il4mb/merapipanel/toast";
import * as http from "@il4mb/merapipanel/http";


const endpoints = (window as any).__.endpoints;

$('input[name="selectedFiles"]').on('change', function () {
    renderBottomTools();
});


$('select[name="action"]').on('change', function () {

    const url = new URL(window.location.href);
    const selectedFiles: string[] = [];

    $('input[name="selectedFiles"]:checked').each(function () {
        selectedFiles.push($(this).val() as string);
    });

    if (!selectedFiles.length) {
        $('select[name="action"]').prop('selectedIndex', 0);
        return;
    }


    const parent = url.searchParams.get('d');
    const action = $('select[name="action"]').val();

    if (action === 'rename') {
        rename(selectedFiles[0])
            .then(function (newName) {

                http.post(endpoints.rename, {
                    old_name: selectedFiles[0],
                    new_name: newName,
                    parent: parent || ''
                }).then(function () {

                    toast("File renamed successfully", 5, 'text-success');
                    window.location.reload();

                }).catch(function (e) {
                    toast(e.message || "Error renaming file", 5, 'text-danger');
                }).finally(function () {
                    $('input[name="selectedFiles"]').prop('checked', false);
                    $('select[name="action"]').prop('selectedIndex', 0);
                    renderBottomTools();
                })


            }).catch(function () {
                toast("Action cancelled", 5, 'text-info');
            }).finally(function () {
                $('input[name="selectedFiles"]').prop('checked', false);
                $('select[name="action"]').prop('selectedIndex', 0);
                renderBottomTools();
            })
        return;

    }

    if (action === "copy") {

        http.get(endpoints.fetchFolder, {})
            .then(function (response) {

                if (response.code === 200) {

                    console.log([{ name: 'root', path: '' }, ...(response.data as Folder[])])

                    copy([{ name: '/', children: [] }, ...(response.data as Folder[])] as Folder[])
                        .then(function (target) {

                            const formData = new FormData();
                            for (const i in selectedFiles) {
                                formData.append(`files[${i}]`, selectedFiles[i]);
                            }
                            formData.append('parent', parent || '');
                            formData.append('target', target);

                            http.post(endpoints.copy, formData).then(function () {
                                toast("File copied successfully", 5, 'text-success');
                                window.location.reload();
                            }).catch(function (e) {
                                toast(e.message || "Error copying file", 5, 'text-danger');
                            }).finally(function () {
                                $('input[name="selectedFiles"]').prop('checked', false);
                                $('select[name="action"]').prop('selectedIndex', 0);
                                renderBottomTools();
                            })
                        }).catch(function () {
                            toast("Action cancelled", 5, 'text-info');
                        }).finally(function () {
                            $('input[name="selectedFiles"]').prop('checked', false);
                            $('select[name="action"]').prop('selectedIndex', 0);
                            renderBottomTools();
                        })


                } else {
                    toast(response.message || "Error fetching folders", 5, 'text-danger');
                }

            }).catch(function (e) {
                toast(e.message || "Error fetching folders", 5, 'text-danger');
            })

    }
    if (action === "move") {

        http.get(endpoints.fetchFolder, {})
            .then(function (response) {

                if (response.code === 200) {

                    copy([{ name: '/', children: [] }, ...(response.data as Folder[])] as Folder[])
                        .then(function (target) {

                            const formData = new FormData();
                            for (const i in selectedFiles) {
                                formData.append(`files[${i}]`, selectedFiles[i]);
                            }
                            formData.append('parent', parent || '');
                            formData.append('target', target);

                            http.post(endpoints.move, formData).then(function () {
                                toast("File moved successfully", 5, 'text-success');
                                window.location.reload();
                            }).catch(function (e) {
                                toast(e.message || "Error moving file", 5, 'text-danger');
                            }).finally(function () {
                                $('input[name="selectedFiles"]').prop('checked', false);
                                $('select[name="action"]').prop('selectedIndex', 0);
                                renderBottomTools();
                            })
                        }).catch(function () {
                            toast("Action cancelled", 5, 'text-info');
                        }).finally(function () {
                            $('input[name="selectedFiles"]').prop('checked', false);
                            $('select[name="action"]').prop('selectedIndex', 0);
                            renderBottomTools();
                        })

                } else {
                    toast(response.message || "Error fetching folders", 5, 'text-danger');
                }

            }).catch(function (e) {
                toast(e.message || "Error fetching folders", 5, 'text-danger');
            }).finally(function () {
                $('input[name="selectedFiles"]').prop('checked', false);
                $('select[name="action"]').prop('selectedIndex', 0);
                renderBottomTools();
            })
    }

    if (action === 'delete') {

        const content = $(`<div>Are you sure you want to delete these files?</div>`).append(selectedFiles.map(function (file) {
            return $(`<code class='d-block'>${file}</code>`);
        }));
        dialog.confirm("Are you sure you want to delete these files?", content)
            .then(function () {

                const formData = new FormData();
                for (const i in selectedFiles) {
                    formData.append(`files[${i}]`, selectedFiles[i]);
                }
                formData.append('parent', parent || '');

                http.post(endpoints.delete, formData).then(function () {
                    toast("File deleted successfully", 5, 'text-success');
                    window.location.reload();
                }).catch(function (e) {
                    toast(e.message || "Error deleting file", 5, 'text-danger');
                }).finally(function () {
                    $('input[name="selectedFiles"]').prop('checked', false);
                    $('select[name="action"]').prop('selectedIndex', 0);
                    renderBottomTools();
                })
            })
            .catch(function () {
                toast("Action cancelled", 5, 'text-info');
            }).finally(function () {
                $('input[name="selectedFiles"]').prop('checked', false);
                $('select[name="action"]').prop('selectedIndex', 0);
                renderBottomTools();
            })

    }
});



type Folder = {
    name: string
    children: Folder[]
}


function copy(folders: Folder[] = []) {


    function renderNestedFolders(folders: Folder[], container: JQuery, parent?: string) {

        for (const folder of folders) {
            const name = folder.name;
            const children = folder.children;


            const el = $(`<div class="mb-2"><input type="checkbox" name="folder-target" value="${parent ? parent + '/' + name : name}" /> <span>${name}</span></div>`);
            container.append(el);

            if (children.length) {

                const collapse = $(`<div id="child-${name}" class="collapse ms-3"></div>`);
                el.append(
                    $(`<button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#child-${name}"><i class="fa-solid fa-angle-down"></i></button>`),
                    collapse
                );

                renderNestedFolders(children, collapse, parent ? parent + '/' + name : name);
            }
        }
    }


    return new Promise<string>(function (resolve, reject) {

        const content = $(`<div >`);
        renderNestedFolders(folders, content);

        dialog.confirm("Select Folder to Destination", content)
            .then(function () {
                const checked = $("input[name='folder-target']:checked");
                if (!checked.length) {
                    reject();
                    return;
                }
                resolve(checked.val() as string);

            }).catch(function () {
                reject();
            })

        $("input[name='folder-target']").on('change', function () {
            $("input[name='folder-target']").prop('checked', false);
            $(this).prop('checked', true);
        });
    })

}


function renderBottomTools() {
    if ($('input[name="selectedFiles"]:checked').length > 0) {
        $("#list-action").removeClass("d-none");
        $("#total-selected").text($("input[name='selectedFiles']:checked").length + " files");
        if ($("input[name='selectedFiles']:checked").length === 1) {
            $("#list-action select option[value='rename']").prop('disabled', false);
        } else {
            $("#list-action select option[value='rename']").prop('disabled', true);
        }
    } else {
        $("#list-action").addClass("d-none");
        $("#total-selected").text("0 files");
    }
}


function rename(oldName: string = "") {

    const content: JQuery<HTMLInputElement> = $(`<input type="text" class="form-control" value="" />`);

    return new Promise(function (resolve, reject) {
        dialog.confirm("Please enter new name", content)
            .then(function () {
                resolve(content.val());
            }).catch(function () {
                reject();
            })

        content.trigger('focus').val("").val((oldName || "").replace(/\.\w+$/, "")).on('keyup', function (e) {
            if (e.code === 'Enter' || e.key === 'Enter') {
                resolve(content.val());
                return;
            }
        });
        if ((content[0] as any).setSelectionRange)
            (content[0] as any).setSelectionRange(0, content[0].value.length);
    })
}



function newFolder(parent: string = "") {
    const content = $(`<input type="text" class="form-control" value="" />`);


    function submitNewFolder() {

        const url = new URL(window.location.href);
        const parent = url.searchParams.get('d');

        const name: string = (content.val() as any) || "";
        if (!name || !(name || "").trim().length) {
            toast("Please enter name", 5, 'text-info');
            return;
        }

        http.post(endpoints.newFolder, {
            name: name,
            parent: parent || ''
        }).then(function () {
            toast("Folder created successfully", 5, 'text-success');
            window.location.reload();

        }).catch(function (e) {
            toast(e.message || "Error creating folder", 5, 'text-danger');
        }).finally(function () {
            $('input[name="selectedFiles"]').prop('checked', false);
            $('select[name="action"]').prop('selectedIndex', 0);
            renderBottomTools();
        })
    }
    dialog.confirm("Please enter name", content)
        .then(function () {
            submitNewFolder();
        }).catch(function () {
            toast("Action cancelled", 5, 'text-info');
        })
    content.trigger('focus').val("").on('keyup', function (e) {
        if (e.code === 'Enter' || e.key === 'Enter') {
            submitNewFolder();
            return;
        }
    });
}

(window as any).__.createFolder = newFolder;