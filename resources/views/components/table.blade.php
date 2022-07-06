<main class="main pt-2">
    <nav aria-label="breadcrumb" class="d-none d-lg-block">
        <ol class="breadcrumb bg-transparent p-0 justify-content-end">
            <li class="breadcrumb-item text-capitalize"><a href="https://bizenglish-app.com.vn/admin/dashboard">Admin</a></li>
            <li class="breadcrumb-item text-capitalize"><a href="https://bizenglish-app.com.vn/admin/user">Những người dùng</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Danh sách</li>
        </ol>
    </nav>

    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">Những người dùng</span>
            <small id="datatable_info_stack" class="animated fadeIn" style="display: inline-flex;">
                <div class="dataTables_info" id="crudTable_info" role="status" aria-live="polite">Từ 1 đến 4 of 4 hàng.</div>
                <a href="https://bizenglish-app.com.vn/admin/user" class="ml-1" id="crudTable_reset_button">Reset</a>
            </small>
        </h2>
    </div>

    <div class="container-fluid animated fadeIn">
        <!-- Default box -->
        <div class="row">
            <!-- THE ACTUAL CONTENT -->
            <div class="col-md-12">
                <div class="row mb-0">
                    <div class="col-sm-6">
                        <div class="d-print-none"></div>
                    </div>
                    <div class="col-sm-6">
                        <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none">
                            <div id="crudTable_filter" class="dataTables_filter">
                                <label><input type="search" class="form-control" placeholder="Tìm kiếm..." aria-controls="crudTable" /></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="crudTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row hidden">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6 d-print-none"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table
                                id="crudTable"
                                class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2 dataTable dtr-inline"
                                data-has-details-row="0"
                                data-has-bulk-actions="0"
                                cellspacing="0"
                                aria-describedby="crudTable_info"
                            >
                                <thead>
                                <tr>
                                    <th
                                        data-orderable="true"
                                        data-priority="0"
                                        data-visible-in-table="false"
                                        data-visible="true"
                                        data-can-be-visible-in-table="true"
                                        data-visible-in-modal="true"
                                        data-visible-in-export="true"
                                        data-force-export="false"
                                        class="sorting sorting_desc"
                                        tabindex="0"
                                        aria-controls="crudTable"
                                        rowspan="1"
                                        colspan="1"
                                        aria-label="

                                        Tên
                  : activate to sort column ascending"
                                        aria-sort="descending"
                                    >
                                        Tên
                                    </th>
                                    <th
                                        data-orderable="true"
                                        data-priority="1"
                                        data-visible-in-table="false"
                                        data-visible="true"
                                        data-can-be-visible-in-table="true"
                                        data-visible-in-modal="true"
                                        data-visible-in-export="true"
                                        data-force-export="false"
                                        class="sorting"
                                        tabindex="0"
                                        aria-controls="crudTable"
                                        rowspan="1"
                                        colspan="1"
                                        aria-label="

                                        Email
                  : activate to sort column ascending"
                                    >
                                        Email
                                    </th>
                                    <th
                                        data-orderable="true"
                                        data-priority="2"
                                        data-visible-in-table="false"
                                        data-visible="true"
                                        data-can-be-visible-in-table="true"
                                        data-visible-in-modal="true"
                                        data-visible-in-export="true"
                                        data-force-export="false"
                                        class="sorting"
                                        tabindex="0"
                                        aria-controls="crudTable"
                                        rowspan="1"
                                        colspan="1"
                                        aria-label="

                                        Phân quyền
                  : activate to sort column ascending"
                                    >
                                        Phân quyền
                                    </th>
                                    <th data-orderable="false" data-priority="1" data-visible-in-export="false" class="sorting_disabled" rowspan="1" colspan="1" aria-label="Hành động">Hành động</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr class="odd">
                                    <td class="sorting_1 dtr-control">
                                            <span>
                                                Phan Bích Hương
                                            </span>
                                    </td>
                                    <td>
                                            <span>
                                                phanbichhuong@gmail.com
                                            </span>
                                    </td>
                                    <td>
                                            <span>
                                                <span class="d-inline-flex">
                                                    Giáo viên
                                                </span>
                                            </span>
                                    </td>
                                    <td>
                                        <!-- Single edit button -->
                                        <a href="https://bizenglish-app.com.vn/admin/user/3/edit" class="btn btn-sm btn-link"><i class="la la-edit"></i> Sửa</a>

                                        <a href="javascript:void(0)" onclick="deleteEntry(this)" data-route="https://bizenglish-app.com.vn/admin/user/3" class="btn btn-sm btn-link" data-button-type="delete">
                                            <i class="la la-trash"></i> Xóa
                                        </a>

                                        <script>
                                            if (typeof deleteEntry != "function") {
                                                $("[data-button-type=delete]").unbind("click");

                                                function deleteEntry(button) {
                                                    // ask for confirmation before deleting an item
                                                    // e.preventDefault();
                                                    var route = $(button).attr("data-route");

                                                    swal({
                                                        title: "Cảnh báo",
                                                        text: "Bạn có chắc chắn muốn xóa mục này không?",
                                                        icon: "warning",
                                                        buttons: ["Hủy", "Xóa"],
                                                        dangerMode: true,
                                                    }).then((value) => {
                                                        if (value) {
                                                            $.ajax({
                                                                url: route,
                                                                type: "DELETE",
                                                                success: function (result) {
                                                                    if (result == 1) {
                                                                        // Redraw the table
                                                                        if (typeof crud != "undefined" && typeof crud.table != "undefined") {
                                                                            // Move to previous page in case of deleting the only item in table
                                                                            if (crud.table.rows().count() === 1) {
                                                                                crud.table.page("previous");
                                                                            }

                                                                            crud.table.draw(false);
                                                                        }

                                                                        // Show a success notification bubble
                                                                        new Noty({
                                                                            type: "success",
                                                                            text: "<strong>Mục đã bị xóa</strong><br>Mục đã được xóa thành công.",
                                                                        }).show();

                                                                        // Hide the modal, if any
                                                                        $(".modal").modal("hide");
                                                                    } else {
                                                                        // if the result is an array, it means
                                                                        // we have notification bubbles to show
                                                                        if (result instanceof Object) {
                                                                            // trigger one or more bubble notifications
                                                                            Object.entries(result).forEach(function (entry, index) {
                                                                                var type = entry[0];
                                                                                entry[1].forEach(function (message, i) {
                                                                                    new Noty({
                                                                                        type: type,
                                                                                        text: message,
                                                                                    }).show();
                                                                                });
                                                                            });
                                                                        } else {
                                                                            // Show an error alert
                                                                            swal({
                                                                                title: "KHÔNG bị xóa",
                                                                                text: "Đã xảy ra lỗi. Mục của bạn có thể chưa bị xóa.",
                                                                                icon: "error",
                                                                                timer: 4000,
                                                                                buttons: false,
                                                                            });
                                                                        }
                                                                    }
                                                                },
                                                                error: function (result) {
                                                                    // Show an alert with the result
                                                                    swal({
                                                                        title: "KHÔNG bị xóa",
                                                                        text: "Đã xảy ra lỗi. Mục của bạn có thể chưa bị xóa.",
                                                                        icon: "error",
                                                                        timer: 4000,
                                                                        buttons: false,
                                                                    });
                                                                },
                                                            });
                                                        }
                                                    });
                                                }
                                            }

                                            // make it so that the function above is run after each DataTable draw event
                                            // crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
                                        </script>
                                    </td>
                                </tr>
                                <tr class="even">
                                    <td class="sorting_1 dtr-control">
                                            <span>
                                                Phạm Quang Linh
                                            </span>
                                    </td>
                                    <td>
                                            <span>
                                                phamquanglinhdev@gmail.com
                                            </span>
                                    </td>
                                    <td>
                                            <span>
                                                <span class="d-inline-flex">
                                                    Học sinh
                                                </span>
                                            </span>
                                    </td>
                                    <td>
                                        <!-- Single edit button -->
                                        <a href="https://bizenglish-app.com.vn/admin/user/2/edit" class="btn btn-sm btn-link"><i class="la la-edit"></i> Sửa</a>

                                        <a href="javascript:void(0)" onclick="deleteEntry(this)" data-route="https://bizenglish-app.com.vn/admin/user/2" class="btn btn-sm btn-link" data-button-type="delete">
                                            <i class="la la-trash"></i> Xóa
                                        </a>

                                        <script>
                                            if (typeof deleteEntry != "function") {
                                                $("[data-button-type=delete]").unbind("click");

                                                function deleteEntry(button) {
                                                    // ask for confirmation before deleting an item
                                                    // e.preventDefault();
                                                    var route = $(button).attr("data-route");

                                                    swal({
                                                        title: "Cảnh báo",
                                                        text: "Bạn có chắc chắn muốn xóa mục này không?",
                                                        icon: "warning",
                                                        buttons: ["Hủy", "Xóa"],
                                                        dangerMode: true,
                                                    }).then((value) => {
                                                        if (value) {
                                                            $.ajax({
                                                                url: route,
                                                                type: "DELETE",
                                                                success: function (result) {
                                                                    if (result == 1) {
                                                                        // Redraw the table
                                                                        if (typeof crud != "undefined" && typeof crud.table != "undefined") {
                                                                            // Move to previous page in case of deleting the only item in table
                                                                            if (crud.table.rows().count() === 1) {
                                                                                crud.table.page("previous");
                                                                            }

                                                                            crud.table.draw(false);
                                                                        }

                                                                        // Show a success notification bubble
                                                                        new Noty({
                                                                            type: "success",
                                                                            text: "<strong>Mục đã bị xóa</strong><br>Mục đã được xóa thành công.",
                                                                        }).show();

                                                                        // Hide the modal, if any
                                                                        $(".modal").modal("hide");
                                                                    } else {
                                                                        // if the result is an array, it means
                                                                        // we have notification bubbles to show
                                                                        if (result instanceof Object) {
                                                                            // trigger one or more bubble notifications
                                                                            Object.entries(result).forEach(function (entry, index) {
                                                                                var type = entry[0];
                                                                                entry[1].forEach(function (message, i) {
                                                                                    new Noty({
                                                                                        type: type,
                                                                                        text: message,
                                                                                    }).show();
                                                                                });
                                                                            });
                                                                        } else {
                                                                            // Show an error alert
                                                                            swal({
                                                                                title: "KHÔNG bị xóa",
                                                                                text: "Đã xảy ra lỗi. Mục của bạn có thể chưa bị xóa.",
                                                                                icon: "error",
                                                                                timer: 4000,
                                                                                buttons: false,
                                                                            });
                                                                        }
                                                                    }
                                                                },
                                                                error: function (result) {
                                                                    // Show an alert with the result
                                                                    swal({
                                                                        title: "KHÔNG bị xóa",
                                                                        text: "Đã xảy ra lỗi. Mục của bạn có thể chưa bị xóa.",
                                                                        icon: "error",
                                                                        timer: 4000,
                                                                        buttons: false,
                                                                    });
                                                                },
                                                            });
                                                        }
                                                    });
                                                }
                                            }

                                            // make it so that the function above is run after each DataTable draw event
                                            // crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
                                        </script>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1 dtr-control">
                                            <span>
                                                Dl Dev Team
                                            </span>
                                    </td>
                                    <td>
                                            <span>
                                                dldev.education@gmail.com
                                            </span>
                                    </td>
                                    <td>
                                            <span>
                                                <span class="d-inline-flex">
                                                    Đối tác
                                                </span>
                                            </span>
                                    </td>
                                    <td>
                                        <!-- Single edit button -->
                                        <a href="https://bizenglish-app.com.vn/admin/user/4/edit" class="btn btn-sm btn-link"><i class="la la-edit"></i> Sửa</a>

                                        <a href="javascript:void(0)" onclick="deleteEntry(this)" data-route="https://bizenglish-app.com.vn/admin/user/4" class="btn btn-sm btn-link" data-button-type="delete">
                                            <i class="la la-trash"></i> Xóa
                                        </a>

                                        <script>
                                            if (typeof deleteEntry != "function") {
                                                $("[data-button-type=delete]").unbind("click");

                                                function deleteEntry(button) {
                                                    // ask for confirmation before deleting an item
                                                    // e.preventDefault();
                                                    var route = $(button).attr("data-route");

                                                    swal({
                                                        title: "Cảnh báo",
                                                        text: "Bạn có chắc chắn muốn xóa mục này không?",
                                                        icon: "warning",
                                                        buttons: ["Hủy", "Xóa"],
                                                        dangerMode: true,
                                                    }).then((value) => {
                                                        if (value) {
                                                            $.ajax({
                                                                url: route,
                                                                type: "DELETE",
                                                                success: function (result) {
                                                                    if (result == 1) {
                                                                        // Redraw the table
                                                                        if (typeof crud != "undefined" && typeof crud.table != "undefined") {
                                                                            // Move to previous page in case of deleting the only item in table
                                                                            if (crud.table.rows().count() === 1) {
                                                                                crud.table.page("previous");
                                                                            }

                                                                            crud.table.draw(false);
                                                                        }

                                                                        // Show a success notification bubble
                                                                        new Noty({
                                                                            type: "success",
                                                                            text: "<strong>Mục đã bị xóa</strong><br>Mục đã được xóa thành công.",
                                                                        }).show();

                                                                        // Hide the modal, if any
                                                                        $(".modal").modal("hide");
                                                                    } else {
                                                                        // if the result is an array, it means
                                                                        // we have notification bubbles to show
                                                                        if (result instanceof Object) {
                                                                            // trigger one or more bubble notifications
                                                                            Object.entries(result).forEach(function (entry, index) {
                                                                                var type = entry[0];
                                                                                entry[1].forEach(function (message, i) {
                                                                                    new Noty({
                                                                                        type: type,
                                                                                        text: message,
                                                                                    }).show();
                                                                                });
                                                                            });
                                                                        } else {
                                                                            // Show an error alert
                                                                            swal({
                                                                                title: "KHÔNG bị xóa",
                                                                                text: "Đã xảy ra lỗi. Mục của bạn có thể chưa bị xóa.",
                                                                                icon: "error",
                                                                                timer: 4000,
                                                                                buttons: false,
                                                                            });
                                                                        }
                                                                    }
                                                                },
                                                                error: function (result) {
                                                                    // Show an alert with the result
                                                                    swal({
                                                                        title: "KHÔNG bị xóa",
                                                                        text: "Đã xảy ra lỗi. Mục của bạn có thể chưa bị xóa.",
                                                                        icon: "error",
                                                                        timer: 4000,
                                                                        buttons: false,
                                                                    });
                                                                },
                                                            });
                                                        }
                                                    });
                                                }
                                            }

                                            // make it so that the function above is run after each DataTable draw event
                                            // crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
                                        </script>
                                    </td>
                                </tr>
                                <tr class="even">
                                    <td class="sorting_1 dtr-control">
                                            <span>
                                                BizEnglish Admin
                                            </span>
                                    </td>
                                    <td>
                                            <span>
                                                admin@biz.com
                                            </span>
                                    </td>
                                    <td>
                                            <span>
                                                <span class="d-inline-flex">
                                                    Quản trị
                                                </span>
                                            </span>
                                    </td>
                                    <td>
                                        <!-- Single edit button -->
                                        <a href="https://bizenglish-app.com.vn/admin/user/1/edit" class="btn btn-sm btn-link"><i class="la la-edit"></i> Sửa</a>

                                        <a href="javascript:void(0)" onclick="deleteEntry(this)" data-route="https://bizenglish-app.com.vn/admin/user/1" class="btn btn-sm btn-link" data-button-type="delete">
                                            <i class="la la-trash"></i> Xóa
                                        </a>

                                        <script>
                                            if (typeof deleteEntry != "function") {
                                                $("[data-button-type=delete]").unbind("click");

                                                function deleteEntry(button) {
                                                    // ask for confirmation before deleting an item
                                                    // e.preventDefault();
                                                    var route = $(button).attr("data-route");

                                                    swal({
                                                        title: "Cảnh báo",
                                                        text: "Bạn có chắc chắn muốn xóa mục này không?",
                                                        icon: "warning",
                                                        buttons: ["Hủy", "Xóa"],
                                                        dangerMode: true,
                                                    }).then((value) => {
                                                        if (value) {
                                                            $.ajax({
                                                                url: route,
                                                                type: "DELETE",
                                                                success: function (result) {
                                                                    if (result == 1) {
                                                                        // Redraw the table
                                                                        if (typeof crud != "undefined" && typeof crud.table != "undefined") {
                                                                            // Move to previous page in case of deleting the only item in table
                                                                            if (crud.table.rows().count() === 1) {
                                                                                crud.table.page("previous");
                                                                            }

                                                                            crud.table.draw(false);
                                                                        }

                                                                        // Show a success notification bubble
                                                                        new Noty({
                                                                            type: "success",
                                                                            text: "<strong>Mục đã bị xóa</strong><br>Mục đã được xóa thành công.",
                                                                        }).show();

                                                                        // Hide the modal, if any
                                                                        $(".modal").modal("hide");
                                                                    } else {
                                                                        // if the result is an array, it means
                                                                        // we have notification bubbles to show
                                                                        if (result instanceof Object) {
                                                                            // trigger one or more bubble notifications
                                                                            Object.entries(result).forEach(function (entry, index) {
                                                                                var type = entry[0];
                                                                                entry[1].forEach(function (message, i) {
                                                                                    new Noty({
                                                                                        type: type,
                                                                                        text: message,
                                                                                    }).show();
                                                                                });
                                                                            });
                                                                        } else {
                                                                            // Show an error alert
                                                                            swal({
                                                                                title: "KHÔNG bị xóa",
                                                                                text: "Đã xảy ra lỗi. Mục của bạn có thể chưa bị xóa.",
                                                                                icon: "error",
                                                                                timer: 4000,
                                                                                buttons: false,
                                                                            });
                                                                        }
                                                                    }
                                                                },
                                                                error: function (result) {
                                                                    // Show an alert with the result
                                                                    swal({
                                                                        title: "KHÔNG bị xóa",
                                                                        text: "Đã xảy ra lỗi. Mục của bạn có thể chưa bị xóa.",
                                                                        icon: "error",
                                                                        timer: 4000,
                                                                        buttons: false,
                                                                    });
                                                                },
                                                            });
                                                        }
                                                    });
                                                }
                                            }

                                            // make it so that the function above is run after each DataTable draw event
                                            // crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
                                        </script>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th rowspan="1" colspan="1">
                                        Tên
                                    </th>
                                    <th rowspan="1" colspan="1">
                                        Email
                                    </th>
                                    <th rowspan="1" colspan="1">
                                        Phân quyền
                                    </th>
                                    <th rowspan="1" colspan="1">Hành động</th>
                                </tr>
                                </tfoot>
                            </table>
                            <div id="crudTable_processing" class="dataTables_processing card" style="display: none;"><img src="https://bizenglish-app.com.vn/packages/backpack/crud/img/ajax-loader.gif" alt="Processing..." /></div>
                        </div>
                    </div>
                    <div class="row mt-2 d-print-none">
                        <div class="col-sm-12 col-md-4">
                            <div class="dataTables_length" id="crudTable_length">
                                <label>
                                    <select name="crudTable_length" aria-controls="crudTable" class="custom-select custom-select-sm form-control form-control-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="-1">Tất cả </option>
                                    </select>
                                    mục trên mỗi trang
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-0 col-md-4 text-center"></div>
                        <div class="col-sm-12 col-md-4">
                            <div class="dataTables_paginate paging_simple_numbers" id="crudTable_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item previous disabled" id="crudTable_previous"><a href="#" aria-controls="crudTable" data-dt-idx="0" tabindex="0" class="page-link">&lt;</a></li>
                                    <li class="paginate_button page-item active"><a href="#" aria-controls="crudTable" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                                    <li class="paginate_button page-item next disabled" id="crudTable_next"><a href="#" aria-controls="crudTable" data-dt-idx="2" tabindex="0" class="page-link">&gt;</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
