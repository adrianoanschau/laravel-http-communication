import $ from "jquery";
import { Datatable as TeDatatable } from "tw-elements";
import { format } from "date-fns";

export class DataTable {
    $loadingProgressBar = "progress-bar h-full w-[45%] bg-primary-400";
    $loadingMessage =
        "loading-message text-center text-neutral-500 font-ligh text-sm my-4 w-full bg-gray-100 py-3";

    $rows = [];

    $options = {
        loading: false,
        entries: 25,
        entriesOptions: [10, 25, 50, 100],
        fullPagination: true,
        selectable: true,
        multi: true,
        loadingMessage: "Fetching data...",
    };

    $listeners = {
        loading: [],
        editRow: [],
        deleteRow: [],
        bulkDelete: [],
    };

    $selectedRowsIds = [];

    constructor(container, resourceUrl, columns, editModal, bulkDeleteBtn) {
        this.$container = $(container);
        this.$columns = columns;
        this.$resourceUrl = resourceUrl;
        this.$editModal = editModal;
        this.$bulkDeleteBtn = $(bulkDeleteBtn);

        this.$table = new TeDatatable(
            this.$container.get(0),
            { columns: this.$columns },
            this.$options,
            {
                loadingProgressBar: this.$loadingProgressBar,
                loadingMessage: this.$loadingMessage,
            }
        );

        this.$container
            .get(0)
            .addEventListener(
                "render.te.datatable",
                this.onRenderDataTable.bind(this)
            );

        this.$container
            .get(0)
            .addEventListener("selectRows.te.datatable", ({ selectedRows }) => {
                if (!!selectedRows.length) {
                    this.$selectedRowsIds = selectedRows.map((row) => row.id);
                    this.$bulkDeleteBtn.removeAttr("disabled");
                    this.$bulkDeleteBtn.removeClass("pointer-events-none");
                } else {
                    this.$selectedRowsIds = [];
                    this.$bulkDeleteBtn.attr("disabled", "disabled");
                    this.$bulkDeleteBtn.addClass("pointer-events-none");
                }
            });

        this.$bulkDeleteBtn.on("click", this.onBulkDelete.bind(this));
    }

    formatDate(dateFormat) {
        return (cell, dateString) => {
            cell.textContent = format(
                new Date(dateString),
                dateFormat ?? "dd/MM/yyyy"
            );
        };
    }

    on(property, callback) {
        if (Object.keys(this.$listeners).includes(property)) {
            this.$listeners[property].push(callback);
        }
    }

    callListeners(property, value) {
        this.$listeners[property].forEach((callback) => {
            callback(value);
        });
    }

    onRenderDataTable() {
        this.$container
            .find(".progress-bar")
            .parent()
            .parent()
            .addClass("progress-bar-container");

        this.$container.find(".edit-row-btn").each((_, btn) => {
            $(btn).on("click", () => {
                if (this.$options.loading) return;

                const rowId = $(btn).data("row-id");
                const rowData = this.$rows.find((row) => row.id === rowId);
                this.callListeners("editRow", {
                    rowId,
                    rowData,
                    columns: this.$columns,
                });
            });
        });

        this.$container.find(".delete-row-btn").each((_, btn) => {
            $(btn).on("click", () => {
                if (this.$options.loading) return;

                if (window.confirm("Do you really want to delete this item?")) {
                    const rowId = $(btn).data("row-id");
                    this.callListeners("deleteRow", { rowId });
                }
            });
        });
    }

    onBulkDelete() {
        if (
            window.confirm(
                `Do you really want to delete ${this.$selectedRowsIds.length} users?`
            )
        ) {
            if (this.$options.loading) return;

            this.callListeners("bulkDelete", {
                rowsIds: this.$selectedRowsIds,
            });
        }
    }

    load(loading = true) {
        if (loading) this.setLoading();

        axios.get(this.$resourceUrl).then(({ data: { data } }) => {
            this.$rows = data.map((row) =>
                this.$columns.reduce(
                    (acc, column, index) => {
                        if (column.field !== "actions") {
                            acc[column.field] = row[column.field];

                            if (column.type?.includes("date|")) {
                                const dateFormat = column.type.split("|")[1];

                                this.$columns[index].format =
                                    this.formatDate(dateFormat);
                            }
                        }

                        return acc;
                    },
                    {
                        actions: this.rowActions(row),
                    }
                )
            );

            this.$options.loading = false;

            this.update();

            this.callListeners("loading", false);
        });
    }

    setLoading() {
        this.$options.loading = true;

        this.update();
        this.callListeners("loading", true);
    }

    update() {
        this.$table.update(
            { rows: this.$rows, columns: this.$columns },
            this.$options
        );
    }

    rowActions(row) {
        const editBtn = $("<button>")
            .addClass(
                "edit-row-btn inline-block rounded-full bg-primary p-2 uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
            )
            .attr("data-te-ripple-init", true)
            .attr("data-te-ripple-color", "light")
            .attr("data-row-id", row.id)
            .attr("data-te-toggle", "modal")
            .attr("data-te-target", `${this.$editModal}`)
            .html(
                "<svg class='w-4 h-4 text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 24 24'><path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m10.8 17.8-6.4 2.1 2.1-6.4m4.3 4.3L19 9a3 3 0 0 0-4-4l-8.4 8.6m4.3 4.3-4.3-4.3m2.1 2.1L15 9.1m-2.1-2 4.2 4.2'/></svg>"
            );
        const deleteBtn = $("<button>")
            .addClass(
                "delete-row-btn inline-block rounded-full bg-red-600 p-2 uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-red-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-red-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-red-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
            )
            .attr("data-te-ripple-init", true)
            .attr("data-te-ripple-color", "light")
            .attr("data-row-id", row.id)
            .html(
                "<svg class='w-4 h-4 text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 24 24'><path fill-rule='evenodd' d='M8.6 2.6A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4c0-.5.2-1 .6-1.4ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z' clip-rule='evenodd'/></svg>"
            );

        return $("<div>").append([editBtn, deleteBtn]).html();
    }

    flagRowsForDelete(ids = []) {
        this.$container.find("td[data-te-field='id']").each((_, cell) => {
            if (ids.includes($(cell).text())) {
                $(cell).parent().addClass("row-deleted");
            }
        });
    }
}
