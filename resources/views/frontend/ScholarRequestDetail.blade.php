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
                <h1 class="d-flex flex-column text-dark fw-bold my-0 fs-1">Scholar Request Detail
                </h1>
                <!--end::Heading-->
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
                        <div class="me-7 mb-4">
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
                                    <div class="d-flex align-items-center  text-success fs-6 fw-bolder me-1">
                                        {{ $response['user']->mufti_detail->fiqa }}
                                    </div>
                                    <!--begin::Name-->
                                    <div class="d-flex align-items-center mb-0">
                                        <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">
                                            {{ $response['user']->mufti_detail->name }}
                                        </a>
                                    </div>
                                    <!--end::Name-->
                                    <!--begin::Info-->
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
                                            {{ $response['user']->mufti_detail->phone_number }}
                                        </a>
                                    </div>
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
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                                <!--begin::Actions-->
                                <div class="d-flex">
                                    {{-- <a href="{{ URL::to('Approve/' . $response['user']->id) }}" style="pointer-events: none; cursor: default;">
                                        <button type="button" class="btn btn-success w-100 text-uppercase"
                                            style="background-color:#38B89A;" disabled>Approve</button>
                                    </a> --}}
                                    <a href="{{ URL::to('Approve/' . $response['user']->id) }}">
                                        <button type="button" class="btn btn-success w-100 text-uppercase"
                                            style="background-color:#38B89A;">Approve</button>
                                    </a>
                                    <a href="{{ URL::to('Reject/' . $response['user']->id) }}">
                                        <button type="button" class="btn btn-danger w-100 text-uppercase ms-5"
                                            style="background-color:#F52E2E;">Reject</button>
                                    </a>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Details-->
                </div>
            </div>
            <div class="card ">
                <div class="card-body pt-9 pb-0">
                    <div class="row mb-5 flex-row">
                        <div class="col-12 fs-2 fw-bold text-dark mb-10">
                            Work Experience
                        </div>
                        <div class="col-12 fs-2 fw-bold text-muted mb-10">
                            {{ $response['experience'] }}
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-12 fs-2 fw-bold text-dark mb-10">
                            Category
                        </div>
                        <div class="col-12 mb-10">
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
                </div>
            </div>
            <div class="row">
                <div class="col-12 fs-2 fw-bold text-dark pt-10">
                    Degrees
                </div>
                <div class="col-12 mb-2">
                    @if (count($response['degrees']))
                        <div class="row pt-md-7">
                            @foreach ($response['degrees'] as $row)
                                <div class="col-6 mb-6">
                                    <div class="box">
                                        <div class="row pb-5">
                                            <div class="col-9">
                                                <div class="d-flex">
                                                    @if ($row->degree_image == '')
                                                        <div
                                                            class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                                            <img src="{{ url('public/frontend/media/blank.svg') }}"
                                                                alt="image" style="height: 90px; width:90px; " />
                                                        </div>
                                                    @else
                                                        <a href="{{ asset('public/storage/' . $row->degree_image) }}"
                                                            target="_blank">
                                                            <div
                                                                class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                                                <img src="{{ asset('public/storage/' . $row->degree_image) }}"
                                                                    alt="image"
                                                                    style="height: 90px; width:90px; object-fit: cover;" />
                                                            </div>
                                                        </a>
                                                    @endif
                                                    <div class="ms-3">
                                                        <div class="fw-bold fs-2 text-dark">
                                                            {{ $row->degree_title }}
                                                        </div>
                                                        <div class="fw-normal fs-6 text-muted pt-2">
                                                            {{ $row->institute_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="col-3 text-muted fs-3 d-flex justify-content-end align-content-end">
                                                {{ \Carbon\Carbon::parse($row->degree_startDate)->format('Y') }} -
                                                {{ \Carbon\Carbon::parse($row->degree_endDate)->format('Y') }}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class=" text-center">
                            <img alt="Logo" style="align-items: center; margin-top:50px"
                                src="{{ url('public/frontend/media/noDegrees.svg') }}" class="img-fluid ">
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection
