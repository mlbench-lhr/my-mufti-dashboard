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
    }

    .line-container {
        display: flex;
        justify-content: flex-start;
        margin-top: 10px;
    }

    .content-line {
        border: none;
        border-top: 2px solid #4a4a4a;
        width: calc(100% - 10px);
        margin-left: 10px;
        margin-right: 0;
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
                <a
                    href="{{ URL::to('DeletePublicQuestion/' . $question->id) }}?flag={{ $type }}&uId={{ $user_id }}">
                    <button type="button" class="btn btn-danger w-100 text-uppercase" style="background-color:#EA4335;">
                        Delete
                    </button>
                </a>
            </div>

        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-fluid" id="">
            <div class="row">
                <div class="col-12 fs-3 fw-bold text-muted pb-4">
                    {{ \Carbon\Carbon::parse($question->created_at)->format('M d, Y') }}
                    -
                    {{ \Carbon\Carbon::parse($question->time_limit)->format('M d, Y') }}
                </div>
                <div class="col-12 fs-3 fw-normal text-dark pb-10">
                    {{ $question->question }}
                </div>
                <div class="col-md-6">
                    <div class="fs-2 fw-bolder text-dark pb-2">
                        Posted By
                    </div>
                    <div class="d-flex align-items-center pb-5">
                        @if ($question->user_detail->image == '')
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                    style="height: 80px; width:80px;" />
                            </div>
                        @else
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                <img src="{{ asset('public/storage/' . $question->user_detail->image) }}" alt="image"
                                    style="height: 80px; width:80px; object-fit: cover;" />
                            </div>
                        @endif

                        <div class="ms-2">
                            <div class="fs-5 fw-normal text-success">
                                {{ $question->user_detail->user_type }}
                            </div>
                            <div class="fs-4 fw-bold">
                                {{ $question->user_detail->name }}
                            </div>
                            <div class="fs-6 fw-normal text-muted">
                                {{ $question->user_detail->email }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="line-container">
                    <hr class="content-line">
                </div>

            </div>

            {{-- reported users and their reasons --}}

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class=" mb-5">
                        <h3 class="mt-4 fs-1 fw-bolder ">Reports</h3>
                    </div>
                    <div class="card-toolbar mb-5">
                        <h3 class="mt-4 fs-2 text-muted fw-normal">Total Reports: <span class="fs-5" id="user-count"
                                style="font-weight:500 "> </span> </h3>
                    </div>
                </div>
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    @if (count($question['reports']))
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-dark fw-bold fs-5 text-uppercase gs-0">
                                        <th class="min-w-125px">Reported By</th>
                                        <th class="min-w-175px text-center">Account Type</th>
                                        <th class="min-w-175px text-center">Date</th>
                                        <th class="min-w-175px text-center">Reason</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <tbody class="text-gray-600 fw-bold" id="verification-table-body">
                                    <div id="loader" class="loader"></div>

                                </tbody>

                            </table>
                        </div>

                        <div class="pagination d-flex justify-content-end" id="pagination-links"></div>
                    @else
                        <div class=" text-center">
                            <img alt="Logo" style="align-items: center; margin-top:50px"
                                src="{{ url('public/frontend/media/nocomments.svg') }}" class="img-fluid ">
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
    <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var question_id = @json($question_id);
    var currentPage = 1;

    function loadVerificationData(page, search = '', sortingOption = '') {
        $('#loader').removeClass('d-none');
        $.ajax({
            url: '{{ route('getQuestionReports', ['id' => ':id']) }}'.replace(':id', question_id) + '?page=' +
                page + '&search=' + encodeURIComponent(search) + '&sorting=' +
                sortingOption,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                var reports = response.reports;
                var reportCount = response.reportCount;
                $('#user-count').text(reportCount);
                var tableBody = $('#verification-table-body');

                tableBody.empty();

                if (reports.data.length === 0) {
                    var noUserRow = `
            <tr>
                <td colspan="6" class="text-center pt-10 fw-bolder fs-2">Reports found</td>
            </tr>
        `;
                    tableBody.append(noUserRow);
                } else {

                    var count = (reports.data.length > 0) ? (reports.current_page - 1) * reports.per_page :
                        0;
                    $.each(reports.data, function(index, row) {
                        var modifiedSerialNumber = pad(count + 1, 2,
                            '0');
                        var newRow = `
                    <tr>
                        <td>
                           <div class="d-flex align-items-center justify-content-start">
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
                                    <div class="text-gray-800 text-hover-primary cursor-pointer mb-1">
                                        ${row.user.name}
                                    </div>
                                    <span> #${row.user.email}</span>
                                </div>
                        </div>
                        </td>

                        <td style = "padding-left: 50px;" >${row.user.user_type}</td>
                        <td style = "padding-left: 50px;">${row.reported_at}</td>
                        <td style="padding-left: 50px;">${row.reason ? row.reason : 'No reason provided'}</td>
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

                    var totalPages = reports.last_page;

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
</script>
