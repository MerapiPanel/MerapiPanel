class Paragraph {

    api: any = null;
    paragraph: any = null;

    constructor({ api }) {
        this.api = api;
        this.paragraph = null;
    }


    static get toolbox() {
        return {
            title: 'Paragraph',
            icon: '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <path d="m10.753 6.1813h5.8187c.45978 0 .83125.37146.83125.83125 0 .45978-.37146.83125-.83125.83125h-.83124v9.1437c0 .45978-.37146.83125-.83125.83125s-.83125-.37146-.83125-.83125v-9.1437h-.83125v9.1437c0 .45978-.37146.83125-.83125.83125s-.83125-.37146-.83125-.83125v-2.4937h-.83125c-2.2963 0-4.1562-1.8599-4.1562-4.1562 0-2.2963 1.8599-4.1562 4.1562-4.1562z" style="stroke-width:.025976"/> </svg>'
        };
    }


    render() {

        this.paragraph = document.createElement('div');
        this.paragraph.type = 'p';
        this.paragraph.contentEditable = 'true';
        this.paragraph.classList.add('ce-paragraph', 'cdx-block');

        $(this.paragraph).attr('placeholder', 'Enter text here...');

        return this.paragraph;
    }

    renderSettings() {
        const settings = [
            {
                name: 'text start',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M2 12.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5m0-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/> </svg>`
            },
            {
                name: 'text center',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M4 12.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5m2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/> </svg>`
            },
            {
                name: 'text end',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-4-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5m4-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-4-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/> </svg>`
            },
            {
                name: 'text justify',
                icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-justify" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M2 12.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5m0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5m0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5m0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/> </svg>`
            }
        ];
        const wrapper = $(`<div>`);
        wrapper.addClass('flex justify-around items-center mb-4');

        settings.forEach(tune => {

            let paragraphAlign = "text " + ($(this.paragraph).css('text-align'));
            let button = document.createElement('div');

            button.classList.add('cdx-settings-button');
            button.title = tune.name;
            button.innerHTML = tune.icon;
            button.addEventListener('click', () => {
                wrapper.find('.cdx-settings-button--active').removeClass('cdx-settings-button--active');
                this._toggleTune(tune.name);
                button.classList.toggle('cdx-settings-button--active');
            });

            if (paragraphAlign === tune.name) {
                button.classList.add('cdx-settings-button--active');
            }

            wrapper.append(button);
        });

        return wrapper[0];
    }

    _toggleTune(tune: string) {

        switch (tune) {
            case 'text justify':
                $(this.paragraph).css('text-align', 'justify');
                break;
            case 'text start':
                $(this.paragraph).css('text-align', 'start');
                break;
            case 'text center':
                $(this.paragraph).css('text-align', 'center');
                break;
            case 'text end':
                $(this.paragraph).css('text-align', 'end');
                break;
            default:
                $(this.paragraph).css('text-align', 'left');
                break;
        }
    }

    save(blockElement: any) {

        return {
            text: ($(this.paragraph).text()).trim(),
            align: $(this.paragraph).css('text-align')
        }
    }
}


export default Paragraph;