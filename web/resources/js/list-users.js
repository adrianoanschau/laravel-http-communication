(function () {
    const datatable = document.getElementById("datatable");

    const columns = [
        { field: "id", width: 0 },
        { label: "Username", field: "username", width: 180, fixed: true },
        { label: "First Name", field: "firstname", width: 150 },
        { label: "Last Name", field: "lastname", width: 150 },
        { label: "Email", field: "email", width: 280 },
        {
            label: "Admin",
            field: "admin",
            width: 100,
            format: (cell, value) => {
                cell.classList.add("text-center");
                cell.textContent = "";

                if (value === true) {
                    const flag = document.createElement("span");
                    flag.setAttribute(
                        "class",
                        "inline-block whitespace-nowrap rounded-[0.27rem] bg-primary px-2 rounded text-[13px] font-black text-center align-baseline text-[0.75em] font-bold leading-none text-white"
                    );
                    flag.textContent = "✓";

                    cell.append(flag);
                }
            },
        },
        {
            label: "Created At",
            field: "created_at",
            width: 200,
            format: (cell, value) => {
                cell.textContent = formatDate(value, "dd/MM/yyyy HH:ii:ss");
            },
        },
        {
            label: "Updated At",
            field: "updated_at",
            width: 200,
            format: (cell, value) => {
                cell.textContent = formatDate(value, "dd/MM/yyyy HH:ii:ss");
            },
        },
        { field: "actions", sort: false, width: 120, fixed: "right" },
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
    let selectedRowsIds = [];

    const rowActions = (row) => `
        <button
            type="button"
            data-te-ripple-init
            data-te-ripple-color="light"
            data-user-id="${row.id}"
            data-te-toggle="modal"
            data-te-target="#editUserFormModal"
            class="edit-user inline-block rounded-full bg-primary p-2 uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]">
            <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10.8 17.8-6.4 2.1 2.1-6.4m4.3 4.3L19 9a3 3 0 0 0-4-4l-8.4 8.6m4.3 4.3-4.3-4.3m2.1 2.1L15 9.1m-2.1-2 4.2 4.2"/>
            </svg>
        </button>
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
                selectedRowsIds = selectedRows.map((row) => row.id);
                enableButton(bulkDeleteButton);
            } else {
                selectedRowsIds = [];
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
        options.loading = true;
        datatable.classList.add("loading");
        document.querySelector(".disable-table").classList.remove("hidden");

        asyncTable.update({ rows }, options);
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

            options.loading = false;
            datatable.classList.remove("loading");
            document.querySelector(".disable-table").classList.add("hidden");
            asyncTable.update({ rows }, options);
        });
    };

    const createUserFormModalForm = document.getElementById(
        "createUserFormModalForm"
    );

    const editUserFormModalForm = document.getElementById(
        "editUserFormModalForm"
    );

    function submitUserFormData(form, userId = null, previousData = null) {
        return (event) => {
            event.preventDefault();

            const method = form.getAttribute("method");
            const action = form.getAttribute("action");
            const data = Object.fromEntries(new FormData(form));

            if (previousData) {
                Object.entries(previousData).forEach(([key, value]) => {
                    if (data[key] === value) {
                        delete data[key];
                    }
                });
            }

            if (!Object.keys(data).length) return;

            axios
                .request({
                    url: action.replace("userId", userId),
                    method,
                    data,
                })
                .then(({ data }) => {
                    console.log({ data });

                    window.location.reload();
                });
        };
    }

    function flagRowsForDelete(ids = []) {
        const deletedRows = Array.from(
            datatable.querySelectorAll("& td[data-te-field='id']")
        ).filter((row) => ids.includes(row.textContent));

        deletedRows.forEach((row) => {
            row.parentNode.classList.add("row-deleted");
            disableButton(row.parentNode.querySelector("input"));
            disableButton(row.parentNode.querySelector("button"));
        });
    }

    createUserFormModalForm.addEventListener(
        "submit",
        submitUserFormData(createUserFormModalForm)
    );

    const onRenderDataTable = () => {
        const progressBar = datatable.querySelector(".progress-bar");
        progressBar?.parentNode?.parentNode?.classList.add(
            "progress-bar-container"
        );

        const editUserFormModalFormListenerMaker = (userId) => {
            const userData = rows.find((row) => row.id === userId);

            const firstname = editUserFormModalForm.querySelector("#firstname");
            const lastname = editUserFormModalForm.querySelector("#lastname");
            const email = editUserFormModalForm.querySelector("#email");

            firstname.value = userData.firstname;
            lastname.value = userData.lastname;
            email.value = userData.email;

            return submitUserFormData(editUserFormModalForm, userId, userData);
        };

        let editUserFormModalFormListener = () => {};

        datatable.querySelectorAll(".edit-user").forEach((btn) => {
            btn.addEventListener("click", () => {
                if (options.loading) return;

                const userId = btn.attributes["data-user-id"].value;

                editUserFormModalForm.removeEventListener(
                    "submit",
                    editUserFormModalFormListener
                );

                editUserFormModalFormListener =
                    editUserFormModalFormListenerMaker(userId);

                editUserFormModalForm.addEventListener(
                    "submit",
                    editUserFormModalFormListener
                );
            });
        });

        datatable.querySelectorAll(".delete-user").forEach((btn) => {
            btn.addEventListener("click", () => {
                if (options.loading) return;

                if (window.confirm("Do you really want to delete this user?")) {
                    const userId = btn.attributes["data-user-id"].value;

                    setLoading();
                    flagRowsForDelete([userId]);

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

    bulkDeleteButton.addEventListener("click", () => {
        if (
            window.confirm(
                `Do you really want to delete ${selectedRowsIds.length} users?`
            )
        ) {
            if (options.loading) return;

            setLoading();
            flagRowsForDelete(selectedRowsIds);

            axios
                .delete(`/users/bulk/${selectedRowsIds.join(";")}`)
                .then(({ data: { data } }) => {
                    console.log(data);

                    loadData({ loading: false });
                });
        }
    });

    datatable.addEventListener("render.te.datatable", onRenderDataTable);

    loadData();
})();
