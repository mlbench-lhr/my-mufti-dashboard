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
                <a class="d-flex align-items-center">
                    <img alt="Logo" src="{{ '../../public/frontend/media/sidebarLogo.svg' }}" class="h-20px" />
                </a>
                <!--end::Logo-->
            </div>

            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-2 pb-lg-0"
                data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                <!--begin::Heading-->
                <h1 class="d-flex flex-column text-dark fw-bold my-0 fs-1">Events
                </h1>
                <h3 class="mt-4" style=" font-weight:400; ">All Events: <span class="fs-5" id="user-count"
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
                    placeholder="Search event" />
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
                    <div class="d-flex overflow-auto h-55px">
                        <ul
                            class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-3 fw-bolder flex-nowrap">
                            <!--begin::Nav item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-success me-6 {{ Request::is('AllEvents') ? 'active' : null }}"
                                    href="{{ URL::to('AllEvents') }}">All Events</a>
                            </li>
                            <!--end::Nav item-->
                            <!--begin::Nav item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-success me-6 {{ Request::is('RequestedEvents') ? 'active' : null }}"
                                    href="{{ URL::to('RequestedEvents') }}">Requested Events</a>
                            </li>
                            <!--end::Nav item-->
                    </div>


                    @if (count($events))
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-dark fw-bold fs-5 text-uppercase gs-0">
                                        <th class="min-w-100px">Event Name</th>
                                        <th class="min-w-125px">Date</th>
                                        <th class="min-w-125px">Time</th>
                                        <th class="min-w-125px">Duration</th>
                                        <th class="min-w-125px">Location</th>
                                        <th class="text-center min-w-100px">Action</th>
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
                                src="{{ url('public/frontend/media/noEvents.svg') }}" class="img-fluid ">
                        </div>
                    @endif
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            url: '{{ route('getEvents') }}?page=' + page + '&search=' + encodeURIComponent(search) + '&sorting=' +
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
                    var noUserRow = `
            <tr>
                <td colspan="6" class="text-center pt-10 fw-bolder fs-2">No Events found</td>
            </tr>
        `;
                    tableBody.append(noUserRow);
                } else {
                    var count = 0;
                    $.each(users.data, function(index, row) {
                        var categoryName = row.event_category.map(function(category) {
                            return category;
                        });

                        var category = categoryName.join(', ');
                        var newRow = `
                    <tr>
                        <td class="d-flex align-items-center">
                            ${row.image? `
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

                            <div class="d-flex flex-column">
                                <div class="text-gray-800 mb-1">
                                    ${row.event_title}
                                </div>
                                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 230px;">${categoryName}</span>
                            </div>
                        </td>
                        <td>${row.event_date}</td>
                        <td>${row.event_time}</td>
                        <td>${row.duration} Hours</td>
                        <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 230px;" >${row.location}</td>
                        <td class="text-end">
                            <div class="fs-4 fw-bolder text-dark">
                                <a href="{{ URL::to('EventDetail') }}/${row.id}" class="link-success fw-bold">
                                    View detail
                                </a>
                            </div>
                        </td>
                       
                    </tr>
                `;
                        tableBody.append(newRow);
                        count++;
                    });
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

    // Handle page clicks
    // $(document).on('click', '.page-link', function(e) {
    //     e.preventDefault();
    //     currentPage = $(this).data('page');
    //     var searchTerm = $('#global-search').val();
    //     loadVerificationData(currentPage, searchTerm);
    // });
    // $(document).on('click', '#apply-filter-button', function(e) {
    //     e.preventDefault();
    //     var searchTerm = $('#global-search').val();
    //     var sortingOption = $('#request-date-filter').val();
    //     loadVerificationData(currentPage, searchTerm, sortingOption);
    // });

    // $(document).on('click', '#reset-filter-button', function(e) {
    //     e.preventDefault();
    //     $('#request-date-filter').val('').trigger('change');
    //     loadVerificationData(currentPage);
    // });
    // $(document).ready(function() {
    //     // Handle global search input
    //     $('#global-search').on('input', function() {
    //         var searchTerm = $(this).val();
    //         loadVerificationData(1, searchTerm);
    //     });
    // });
    // $(document).ready(function() {
    //     loadVerificationData(currentPage);
    // });


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
