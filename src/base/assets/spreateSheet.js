"use strict";
const PREFIX = "sp"
const _CSS =
    `
.${PREFIX}-menu-tg {
    box-shadow: 0px 0px 0px 2px #008effbf
}
.${PREFIX}-menu-item {
    border: 1px solid #ffffff00 !important;
    padding: .75rem 1.2rem;
    cursor: pointer;
    position: relative
}
.${PREFIX}-menu-item:hover {
    border-color: #00BBFF !important;
}
.${PREFIX}-item {
    text-align: center;
    border: 1px solid #DDDDDD;
    outline: none !important;
    position: relative
}
.${PREFIX}-item:focus-visible, .${PREFIX}-item:focus {
    border: 1px solid #000000 !important;
}
.${PREFIX}-selector {
    border: 1px lightgrey dashed;
    position: absolute; 
    cursor: pointer;
    z-index: 1
}
`;

$.fn.spreadSheet = function (payload = {
    data: [
        [1, 2], [1, 2, 3]
    ],
    columns: [
        {
            text: "Hallo",
            width: "100px",
            type: "text"
        }
    ]
}) {

    const holder = $(this);
    holder.bind("spreadSheet",)

    const CTX_MENU = {
        column: [
            {
                text: "copy alamat",
                fun: function (data) {

                    let text = String(`${data.col}:${data.row}`)
                    unsecuredCopyToClipboard(text)
                }
            },
            {
                text: "Tambahkan sebelum",
                fun: function (data) {
                    addRow(data.row > 0 ? data.row - 1 : data.row, 0)
                }
            },
            {
                text: "Tambahkan sesudah",
                fun: function (data) {
                    addRow(data.row > 0 ? data.row - 1 : data.row, 1)
                }
            },
            {
                text: "Hapus baris",
                fun: function (data) {
                    removeRow(data.row > 0 ? data.row - 1 : data.row)
                }
            }
        ],
        default: [
            {
                text: "Baris baru",
                fun: function () {
                    addRow(payload.data, 0, 0)
                }
            },
            {
                text: "Il4mb",
                fun: null
            }
        ]

    }

    generateTable(holder, payload)
    holder.on("contextmenu", contextMenu);


    let style = $("<style>")
    style.html(_CSS)
    $(document.head).append(style)
    $(document.body).on("click", (e) => { contextMenuClearAll() })


    function generateTable() {

        const columns = santizeData(payload.columns ? payload.columns : [])
        const data = santizeData(payload.data ? payload.data : [])

        holder.html(`<table style='table-layout: fixed; width: fit-content; user-select: none;-moz-user-select: none; -webkit-user-select: none; -ms-user-select: none;'><thead></thead><tbody></tbody></table>`)

        const table = holder.find("table")
        const thead = holder.find("thead")
        const tbody = holder.find("tbody")

        const holdTime = 200;


        let h = false;
        let drawCode = 0;
        let holdTimeUp = holdTime;
        let holdDelay = 0;



        table.on("mousedown", (e) => {

            if (drawCode == 0) {
                holdDelay = setTimeout(() => {
                    drawCode = 1
                }, holdTimeUp)
            }
        });

        $(document).on("mouseup", () => {

            $(`${PREFIX}-selector`).remove()
            clearTimeout(holdDelay)
            drawCode = 0;

            const tds = tbody.find(`td.${PREFIX}-item`);
            for (let i = 0; i < tds.length; i++) {

                $(tds.get(i)).css("background", "transparent")
                $(tds.get(i)).css("border-color", "#DDDDDD")

            }
        })

        table.on("mousemove", (e) => {

            if (drawCode == 0) {
                $(`${PREFIX}-selector`).remove()
                clearTimeout(holdDelay)
                drawCode = 0;
            }
            if (drawCode == 1) {

                h = $(`<${PREFIX}-selector class='${PREFIX}-selector' style='width: 5px; height: 5px;'>`)
                h.css("top", e.pageY)
                h.css("left", e.pageX)
                $(document.body).append(h)
                drawCode = 2

            }
            if (drawCode == 2) {

                let selectY = h.get(0).documentOffsetTop;
                let selectX = h.get(0).documentOffsetLeft;
                let selectW = e.pageX - selectX;
                let selectH = e.pageY - selectY;
                let selectXrange = selectX + h.get(0).offsetWidth
                let selectYrange = selectY + h.get(0).offsetHeight

                h.css("width", `${selectW}px`)
                h.css("height", `${selectH}px`)


                const tds = tbody.find(`td.${PREFIX}-item`);
                const selected = [];

                for (let i = 0; i < tds.length; i++) {

                    $(tds.get(i)).css("background", "transparent")
                    $(tds.get(i)).css("border-color", "#DDDDDD")

                    let x = tds.get(i).documentOffsetLeft;
                    let y = tds.get(i).documentOffsetTop;

                    if ((x > selectX && x < selectXrange) && (y > selectY && y < selectYrange)) {

                        selected.push(tds.get(i))
                        $(tds.get(i)).css("background", "#E0E0E0")
                        $(tds.get(i)).css("border-color", "#000000")
                    }
                }
            }
        })




        table.css("padding", "15px")
        thead.append($(`<td width='50px' style='background: #DDDDDD;'></td>`))

        Object.keys(data.value).forEach(colKey => {

            let th = $("<th>")
            th.text(colKey)
            th.addClass(`${PREFIX}-item`)
            th.css("width", "150px")
            thead.append(th)

            if (columns.value[colKey] && columns.value[colKey].text) {
                th.text(columns.value[colKey].text)
            }
            if (columns.value[colKey] && columns.value[colKey].width) {
                th.css("width", columns.value[colKey].width)
            }

        })

        for (let y = 0; y < data.max; y++) {

            let row = $("<tr>")
            row.addClass(`${PREFIX}-item`)
            row.append($(`<td width='50px'>${y + 1}</td>`))


            let collSize = Object.keys(data.value).length;

            for (let x = 0; x < collSize; x++) {

                let ykey = getLabel(x + 1);
                let val = data.value[ykey][y]

                let td = $("<td>")
                td.text(val ? val : "")
                td.attr("contenteditable", true)
                td.addClass(`${PREFIX}-item`)
                td.on("oncontextmenu", function (e, d) {
                    d.row = y + 1
                    d.col = getLabel(x + 1)
                })
                td.on("input", () => {
                    payload.data[x][y] = td.text()
                })
                td.on("paste", (e) => {
                    setTimeout(() => {
                        td.html(td.text())
                    }, 4)
                })
                td.on("focus", (e) => {

                })
                td.on("focusout", (e) => {

                })
                row.append(td)

            }

            tbody.append(row)
        }
    }


    function santizeData(array) {

        let output = {};
        let max = 0;

        for (let i = 0; i < array.length; i++) {

            if (max < array[i].length) {
                max = array[i].length;
            }
            output[getLabel(i + 1)] = array[i];
        }
        return {
            value: output,
            max: max

        };
    }


    function addRow(idx = 0, ab = 1) {

        for (let i = 0; i < payload.data.length; i++) {

            let putat = ab > 0 ? idx + 1 : idx
            payload.data[i].splice(putat, 0, "");
        }
        generateTable();

    }


    function removeRow(idx = -1) {

        for (let i = 0; i < payload.data.length; i++) {
            payload.data[i].splice(idx, 1);
        }
        generateTable();

    }

    function unsecuredCopyToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
        } catch (err) {
            console.error('Unable to copy to clipboard', err);
        }
        document.body.removeChild(textArea);
    }

    function contextMenu(e) {

        contextMenuClearAll()

        let menuItems = CTX_MENU.default;
        let row = { id: false };

        if (e.target) {

            $(e.target).trigger("oncontextmenu", [row])
            $(e.target).addClass(`${PREFIX}-menu-tg`)

            if (document.activeElement == e.target)
                menuItems = CTX_MENU.column
        }

        const menu = $(`<${PREFIX}-context-menu>`)
        menu.css("position", "absolute")
        menu.css("top", e.pageY + "px")
        menu.css("left", e.pageX + "px")
        menu.css("max-width", "230px")
        menu.css("width", "100%")
        menu.css("background", "white")
        menu.css("box-shadow", "0 0 2px #00000044")
        menu.css("z-index", "1")


        menuItems.forEach(item => {
            let menuItem = $(`<div>${item.text}</div>`)
            menuItem.addClass(`${PREFIX}-menu-item`)

            menuItem.on("click", () => {
                if (typeof item.fun == "function") {
                    item.fun(row)
                }
            })
            menu.append(menuItem)
        })

        $(document.body).append(menu)
        //console.log(e);
        return false;
    }


    function contextMenuClearAll() {

        let findCall = $(`.${PREFIX}-menu-tg`)
        findCall.removeClass(`${PREFIX}-menu-tg`)

        let findMenu = $(`${PREFIX}-context-menu`)
        if (findMenu) {
            findMenu.remove()
        }
    }


    function getLabel(x) {

        const labels = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
        let extra = 0;
        let label = labels[0]

        for (let i = 0; i < x; i++) {

            let lkey = i % Number(labels.length)

            if (i % Number(labels.length) == 0 && i > 0) {
                extra++;
            }
            label = labels[lkey] + (extra > 0 ? extra : "")
        }
        return label
    }

    return {
        getAll: () => {

            const data = [];

            for (let x = 0; x < payload.data.length; x++) {
                let column = [];
                if (Array.isArray(payload.data[x])) {
                    for (let y = 0; y < payload.data[x].length; y++) {
                        column.push(payload.data[x][y] ? payload.data[x][y] : "")
                    }
                }
                data.push(column);
            }
            return data;
        },
        get: (column) => {

        }
    }
}

Object.defineProperty(Element.prototype, 'documentOffsetTop', {
    get: function () {
        return this.offsetTop + (this.offsetParent ? this.offsetParent.documentOffsetTop : 0);
    }
});

Object.defineProperty(Element.prototype, 'documentOffsetLeft', {
    get: function () {
        return this.offsetLeft + (this.offsetParent ? this.offsetParent.documentOffsetLeft : 0);
    }
});