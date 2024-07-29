@extends('frontend.layout.master')
@section('content')
    <!--begin::Header-->
    <div id="kt_header" class="header">
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
                <h1 class="d-flex flex-column text-dark fw-bolder my-0 fs-1">Dashboard 1
                </h1>

                <!--end::Heading-->
            </div>
            <!--end::Page title=-->
        </div>



    </div>
    <!--end::Header-->


    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-fluid mb-md-5 mb-xl-10">
            <!--begin::Row-->
            <div class="row g-xl-10">
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-2 mb-xl-4">


                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                        <div class="row">

                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                <!--begin::Card widget 6-->
                                <div class="card card-flush  h-md-50 mb-md-5 mb-xl-10 mt-4"
                                    style="background-color: #f4d7dc
                                    ; max-height:230px;">
                                    <div class="card-body d-flex flex-column">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-column flex-grow-1">
                                            <!--begin::Title-->
                                            <a class="text-dark  fw-bolder fs-3">Public
                                                Questions
                                            </a>
                                            <!--end::Title-->
                                            <!--begin::Chart-->
                                            <div class="mixed-widget-14-chart" style="height: 100px"></div>
                                            <!--end::Chart-->
                                        </div>
                                        <!--end::Wrapper-->
                                        <!--begin::Stats-->
                                        <div class="pt-5">
                                            <!--begin::Number-->
                                            <span class="text-dark fw-bolder me-2 lh-0" style="font-size:2vw;">
                                                @if ($response['get_questions']['total_matches'] >= 0 && $response['get_questions']['total_matches'] < 1000)
                                                    {{ $response['get_questions']['total_matches'] }}
                                                @elseif ($response['get_questions']['total_matches'] >= 1000 && $response['get_questions']['total_matches'] < 1000000)
                                                    {{ round($response['get_questions']['total_matches'] / 1000, 1) }}K
                                                @elseif ($response['get_questions']['total_matches'] >= 1000000 && $response['get_questions']['total_matches'] < 1000000000)
                                                    {{ round($response['get_questions']['total_matches'] / 1000000, 1) }}M
                                                @endif
                                            </span>
                                            <!--end::Number-->
                                            <!--begin::Text-->
                                            <span class="text-dark fw-bolder  lh-0" style="font-size:0.9vw;">
                                                {{ $response['get_questions']['current_month_match'] }}
                                                % this month
                                            </span>
                                            <!--end::Text-->
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                </div>
                                <!--end::Card widget 6-->
                                <!--begin::Card widget 6-->
                                <div class="card card-flush  h-md-50 mb-md-5 mb-xl-10 mt-3"
                                    style="background-color: #E3EAF4; max-height:230px;">
                                    <div class="card-body d-flex flex-column">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-column flex-grow-1">
                                            <!--begin::Title-->
                                            <a class="text-dark  fw-bolder fs-3">Total
                                                Scholars
                                            </a>
                                            <!--end::Title-->
                                            <!--begin::Chart-->
                                            <div class="mixed-widget-21-chart" style="height: 100px"></div>
                                            <!--end::Chart-->
                                        </div>
                                        <!--end::Wrapper-->
                                        <!--begin::Stats-->
                                        <div class="pt-5">
                                            <!--begin::Number-->
                                            <span class="text-dark fw-bolder me-2 lh-0" style="font-size:2vw;">
                                                @if ($response['get_scholars']['total_matches'] >= 0 && $response['get_scholars']['total_matches'] < 1000)
                                                    {{ $response['get_scholars']['total_matches'] }}
                                                @elseif ($response['get_scholars']['total_matches'] >= 1000 && $response['get_scholars']['total_matches'] < 1000000)
                                                    {{ round($response['get_scholars']['total_matches'] / 1000, 1) }}K
                                                @elseif ($response['get_scholars']['total_matches'] >= 1000000 && $response['get_scholars']['total_matches'] < 1000000000)
                                                    {{ round($response['get_scholars']['total_matches'] / 1000000, 1) }}M
                                                @endif

                                            </span>
                                            <!--end::Number-->
                                            <!--begin::Text-->
                                            <span class="text-dark fw-bolder lh-0" style="font-size:0.9vw;">
                                                {{ $response['get_scholars']['current_month_match'] }}
                                                % this month
                                            </span>
                                            <!--end::Text-->
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                </div>
                                <!--end::Card widget 6-->
                            </div>
                            <!--begin::Col-->
                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6">

                                <!--begin::Card widget 6-->
                                <div class="card card-flush  h-md-50 mb-md-5 mb-xl-10 mt-4"
                                    style="background-color: #cdebcd
                                    ; max-height:230px;">
                                    <div class="card-body d-flex flex-column">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-column flex-grow-1">
                                            <!--begin::Title-->
                                            <a class="text-dark  fw-bolder fs-3">Total
                                                Users
                                            </a>
                                            <!--end::Title-->
                                            <!--begin::Chart-->
                                            <div class="mixed-widget-20-chart" style="height: 100px"></div>
                                            <!--end::Chart-->
                                        </div>
                                        <!--end::Wrapper-->
                                        <!--begin::Stats-->
                                        <div class="pt-5">
                                            <!--begin::Number-->
                                            <span class="text-dark fw-bolder  me-2 lh-0" style="font-size:2vw;">
                                                @if ($response['get_all_users']['total_users'] >= 0 && $response['get_all_users']['total_users'] < 1000)
                                                    {{ $response['get_all_users']['total_users'] }}
                                                @elseif ($response['get_all_users']['total_users'] >= 1000 && $response['get_all_users']['total_users'] < 1000000)
                                                    {{ round($response['get_all_users']['total_users'] / 1000, 1) }}K
                                                @elseif ($response['get_all_users']['total_users'] >= 1000000 && $response['get_all_users']['total_users'] < 1000000000)
                                                    {{ round($response['get_all_users']['total_users'] / 1000000, 1) }}M
                                                @endif

                                            </span>
                                            <!--end::Number-->
                                            <!--begin::Text-->
                                            <span class="text-dark fw-bolder  lh-0" style="font-size:0.9vw;">
                                                {{ $response['get_all_users']['current_month'] }}
                                                % this month
                                            </span>
                                            <!--end::Text-->
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                </div>
                                <!--end::Card widget 6-->
                                <!--begin::Card widget 6-->
                                <div class="card card-flush  h-md-50 mb-md-5 mb-xl-10 mt-3"
                                    style="background-color: #F0F1F3; max-height:230px;">
                                    <div class="card-body d-flex flex-column">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-column flex-grow-1">
                                            <!--begin::Title-->
                                            <a class="text-dark  fw-bolder fs-3">Total
                                                Events
                                            </a>
                                            <!--end::Title-->
                                            <!--begin::Chart-->
                                            <div class="mixed-widget-13-chart" style="height: 100px"></div>
                                            <!--end::Chart-->
                                        </div>
                                        <!--end::Wrapper-->
                                        <!--begin::Stats-->
                                        <div class="pt-5">
                                            <!--begin::Number-->
                                            <span class="text-dark fw-bolder  me-2 lh-0" style="font-size:2vw;">
                                                @if ($response['get_all_events']['total_events'] >= 0 && $response['get_all_events']['total_events'] < 1000)
                                                    {{ $response['get_all_events']['total_events'] }}
                                                @elseif ($response['get_all_events']['total_events'] >= 1000 && $response['get_all_events']['total_events'] < 1000000)
                                                    {{ round($response['get_all_events']['total_events'] / 1000, 1) }}K
                                                @elseif ($response['get_all_events']['total_events'] >= 1000000 && $response['get_all_events']['total_events'] < 1000000000)
                                                    {{ round($response['get_all_events']['total_events'] / 1000000, 1) }}M
                                                @endif

                                            </span>
                                            <!--end::Number-->
                                            <!--begin::Text-->
                                            <span class="text-dark fw-bolder  lh-0" style="font-size:0.9vw;">
                                                {{ $response['get_all_events']['current_month'] }}
                                                % this month
                                            </span>
                                            <!--end::Text-->
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                </div>
                                <!--end::Card widget 6-->


                            </div>
                            <!--end::Card body-->

                        </div>
                    </div>
                    <!--end::Col-->

                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-md-2 mb-xl-4">
                        <!--begin::Chart widget 3-->
                        <div class="card card-flush mt-5" style="height: 500px;">
                            <!--begin::Header-->
                            <div class="card-header align-items-center border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="fw-bolder mb-2 text-dark">Recent Activity</span>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            @if ($response['countActivities'] == 0)
                                <div class="card-body pt-5">

                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="{{ '../../public/frontend/media/noActivity.png' }}" alt="image">
                                    </div>
                                </div>
                            @else
                                <div class="card-body pt-5" style="overflow-y: auto;">

                                    <!--begin::Timeline-->
                                    <div class="timeline-label">

                                        <!--begin::Item-->
                                        @php
                                            $arr = ['success', 'primary', 'warning', 'danger'];
                                            $count = 0;
                                        @endphp
                                        @foreach ($response['activities'] as $item)
                                            <div class="timeline-item">
                                                <!--begin::Label-->
                                                <div class="timeline-label fw-bolder text-gray-800 fs-6">
                                                    {{ \Carbon\Carbon::create($item['created_at'])->format('H:i') }}
                                                </div>
                                                <!--end::Label-->
                                                <!--begin::Badge-->
                                                <div class="timeline-badge">
                                                    <i
                                                        class="fa fa-genderless
                                            {{ 'text-' . $arr[$count] }}
                                             fs-1"></i>
                                                </div>
                                                <!--end::Badge-->
                                                <!--begin::Text-->
                                                <div class="fw-mormal timeline-content text-muted ps-3">
                                                    @php
                                                        $link = URL::to(
                                                            'UserDetail/PublicQuestions/' . $item['data_id'],
                                                        );
                                                        echo str_replace(
                                                            'Review',
                                                            "<a href=\"{$link}\">Review</a>",
                                                            $item['message'],
                                                        );
                                                    @endphp
                                                </div>
                                                <!--end::Text-->
                                            </div>
                                            @php
                                                $count = ($count + 1) % count($arr);
                                            @endphp
                                        @endforeach

                                    </div>
                                    <!--end::Timeline-->


                                </div>
                            @endif

                            <!--end: Card Body-->
                        </div>
                        <!--end::Chart widget 3-->
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-2 mb-xl-4">

                    <div class="card card-xl-stretch mb-xl-7 mt-4" style="max-height:250px;">
                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin::Heading-->
                            <div class="fs-1 fw-bolder">
                                {{ $response['accountCount'] }}
                                <span class="badge badge-light-success fw-bolder pt-2">
                                    {{ $response['inApp'] }}%
                                </span>
                            </div>
                            <div class="fs-4 fw-bold mb-7">Total Account created</div>
                            <!--end::Heading-->
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-wrap">
                                <!--begin::Chart-->
                                <div class="d-flex flex-center h-100px w-100px me-9 mb-5">
                                    <canvas id="user_list_chart"></canvas>
                                </div>
                                <!--end::Chart-->
                                <!--begin::Labels-->
                                <div class="d-flex flex-column justify-content-center flex-row-fluid mb-5">
                                    <!--begin::Label-->
                                    <div class="d-flex fs-6 fw-bold align-items-center mb-3">
                                        <div class="bullet me-3"
                                            style="background-color: #F52E2E;height:9px; width:13px;">
                                        </div>
                                        <div class="text-gray-400">In App</div>
                                        <div class="ms-auto fw-bolder text-gray-700">
                                            {{ $response['inApp'] }}%
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                    <!--begin::Label-->
                                    <div class="d-flex fs-6 fw-bold align-items-center mb-3">
                                        <div class="bullet me-3"
                                            style="background-color: #38B89A; height:9px; width:13px;">
                                        </div>
                                        <div class="text-gray-400">Google</div>

                                        <div class="ms-auto fw-bolder text-gray-700">
                                            {{ $response['google'] }}%
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                    <!--begin::Label-->
                                    <div class="d-flex fs-6 fw-bold align-items-center">
                                        <div class="bullet me-3"
                                            style="background-color: #DBDFE9;height:9px; width:13px;">
                                        </div>
                                        <div class="text-gray-400">Apple</div>
                                        <div class="ms-auto fw-bolder text-gray-700">

                                            {{ $response['apple'] }}%

                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Labels-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Card body-->
                    </div>

                    <div class="card card-flush mt-5">
                        <!--begin::Card header-->
                        <div class="card-header col px-10 mt-10" style="min-height: 25px">
                            <!--begin::Card title-->
                            <span>
                                <h3>{{ $response['appointmentCount'] }} <span
                                        class="badge badge-light-success fw-bolder pt-2">
                                        {{ $response['get_all_appoinments']['current_month'] }}
                                        %
                                    </span></h3>
                            </span>
                            <span>
                                <h3 class="fw-bolder mb-1">Appointments</h3>
                            </span>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pb-0 px-5">
                            <!--begin::Chart-->
                            <div id="kt_project_overview_graph" class="card-rounded-bottom" style="height: 250px"></div>
                            <!--end::Chart-->
                        </div>
                        <!--end::Card body-->
                    </div>

                    <div class="card card-flush mt-5">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <div class="d-flex justify-content-between align-items-center col-12">
                                <span class="d-flex flex-column g-0">
                                    <p class="fw-bolder fs-3 mb-0" style="color: #38B89A;">
                                        {{ $response['questionCount'] }} <span
                                            class="badge badge-light-success fw-bolder pt-2">
                                            {{ $response['count'] }}%
                                        </span>
                                    </p>
                                    <p style="color:#B5B5C3;">

                                        Question requests

                                    </p>
                                </span>
                                <span class="d-flex align-items-center justify-content-end">

                                    <div class="d-flex">
                                        <div class="fs-6 d-flex text-gray-400 fs-6 fw-bold ms-7">
                                            <div class="d-flex align-items-center">
                                                <span class="menu-bullet-circle  d-flex align-items-center me-2">
                                                    <span class="bullet-dot bg-success"
                                                        style="width: 10px;height:10px;"></span>
                                                </span>Accepted
                                            </div>
                                        </div>
                                        <!--begin::Labels-->
                                        <div class="fs-6 d-flex text-gray-400 fs-6 fw-bold ms-2">
                                            <!--begin::Label-->
                                            <div class="d-flex align-items-center">
                                                <span class="menu-bullet d-flex align-items-center me-2">
                                                    <span class="bullet-dot bg-danger"
                                                        style="width: 10px;height:10px;"></span> </span>Rejected
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Labels-->
                                    </div>
                                </span>

                            </div>


                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Chart-->
                            <div id="kt_charts_widget_2_chart" style="height: 250px"></div>
                            <!--end::Chart-->
                        </div>
                        <!--end::Body-->
                    </div>

                </div>
            </div>
            <!--end::Container-->
        </div>
    </div>
    <!--end::Content-->
    <script>
        var get_all_appoinments = @json($response['get_all_appoinments']);

        var KTProjectOverviewgraph = (function() {
            var t = KTUtil.getCssVariableValue("--bs-primary"),
                e = KTUtil.getCssVariableValue("--bs-light-primary"),
                a = KTUtil.getCssVariableValue("--bs-success"),
                r = KTUtil.getCssVariableValue("--bs-light-success"),
                o = KTUtil.getCssVariableValue("--bs-gray-200"),
                n = KTUtil.getCssVariableValue("--bs-gray-500");
            return {
                init: function() {
                    var s, i;
                    (s = document.getElementById("kt_project_overview_graph")),
                    (i = parseInt(KTUtil.css(s, "height"))),
                    s &&
                        new ApexCharts(s, {
                            series: [{
                                name: "Appointments",
                                data: [
                                    get_all_appoinments.six_months_before,
                                    get_all_appoinments.five_months_before,
                                    get_all_appoinments.four_months_before,
                                    get_all_appoinments.three_months_before,
                                    get_all_appoinments.two_months_before,
                                    get_all_appoinments.one_month_before,
                                    get_all_appoinments.current_month,
                                ],
                            }, ],
                            chart: {
                                type: "area",
                                height: i,
                                toolbar: {
                                    show: !1
                                },
                            },
                            plotOptions: {},
                            legend: {
                                show: !1
                            },
                            dataLabels: {
                                enabled: !1
                            },
                            fill: {
                                type: "solid",
                                opacity: 1
                            },
                            stroke: {
                                curve: "smooth",
                                show: !0,
                                width: 3,
                                colors: ["#38B89A", a],
                            },
                            xaxis: {
                                categories: [
                                    get_all_appoinments.month_name['six_before'],
                                    get_all_appoinments.month_name['five_before'],
                                    get_all_appoinments.month_name['four_before'],
                                    get_all_appoinments.month_name['three_before'],
                                    get_all_appoinments.month_name['two_before'],
                                    get_all_appoinments.month_name['one_before'],
                                    get_all_appoinments.month_name['current'],
                                ],
                                axisBorder: {
                                    show: !1
                                },
                                axisTicks: {
                                    show: !1
                                },
                                labels: {
                                    style: {
                                        colors: n,
                                        fontSize: "12px"
                                    }
                                },
                                crosshairs: {
                                    position: "front",
                                    stroke: {
                                        color: "#38B89A",
                                        width: 1,
                                        dashArray: 3
                                    },
                                },
                                tooltip: {
                                    enabled: !0,
                                    formatter: void 0,
                                    offsetY: 0,
                                    style: {
                                        fontSize: "12px"
                                    },
                                },
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        colors: n,
                                        fontSize: "12px"
                                    }
                                },
                            },
                            states: {
                                normal: {
                                    filter: {
                                        type: "none",
                                        value: 0
                                    }
                                },
                                hover: {
                                    filter: {
                                        type: "none",
                                        value: 0
                                    }
                                },
                                active: {
                                    allowMultipleDataPointsSelection: !1,
                                    filter: {
                                        type: "none",
                                        value: 0
                                    },
                                },
                            },
                            tooltip: {
                                style: {
                                    fontSize: "12px"
                                },
                                y: {
                                    formatter: function(t) {
                                        return t + "% registered";
                                    },
                                },
                            },
                            colors: ["#FFFFFF", r],
                            grid: {
                                borderColor: o,
                                strokeDashArray: 4,
                                yaxis: {
                                    lines: {
                                        show: !0
                                    }
                                },
                            },
                            markers: {
                                colors: ["#38B89A", r],
                                strokeColor: ["#38B89A", a],
                                strokeWidth: 3,
                            },
                        }).render(),

                        (function() {
                            var t = document.querySelector(
                                "#kt_profile_overview_table"
                            );
                            if (!t) return;
                            t.querySelectorAll("tbody tr").forEach((t) => {
                                const e = t.querySelectorAll("td"),
                                    a = moment(e[1].innerHTML, "MMM D, YYYY").format();
                                e[1].setAttribute("data-order", a);
                            });
                            const e = $(t).DataTable({
                                    info: !1,
                                    order: []
                                }),
                                a = document.getElementById("kt_filter_orders"),
                                r = document.getElementById("kt_filter_year");
                            var o, n;
                            a.addEventListener("change", function(t) {
                                    e.column(3).search(t.target.value).draw();
                                }),
                                r.addEventListener("change", function(t) {
                                    switch (t.target.value) {
                                        case "thisyear":
                                            (o = moment().startOf("year").format()),
                                            (n = moment().endOf("year").format()),
                                            e.draw();
                                            break;
                                        case "thismonth":
                                            (o = moment().startOf("month").format()),
                                            (n = moment().endOf("month").format()),
                                            e.draw();
                                            break;
                                        case "lastmonth":
                                            (o = moment()
                                                .subtract(1, "months")
                                                .startOf("month")
                                                .format()),
                                            (n = moment()
                                                .subtract(1, "months")
                                                .endOf("month")
                                                .format()),
                                            e.draw();
                                            break;
                                        case "last90days":
                                            (o = moment()
                                                .subtract(30, "days")
                                                .format()),
                                            (n = moment().format()),
                                            e.draw();
                                            break;
                                        default:
                                            (o = moment()
                                                .subtract(100, "years")
                                                .startOf("month")
                                                .format()),
                                            (n = moment()
                                                .add(1, "months")
                                                .endOf("month")
                                                .format()),
                                            e.draw();
                                    }
                                }),
                                $.fn.dataTable.ext.search.push(function(t, e, a) {
                                    var r = o,
                                        s = n,
                                        i = parseFloat(moment(e[1]).format()) || 0;
                                    return !!(
                                        (isNaN(r) && isNaN(s)) ||
                                        (isNaN(r) && i <= s) ||
                                        (r <= i && isNaN(s)) ||
                                        (r <= i && i <= s)
                                    );
                                }),
                                document
                                .getElementById("kt_filter_search")
                                .addEventListener("keyup", function(t) {
                                    e.search(t.target.value).draw();
                                });
                        })();
                },
            };
        })();

        KTUtil.onDOMContentLoaded(function() {
            KTProjectOverviewgraph.init();
        });
    </script>
    <script>
        // console.log(output.Monday);
        var get_all_appoinments = @json($response['get_all_appoinments']);
        var get_all_questions = @json($response['get_all_questions']);
        // console.log(get_all_questions);
        (function() {
            var e = document.getElementById("kt_charts_widget_2_chart"),
                t = parseInt(KTUtil.css(e, "height")),
                a = KTUtil.getCssVariableValue("--bs-gray-500"),
                o = KTUtil.getCssVariableValue("--bs-gray-200"),
                r = KTUtil.getCssVariableValue("--bs-gray-300");
            e &&
                new ApexCharts(e, {
                    series: [{
                            name: "Accepted",
                            data: [
                                get_all_questions.six_months_before1,
                                get_all_questions.five_months_before1,
                                get_all_questions.four_months_before1,
                                get_all_questions.three_months_before1,
                                get_all_questions.two_months_before1,
                                get_all_questions.one_month_before1,
                                get_all_questions.current_month1,
                            ],
                        },
                        {
                            name: "Rejected",
                            data: [
                                get_all_questions.six_months_before,
                                get_all_questions.five_months_before,
                                get_all_questions.four_months_before,
                                get_all_questions.three_months_before,
                                get_all_questions.two_months_before,
                                get_all_questions.one_month_before,
                                get_all_questions.current_month,
                            ],
                        },
                    ],
                    chart: {
                        fontFamily: "inherit",
                        type: "bar",
                        height: t,
                        toolbar: {
                            show: !1,
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: !1,
                            columnWidth: ["30%"],
                            borderRadius: 4,
                        },
                    },
                    legend: {
                        show: !1,
                    },
                    dataLabels: {
                        enabled: !1,
                    },
                    stroke: {
                        show: !0,
                        width: 2,
                        colors: ["transparent"],
                    },
                    xaxis: {
                        categories: [
                            get_all_appoinments.month_name['six_before'],
                            get_all_appoinments.month_name['five_before'],
                            get_all_appoinments.month_name['four_before'],
                            get_all_appoinments.month_name['three_before'],
                            get_all_appoinments.month_name['two_before'],
                            get_all_appoinments.month_name['one_before'],
                            get_all_appoinments.month_name['current'],
                        ],
                        axisBorder: {
                            show: !1,
                        },
                        axisTicks: {
                            show: !1,
                        },
                        labels: {
                            style: {
                                colors: a,
                                fontSize: "12px",
                            },
                        },
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: a,
                                fontSize: "12px",
                            },
                        },
                    },
                    fill: {
                        opacity: 1,
                    },
                    states: {
                        normal: {
                            filter: {
                                type: "none",
                                value: 0,
                            },
                        },
                        hover: {
                            filter: {
                                type: "none",
                                value: 0,
                            },
                        },
                        active: {
                            allowMultipleDataPointsSelection: !1,
                            filter: {
                                type: "none",
                                value: 0,
                            },
                        },
                    },
                    tooltip: {
                        style: {
                            fontSize: "12px",
                        },
                        y: {
                            formatter: function(e) {
                                return e + " Cal";
                            },
                        },
                    },

                    colors: ['#38B89A', "#FA4A0C"],
                    grid: {
                        borderColor: o,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: !0,
                            },
                        },
                    },
                }).render();
        })();
    </script>
    <script>
        "use strict";


        var apple = @json($response['apple']);
        var google = @json($response['google']);
        var inApp = @json($response['inApp']);



        if (apple == 0 && google == 0 &&
            inApp == 0) {
            var zerovalue = 100;
        }


        var KTProjectLists = {
            initProjectListChart: function() {
                var t = document.getElementById("user_list_chart");
                if (t) {
                    var e = t.getContext("2d");
                    new Chart(e, {
                        type: "doughnut",
                        innerHeight: 200,
                        data: {
                            datasets: [{
                                data: [inApp, google, apple, zerovalue],
                                backgroundColor: [
                                    "#F52E2E",
                                    "#38B89A",
                                    "#DBDFE9",
                                    '#E0E2EC',
                                ],
                            }, ],
                            labels: [
                                "In App",
                                "Google",
                                "Apple"
                                // "None"
                            ],
                        },
                        options: {
                            chart: {
                                fontFamily: "inherit"
                            },
                            cutout: "75%",
                            cutoutPercentage: 65,
                            responsive: !0,
                            maintainAspectRatio: !1,
                            title: {
                                display: !1
                            },
                            animation: {
                                animateScale: !0,
                                animateRotate: !0
                            },
                            tooltips: {
                                mode: "nearest",
                                enabled: !0,
                                intersect: !1,
                                bodySpacing: 5,
                                yPadding: 10,
                                xPadding: 10,
                                caretPadding: 0,
                                displayColors: !1,
                                backgroundColor: "#20D489",
                                titleFontColor: "#ffffff",
                                cornerRadius: 4,
                                footerSpacing: 0,
                                titleSpacing: 0,
                            },
                            plugins: {
                                legend: {
                                    display: !1
                                }
                            },
                        },
                    });
                }
            },
        };

        document.addEventListener("DOMContentLoaded", function() {
            KTProjectLists.initProjectListChart();
        });
    </script>
    <script>
        var get_all_users = @json($response['get_all_users']);
        var get_all_events = @json($response['get_all_events']);
        var get_questions = @json($response['get_questions']);
        var get_scholars = @json($response['get_scholars']);


        var KTWidget = {
            init: function() {
                var e, t, a, o, s, r, i, l, n, c, d, h;

                t = document.querySelectorAll(".mixed-widget-13-chart");
                [].slice.call(t).map(function(t) {
                    if (((e = parseInt(KTUtil.css(t, "height"))), t)) {
                        var a = KTUtil.getCssVariableValue("--bs-gray-800"),
                            o = KTUtil.getCssVariableValue("--bs-gray-300");
                        new ApexCharts(t, {
                            series: [{
                                name: "Event Registered",
                                data: [
                                    get_all_events.five_months_before,
                                    get_all_events.four_months_before,
                                    get_all_events.three_months_before,
                                    get_all_events.two_months_before,
                                    get_all_events.one_month_before,
                                    get_all_events.current_month,
                                ],
                            }, ],
                            grid: {
                                show: !1,
                                padding: {
                                    top: 0,
                                    bottom: 0,
                                    left: 0,
                                    right: 0,
                                },
                            },
                            chart: {
                                fontFamily: "inherit",
                                type: "area",
                                height: e,
                                toolbar: {
                                    show: !1,
                                },
                                zoom: {
                                    enabled: !1,
                                },
                                sparkline: {
                                    enabled: !0,
                                },
                            },
                            plotOptions: {},
                            legend: {
                                show: !1,
                            },
                            dataLabels: {
                                enabled: !1,
                            },
                            fill: {
                                type: "gradient",
                                gradient: {
                                    opacityFrom: 0.4,
                                    opacityTo: 0,
                                    stops: [20, 120, 120, 120],
                                },
                            },
                            stroke: {
                                curve: "smooth",
                                show: !0,
                                width: 3,
                                colors: ["#FFFFFF"],
                            },
                            xaxis: {
                                categories: [
                                    get_all_events.month_name['five_before'],
                                    get_all_events.month_name['four_before'],
                                    get_all_events.month_name['three_before'],
                                    get_all_events.month_name['two_before'],
                                    get_all_events.month_name['one_before'],
                                    get_all_events.month_name['current'],
                                ],
                                axisBorder: {
                                    show: !1,
                                },
                                axisTicks: {
                                    show: !1,
                                },
                                labels: {
                                    show: !1,
                                    style: {
                                        colors: a,
                                        fontSize: "12px",
                                    },
                                },
                                crosshairs: {
                                    show: !1,
                                    position: "front",
                                    stroke: {
                                        color: o,
                                        width: 1,
                                        dashArray: 3,
                                    },
                                },
                                tooltip: {
                                    enabled: !0,
                                    formatter: void 0,
                                    offsetY: 0,
                                    style: {
                                        fontSize: "12px",
                                    },
                                },
                            },
                            yaxis: {
                                min: 0,
                                max: 60,
                                labels: {
                                    show: !1,
                                    style: {
                                        colors: a,
                                        fontSize: "12px",
                                    },
                                },
                            },
                            states: {
                                normal: {
                                    filter: {
                                        type: "none",
                                        value: 0,
                                    },
                                },
                                hover: {
                                    filter: {
                                        type: "none",
                                        value: 0,
                                    },
                                },
                                active: {
                                    allowMultipleDataPointsSelection: !1,
                                    filter: {
                                        type: "none",
                                        value: 0,
                                    },
                                },
                            },
                            tooltip: {
                                style: {
                                    fontSize: "12px",
                                },
                                y: {
                                    formatter: function(e) {
                                        return e + "% Events";
                                    },
                                },
                            },
                            colors: ["#ffffff"],
                            markers: {
                                colors: [a],
                                strokeColor: [o],
                                strokeWidth: 3,
                            },
                        }).render();
                    }
                });

                t = document.querySelectorAll(".mixed-widget-14-chart");
                [].slice.call(t).map(function(t) {
                    e = parseInt(KTUtil.css(t, "height"));
                    var a = KTUtil.getCssVariableValue("--bs-gray-800");
                    new ApexCharts(t, {
                        series: [{
                            name: "Questions",
                            data: [
                                get_questions.eleven_months_before_match,
                                get_questions.ten_months_before_match,
                                get_questions.nine_months_before_match,
                                get_questions.eight_months_before_match,
                                get_questions.seven_months_before_match,
                                get_questions.six_months_before_match,
                                get_questions.five_months_before_match,
                                get_questions.four_months_before_match,
                                get_questions.three_months_before_match,
                                get_questions.two_months_before_match,
                                get_questions.one_month_before_match,
                                get_questions.current_month_match

                            ],
                        }, ],
                        chart: {
                            fontFamily: "inherit",
                            height: e,
                            type: "bar",
                            toolbar: {
                                show: !1,
                            },
                        },
                        grid: {
                            show: !1,
                            padding: {
                                top: 0,
                                bottom: 0,
                                left: 0,
                                right: 0,
                            },
                        },
                        colors: ["#ffffff"],
                        plotOptions: {
                            bar: {
                                borderRadius: 2.5,
                                dataLabels: {
                                    position: "top",
                                },
                                columnWidth: "20%",
                            },
                        },
                        dataLabels: {
                            enabled: !1,
                            formatter: function(e) {
                                return e + "%";
                            },
                            offsetY: -20,
                            style: {
                                fontSize: "12px",
                                colors: ["#304758"],
                            },
                        },
                        xaxis: {
                            labels: {
                                show: !1,
                            },
                            categories: [
                                get_all_users.month_name['eleven_before'],
                                get_all_users.month_name['ten_before'],
                                get_all_users.month_name['nine_before'],
                                get_all_users.month_name['eight_before'],
                                get_all_users.month_name['seven_before'],
                                get_all_users.month_name['six_before'],
                                get_all_users.month_name['five_before'],
                                get_all_users.month_name['four_before'],
                                get_all_users.month_name['three_before'],
                                get_all_users.month_name['two_before'],
                                get_all_users.month_name['one_before'],
                                get_all_users.month_name['current'],
                            ],
                            position: "top",
                            axisBorder: {
                                show: !1,
                            },
                            axisTicks: {
                                show: !1,
                            },
                            crosshairs: {
                                show: !1,
                            },
                            tooltip: {
                                enabled: !1,
                            },
                        },
                        yaxis: {
                            show: !1,
                            axisBorder: {
                                show: !1,
                            },
                            axisTicks: {
                                show: !1,
                                background: a,
                            },
                            labels: {
                                show: !1,
                                formatter: function(e) {
                                    return e + "%";
                                },
                            },
                        },
                    }).render();
                });

                t = document.querySelectorAll(".mixed-widget-21-chart");
                [].slice.call(t).map(function(t) {
                    e = parseInt(KTUtil.css(t, "height"));
                    var a = KTUtil.getCssVariableValue("--bs-gray-800");
                    new ApexCharts(t, {
                        series: [{
                            name: "Scholars",
                            data: [
                                get_scholars.eleven_months_before_match,
                                get_scholars.ten_months_before_match,
                                get_scholars.nine_months_before_match,
                                get_scholars.eight_months_before_match,
                                get_scholars.seven_months_before_match,
                                get_scholars.six_months_before_match,
                                get_scholars.five_months_before_match,
                                get_scholars.four_months_before_match,
                                get_scholars.three_months_before_match,
                                get_scholars.two_months_before_match,
                                get_scholars.one_month_before_match,
                                get_scholars.current_month_match

                            ],
                        }, ],
                        chart: {
                            fontFamily: "inherit",
                            height: e,
                            type: "bar",
                            toolbar: {
                                show: !1,
                            },
                        },
                        grid: {
                            show: !1,
                            padding: {
                                top: 0,
                                bottom: 0,
                                left: 0,
                                right: 0,
                            },
                        },
                        colors: ["#ffffff"],
                        plotOptions: {
                            bar: {
                                borderRadius: 2.5,
                                dataLabels: {
                                    position: "top",
                                },
                                columnWidth: "20%",
                            },
                        },
                        dataLabels: {
                            enabled: !1,
                            formatter: function(e) {
                                return e + "%";
                            },
                            offsetY: -20,
                            style: {
                                fontSize: "12px",
                                colors: ["#304758"],
                            },
                        },
                        xaxis: {
                            labels: {
                                show: !1,
                            },
                            categories: [
                                get_all_users.month_name['eleven_before'],
                                get_all_users.month_name['ten_before'],
                                get_all_users.month_name['nine_before'],
                                get_all_users.month_name['eight_before'],
                                get_all_users.month_name['seven_before'],
                                get_all_users.month_name['six_before'],
                                get_all_users.month_name['five_before'],
                                get_all_users.month_name['four_before'],
                                get_all_users.month_name['three_before'],
                                get_all_users.month_name['two_before'],
                                get_all_users.month_name['one_before'],
                                get_all_users.month_name['current'],
                            ],
                            position: "top",
                            axisBorder: {
                                show: !1,
                            },
                            axisTicks: {
                                show: !1,
                            },
                            crosshairs: {
                                show: !1,
                            },
                            tooltip: {
                                enabled: !1,
                            },
                        },
                        yaxis: {
                            show: !1,
                            axisBorder: {
                                show: !1,
                            },
                            axisTicks: {
                                show: !1,
                                background: a,
                            },
                            labels: {
                                show: !1,
                                formatter: function(e) {
                                    return e + "%";
                                },
                            },
                        },
                    }).render();
                });

                t = document.querySelectorAll(".mixed-widget-20-chart");
                [].slice.call(t).map(function(t) {
                    if (((e = parseInt(KTUtil.css(t, "height"))), t)) {
                        var a = KTUtil.getCssVariableValue("--bs-gray-800"),
                            o = KTUtil.getCssVariableValue("--bs-gray-300");
                        new ApexCharts(t, {
                            series: [{
                                name: "User Registered",
                                data: [
                                    get_all_users.five_months_before,
                                    get_all_users.four_months_before,
                                    get_all_users.three_months_before,
                                    get_all_users.two_months_before,
                                    get_all_users.one_month_before,
                                    get_all_users.current_month,
                                ],
                            }, ],
                            grid: {
                                show: !1,
                                padding: {
                                    top: 0,
                                    bottom: 0,
                                    left: 0,
                                    right: 0,
                                },
                            },
                            chart: {
                                fontFamily: "inherit",
                                type: "area",
                                height: e,
                                toolbar: {
                                    show: !1,
                                },
                                zoom: {
                                    enabled: !1,
                                },
                                sparkline: {
                                    enabled: !0,
                                },
                            },
                            plotOptions: {},
                            legend: {
                                show: !1,
                            },
                            dataLabels: {
                                enabled: !1,
                            },
                            fill: {
                                type: "gradient",
                                gradient: {
                                    opacityFrom: 0.4,
                                    opacityTo: 0,
                                    stops: [20, 120, 120, 120],
                                },
                            },
                            stroke: {
                                curve: "smooth",
                                show: !0,
                                width: 3,
                                colors: ["#FFFFFF"],
                            },
                            xaxis: {
                                categories: [
                                    get_all_users.month_name['five_before'],
                                    get_all_users.month_name['four_before'],
                                    get_all_users.month_name['three_before'],
                                    get_all_users.month_name['two_before'],
                                    get_all_users.month_name['one_before'],
                                    get_all_users.month_name['current'],

                                ],
                                axisBorder: {
                                    show: !1,
                                },
                                axisTicks: {
                                    show: !1,
                                },
                                labels: {
                                    show: !1,
                                    style: {
                                        colors: a,
                                        fontSize: "12px",
                                    },
                                },
                                crosshairs: {
                                    show: !1,
                                    position: "front",
                                    stroke: {
                                        color: o,
                                        width: 1,
                                        dashArray: 3,
                                    },
                                },
                                tooltip: {
                                    enabled: !0,
                                    formatter: void 0,
                                    offsetY: 0,
                                    style: {
                                        fontSize: "12px",
                                    },
                                },
                            },
                            yaxis: {
                                min: 0,
                                max: 60,
                                labels: {
                                    show: !1,
                                    style: {
                                        colors: a,
                                        fontSize: "12px",
                                    },
                                },
                            },
                            states: {
                                normal: {
                                    filter: {
                                        type: "none",
                                        value: 0,
                                    },
                                },
                                hover: {
                                    filter: {
                                        type: "none",
                                        value: 0,
                                    },
                                },
                                active: {
                                    allowMultipleDataPointsSelection: !1,
                                    filter: {
                                        type: "none",
                                        value: 0,
                                    },
                                },
                            },
                            tooltip: {
                                style: {
                                    fontSize: "12px",
                                },
                                y: {
                                    formatter: function(e) {
                                        return e + "% Users";
                                    },
                                },
                            },
                            colors: ["#ffffff"],
                            markers: {
                                colors: [a],
                                strokeColor: [o],
                                strokeWidth: 3,
                            },
                        }).render();
                    }
                });

            },
        };

        "undefined" != typeof module && (module.exports = KTWidget),
            KTUtil.onDOMContentLoaded(function() {
                KTWidget.init();
            });
    </script>
@endsection
