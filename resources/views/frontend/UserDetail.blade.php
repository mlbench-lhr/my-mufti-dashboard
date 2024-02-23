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
        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-fluid" id="">
            <div class="card ">
                <div class="card-body pt-9 pb-0">
                    <!--begin::Details-->
                    <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                        <!--begin: Pic-->
                        <div class="me-3 mb-4">
                            @if ($response['user']->image == '')
                                <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                        style="height: 100px; width:100px;" />
                                </div>
                            @else
                                <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="{{ asset('public/storage/' . $response['user']->image) }}" alt="image"
                                        style="height: 100px; width:100px; object-fit: cover;" />
                                </div>
                            @endif
                        </div>
                        <!--end::Pic-->
                        <!--begin::Info-->
                        <div class="flex-grow-1">
                            <!--begin::Title-->
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    @if ($response['user']->user_type == 'scholar')
                                        <div class="d-flex align-items-center  text-success fs-6 fw-bolder me-1">
                                            {{ $response['user']->fiqa }}
                                        </div>
                                    @endif
                                    <!--begin::Name-->
                                    <div class="d-flex align-items-center mb-0">
                                        <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">
                                            {{ $response['user']->name }}
                                        </a>
                                    </div>
                                    <!--end::Name-->
                                    <!--begin::Info-->

                                    @if ($response['user']->user_type == 'scholar')
                                        <div class="d-flex flex-wrap flex-row fw-bold fs-5 pe-2 ">
                                            <a href="#"
                                                class="d-flex align-items-center text-gray-400 text-hover-primary me-5 ">
                                                <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                                <span class="  me-2">
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.96037 1.99123L6.50597 2.96886C6.99835 3.85113 6.80069 5.00851 6.0252 5.784C6.0252 5.784 6.0252 5.784 6.0252 5.78401C6.02508 5.78412 5.08465 6.72477 6.79004 8.43016C8.49477 10.1349 9.43538 9.19581 9.43619 9.195C9.43622 9.19498 9.4362 9.19499 9.43623 9.19496C10.2117 8.4195 11.3691 8.22185 12.2513 8.71423L13.229 9.25983C14.5612 10.0033 14.7185 11.8716 13.5475 13.0426C12.8439 13.7463 11.9819 14.2938 11.029 14.3299C9.42491 14.3907 6.70073 13.9848 3.96808 11.2521C1.23543 8.51946 0.829462 5.79529 0.890273 4.19118C0.926397 3.2383 1.47391 2.37631 2.17755 1.67266C3.34855 0.501659 5.21687 0.658994 5.96037 1.99123Z"
                                                            fill="#7B849A" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                                {{ $response['user']->phone_number }}
                                            </a>
                                        </div>
                                    @endif

                                    <div class="d-flex flex-wrap flex-row fw-bold fs-5 pe-2 ">
                                        <a href="#"
                                            class="d-flex align-items-center text-gray-400 text-hover-primary me-5 ">
                                            <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                            <span class="  me-2">
                                                <svg width="18" height="14" viewBox="0 0 18 14" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M1.18803 1.14939C0.203125 2.13429 0.203125 3.71947 0.203125 6.88982C0.203125 10.0602 0.203125 11.6454 1.18803 12.6303C2.17293 13.6152 3.75811 13.6152 6.92846 13.6152H10.2911C13.4615 13.6152 15.0467 13.6152 16.0316 12.6303C17.0165 11.6454 17.0165 10.0602 17.0165 6.88982C17.0165 3.71947 17.0165 2.13429 16.0316 1.14939C15.0467 0.16449 13.4615 0.16449 10.2911 0.16449H6.92846C3.75811 0.16449 2.17293 0.16449 1.18803 1.14939ZM14.1382 3.12352C14.3611 3.39103 14.3249 3.7886 14.0574 4.01152L12.2109 5.55028C11.4658 6.17124 10.8618 6.67455 10.3288 7.01737C9.77355 7.37449 9.23279 7.60008 8.60979 7.60008C7.98679 7.60008 7.44603 7.37449 6.89078 7.01737C6.35774 6.67455 5.7538 6.17125 5.00867 5.55029L3.16216 4.01152C2.89465 3.7886 2.85851 3.39103 3.08143 3.12352C3.30435 2.85601 3.70192 2.81987 3.96943 3.04279L5.78434 4.55522C6.56865 5.20881 7.11318 5.66112 7.5729 5.95679C8.01791 6.24301 8.3197 6.33908 8.60979 6.33908C8.89988 6.33908 9.20167 6.24301 9.64668 5.95679C10.1064 5.66112 10.6509 5.20881 11.4352 4.55522L13.2502 3.04279C13.5177 2.81987 13.9152 2.85601 14.1382 3.12352Z"
                                                        fill="#7B849A" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            {{ $response['user']->email }}
                                        </a>
                                    </div>
                                    @if ($response['user']->user_type == 'user')
                                        <div class="d-flex flex-wrap fw-bold fs-6 pe-2 mt-2">
                                            <a href="#"
                                                class="d-flex align-items-center text-gray-400 text-hover-primary me-5 ">
                                                <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                                <span class="  me-2">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.03751 1.12361C5.03751 0.775391 4.75522 0.493103 4.407 0.493103C4.05878 0.493103 3.77649 0.775391 3.77649 1.12361V2.45126C2.56648 2.54815 1.77213 2.78595 1.18853 3.36954C0.604931 3.95314 0.367136 4.7475 0.270243 5.95751H16.9505C16.8536 4.7475 16.6159 3.95314 16.0323 3.36954C15.4487 2.78595 14.6543 2.54815 13.4443 2.45126V1.12361C13.4443 0.775391 13.162 0.493103 12.8138 0.493103C12.4656 0.493103 12.1833 0.775391 12.1833 1.12361V2.39547C11.624 2.38463 10.9971 2.38463 10.2917 2.38463H6.92904C6.22368 2.38463 5.59679 2.38463 5.03751 2.39547V1.12361Z"
                                                            fill="#7B849A" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M0.203613 9.11005C0.203613 8.40469 0.203613 7.7778 0.21446 7.21853H17.0063C17.0172 7.7778 17.0172 8.40469 17.0172 9.11005V10.7914C17.0172 13.9618 17.0172 15.547 16.0323 16.5319C15.0473 17.5168 13.4621 17.5168 10.2917 17.5168H6.92904C3.75864 17.5168 2.17344 17.5168 1.18853 16.5319C0.203613 15.547 0.203613 13.9618 0.203613 10.7914V9.11005ZM12.8138 10.7914C13.2781 10.7914 13.6545 10.415 13.6545 9.95073C13.6545 9.48644 13.2781 9.11005 12.8138 9.11005C12.3495 9.11005 11.9731 9.48644 11.9731 9.95073C11.9731 10.415 12.3495 10.7914 12.8138 10.7914ZM12.8138 14.1541C13.2781 14.1541 13.6545 13.7777 13.6545 13.3134C13.6545 12.8491 13.2781 12.4728 12.8138 12.4728C12.3495 12.4728 11.9731 12.8491 11.9731 13.3134C11.9731 13.7777 12.3495 14.1541 12.8138 14.1541ZM9.45107 9.95073C9.45107 10.415 9.07469 10.7914 8.61039 10.7914C8.1461 10.7914 7.76971 10.415 7.76971 9.95073C7.76971 9.48644 8.1461 9.11005 8.61039 9.11005C9.07469 9.11005 9.45107 9.48644 9.45107 9.95073ZM9.45107 13.3134C9.45107 13.7777 9.07469 14.1541 8.61039 14.1541C8.1461 14.1541 7.76971 13.7777 7.76971 13.3134C7.76971 12.8491 8.1461 12.4728 8.61039 12.4728C9.07469 12.4728 9.45107 12.8491 9.45107 13.3134ZM4.407 10.7914C4.8713 10.7914 5.24768 10.415 5.24768 9.95073C5.24768 9.48644 4.8713 9.11005 4.407 9.11005C3.94271 9.11005 3.56632 9.48644 3.56632 9.95073C3.56632 10.415 3.94271 10.7914 4.407 10.7914ZM4.407 14.1541C4.8713 14.1541 5.24768 13.7777 5.24768 13.3134C5.24768 12.8491 4.8713 12.4728 4.407 12.4728C3.94271 12.4728 3.56632 12.8491 3.56632 13.3134C3.56632 13.7777 3.94271 14.1541 4.407 14.1541Z"
                                                            fill="#7B849A" />
                                                    </svg>

                                                </span>
                                                <!--end::Svg Icon-->
                                                {{ \Carbon\Carbon::parse($response['user']->created_at)->format('M d, Y') }}
                                            </a>
                                        </div>
                                    @endif
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                                <!--begin::Actions-->
                                <div class="d-flex ">
                                    <a href="{{ URL::to('DeleteUser/' . $response['user']->id) }}">
                                        <button type="button" class="btn btn-danger w-100 text-uppercase"
                                            style="background-color:#EA4335;">Delete User</button>
                                    </a>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Details-->
                    @if ($response['user']->user_type == 'scholar')
                        <div class="row mb-5">
                            <div class="col-2 fs-2 fw-bold text-dark">
                                Category
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    @foreach ($response['user']['interests'] as $data)
                                        <div class="col-3 badge badge-light fw-normal fs-4 ms-3 mb-3"
                                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                            {{ $data->interest }}
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    @endif

                    <div class="d-flex overflow-auto h-55px">
                        <ul
                            class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-3 fw-bolder flex-nowrap">
                            <!--begin::Nav item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-success me-6 {{ Request::is('UserDetail/PublicQuestions/' . $response['user']->id) ? 'active' : null }}"
                                    href="{{ URL::to('UserDetail/PublicQuestions/' . $response['user']->id) }}">Public
                                    Questions</a>
                            </li>
                            <!--end::Nav item-->
                            <!--begin::Nav item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-success me-6 {{ Request::is('UserDetail/PrivateQuestions/' . $response['user']->id) ? 'active' : null }}"
                                    href="{{ URL::to('UserDetail/PrivateQuestions/' . $response['user']->id) }}">Private
                                    Questions</a>
                            </li>
                            <!--end::Nav item-->
                            @if ($response['user']->user_type == 'scholar')
                                <!--begin::Nav item-->
                                <li class="nav-item">
                                    <a class="nav-link text-active-success me-6 {{ Request::is('UserDetail/AskedFromScholar/' . $response['user']->id) ? 'active' : null }}"
                                        href="{{ URL::to('UserDetail/AskedFromScholar/' . $response['user']->id) }}">Asked
                                        From Me</a>
                                </li>
                                <!--end::Nav item-->
                            @endif
                            <!--begin::Nav item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-success me-6 {{ Request::is('UserDetail/Appointments/' . $response['user']->id) ? 'active' : null }}"
                                    href="{{ URL::to('UserDetail/Appointments/' . $response['user']->id) }}">Appointments</a>
                            </li>
                            <!--end::Nav item-->
                             <!--begin::Nav item-->
                             <li class="nav-item">
                                <a class="nav-link text-active-success me-6 {{ Request::is('UserDetail/UserEvents/' . $response['user']->id) ? 'active' : null }}"
                                    href="{{ URL::to('UserDetail/UserEvents/' . $response['user']->id) }}">Events</a>
                            </li>
                            <!--end::Nav item-->
                             <!--begin::Nav item-->
                             <li class="nav-item">
                                <a class="nav-link text-active-success me-6 {{ Request::is('UserDetail/UserEventsRequest/' . $response['user']->id) ? 'active' : null }}"
                                    href="{{ URL::to('UserDetail/UserEventsRequest/' . $response['user']->id) }}">Events Request</a>
                            </li>
                            <!--end::Nav item-->
                            @if ($response['user']->user_type == 'scholar')
                                <!--begin::Nav item-->
                                <li class="nav-item">
                                    <a class="nav-link text-active-success me-6 {{ Request::is('UserDetail/Degrees/' . $response['user']->id) ? 'active' : null }}"
                                        href="{{ URL::to('UserDetail/Degrees/' . $response['user']->id) }}">Degrees</a>
                                </li>
                                <!--end::Nav item-->
                            @endif
                    </div>
                </div>
            </div>


            {{-- posted questions  --}}
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class=" mb-5">
                        <!--begin::Title-->
                        <h3 class="mt-4" style=" font-weight:400; ">Total Questions: <span class="fs-5"
                                id="user-count" style="font-weight:500 "> </span> </h3>
                        <!--end::Title-->
                    </div>

                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar mb-5">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-between " data-kt-user-table-toolbar="base">

                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative ">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <input type="text" id="global-search"
                                    class="form-control form-control-solid w-250px ps-14"
                                    placeholder="Search Questions" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    @if (count($response['posted_questions']))
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-dark fw-bold fs-5 text-uppercase gs-0">
                                        <th class="min-w-100px">Sr No</th>
                                        <th class="min-w-275px">Question</th>
                                        <th class="min-w-275px">Question Categories</th>
                                        <th class="min-w-275px">Posted on</th>
                                        <th class="min-w-175px">Voting Option</th>
                                        <th class="text-end min-w-175px">Action</th>
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
                                src="{{ url('public/frontend/media/noPublicQus.svg') }}" class="img-fluid ">
                        </div>
                    @endif
                </div>
                <!--end::Card body-->
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var user_id = @json($id);
    console.log(user_id);
    var currentPage = 1;

    function loadVerificationData(page, search = '', sortingOption = '') {
        $('#loader').removeClass('d-none');
        $.ajax({
            url: '{{ route('getUserPublicQuestions', ['id' => ':id']) }}'.replace(':id', user_id) + '?page=' +
                page + '&search=' + search + '&sorting=' +
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
                        <td class="d-flex align-items-center">${modifiedSerialNumber}</td>

                        <td>${row.question}</td>
                        <td style = "padding-left: 50px;">${row.total_categories}</td>
                        <td style = "">${row.registration_date}</td>
                        <td style = "padding-left: 50px;">
                                ${row.voting_option == 0 ?
                                    `Yes, No` :
                                    `True, False`}
                        </td>
                        <td class="text-end">
                            <div class="fs-4 fw-bolder text-dark">
                                <a href="{{ URL::to('PublicQuestionDetail') }}/${row.id}" class="link-success fw-bold">
                                    View Detail
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
</script>
