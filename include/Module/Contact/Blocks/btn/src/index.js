

BlockRegister({

    init() {
        this.on('change:attributes:contact-type', this.handleTypeChange);
        this.on('change:attributes:use_template', this.handleUseTemplateChange);
        this.on('change', this.handleUpdate);
        this.handleUseTemplateChange.call(this);
    },

    updated(property, value, prevValue) {
        if (value == "selected") {
            this.handleSelected.call(this);
        }
    },

    handleSelected(e) {
        this.updateTraits.call(this);
    },
    handleTypeChange() {
        this.updateTraits.call(this);
    },

    updateTraits() {

        const fetchURL = this.defaults.fetchURL;
        const attributes = this.getAttributes();
        const contactType = attributes['contact-type'];
        const contactTrait = this.getTrait("contact");

        if (!fetchURL || !contactTrait) {
            return;
        }

        __.http.post(fetchURL, { 'contact-type': contactType })
            .then((response) => {
                if (!response.data || response.data.length === 0) {
                    contactTrait.set("value", '');
                    contactTrait.set("options", [{ id: '', name: '-- Select Contact --' }]);
                    return;
                }
                contactTrait.set("options", [{ id: '', name: '-- Select Contact --' }, ...response.data.map((item) => {
                    return {
                        id: `CT-${item.id}`,
                        name: item.name
                    }
                })]);
            })
            .catch((error) => {
                __.toast(error.message || "Something went wrong", 5, 'text-danger');
            });
    },

    handleUseTemplateChange() {

        const templateFetchURL = this.defaults.templateFetchURL;
        const attributes = this.getAttributes();
        const useTemplate = attributes.use_template || false;

        if (useTemplate) {

            this.removeTrait("contact-type");
            this.removeTrait("contact");

            this.addTrait({
                name: "template",
                type: "select",
                label: "Template",
                options: [
                    { id: '', name: '-- Select Template --' }
                ]
            });

            const templateTrait = this.getTrait("template");
            if (!templateFetchURL || !templateTrait) return;


            __.http.get(templateFetchURL)
                .then((response) => {
                    if (!response.data || response.data.length === 0) {
                        return;
                    }

                    templateTrait.set("options", [{
                        id: '',
                        name: '-- Select Template --'
                    }, ...(response.data || []).map((item) => {
                        return {
                            id: `TP-${item.id}`,
                            name: item.name
                        }
                    })]);
                })
                .catch((error) => {
                    __.toast(error.message || "Something went wrong", 5, 'text-danger');
                });
        } else {

            this.removeTrait("template");
            this.addTrait({
                type: "select",
                name: "contact-type",
                label: "Contact Type",
                options: [
                    { id: 'phone', label: 'Phone' },
                    { id: 'email', label: 'Email' },
                    { id: 'whatsapp', label: 'Whatsapp' }
                ]
            });
            this.addTrait({
                type: "select",
                name: "contact",
                label: "Contact",
                options: [
                    { id: '', name: '-- Select Contact --' }
                ]
            });
            this.updateTraits();
        }
    }
});