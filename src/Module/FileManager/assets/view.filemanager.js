const { parseHTML } = require("jquery");

const FileManager = {}
FileManager.container = {
    "root": null,
    "data": []
};

FileManager.setContainer = (rawdata) => {
    FileManager.container = JSON.parse(parseHTML(rawdata)[0].textContent)
}

FileManager.ItemFocusHandle = (element) => {

    let childs = $(element).parent().find($(element).prop("tagName").toLowerCase());
    childs.each((x) => { $(childs[x]).removeClass('active'); })

    $(element).addClass("active");

    $(element).parent().addClass('grid-cols-5')
    $(element).parent().removeClass('grid-cols-7')

    FileManager.offCanvas(FileManager.container.data[$(element).attr('data-fm-key')])
}

FileManager.offCanvas = (data) => {

    console.log(data);

    let element = $(`<div class="offcanvas offcanvas-end show ps-10 px-5" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header pb-5">
      <h5 class="offcanvas-title font-bold" id="offcanvasLabel">File Detail</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <table class='w-full text-sm'> 
        <tr class='align-baseline'>
            <td class='text-left whitespace-nowrap pr-5 pb-2'>File name</td>
            <td class='break-all pb-2'>${data.name}</td>
        </tr>
        <tr class='align-baseline'>
            <td class='text-left whitespace-nowrap pr-5 pb-2'>File path</td>
            <td class='break-all pb-2'>${data.path}</td>
        </tr>
        <tr class='align-baseline'>
            <td class='text-left whitespace-nowrap pr-5 pb-2'>Size</td>
            <td class='break-all pb-2'>${Math.floor(data.size/1024)}Kb</td>
        </tr>
        <tr class='align-baseline'>
            <td class='text-left whitespace-nowrap pr-5 pb-2'>Last modify</td>
            <td class='break-all pb-2'>${data.time}</td>
        </tr>
        <tr class='align-baseline'>
            <td class='text-left whitespace-nowrap pr-5 pb-2'>Mime type</td>
            <td class='break-all pb-2'>${data.type}</td>
        </tr>
      </table>
    </div>
  </div>`)

    $(document.body).find('.offcanvas-placement').html(element)
}


window.FileManager = FileManager;