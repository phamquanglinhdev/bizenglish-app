<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backpack Crud Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the CRUD interface.
    | You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    // Forms
    'save_action_save_and_new' => 'Lưu và mục mới',
    'save_action_save_and_edit' => 'Lưu và chỉnh sửa mục này',
    'save_action_save_and_back' => 'Lưu và quay lại',
    'save_action_save_and_preview' => 'Lưu và xem trước',
    'save_action_changed_notification' => 'Hành vi mặc định sau khi lưu đã được thay đổi.',

    // Create form
    'add'                 => 'Thêm',
    'back_to_all'         => 'Quay về ',
    'cancel'              => 'Hủy',
    'add_a_new'           => 'Thêm mới một ',

    // Edit form
    'edit'                 => 'Sửa',
    'save'                 => 'Lưu',

    // Translatable models
    'edit_translations' => 'Translation',
    'language'          => 'Language',

    // CRUD table view
    'all'                       => 'Tất cả ',
    'in_the_database'           => 'trong cơ sở dữ liệu',
    'list'                      => 'Danh sách',
    'reset'                     => 'Reset',
    'actions'                   => 'Hành động',
    'preview'                   => 'Xem trước',
    'delete'                    => 'Xóa',
    'admin'                     => 'Admin',
    'details_row'               => 'Đây là hàng chi tiết. Sửa đổi theo ý bạn',
    'details_row_loading_error' => 'There was an error loading the details. Please retry.',
    'clone'                     => 'Clone',
    'clone_success'             => '<strong>Entry cloned</strong><br>A new entry has been added, with the same information as this one.',
    'clone_failure'             => '<strong>Cloning failed</strong><br>The new entry could not be created. Please try again.',

    // Confirmation messages and bubbles
    'delete_confirm' => 'Bạn có chắc chắn muốn xóa mục này không?',
    'delete_confirmation_title' => 'Mục đã bị xóa',
    'delete_confirmation_message' => 'Mục đã được xóa thành công.',
    'delete_confirmation_not_title' => 'KHÔNG bị xóa',
    'delete_confirmation_not_message' => "Đã xảy ra lỗi. Mục của bạn có thể chưa bị xóa.",
    'delete_confirmation_not_deleted_title' => 'Chưa xóa',
    'delete_confirmation_not_deleted_message' => 'Không có gì xảy ra. Đồ của bạn an toàn. ',

    // Hành động số lượng lớn
    'Bul_no_entries_selected_title' => 'Không có mục nhập nào được chọn',
    'Bul_no_entries_selected_message' => "Vui lòng chọn một hoặc nhiều mục để thực hiện một hành động hàng loạt đối với chúng.",

    // Xóa hàng loạt
    'Bul_delete_are_you_sure' => 'Bạn có chắc chắn muốn xóa những: mục nhập số này không?',
    'Bul_delete_sucess_title' => "Đã xóa các mục nhập",
    'Bul_delete_sucess_message' => 'item đã bị xóa',
    'Bul_delete_error_title' => "Xóa không thành công",
    'Bul_delete_error_message' => 'Không thể xóa một hoặc nhiều mục',

    // Sao chép hàng loạt
    'Bul_clone_are_you_sure' => 'Bạn có chắc chắn muốn sao chép các mục: number này không?',
    'Bulk_clone_sucess_title' => "Đã sao chép các mục nhập",
    'Bulk_clone_sucess_message' => "các mục đã được sao chép.",
    'Bulk_clone_error_title' => "Sao chép không thành công",
    'Bul_clone_error_message' => "Không thể tạo một hoặc nhiều mục nhập. Vui lòng thử lại.",

    // Lỗi Ajax
    'ajax_error_title' => 'Lỗi',
    'ajax_error_text' => 'Lỗi khi tải trang. Vui lòng làm mới trang. ',

    // DataTables translation
    'emptyTable'     => 'Không có dữ liêu',
    'info'           => 'Từ _START_ đến _END_ of _TOTAL_ hàng',
    'infoEmpty'      => 'Không có hàng',
    'infoFiltered'   => '(lọc từ _MAX_ tổng số dòng)',
    'infoPostFix'    => '.',
    'thousands'      => ',',
    'lengthMenu'     => '_MENU_ mục trên mỗi trang',
    'loadingRecords' => 'Loading...',
    'processing'     => 'Processing...',
    'search'         => 'Tìm kiếm',
    'zeroRecords'    => 'Không tìm thấy dữ liệu',
    'paginate'       => [
        'first'    => 'Đầu tiên',
        'last'     => 'Cuối',
        'next'     => 'Tiếp',
        'previous' => 'Trước',
    ],
    'aria' => [
        'sortAscending'  => ': activate to sort column ascending',
        'sortDescending' => ': activate to sort column descending',
    ],
    'export' => [
        'export'            => 'Export',
        'copy'              => 'Copy',
        'excel'             => 'Excel',
        'csv'               => 'CSV',
        'pdf'               => 'PDF',
        'print'             => 'Print',
        'column_visibility' => 'Column visibility',
    ],

    // toàn cầu crud - lỗi
    'unauthorized_access' => 'Truy cập trái phép - bạn không có quyền cần thiết để xem trang này.',
    'please_fix' => 'Vui lòng sửa các lỗi sau:',

    // global crud - thành công / bong bóng thông báo lỗi
    'insert_success' => "Mục đã được thêm thành công.",
    'update_success' => 'Mục đã được sửa đổi thành công.',

    // CRUD reorder view
    'reorder'                      => 'Sắp sếp',
    'reorder_text'                 => 'Use drag&drop to reorder.',
    'reorder_success_title'        => 'Done',
    'reorder_success_message'      => 'Your order has been saved.',
    'reorder_error_title'          => 'Error',
    'reorder_error_message'        => 'Your order has not been saved.',

    // CRUD yes/no
    'yes' => 'Có',
    'no'  => 'Không',

    // CRUD filters navbar view
    'filters'        => 'Lọc',
    'toggle_filters' => 'Toggle filters',
    'remove_filters' => 'Xóa bộ lọc',
    'apply' => 'Xác nhận',

    //filters language strings
    'choose_file'=>"Chọn ảnh",
    'today' => 'Hôm nay',
    'yesterday' => 'Hôm qua',
    'last_7_days' => '7 Ngày trước',
    'last_30_days' => '30 Ngày trước',
    'this_month' => 'Tháng này',
    'last_month' => 'Tháng trước',
    'custom_range' => 'Tùy chỉnh',
    'weekLabel' => 'W',

    // Lĩnh vực
    'browse_uploads' => 'Chọn',
    'select_all' => 'Chọn tất cả',
    'select_files' => 'Chọn tệp',
    'select_file' => 'Chọn tệp',
    'clear' => 'Xóa',
    'page_link' => 'Liên kết trang',
    'page_link_placeholder' => 'https://example.com/your-desosystem-page',
    'Internal_link' => 'Liên kết nội bộ',
    'internal_link_placeholder' => 'Nội bộ slug.',
    'external_link' => 'Liên kết ngoài',
    'new_item' => 'Mặt hàng mới',
    'select_entry' => 'Chọn một mục nhập',
    'select_entries' => 'Chọn các mục nhập',

    // Trường bảng
    'table_cant_add' => 'Không thể thêm mới: entity',
    'table_max_reached' => 'Số lượng tối đa: đạt được tối đa',

    // Quản lý tập tin
    'file_manager' => 'Trình quản lý tệp',

    // InlineCreateOperation
    'Related_entry_create_success' => 'Mục nhập có liên quan đã được tạo và chọn.',
    'Related_entry_create_error' => 'Không thể tạo mục nhập có liên quan.',

    // được trả về khi không tìm thấy bản dịch nào trong các đầu vào đã chọn
    'blank_translations' => '(rỗng)',

];
