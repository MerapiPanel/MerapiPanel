import { toast } from "@il4mb/merapipanel/toast";

window.$('form#form-add-user').on("xhr-success", function (e, response) {

    toast('User added successfully', 30, 'text-success');
    window.$('form#form-add-user')[0].reset();

})
    .on("xhr-error", function (e, error) {

        toast(error.message || error.statusText || "Error", 5, 'text-danger');
    });