(function () {
    const datatable = document.getElementById("datatable");

    const columns = [
        { field: "id" },
        { label: "Username", field: "username" },
        { label: "First Name", field: "firstname" },
        { label: "Last Name", field: "lastname" },
        { label: "Email", field: "email" },
        { label: "Admin", field: "admin" },
        { field: "actions", sort: false },
    ];

    const options = {
        loading: false,
        entries: 25,
        entriesOptions: [10, 25, 50, 100],
        fullPagination: true,
        selectable: true,
        multi: true,
        loadingMessage: "Fetching data...",
    };

    let rows = [];

    const rowActions = (row) => `
        <button
            type="button"
            data-te-ripple-init
            data-te-ripple-color="light"
            data-user-id="${row.id}"
            class="delete-user inline-block rounded-full bg-red-600 p-2 uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-red-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-red-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-red-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]">
            <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M8.6 2.6A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4c0-.5.2-1 .6-1.4ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
            </svg>
        </button>
    `;

    const bulkDeleteButton = document.getElementById("bulk-delete-button");

    function enableButton(button) {
        button.removeAttribute("disabled");
        button.classList.remove("pointer-events-none");
    }

    function disableButton(button) {
        button.setAttribute("disabled", "disabled");
        button.classList.add("pointer-events-none");
    }

    datatable.addEventListener(
        "selectRows.te.datatable",
        ({ selectedRows }) => {
            if (!!selectedRows.length) {
                enableButton(bulkDeleteButton);
            } else {
                disableButton(bulkDeleteButton);
            }
        }
    );

    const asyncTable = new te.Datatable(datatable, { columns }, options, {
        loadingProgressBar:
            "progress-bar h-full w-[45%] bg-primary-400 dark:bg-primary-600",
        loadingMessage:
            "loading-message text-center text-neutral-500 font-ligh text-sm my-4 dark:text-neutral-400 w-full bg-gray-100 py-3",
    });

    const setLoading = () => {
        asyncTable.update({ rows }, { ...options, loading: true });
    };

    const loadData = ({ loading } = { loading: true }) => {
        if (loading) setLoading();

        axios.get("/users").then(({ data: { data } }) => {
            rows = data.map((row) =>
                columns.reduce(
                    (acc, curr) => {
                        if (curr.field !== "actions") {
                            acc[curr.field] = row[curr.field];
                        }

                        return acc;
                    },
                    {
                        actions: rowActions(row),
                    }
                )
            );

            asyncTable.update({ rows }, { ...options, loading: false });
        });
    };

    const onRenderDataTable = () => {
        const progressBar = datatable.querySelector(".progress-bar");
        progressBar?.parentNode?.parentNode?.classList.add(
            "progress-bar-container"
        );
        // progressBar?.parentNode?.parentNode?.querySelector)

        datatable.querySelectorAll(".delete-user").forEach((btn) => {
            btn.addEventListener("click", () => {
                console.log("delete", btn.attributes["data-user-id"].value);
                if (window.confirm("Do you really want to delete this user?")) {
                    const userId = btn.attributes["data-user-id"].value;
                    setLoading();

                    const deletedRow = Array.from(
                        datatable.querySelectorAll("& td[data-te-field='id']")
                    ).find((row) => row.textContent === userId);

                    deletedRow.parentNode.classList.add("row-deleted");
                    disableButton(deletedRow.parentNode.querySelector("input"));
                    disableButton(
                        deletedRow.parentNode.querySelector("button")
                    );

                    axios
                        .delete(`/users/${userId}`)
                        .then(({ data: { data } }) => {
                            console.log({ data });

                            loadData({ loading: false });
                        });
                }
            });
        });
    };

    datatable.addEventListener("render.te.datatable", onRenderDataTable);

    loadData();
})();
