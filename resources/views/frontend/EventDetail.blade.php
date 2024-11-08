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
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                <!--begin: Pic-->
                <div class="me-3 mb-4">
                    @if ($event->image == '')
                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ url('frontend/media/blank.svg') }}" alt="image"
                                style="height: 100px; width:100px;" />
                        </div>
                    @else
                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ asset('public/storage/' . $event->image) }}" alt="image"
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
                            <!--begin::Name-->
                            <div class="d-flex align-items-center mb-3">
                                <a class="text-gray-900  fs-2 fw-bolder me-1">
                                    {{ $event->event_title }}
                                </a>
                            </div>
                            <!--end::Name-->
                            <!--begin::Info-->

                            <div class="d-flex flex-wrap flex-row fw-bold fs-5 pe-2 mb-3">
                                <a class="d-flex align-items-center text-gray-400  me-5 ">
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
                                    {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}
                                </a>
                                <a class="d-flex align-items-center text-gray-400  me-5 ">
                                    <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                    <span class="  me-2">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.4911 9.39007C17.4911 14.0329 13.7273 17.7967 9.0844 17.7967C4.44153 17.7967 0.677734 14.0329 0.677734 9.39007C0.677734 4.74719 4.44153 0.983398 9.0844 0.983398C13.7273 0.983398 17.4911 4.74719 17.4911 9.39007Z"
                                                fill="#7B849A" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.0844 5.3969C9.43262 5.3969 9.7149 5.67918 9.7149 6.0274V9.1289L11.6319 11.0459C11.8781 11.2921 11.8781 11.6913 11.6319 11.9376C11.3857 12.1838 10.9865 12.1838 10.7402 11.9376L8.63857 9.8359C8.52033 9.71765 8.4539 9.55728 8.4539 9.39007V6.0274C8.4539 5.67918 8.73619 5.3969 9.0844 5.3969Z"
                                                fill="white" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    {{ \Carbon\Carbon::parse($event->date)->format('H:i A') }}
                                </a>
                                <a class="d-flex align-items-center text-gray-400  me-5 ">
                                    <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                    <span class="  me-2">
                                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.92815 0.984016C8.92815 0.6358 9.21044 0.353516 9.55865 0.353516C14.5497 0.353516 18.5958 4.39959 18.5958 9.39068C18.5958 14.3818 14.5497 18.4278 9.55865 18.4278C4.56756 18.4278 0.521484 14.3818 0.521484 9.39068C0.521484 9.04247 0.803769 8.76018 1.15198 8.76018C1.5002 8.76018 1.78248 9.04247 1.78248 9.39068C1.78248 13.6853 5.26399 17.1668 9.55865 17.1668C13.8533 17.1668 17.3348 13.6853 17.3348 9.39068C17.3348 5.09602 13.8533 1.61452 9.55865 1.61452C9.21044 1.61452 8.92815 1.33223 8.92815 0.984016ZM9.55865 6.23818C9.90687 6.23818 10.1892 6.52047 10.1892 6.86868V9.60085H12.9213C13.2695 9.60085 13.5518 9.88313 13.5518 10.2313C13.5518 10.5796 13.2695 10.8618 12.9213 10.8618H9.55865C9.21044 10.8618 8.92815 10.5796 8.92815 10.2313V6.86868C8.92815 6.52047 9.21044 6.23818 9.55865 6.23818Z"
                                                fill="#7B849A" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.12036 1.31821C7.24576 1.64307 7.08407 2.00807 6.75922 2.13347C6.64032 2.17937 6.52283 2.2281 6.40682 2.2796C6.08856 2.42089 5.71602 2.27743 5.57473 1.95917C5.43344 1.6409 5.5769 1.26836 5.89517 1.12707C6.0301 1.06717 6.16678 1.01047 6.30511 0.957074C6.62996 0.831674 6.99496 0.993362 7.12036 1.31821ZM4.21849 2.86733C4.45863 3.11949 4.4489 3.51858 4.19674 3.75873C4.10454 3.84653 4.0145 3.93658 3.9267 4.02877C3.68655 4.28093 3.28746 4.29067 3.0353 4.05052C2.78314 3.81037 2.7734 3.41128 3.01355 3.15912C3.1155 3.05208 3.22004 2.94753 3.32709 2.84558C3.57925 2.60544 3.97834 2.61517 4.21849 2.86733ZM2.12713 5.40676C2.4454 5.54805 2.58886 5.92059 2.44757 6.23885C2.39607 6.35486 2.34733 6.47235 2.30144 6.59125C2.17604 6.9161 1.81103 7.07779 1.48618 6.95239C1.16133 6.82699 0.999642 6.46199 1.12504 6.13714C1.17844 5.99881 1.23514 5.86213 1.29504 5.7272C1.43633 5.40894 1.80887 5.26547 2.12713 5.40676Z"
                                                fill="#7B849A" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    {{ $event->duration }} Hours
                                </a>
                            </div>

                            <div class="d-flex flex-wrap flex-row fw-bold fs-5 pe-2 ">
                                <a class="d-flex align-items-center text-gray-400  me-5 ">
                                    <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                    <span class="  me-2">
                                        <svg width="15" height="18" viewBox="0 0 15 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.60815 0.204102C3.89385 0.204102 0.882812 3.56894 0.882812 7.34977C0.882812 11.101 3.02931 15.1784 6.37831 16.7438C7.15901 17.1087 8.05728 17.1087 8.83798 16.7438C12.187 15.1784 14.3335 11.101 14.3335 7.34977C14.3335 3.56894 11.3224 0.204102 7.60815 0.204102ZM7.60815 8.61077C8.53672 8.61077 9.28948 7.85801 9.28948 6.92943C9.28948 6.00086 8.53672 5.2481 7.60815 5.2481C6.67957 5.2481 5.92681 6.00086 5.92681 6.92943C5.92681 7.85801 6.67957 8.61077 7.60815 8.61077Z"
                                                fill="#7B849A" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    {{ $event->location }}
                                </a>
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::User-->
                        <!--begin::Actions-->
                        @if ($event->event_status == 2)
                            <div class="d-flex">
                                {{-- <a href="{{ URL::to('EventRequestApprove/' . $event->id) }}">
                                    <button type="button" class="btn btn-md btn-success w-100"
                                        style="background-color:#38B89A;">Accept</button>
                                </a> --}}
                                <a href="#" data-url="{{ URL::to('EventRequestApprove/' . $event->id) }}"
                                    class="btn-approve">
                                    <button type="button" class="btn btn-md btn-success w-100"
                                        style="background-color:#38B89A;">Accept</button>
                                </a>

                                <a href="{{ URL::to('EventRequestDecline/' . $event->id) }}">
                                    <button type="button" class="btn btn-md btn-danger w-100 ms-5"
                                        style="background-color:#F52E2E;">Reject</button>
                                </a>
                            </div>
                        @elseif($event->event_status == 1)
                            <div class="d-flex">
                                <p class='badge px-5 py-4 fs-5 fw-bold' style='background: #e4f9f4;color: #38B89A;'>
                                    Accepted
                                </p>
                            </div>
                        @elseif($event->event_status == 0)
                            <div class="d-flex">
                                <p class='badge px-5 py-4 fs-5 fw-bold' style='background: #EA43351A;color: #EA4335;'>
                                    Rejected
                                </p>
                            </div>
                        @endif
                        <!--end::Actions-->
                    </div>
                    <!--end::Title-->
                </div>
                <!--end::Info-->
            </div>
            <div class="row mb-5">
                <div class="col-3 fs-2 fw-bold text-dark">
                    Event Category
                </div>
                <div class="col-9">
                    <div class="row">
                        @foreach ($event->event_category as $data)
                            <div class="col-3 badge badge-light fw-normal fs-4 ms-3 mb-3"
                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                {{ $data }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12 fs-2 fw-bold text-dark mb-5">
                    Posted By
                </div>
                <div class="col-12 fs-2 fw-bolder text-dark d-flex pb-5">
                    @if ($event->hosted_by->image == '')
                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                style="height: 80px; width:80px;" />
                        </div>
                    @else
                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ asset('public/storage/' . $event->hosted_by->image) }}" alt="image"
                                style="height: 80px; width:80px; object-fit: cover;" />
                        </div>
                    @endif
                    <div class="ms-2">
                        <div class="fs-5 fw-normal text-success">
                            {{ $event->hosted_by->user_type }}
                        </div>
                        <div class="fs-2 mb-1">
                            {{ $event->hosted_by->name }}
                        </div>
                        <div class="fs-5 fw-normal text-muted">
                            {{ $event->hosted_by->email }}
                        </div>
                    </div>
                </div>
            </div>


            {{-- Scholars --}}
            <div class="row mb-5">
                <div class="col-12 ">
                    <div class="row">
                        <div class="col-6 fs-2 fw-bold text-dark mb-5">
                            Scholars
                        </div>
                        <div class="col-6 fs-5 fw-bold text-success text-end cursor-pointer" data-bs-toggle="modal"
                            data-bs-target="#view_all_scholars">
                            See All
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($event_scholar as $row)
                        <div class="col-6 fs-2 fw-bolder text-dark d-flex pb-5">
                            @if ($row->image == '')
                                <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                        style="height: 80px; width:80px;" />
                                </div>
                            @else
                                <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="{{ asset('public/storage/' . $row->image) }}" alt="image"
                                        style="height: 80px; width:80px; object-fit: cover;" />
                                </div>
                            @endif
                            <div class="ms-2">
                                <div class="fs-5 fw-normal text-success">
                                    {{ $row->fiqa }}
                                </div>
                                <div class="fs-2 mb-1">
                                    {{ $row->name }}
                                </div>
                                <div class="text-muted fs-5 fw-normal"
                                    style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 400px;">
                                    @foreach ($row->category as $data)
                                        {{ $data }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12 fs-2 fw-bold text-dark mb-5">
                    About
                </div>
                <div class="col-12 fs-4 fw-bold text-muted d-flex pb-5">
                    {{ $event->about }}
                </div>
            </div>

            @if ($event->event_status !== 2)
                <div class="row mb-20">
                    <div class="col-12 fs-2 fw-bold text-dark mb-5">
                        Questions
                    </div>
                    <div class="col-12">
                        @if (count($event['event_questions']))
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                    <!--begin::Table head-->
                                    <thead>
                                        <!--begin::Table row-->
                                        <tr class="text-start text-dark fw-bold fs-5 text-uppercase gs-0">
                                            <th class="min-w-125px">Username</th>
                                            <th class="min-w-125px">Category</th>
                                            <th class="min-w-125px ">Date</th>
                                            <th class="min-w-175px">Question</th>
                                            <th class="min-w-125px ">Action</th>
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
                                    src="{{ url('public/frontend/media/noEventQus.svg') }}" class="img-fluid ">
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!--end::Container-->

    <!--begin::Modal Request Decline -->
    <div class="modal fade" id="view_all_scholars" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header pb-0 border-0 d-f justify-content-between">
                    <!--begin::Close-->
                    <p>

                    </p>
                    <p class="fs-1 fw-bolder text-dark">
                        Event Scholars
                    </p>
                    <div class="btn btn-sm btn-icon btn-active-color-success" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->

                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M17.4735 10.8068C17.4735 14.4887 14.4887 17.4735 10.8068 17.4735C7.1249 17.4735 4.14014 14.4887 4.14014 10.8068C4.14014 7.1249 7.1249 4.14014 10.8068 4.14014C14.4887 4.14014 17.4735 7.1249 17.4735 10.8068ZM8.78655 8.78657C8.98182 8.59131 9.2984 8.59131 9.49366 8.78657L10.8068 10.0997L12.1199 8.78658C12.3152 8.59132 12.6317 8.59132 12.827 8.78658C13.0223 8.98185 13.0223 9.29843 12.827 9.49369L11.5139 10.8068L12.827 12.1199C13.0222 12.3152 13.0222 12.6317 12.827 12.827C12.6317 13.0223 12.3151 13.0223 12.1199 12.827L10.8068 11.5139L9.49368 12.827C9.29841 13.0223 8.98183 13.0223 8.78657 12.827C8.59131 12.6317 8.59131 12.3152 8.78657 12.1199L10.0997 10.8068L8.78655 9.49368C8.59129 9.29841 8.59129 8.98183 8.78655 8.78657Z"
                                    fill="#252F4A" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--begin::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body  pt-0 mx-0">
                    <div class="card-body pt-5" style="overflow-y: auto;">
                        <div class="row">
                            @foreach ($all_event_scholar as $row)
                                <div class="col-6 fs-2 fw-bolder text-dark d-flex pb-7">
                                    @if ($row->image == '')
                                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                            <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                                style="height: 80px; width:80px;" />
                                        </div>
                                    @else
                                        <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                            <img src="{{ asset('public/storage/' . $row->image) }}" alt="image"
                                                style="height: 80px; width:80px; object-fit: cover;" />
                                        </div>
                                    @endif
                                    <div class="ms-2">
                                        <div class="fs-5 fw-normal text-success">
                                            {{ $row->fiqa }}
                                        </div>
                                        <div class="fs-2 mb-1">
                                            {{ $row->name }}
                                        </div>
                                        <div class="text-muted fs-5 fw-normal"
                                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                            @foreach ($row->category as $data)
                                                {{ $data }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                    </div>
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Request Decline-->
    </div>
    <!--end::Content-->
    <script type="module">
        $(document).ready(function() {
            $('.btn-approve').on('click', function(e) {
                e.preventDefault();

                var button = $(this);
                var url = button.data('url'); // Use data-url attribute for the endpoint

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr(
                            'content'), // Include CSRF token if needed
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                timer: 1500,
                                showConfirmButton: false,
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'An unexpected error occurred.',
                            icon: 'error',
                        });
                    }
                });
            });
        });
    </script>

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var event_id = @json($event_id);
    var currentPage = 1;

    function loadVerificationData(page, search = '', sortingOption = '') {
        $('#loader').removeClass('d-none');
        $.ajax({
            url: '{{ route('getEventsQuestions', ['id' => ':id']) }}'.replace(':id', event_id) + '?page=' +
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
                            ${row.user_detail.image ? `
                                <div class="symbol symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label">
                                        <img src="{{ asset('public/storage/') }}/${row.user_detail.image}" alt="image" class="w-100" />
                                    </div>
                                </div>` : `
                                <div class="symbol symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label">
                                        <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image" class="w-100" />
                                    </div>
                                </div>`}

                            <div class="d-flex flex-column">
                                <div class="text-gray-800  mb-1">
                                    ${row.user_detail.name}
                                </div>
                                <span>${row.user_detail.email}</span>
                            </div>
                        </td>

                        <td style = "padding-left: 20px;">${row.category}</td>
                        <td >${row.posted_at}</td>
                        <td >${row.question}</td>
                        <td class="">
                            <div class="fs-4 fw-bolder text-dark">
                                <a href="{{ URL::to('EventQuestionDetail') }}/${row.id}" class="link-success fw-bold">
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
        loadVerificationData(currentPage);
    });
    $(document).ready(function() {
        loadVerificationData(currentPage);
    });
</script>
