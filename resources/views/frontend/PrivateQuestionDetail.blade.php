@extends('frontend.layout.master')
<style>
    .loader {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 4px solid #38B89A;
        width: 40px;
        height: 40px;
        animation: spin 0.5s linear infinite;
        position: fixed;
        top: 57%;
        left: 59%;
        transform: translate(-50%, -50%);
        z-index: 9999;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }


    }

    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
        /* Adjust the max-width based on your requirements */
    }

    .box {
        border-radius: 10px;
        background: rgba(255, 255, 255, 1);
        padding: 8px 12px;
        align-items: flex-start;
        gap: 10px;
        flex-wrap: wrap;

    }

    .tooltip-inner {
        max-width: 1000px !important;
        white-space: pre-wrap !important;
        font-size: 15px !important;
    }
</style>
@section('content')
    <!--begin::Header-->
    <div id="kt_header" class="header">
        <!--begin::Container-->
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <!--begin::Wrapper-->
            <div class="d-flex d-lg-none align-items-center ms-n2 me-2">
                <!--begin::Aside mobile toggle-->
                <div class="btn btn-icon btn-active-icon-primary" id="kt_aside_toggle">
                    <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                    <span class="svg-icon svg-icon-1 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                                fill="black" />
                            <path opacity="0.3"
                                d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                                fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Aside mobile toggle-->
                <!--begin::Logo-->
                <a href="" class="d-flex align-items-center">
                    <img alt="Logo" src="{{ '../../public/frontend/media/sidebarLogo.svg' }}" class="h-20px" />
                </a>
                <!--end::Logo-->
            </div>

            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-2 pb-lg-0"
                data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                <!--begin::Heading-->
                <h1 class="d-flex flex-column text-dark fw-bolder my-0 fs-1">Question Detail
                </h1>

                <!--end::Heading-->
            </div>
            <!--end::Page title=-->
            <div class="d-flex">
                <!-- Approve Button -->
                @foreach ($question_from as $item)
                    @if ($item->status == 0)
                        <button style="background-color: #38B89A; color:#FFFFFF" type="button" class="btn me-3"
                            data-bs-toggle="modal" data-bs-target="#approveModal">
                            Approve
                        </button>
                        <button style="background-color: #EA4335; color:#FFFFFF" type="button" class="btn me-3"
                            data-bs-toggle="modal" data-bs-target="#declineModal">
                            Decline
                        </button>
                    @else
                        <button style="background-color: #38B89A1A; color:#38B89A;" type="button" class="btn me-3"
                            disabled>
                            Approve
                        </button>
                        <button style="background-color: #EA43351A; color:#EA4335;" type="button" class="btn me-3"
                            disabled>
                            Decline
                        </button>
                    @endif
                @endforeach

            </div>

            <!-- Approve Modal -->
            <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog mw-500px">
                    <div class="modal-content">
                        <div class="modal-header pb-0 border-0 d-flex justify-content-between">
                            <p class="fs-1 fw-bold mx-auto">Question’s Reply</p>
                            <button type="button" class="btn btn-lg btn-icon btn-active-color-dark" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span class="svg-icon svg-icon-1 w-25">
                                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M33.6663 17.0002C33.6663 26.2049 26.2044 33.6668 16.9997 33.6668C7.79493 33.6668 0.333008 26.2049 0.333008 17.0002C0.333008 7.79542 7.79493 0.333496 16.9997 0.333496C26.2044 0.333496 33.6663 7.79542 33.6663 17.0002ZM11.9491 11.9496C12.4372 11.4614 13.2287 11.4614 13.7168 11.9496L16.9996 15.2324L20.2824 11.9496C20.7705 11.4615 21.562 11.4615 22.0502 11.9496C22.5383 12.4378 22.5383 13.2292 22.0502 13.7174L18.7674 17.0001L22.0501 20.2829C22.5383 20.771 22.5383 21.5625 22.0501 22.0506C21.562 22.5388 20.7705 22.5388 20.2824 22.0506L16.9996 18.7679L13.7169 22.0507C13.2287 22.5388 12.4372 22.5388 11.9491 22.0507C11.4609 21.5625 11.4609 20.7711 11.9491 20.2829L15.2319 17.0001L11.9491 13.7173C11.4609 13.2292 11.4609 12.4377 11.9491 11.9496Z"
                                            fill="#1C274C" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                        <div class="modal-body pt-4">
                            <form action="{{ route('admin.approve') }}" method="POST">
                                @csrf
                                <input type="hidden" name="query_id" value="{{ $detail->id }}">
                                <div class="mb-5">
                                    <label for="replyInput" class="form-label fw-bold fs-3">Reply</label>

                                    <textarea id="replyInput" name="reply" class="form-control form-control-solid" placeholder="Add Reply" rows="7"
                                        style="resize: none;" required oninput="validateInput(this)"></textarea>
                                </div>
                                <div class="d-flex justify-content-center align-content-center pt-2 mt-10">
                                    <button type="submit" class="btn btn-lg col-12"
                                        style="background-color: #38B89A; color:#FFFFFF;">
                                        <span class="indicator-label">Send</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decline Modal -->
            <div class="modal fade" id="declineModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog mw-500px">
                    <div class="modal-content">
                        <div class="modal-header pb-0 border-0 d-flex justify-content-between">
                            <p class="fs-1 fw-bold mx-auto">Reason For Rejection</p>
                            <button type="button" class="btn btn-lg btn-icon btn-active-color-dark" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span class="svg-icon svg-icon-1 w-25">
                                    <!-- Close Icon -->
                                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M33.6663 17.0002C33.6663 26.2049 26.2044 33.6668 16.9997 33.6668C7.79493 33.6668 0.333008 26.2049 0.333008 17.0002C0.333008 7.79542 7.79493 0.333496 16.9997 0.333496C26.2044 0.333496 33.6663 7.79542 33.6663 17.0002ZM11.9491 11.9496C12.4372 11.4614 13.2287 11.4614 13.7168 11.9496L16.9996 15.2324L20.2824 11.9496C20.7705 11.4615 21.562 11.4615 22.0502 11.9496C22.5383 12.4378 22.5383 13.2292 22.0502 13.7174L18.7674 17.0001L22.0501 20.2829C22.5383 20.771 22.5383 21.5625 22.0501 22.0506C21.562 22.5388 20.7705 22.5388 20.2824 22.0506L16.9996 18.7679L13.7169 22.0507C13.2287 22.5388 12.4372 22.5388 11.9491 22.0507C11.4609 21.5625 11.4609 20.7711 11.9491 20.2829L15.2319 17.0001L11.9491 13.7173C11.4609 13.2292 11.4609 12.4377 11.9491 11.9496Z"
                                            fill="#1C274C" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                        <div class="modal-body pt-4">
                            <form action="{{ route('admin.decline') }}" method="POST">
                                @csrf
                                <input type="hidden" name="question_id" value="{{ $detail->id }}">

                                <div class="mb-5">
                                    <label for="reasonInput" class="form-label fw-bold fs-3">Reason</label>
                                    <textarea id="reasonInput" name="reason" class="form-control form-control-solid" placeholder="Add Reason"
                                        rows="7" style="resize: none;" required oninput="validateInput(this)"></textarea>
                                </div>

                                <div class="d-flex justify-content-center align-content-center pt-2 mt-10">
                                    <button type="submit" class="btn btn-lg col-12"
                                        style="background-color: #EA4335; color:#FFFFFF;">
                                        <span class="indicator-label">Send</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-fluid" id="">
            <div class="row mb-5">
                <div class="col-12 fs-2 fw-bold text-dark mb-5">
                    Questioned By
                </div>
                <div class="col-12 fs-2 fw-bolder text-dark d-flex pb-5">
                    @if ($detail->questioned_by->image == '')
                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                style="height: 80px; width:80px;" />
                        </div>
                    @else
                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ asset('public/storage/' . $detail->questioned_by->image) }}" alt="image"
                                style="height: 80px; width:80px; object-fit: cover;" />
                        </div>
                    @endif
                    <div class="ms-2">
                        <div class="fs-5 fw-normal text-success">
                            {{ $detail->questioned_by->user_type }}
                        </div>
                        <div class="fs-4">
                            {{ $detail->questioned_by->name }}
                        </div>
                        <div class="fs-6 fw-normal text-muted">
                            {{ $detail->questioned_by->email }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-3 fs-2 fw-bold text-dark">
                    Category
                </div>
                <div class="col-9">
                    <div class="row">
                        @foreach ($detail->category as $data)
                            <div class="col-3 badge badge-light fw-normal fs-4 ms-3 mb-3"
                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                {{ $data }}
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="row mb-5">
                <div class="col-3 fs-2 fw-bold text-dark">
                    Fiqa
                </div>
                <div class="col-9 fs-2 fw-bold text-muted">
                    {{ $detail->fiqa }}
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-3 fs-2 fw-bold text-dark">
                    Date
                </div>
                <div class="col-9 fs-2 fw-bold text-muted">
                    {{ \Carbon\Carbon::parse($detail->created_at)->format('M d, Y') }}
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-12 fs-2 fw-bold text-dark mb-7">
                    Question
                </div>

                <div class="col-12 fs-2 fw-normal text-dark mb-5">
                    {{ $detail->question }}
                </div>

                <div class="col-12 fs-2 fw-bolder text-dark pb-2">
                    <span>Admin's Reply</span>
                </div>
                <div class="col-12">
                    @if ($detail->adminReply)
                        <div class="col-12 fs-4 fw-bold text-black pb-10" data-reply-id="{{ $detail->adminReply->id }}">
                            {{ $detail->adminReply->reply }}
                        </div>
                    @else
                        <div class="col-12 fs-1 fw-bold text-muted pb-10 text-center mt-10 mb-10">
                            You haven't replied to this question yet!
                        </div>
                    @endif
                </div>

                <div class="col-12 fs-2 fw-bolder text-dark mb-5">
                    Questioned From
                </div>
            </div>

            <div class="row">
                @foreach ($question_from as $row)
                    <div class="col-6 mb-6">
                        <div class="box">
                            <div class="row pb-5">
                                <div class="col-9">
                                    <div class="d-flex">
                                        @if ($row->mufti_detail->image == '')
                                            <div
                                                class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                                <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                                    style="height: 80px; width:80px;" />
                                            </div>
                                        @else
                                            <div
                                                class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                                <img src="{{ asset('public/storage/' . $row->mufti_detail->image) }}"
                                                    alt="image" style="height: 80px; width:80px; object-fit: cover;" />
                                            </div>
                                        @endif
                                        <div class="ms-3">
                                            <div class="fw-bold fs-3 text-success">
                                                {{ $row->mufti_detail->fiqa }}
                                            </div>
                                            <div class="fw-bolder fs-1 text-black pt-1">
                                                {{ $row->mufti_detail->name }}
                                            </div>
                                            <div class="text-muted fs-6 pt-1"
                                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 400px;">
                                                @foreach ($row->mufti_detail['interests'] as $data)
                                                    {{ $data['interest'] }}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($row->status == 0)
                                    <div class="col-3 badge badge-light-warning fs-3 d-flex justify-content-end align-content-end"
                                        style="height:fit-content; width: fit-content;">
                                        Pending
                                    </div>
                                @elseif($row->status == 1)
                                    <div class="col-3 badge badge-light-success fs-3 d-flex justify-content-end align-content-end"
                                        style="height:fit-content; width: fit-content;">
                                        Accepted
                                    </div>
                                @else
                                    <div class="col-3 badge badge-light-danger fs-3 d-flex justify-content-end align-content-end"
                                        style="height:fit-content; width: fit-content;" data-bs-html="true"
                                        data-bs-toggle="tooltip" title="<strong>Reason:</strong> {{ $row->reason }}">
                                        Rejected
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
    <!--end::Container-->

    </div>
    <!--end::Content-->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Initialize all tooltips on the page with HTML support
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            html: true
        });
    });
</script>
<script>
    var question_id = @json($question_id);
    var currentPage = 1;

    function loadVerificationData(page, search = '', sortingOption = '') {
        $('#loader').removeClass('d-none');
        $.ajax({
            url: '{{ route('getQuestionComments', ['id' => ':id']) }}'.replace(':id', question_id) + '?page=' +
                page + '&search=' + encodeURIComponent(search) + '&sorting=' +
                sortingOption,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                var users = response.users;
                var userCount = response.userCount;
                $('#user-count').text(userCount);
                var tableBody = $('#verification-table-body');

                tableBody.empty();

                if (users.data.length === 0) {
                    // If no users found, display a message in a new row
                    var noUserRow = `
            <tr>
                <td colspan="6" class="text-center pt-10 fw-bolder fs-2">No Questions found</td>
            </tr>
        `;
                    tableBody.append(noUserRow);
                } else {

                    var count = (users.data.length > 0) ? (users.current_page - 1) * users.per_page : 0;
                    $.each(users.data, function(index, row) {
                        var modifiedSerialNumber = pad(count + 1, 2,
                            '0'); // Calculate modified serial number
                        var newRow = `
                    <tr>
                        <td class="d-flex align-items-center">
                            ${row.user.image ? `
                                <div class="symbol symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label">
                                        <img src="{{ asset('public/storage/') }}/${row.user.image}" alt="image" class="w-100" />
                                    </div>
                                </div>` : `
                                <div class="symbol symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label">
                                        <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image" class="w-100" />
                                    </div>
                                </div>`}

                            <div class="d-flex flex-column">
                                <div class="text-gray-800 mb-1">
                                    ${row.user.name}
                                </div>
                                <span> #${row.user.email}</span>
                            </div>
                        </td>

                        <td class="px-4"" >${row.user.user_type}</td>
                        <td class="px-4"">${row.registration_date}</td>
                        <td class="px-4">${row.comment}</td>
                    </tr>
                `;
                        tableBody.append(newRow);
                        count++;
                    });

                    // Function to pad numbers with zeros
                    function pad(number, length, character) {
                        var str = '' + number;
                        while (str.length < length) {
                            str = character + str;
                        }
                        return str;
                    }
                    // Update pagination links

                    var paginationLinks = $('#pagination-links');
                    paginationLinks.empty();

                    var totalPages = users.last_page;

                    // Render "Previous" button
                    var previousLink = `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
                    </li>`;
                    paginationLinks.append(previousLink);

                    // Add pagination links to the page
                    for (var i = 1; i <= totalPages; i++) {
                        // Render ellipsis if there are many pages
                        if (totalPages > 7 && (i < currentPage - 2 || i > currentPage + 2)) {
                            if (i === 1 || i === totalPages) {
                                var pageLink =
                                    `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                                paginationLinks.append(pageLink);
                            }
                            continue;
                        }

                        var pageLink = `<li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>`;
                        paginationLinks.append(pageLink);
                    }

                    // Render "Next" button
                    var nextLink = `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>
                </li>`;
                    paginationLinks.append(nextLink);
                }
                $('#loader').addClass('d-none');

            },
        });
    }

    // Handle page clicks
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        currentPage = $(this).data('page');
        var searchTerm = $('#global-search').val();
        loadVerificationData(currentPage, searchTerm);
    });
    $(document).on('click', '#apply-filter-button', function(e) {
        e.preventDefault();
        var searchTerm = $('#global-search').val();
        var sortingOption = $('#request-date-filter').val();
        loadVerificationData(currentPage, searchTerm, sortingOption);
    });

    $(document).on('click', '#reset-filter-button', function(e) {
        e.preventDefault();
        $('#request-date-filter').val('').trigger('change');
        loadVerificationData(currentPage);
    });
    $(document).ready(function() {
        // Handle global search input
        $('#global-search').on('input', function() {
            var searchTerm = $(this).val();
            loadVerificationData(1, searchTerm);
        });
    });
    $(document).ready(function() {
        loadVerificationData(currentPage);
    });

    function validateInput(input) {
        input.value = input.value.replace(/^\s+/, '');
        input.value = input.value.replace(/[^0-9a-zA-Z\s]/g, '');
    }
    // function confirmDeletePrivate(event) {
    //     event.preventDefault();

    //     Swal.fire({
    //         title: 'Delete Private Question',
    //         text: 'Are you sure you want to delete this private question?',
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#38B89A',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, Sure!',
    //         cancelButtonText: 'Cancel',
    //         willOpen: () => {
    //             const cancelButton = Swal.getCancelButton();
    //         }
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             Swal.fire(
    //                 'Deleted!',
    //                 'The private question has been deleted.',
    //                 'success'
    //             ).then(() => {
    //                 window.location.href = event.target.closest('a').href;
    //             });
    //         }
    //     });
    // }
</script>
