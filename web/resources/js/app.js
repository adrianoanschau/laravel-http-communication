import "./bootstrap";
import Alpine from "alpinejs";
import * as datefns from "date-fns";
import { Modal, Ripple, initTE } from "tw-elements";
import $ from "jquery";
import toastr from "toastr";
import { DataTable } from "./datatable";

window.Alpine = Alpine;
window.$ = $;
window.DataTable = DataTable;

Alpine.start();

initTE({ Modal, Ripple });

window.formatDate = (dateString, dateFormat) => {
    return datefns.format(new Date(dateString), dateFormat ?? "dd/MM/yyyy");
};

$("form[async]").on("submit", (event) => {
    event.preventDefault();

    const form = $(event.currentTarget);
    const method = form.attr("method") ?? "post";
    const resourceIdInput = form.find("#resourceId");
    let url = form.attr("action");

    if (!!resourceIdInput.length) {
        url = url.replace("resourceId", resourceIdInput.val());
    }

    const formData = Object.entries(
        Object.fromEntries(new FormData(form.get(0)))
    ).reduce((acc, [field, value]) => {
        if (!!form.find(`#${field}`).attr("dirty")) {
            acc[field] = value;
        }

        return acc;
    }, {});

    if (!Object.keys(formData).length) {
        toastr.warning("There was no change in the data");
        return;
    }

    form.find("button[type=submit]").attr("disabled", "disabled");

    axios
        .request({ url, method, data: formData })
        .then(({ data }) => {
            console.log(data);
            if (data?.message) toastr.success(data.message);

            if (form.attr("async") === "reload") {
                toastr.info("This page refresh in 2s");

                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        })
        .catch(({ response: { data } }) => {
            if (data?.message) toastr.error(data.message);

            form.find("button[type=submit]").removeAttr("disabled");

            Object.entries(data?.errors).forEach(([field, errors]) => {
                const input = form.find(`#${field}`);
                const errorsList = input.find(`+ ul`);
                if (errorsList) {
                    input
                        .addClass("border-red-600")
                        .removeClass("border-gray-300");
                    errorsList.html(
                        errors.map((error) => $("<li>").html(error))
                    );
                }
            });
        });
});

$("form[async] input").on("input", (event) => {
    const input = $(event.currentTarget);
    input.removeClass("border-red-600").addClass("border-gray-300");
    input.find("+ ul").html("");
    input.attr("dirty", true);
});
