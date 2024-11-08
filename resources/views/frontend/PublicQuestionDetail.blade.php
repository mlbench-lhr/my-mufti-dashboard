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
                <h1 class="d-flex flex-column text-dark fw-bolder my-0 fs-1">Question Detail
                </h1>

                <!--end::Heading-->
            </div>
            <!--end::Page title=-->
            <div class="d-flex">
                @if ($isReplied)
                    <button style="background-color: #38B89A1A; color:#38B89A;" type="button" class="btn me-3" disabled>
                        Replied
                    </button>
                @else
                    <button style="background-color: #38B89A; color:#FFFFFF" type="button" class="btn me-3"
                        data-bs-toggle="modal" data-bs-target="#replyModal">
                        Reply as a Admin
                    </button>
                @endif
                <a href="{{ URL::to('DeletePublicQuestion/' . $question->id) }}?flag={{ $type }}&uId={{ $user_id }}"
                    id="delete-btn">
                    <button type="button" class="btn btn-danger w-100 text-uppercase" style="background-color:#EA4335;"
                        onclick="confirmDelete(event)">
                        Delete
                    </button>
                </a>
            </div>
            <div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog mw-500px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header pb-0 border-0 d-f justify-content-between">
                            <p></p>
                            <p class="fs-1 fw-bold mx-auto" id="modalTitle">Admin's Reply</p>
                            <!-- Title dynamically updated -->
                            <!--begin::Close-->
                            <div class="btn btn-lg btn-icon btn-active-color-dark" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span class="svg-icon svg-icon-1 w-25">
                                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M33.6663 17.0002C33.6663 26.2049 26.2044 33.6668 16.9997 33.6668C7.79493 33.6668 0.333008 26.2049 0.333008 17.0002C0.333008 7.79542 7.79493 0.333496 16.9997 0.333496C26.2044 0.333496 33.6663 7.79542 33.6663 17.0002ZM11.9491 11.9496C12.4372 11.4614 13.2287 11.4614 13.7168 11.9496L16.9996 15.2324L20.2824 11.9496C20.7705 11.4615 21.562 11.4615 22.0502 11.9496C22.5383 12.4378 22.5383 13.2292 22.0502 13.7174L18.7674 17.0001L22.0501 20.2829C22.5383 20.771 22.5383 21.5625 22.0501 22.0506C21.562 22.5388 20.7705 22.5388 20.2824 22.0506L16.9996 18.7679L13.7169 22.0507C13.2287 22.5388 12.4372 22.5388 11.9491 22.0507C11.4609 21.5625 11.4609 20.7711 11.9491 20.2829L15.2319 17.0001L11.9491 13.7173C11.4609 13.2292 11.4609 12.4377 11.9491 11.9496Z"
                                            fill="#1C274C" />
                                    </svg>
                                </span>
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->

                        <!--begin::Modal body-->
                        <div class="modal-body pt-4">
                            <!-- Form for Admin's Reply -->
                            <form action="{{ route('admin.reply') }}" method="POST" class="form" id="replyForm">
                                @csrf
                                <input type="hidden" name="question_id" value="{{ $question->id }}">
                                <input type="hidden" name="reply_id" id="replyId"> <!-- Hidden field for reply ID -->

                                <div class="mb-5">
                                    <label for="replyInput" class="form-label fw-bold fs-3">Add Reply</label>
                                    <textarea id="replyInput" name="reply" class="form-control form-control-solid" placeholder="Add Reply" rows="7"
                                        style="resize: none;" required oninput="validateInput(this)"></textarea>
                                </div>
                                <div class="d-flex justify-content-center align-content-center pt-2 mt-10">
                                    <button type="submit" class="btn btn-lg col-12" id="submitButton"
                                        style="background-color: #38B89A; color:#FFFFFF;">
                                        <span class="indicator-label" id="submitLabel">Send</span>
                                        <!-- Button label dynamically updated -->
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                            <!-- End Form -->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <!--end::Modal-->
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

                <div class="col-12 fs-2 fw-bolder text-dark pb-2">
                    Posted By
                </div>

                <div class="col-12 fs-2 fw-bolder text-dark d-flex pb-5">
                    @if ($question->user_detail->image == '')
                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                style="height: 80px; width:80px;" />
                        </div>
                    @else
                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ asset('public/storage/' . $question->user_detail->image) }}" alt="image"
                                style="height: 80px; width:80px; object-fit: cover;" />
                        </div>
                    @endif


                    <div class="ms-2">
                        <div class="fs-5 fw-normal text-success">
                            {{ $question->user_detail->user_type }}
                        </div>
                        <div class="fs-4">
                            {{ $question->user_detail->name }}
                        </div>
                        <div class="fs-6 fw-normal text-muted">
                            {{ $question->user_detail->email }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-3 fs-2 fw-bold text-dark">
                    Question Categories
                </div>
                <div class="col-9">
                    <div class="row">
                        @foreach ($question->question_category as $data)
                            <div class="col-3 badge badge-light fw-normal fs-4 mb-3 ms-2"
                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                {{ $data }}
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="row flex-col mb-7">
                <div class="col-12 fs-2 fw-bolder text-dark mb-5">
                    Public Feedback
                </div>
                <div class="row mb-5">
                    <div class="col-6 d-flex justify-content-center align-content-center">
                        <div class="fs-3 fw-bold text-success">
                            {{ $question->yesVotesPercentage }}%
                        </div>
                        <div class="progress h-15px w-100 ms-3 mt-2">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $question->yesVotesPercentage }}% ;  background-color:#38B89A;"
                                aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="fs-3 fw-bold text-success ms-3 d-flex">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M19.9581 12.3257C19.9581 16.7335 16.3849 20.3067 11.9771 20.3067C7.56932 20.3067 3.99609 16.7335 3.99609 12.3257C3.99609 7.91794 7.56932 4.34473 11.9771 4.34473C16.3849 4.34473 19.9581 7.91794 19.9581 12.3257Z"
                                    fill="#38B89A" />
                                <path
                                    d="M15.1867 9.90676C15.4204 10.1405 15.4204 10.5195 15.1867 10.7533L11.1962 14.7438C10.9624 14.9775 10.5834 14.9775 10.3496 14.7438L8.75344 13.1476C8.51969 12.9138 8.51969 12.5348 8.75344 12.3011C8.9872 12.0673 9.3662 12.0673 9.59996 12.3011L10.7729 13.474L12.5565 11.6904L14.3402 9.90676C14.5739 9.67301 14.9529 9.67301 15.1867 9.90676Z"
                                    fill="#38B89A" />
                            </svg>
                            @if ($question->voting_option == 1)
                                Yes
                            @else
                                True
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 d-flex justify-content-center align-content-center">
                        <div class="fs-3 fw-bold text-danger">
                            {{ $question->noVotesPercentage }}%
                        </div>
                        <div class="progress h-15px w-100 ms-3 mt-2">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $question->noVotesPercentage }}%;  background-color:#F52E2E;"
                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="fs-3 fw-bold text-danger ms-3 d-flex">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M19.9581 12.9742C19.9581 17.3819 16.3849 20.9552 11.9771 20.9552C7.56932 20.9552 3.99609 17.3819 3.99609 12.9742C3.99609 8.56638 7.56932 4.99316 11.9771 4.99316C16.3849 4.99316 19.9581 8.56638 19.9581 12.9742Z"
                                    fill="#F52E2E" />
                                <path
                                    d="M9.55423 10.5562C9.78798 10.3224 10.167 10.3224 10.4007 10.5562L11.9727 12.1282L13.5447 10.5562C13.7785 10.3224 14.1575 10.3224 14.3913 10.5562C14.625 10.79 14.625 11.1689 14.3913 11.4027L12.8193 12.9747L14.3912 14.5467C14.625 14.7804 14.625 15.1594 14.3912 15.3932C14.1575 15.6269 13.7785 15.6269 13.5447 15.3932L11.9727 13.8212L10.4008 15.3932C10.167 15.6269 9.788 15.6269 9.55424 15.3932C9.32048 15.1594 9.32048 14.7804 9.55424 14.5467L11.1262 12.9747L9.55423 11.4027C9.32047 11.1689 9.32047 10.7899 9.55423 10.5562Z"
                                    fill="#F52E2E" />
                            </svg>
                            @if ($question->voting_option == 1)
                                No
                            @else
                                False
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            {{-- Admin's Reply --}}
            <div class="col-12 fs-2 fw-bolder text-dark pb-2 d-flex justify-content-between align-items-center">
                <span>Admin's Reply</span>
                <div>
                    @if ($question->adminReply)
                        <a href="javascript:void(0)" class="link-primary fw-bolder"
                            onclick="confirmDelete({{ $question->adminReply->id }})">
                            <img src="{{ url('public/frontend/media/svg/deleteIcon.svg') }}" alt="Delete"
                                style="width: 30px; height: 30px;">
                        </a>
                        <a href="javascript:void(0)" class="link-primary fw-bolder"
                            onclick="editReply({{ $question->adminReply->id }}, '{{ addslashes($question->adminReply->reply) }}')">
                            <img src="{{ url('public/frontend/media/svg/editPen.svg') }}" alt="Edit"
                                style="width: 30px; height: 30px;">
                        </a>
                    @endif
                </div>
            </div>

            @if ($question->adminReply)
                <div class="col-12 fs-4 fw-bold text-black pb-10" data-reply-id="{{ $question->adminReply->id }}">
                    {{ $question->adminReply->reply }}
                </div>
            @else
                <div class="col-12 fs-1 fw-bold text-muted pb-10 text-center mt-10 mb-10">
                    You haven't replied to this question!
                </div>
            @endif


            {{-- Scholars Reply --}}
            <div class="col-12 fs-2 fw-bolder text-dark pb-2">
                Scholar's Reply
            </div>
            @if (!empty($question->scholar_reply))
                <div class="col-12 fs-2 fw-bolder text-dark d-flex pb-5">
                    @if ($question->scholar_reply->user_detail->image == '')
                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                style="height: 80px; width:80px;" />
                        </div>
                    @else
                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ asset('public/storage/' . $question->scholar_reply->user_detail->image) }}"
                                alt="image" style="height: 80px; width:80px; object-fit: cover;" />
                        </div>
                    @endif


                    <div class="ms-2">
                        <div class="fs-5 fw-normal text-success">
                            {{ $question->scholar_reply->user_detail->fiqa }}
                        </div>
                        <div class="fs-4">
                            {{ $question->scholar_reply->user_detail->name }}
                        </div>
                        <div class="text-muted fs-5 fw-normal"
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 400px;">
                            @foreach ($question->scholar_reply->user_detail->interests as $data)
                                {{ $data->interest }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12 fs-4 fw-bold text-black pb-10">
                    {{ $question->scholar_reply->reply }}
                </div>
            @else
                <div class="col-12 fs-1  fw-bold text-muted pb-10 text-center mt-10 mb-10">
                    No Scholar’s Reply!!
                </div>
            @endif


            {{-- question comments --}}

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class=" mb-5">
                        <!--begin::Title-->
                        <h3 class="mt-4 fs-1 fw-bolder ">Comments </h3>
                        <!--end::Title-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar mb-5">
                        <!--begin::Toolbar-->
                        <h3 class="mt-4 fs-2 text-muted fw-normal">Total Comments: <span class="fs-5" id="user-count"
                                style="font-weight:500 "> </span> </h3>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--begin::Card body-->
                <div class="card-body pt-0">


                    @if (count($question['comments']))
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-dark fw-bold fs-5 text-uppercase gs-0">
                                        <th class="min-w-200px">User name</th>
                                        <th class="min-w-250px text-center">Account Type</th>
                                        <th class="min-w-250px text-center">Date</th>
                                        <th class="min-w-300px text-center">Comment</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="text-gray-600 fw-bold" id="verification-table-body">
                                    {{-- ajax data is appending here  --}}
                                    {{-- <div id="loader" class="loader"></div> --}}

                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                        <div class="pagination d-flex justify-content-end" id="pagination-links"></div>
                    @else
                        <div class=" text-center">
                            <img alt="Logo" style="align-items: center; margin-top:50px"
                                src="{{ url('public/frontend/media/nocomments.svg') }}" class="img-fluid ">
                        </div>
                    @endif
                </div>
                <!--end::Card body-->
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
        $('#global-search').on('input', function() {
            var searchTerm = $(this).val();
            loadVerificationData(1, searchTerm);
        });
    });
    $(document).ready(function() {
        loadVerificationData(currentPage);
    });

    // add admin reply
    document.getElementById('replyForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const actionUrl = form.getAttribute('action');

        fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    const replyDiv = document.createElement('div');
                    replyDiv.classList.add('col-12', 'fs-4', 'fw-bold', 'text-muted', 'pb-10');
                    replyDiv.textContent = data.data.reply;

                    const replyContainer = document.getElementById('adminReplyContainer');
                    replyContainer.appendChild(replyDiv);

                    form.reset();
                } else {
                    alert('Failed to send reply. ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
            });
    });


    function editReply(replyId, replyContent) {
        document.getElementById('modalTitle').innerText = "Edit Admin's Reply";
        document.getElementById('submitLabel').innerText = "Update";

        document.getElementById('replyInput').value = replyContent;

        document.getElementById('replyId').value = replyId;

        $('#replyModal').modal('show');
    }

    function confirmDelete(replyId) {
        if (replyId !== null) {
            Swal.fire({
                title: 'Delete Admin’s Reply',
                text: 'Are you sure you want to delete your reply on this public question?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#38B89A',
                cancelButtonColor: '#38B89A1A',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                willOpen: () => {
                    const cancelButton = Swal.getCancelButton();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteAdminReply(replyId);
                }
            });
        }
    }

    function deleteAdminReply(replyId) {
        $.ajax({
            url: '/admin/reply/delete',
            type: 'POST',
            data: {
                reply_id: replyId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.fire(
                    'Deleted!',
                    'Your reply has been deleted.',
                    'success'
                ).then(() => {
                    location.reload();
                });
            },
            error: function(error) {
                Swal.fire(
                    'Error!',
                    'There was an error deleting your reply.',
                    'error'
                );
            }
        });


    }

    function confirmDelete(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Delete Public Question',
            text: 'Are you sure you want to delete this public question?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#38B89A',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Sure!',
            cancelButtonText: 'Cancel',
            willOpen: () => {
                const cancelButton = Swal.getCancelButton();
                cancelButton.style.color = '#7B849A';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'The public question has been deleted.',
                    'success'
                ).then(() => {
                    window.location.href = event.target.closest('a').href;
                });
            }
        });
    }

    function validateInput(input) {
        input.value = input.value.replace(/^\s+/, '');
        input.value = input.value.replace(/[^0-9a-zA-Z\s]/g, '');
    }
</script>
