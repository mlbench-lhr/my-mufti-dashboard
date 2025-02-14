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
                <h1 class="d-flex flex-column text-dark fw-bold my-0 fs-1">All Deletion Requests
                </h1>
                <h3 class="mt-4" style=" font-weight:400; ">Total Requests: <span class="fs-5" id="user-count"
                        style="font-weight:500 "> </span> </h3>
                <!--end::Heading-->
            </div>
            <div class="d-flex align-items-center position-relative ">
                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                            transform="rotate(45 17.0365 15.1223)" fill="black" />
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="black" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
                <input type="text" id="global-search" class="form-control form-control-solid w-250px ps-14"
                    placeholder="Search by name..." />
            </div>
            <!--end::Page title=-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-fluid" id="">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    @if (count($users))
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-dark fw-bold fs-5 text-uppercase gs-0">
                                        <th class="min-w-125px">User</th>
                                        <th class="min-w-80px text-center">Account Type</th>
                                        <th class="min-w-125px text-center">Email Address</th>
                                        <th class="min-w-125px text-center">Requested On</th>
                                        <th class="min-w-100px text-center">Action</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="text-gray-600 fw-bold" id="verification-table-body">
                                    {{-- ajax data is appending here  --}}
                                    <div id="loader" class="loader"></div>

                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                        <div class="pagination d-flex justify-content-end" id="pagination-links"></div>
                    @else
                        <div class=" text-center">
                            <img alt="Logo" style="align-items: center; margin-top:50px"
                                src="{{ url('public/frontend/media/noDeletionRequest.svg') }}" class="img-fluid ">
                        </div>
                    @endif
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
        <!--begin::Modal - Reason -->
        <div class="modal fade" id="kt_modal_update_interests" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog mw-500px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header pb-0 border-0 d-f justify-content-between">
                        <!--begin::Close-->
                        <p>

                        </p>
                        <p class="fs-2 fw-bold">
                            Reason For Not Accepted
                        </p>
                        <div class="btn btn-sm btn-icon btn-active-color-dark" data-bs-dismiss="modal">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->

                            <span class="">
                                <svg width="35" height="35" viewBox="0 0 40 40" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M33.3307 20.0013C33.3307 27.3651 27.3612 33.3346 19.9974 33.3346C12.6336 33.3346 6.66406 27.3651 6.66406 20.0013C6.66406 12.6375 12.6336 6.66797 19.9974 6.66797C27.3612 6.66797 33.3307 12.6375 33.3307 20.0013ZM15.9569 15.9608C16.3474 15.5703 16.9806 15.5703 17.3711 15.9608L19.9974 18.5871L22.6236 15.9609C23.0141 15.5703 23.6473 15.5703 24.0378 15.9609C24.4283 16.3514 24.4283 16.9846 24.0378 17.3751L21.4116 20.0013L24.0378 22.6275C24.4283 23.018 24.4283 23.6512 24.0378 24.0417C23.6472 24.4322 23.0141 24.4322 22.6235 24.0417L19.9974 21.4155L17.3711 24.0417C16.9806 24.4322 16.3475 24.4322 15.9569 24.0417C15.5664 23.6512 15.5664 23.018 15.9569 22.6275L18.5831 20.0013L15.9569 17.375C15.5664 16.9845 15.5664 16.3514 15.9569 15.9608Z"
                                        fill="#303030" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--begin::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body  pt-4 mx-0">
                        <!--begin::Input group-->
                        <form action="{{ route('rejectRequestDeletion') }}" method="POST" class="form"
                            enctype="multipart/form-data">
                            @csrf
                            <!-- Add a hidden input field to carry the interest ID -->
                            <input type="hidden" name="user_id" id="userId" value="">
                            <div class="fv-row mb-5">
                                <!--begin::Input-->
                                <label for="name" class="fw-bold fs-4 pb-2 fw-600">Reason</label>
                                <textarea style="background-color:#F0F1F3; font-size: 1.3rem;" type="text" placeholder="Add Reason"
                                    name="reason" class="form-control form-control-solid mb-3 border" id="productDescription" cols="20"
                                    rows="6" oninput="validateInput(this)" required></textarea>
                                <!--end::Input-->
                            </div>

                            <div class="d-flex justify-content-center align-content-center pt-2 ">
                                <!--begin::Button-->
                                <button type="submit" id="kt_modal_add_customer_submit" class="btn col-12"
                                    style="background-color: #38B89A; color:#FFFFFF">
                                    <span class="indicator-label fs-4">Send</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                            </div>
                            <!--end::Input group-->
                        </form>
                    </div>
                    <!--end::Modal body-->
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
        </div>
        <!--end::Modal - Reason -->
    </div>
    <!--end::Content-->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#kt_modal_update_interests').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userId = button.data('user-id');

            var modal = $(this);

            modal.find('#userId').val(userId);

            var form = modal.find('form');
            var actionUrl = form.attr('action');
            actionUrl = actionUrl.replace('user-id', userId);
            form.attr('action', actionUrl);
        });
    });

    $(document).ready(function() {
        $(document).on('click', '.delete-interest', function() {
            var interestId = $(this).data('interest-id');
            Swal.fire({
                title: 'Accept the Request',
                text: "Are you sure you want to Accept the Deletion Request?",
                icon: 'warning',
                iconColor: '#38B89A',
                showCancelButton: true,
                confirmButtonColor: '#38B89A',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Sure!',
                confirmButtonText: 'Approved',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'acceptRequestDeletion/' + interestId,
                        type: "GET",
                        success: function(response) {
                            Swal.fire({
                                title: 'Accepted!',
                                text: 'Scholar has been deleted.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 700,
                                willClose: () => {
                                    location.reload();
                                }
                            });
                        },
                        error: function(error) {
                            Swal.fire('Error', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    });

    function validateInput(input) {
        // Only allow alphanumeric characters, spaces, and some basic punctuation (.,!?)
        const regex = /[^a-zA-Z0-9 .,!?'"()-]/g;
        input.value = input.value.replace(regex, '');
        input.value = input.value.replace(/^\s*/, '');
    }
</script>
<script>
    var currentPage = 1;

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    function loadVerificationData(page, search = '', sortingOption = '') {
        $('#loader').removeClass('d-none');
        $.ajax({
            url: '{{ route('getDeletionRequests') }}?page=' + page + '&search=' + encodeURIComponent(search) +
                '&sorting=' +
                sortingOption,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var users = response.users;
                var tableBody = $('#verification-table-body');
                var userCount = response.userCount;
                $('#user-count').text(userCount);

                tableBody.empty();

                if (users.data.length === 0) {
                    // If no users found, display a message in a new row


                    var noUserRow = `
            <tr>
                <td colspan="6" class="text-center pt-10 fw-bolder fs-2">No request found</td>
            </tr>
        `;
                    tableBody.append(noUserRow);
                } else {
                    var badgeColors = ['text-warning', 'text-danger', 'text-primary', 'text-success'];
                    var bgColors = ['bg-light-warning', 'bg-light-danger', 'bg-light-primary',
                        'bg-light-success'
                    ];
                    var count = 0;
                    $.each(users.data, function(index, row) {

                        var statusText = '';

                        switch (row.mufti_status) {
                            case 2:
                                statusText = 'Scholar';
                                break;
                            case 4:
                                statusText = 'Life Coach';
                                break;
                            case 0:
                                statusText = 'User';
                                break;
                            default:
                                statusText = 'Unknown'; 
                        }

                        var newRow = `
                    <tr class="text-start">
                        <td class="d-flex align-items-center">
                            ${row.image ? `
                                <div class="symbol symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label">
                                        <img src="{{ asset('public/storage/') }}/${row.image}" alt="image" class="w-100" />
                                    </div>
                                </div>` : `
                                <div class="symbol symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label">
                                        <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image" class="w-100" />
                                    </div>
                                </div>`}
                            <a href="{{ URL::to('UserDetail/PublicQuestions') }}/${row.id}" class="cursor-pointer text-gray-800 text-hover-success">
                            <div class="d-flex flex-column text-hover-success">
                                <div class="mb-1">
                                    ${row.name}
                                </div>
                                <span> #${row.id}</span>
                            </div>
                             </a>
                        </td>
                        <td class="fw-bold text-center" style="color: #38B89A;">${statusText}</td>
                        <td class="text-center">${row.email}</td>
                        <td class="text-center">${row.requested_date}</td>
                        <td>
                            <div class="fs-4 fw-bolder text-dark d-flex justify-content-center align-content-center gap-3">
                                    <a>
                                        <button type="button" class="btn btn-sm w-100 delete-interest" data-interest-id="${row.id}"
                                        style="background-color:#38B89A; color:white;">Accept</button>
                                    </a>
                                    <a>
                                        <button type="button" class="btn btn-sm w-100"
                                        style="background-color:#F52E2E; color:white;" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_update_interests"
                                        data-user-id="${row.id}" >Reject</button>
                                    </a>
                            </div>
                        </td>
                       
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

                }
                var paginationLinks = $('#pagination-links');
                paginationLinks.empty();

                var totalPages = users.last_page;
                var currentPage = users.current_page;


                var previousLink = `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
                </li>`;
                paginationLinks.append(previousLink);

                for (var i = 1; i <= totalPages; i++) {
                    if (totalPages > 7 && (i < currentPage - 2 || i > currentPage + 2)) {
                        if (i === 1 || i === totalPages) {
                            var pageLink =
                                `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                            paginationLinks.append(pageLink);
                        }
                        continue;
                    }

                    var pageLink = `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}" style="background-color: #38B89A;">${i}</a>
                      </li>`;
                    paginationLinks.append(pageLink);
                }

                var nextLink = `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>
                </li>`;
                paginationLinks.append(nextLink);

                $('#loader').addClass('d-none');
            },
        });
    }

    function updateUrlParameter(key, value) {
        var url = new URL(window.location.href);
        url.searchParams.set(key, value);
        window.history.pushState({
            path: url.href
        }, '', url.href);
    }
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        if (page && page !== currentPage) {
            currentPage = page;
            var searchTerm = $('#global-search').val();
            loadVerificationData(currentPage, searchTerm);
            updateUrlParameter('page', currentPage);
        }
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
        $('#global-search').on('input', function() {
            var searchTerm = $(this).val();
            currentPage = 1;
            loadVerificationData(currentPage, searchTerm);
            updateUrlParameter('page', currentPage);
        });

        currentPage = getUrlParameter('page') || 1;
        loadVerificationData(currentPage);
    });
</script>
