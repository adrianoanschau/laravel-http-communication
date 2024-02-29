import "./bootstrap";
import * as datefns from "date-fns";
import { Modal, Ripple, initTE } from "tw-elements";
import $ from "jquery";
import { DataTable } from "./datatable";

import Alpine from "alpinejs";

window.Alpine = Alpine;
window.$ = $;
window.DataTable = DataTable;

Alpine.start();

initTE({ Modal, Ripple });

window.formatDate = (dateString, dateFormat) => {
    return datefns.format(new Date(dateString), dateFormat ?? "dd/MM/yyyy");
};
