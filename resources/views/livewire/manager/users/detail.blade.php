<div>
    <div class="row">
        <div class="col-xxl-3">
            <div class="card">
                <div class="card-body p-4">
                    <div>
                        <div class="table-responsive">
                            <table class="table mb-0 table-borderless">
                                <tbody>
                                <tr>
                                    <th><span class="fw-medium">نام:</span></th>
                                    <td>{{$user->name}}</td>
                                </tr>
                                <tr>
                                    <th><span class="fw-medium">موبایل:</span></th>
                                    <td>{{$user->mobile}}</td>
                                </tr>
                                <tr>
                                    <th><span class="fw-medium">ایمیل</span></th>
                                    <td>{{$user->email ?? 'ایمیلی ثبت نشده است'}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 border-top border-top-dashed">
                    <h6 class="text-muted text-uppercase fw-semibold mb-4">ارسال پیام به :{{$user->name}}</h6>
                    <form action="#">
                        <div class="mb-3">
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="4"
                                      placeholder="لطفا از ارسال پیام بی مورد خودداری کنید"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary"><i
                                    class="ri-mail-send-line align-bottom me-1"></i>ارسال پیام
                            </button>
                        </div>
                    </form>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->

        <div class="col-xxl-9">
            <div class="card">
                <div class="card-header border-0 align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">درآمد</h4>
                    <div>
                        <button type="button" class="btn btn-soft-secondary btn-sm">همه</button>
                        <button type="button" class="btn btn-soft-secondary btn-sm">1M</button>
                        <button type="button" class="btn btn-soft-secondary btn-sm">کامپیوتر</button>
                        <button type="button" class="btn btn-soft-primary btn-sm">1Y</button>
                    </div>
                </div><!-- end card header -->

                <div class="card-header p-0 border-0 bg-light-subtle">
                    <div class="row g-0 text-center">
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="7585">7585</span></h5>
                                <p class="text-muted mb-0">سفارشات</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1">$<span class="counter-value" data-target="22.89">22.89</span>k</h5>
                                <p class="text-muted mb-0">درآمد</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="367">367</span></h5>
                                <p class="text-muted mb-0">بازپرداخت</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0 border-end-0">
                                <h5 class="mb-1 text-success"><span class="counter-value"
                                                                    data-target="18.92">18.92</span>%</h5>
                                <p class="text-muted mb-0">نسبت مکالمه</p>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                </div><!-- end card header -->

                <div class="card-body p-0 pb-2">
                    <div>
                        <div id="customer_impression_charts"
                             data-colors="[&quot;--vz-primary&quot;, &quot;--vz-success&quot;, &quot;--vz-danger&quot;]"
                             data-colors-minimal="[&quot;--vz-primary-rgb, 0.1&quot;, &quot;--vz-primary&quot;, &quot;--vz-primary-rgb, 0.6&quot;]"
                             data-colors-saas="[&quot;--vz-success&quot;, &quot;--vz-primary&quot;, &quot;--vz-danger&quot;]"
                             data-colors-creative="[&quot;--vz-warning&quot;, &quot;--vz-secondary&quot;, &quot;--vz-success&quot;]"
                             class="apex-charts" style="min-height: 385px;">
                            <div id="apexcharts9k0lhhm2" class="apexcharts-canvas apexcharts9k0lhhm2 apexcharts-theme-"
                                 style="width: 543px; height: 370px;">
                                <svg id="SvgjsSvg1001" width="543" height="370" xmlns="http://www.w3.org/2000/svg"
                                     version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable"
                                     xmlns:data="ApexChartsNS" transform="translate(0, 0)">
                                    <foreignObject x="0" y="0" width="543" height="370">
                                        <div xmlns="http://www.w3.org/1999/xhtml"
                                             style="position: relative; height: 100%; width: 100%;">
                                            <div
                                                class="apexcharts-legend apexcharts-align-center apx-legend-position-bottom"
                                                style="right: 0px; position: absolute; left: 20px; top: 340.282px; max-height: 185px;">
                                                <div class="apexcharts-legend-series" rel="1" seriesname="Orders"
                                                     data:collapsed="false" style="margin: 0px 10px;"><span
                                                        class="apexcharts-legend-marker" rel="1" data:collapsed="false"
                                                        style="height: 16px; width: 16px; left: 0px; top: 0px;"><svg
                                                            id="SvgjsSvg1004" width="100%" height="100%"
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            xmlns:svgjs="http://svgjs.dev"><defs id="SvgjsDefs1005"><clipPath
                                                                    id="gridRectMask9k0lhhm2"><rect id="SvgjsRect1022"
                                                                                                    width="454.16678355823865"
                                                                                                    height="261.1342717933655"
                                                                                                    x="0" y="0" rx="0"
                                                                                                    ry="0" opacity="1"
                                                                                                    stroke-width="0"
                                                                                                    stroke="none"
                                                                                                    stroke-dasharray="0"
                                                                                                    fill="#fff"></rect></clipPath><clipPath
                                                                    id="gridRectBarMask9k0lhhm2"><rect
                                                                        id="SvgjsRect1023" width="486.56871337890624"
                                                                        height="267.3342717933655"
                                                                        x="-16.20096491033381" y="-3.1" rx="0" ry="0"
                                                                        opacity="1" stroke-width="0" stroke="none"
                                                                        stroke-dasharray="0"
                                                                        fill="#fff"></rect></clipPath><clipPath
                                                                    id="gridRectMarkerMask9k0lhhm2"><rect
                                                                        id="SvgjsRect1024" width="454.16678355823865"
                                                                        height="261.1342717933655" x="0" y="0" rx="0"
                                                                        ry="0" opacity="1" stroke-width="0"
                                                                        stroke="none" stroke-dasharray="0"
                                                                        fill="#fff"></rect></clipPath><clipPath
                                                                    id="forecastMask9k0lhhm2"></clipPath><clipPath
                                                                    id="nonForecastMask9k0lhhm2"></clipPath></defs><path
                                                                id="SvgjsPath1011" d="M 0, 0
           m -7, 0
           a 7,7 0 1,0 14,0
           a 7,7 0 1,0 -14,0" fill="#405189" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="1"
                                                                stroke-dasharray="0" cx="0" cy="0" shape="circle"
                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                style="transform: translate(50%, 50%);"></path></svg></span><span
                                                        class="apexcharts-legend-text" rel="1" i="0"
                                                        data:default-text="Orders" data:collapsed="false"
                                                        style="color: rgb(55, 61, 63); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">Orders</span>
                                                </div>
                                                <div class="apexcharts-legend-series" rel="2" seriesname="Earnings"
                                                     data:collapsed="false" style="margin: 0px 10px;"><span
                                                        class="apexcharts-legend-marker" rel="2" data:collapsed="false"
                                                        style="height: 16px; width: 16px; left: 0px; top: 0px;"><svg
                                                            id="SvgjsSvg1012" width="100%" height="100%"
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            xmlns:svgjs="http://svgjs.dev"><defs
                                                                id="SvgjsDefs1013"></defs><path id="SvgjsPath1014" d="M 0, 0
           m -7, 0
           a 7,7 0 1,0 14,0
           a 7,7 0 1,0 -14,0" fill="#0ab39c" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                                                stroke-linecap="butt"
                                                                                                stroke-width="1"
                                                                                                stroke-dasharray="0"
                                                                                                cx="0" cy="0"
                                                                                                shape="circle"
                                                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                                                style="transform: translate(50%, 50%);"></path></svg></span><span
                                                        class="apexcharts-legend-text" rel="2" i="1"
                                                        data:default-text="Earnings" data:collapsed="false"
                                                        style="color: rgb(55, 61, 63); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">Earnings</span>
                                                </div>
                                                <div class="apexcharts-legend-series" rel="3" seriesname="Refunds"
                                                     data:collapsed="false" style="margin: 0px 10px;"><span
                                                        class="apexcharts-legend-marker" rel="3" data:collapsed="false"
                                                        style="height: 16px; width: 16px; left: 0px; top: 0px;"><svg
                                                            id="SvgjsSvg1015" width="100%" height="100%"
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            xmlns:svgjs="http://svgjs.dev"><defs
                                                                id="SvgjsDefs1016"></defs><path id="SvgjsPath1017" d="M 0, 0
           m -7, 0
           a 7,7 0 1,0 14,0
           a 7,7 0 1,0 -14,0" fill="#f06548" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                                                stroke-linecap="butt"
                                                                                                stroke-width="1"
                                                                                                stroke-dasharray="0"
                                                                                                cx="0" cy="0"
                                                                                                shape="circle"
                                                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                                                style="transform: translate(50%, 50%);"></path></svg></span><span
                                                        class="apexcharts-legend-text" rel="3" i="2"
                                                        data:default-text="Refunds" data:collapsed="false"
                                                        style="color: rgb(55, 61, 63); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">Refunds</span>
                                                </div>
                                            </div>
                                        </div>
                                        <style type="text/css">
                                            .apexcharts-legend {
                                                display: flex;
                                                overflow: auto;
                                                padding: 0 10px;
                                            }

                                            .apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
                                                flex-wrap: wrap
                                            }

                                            .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
                                                flex-direction: column;
                                                bottom: 0;
                                            }

                                            .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
                                                justify-content: flex-start;
                                            }

                                            .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                justify-content: center;
                                            }

                                            .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                justify-content: flex-end;
                                            }

                                            .apexcharts-legend-series {
                                                cursor: pointer;
                                                line-height: normal;
                                                display: flex;
                                                align-items: center;
                                            }

                                            .apexcharts-legend-text {
                                                position: relative;
                                                font-size: 14px;
                                            }

                                            .apexcharts-legend-text *, .apexcharts-legend-marker * {
                                                pointer-events: none;
                                            }

                                            .apexcharts-legend-marker {
                                                position: relative;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                cursor: pointer;
                                                margin-right: 1px;
                                            }

                                            .apexcharts-legend-series.apexcharts-no-click {
                                                cursor: auto;
                                            }

                                            .apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
                                                display: none !important;
                                            }

                                            .apexcharts-inactive-legend {
                                                opacity: 0.45;
                                            }</style>
                                    </foreignObject>
                                    <rect id="SvgjsRect1020" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1"
                                          stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                    <g id="SvgjsG1025" class="apexcharts-datalabels-group"
                                       transform="translate(0, 0) scale(1)"></g>
                                    <g id="SvgjsG1026" class="apexcharts-datalabels-group"
                                       transform="translate(0, 0) scale(1)"></g>
                                    <g id="SvgjsG1125" class="apexcharts-yaxis" rel="0"
                                       transform="translate(25.330400466918945, 0)">
                                        <g id="SvgjsG1126" class="apexcharts-yaxis-texts-g">
                                            <text id="SvgjsText1128" font-family="Helvetica, Arial, sans-serif" x="20"
                                                  y="33.666666666666664" text-anchor="end" dominant-baseline="auto"
                                                  font-size="11px" font-weight="400" fill="#373d3f"
                                                  class="apexcharts-text apexcharts-yaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1129">120.00</tspan>
                                                <title>120.00</title></text>
                                            <text id="SvgjsText1131" font-family="Helvetica, Arial, sans-serif" x="20"
                                                  y="77.18904529889424" text-anchor="end" dominant-baseline="auto"
                                                  font-size="11px" font-weight="400" fill="#373d3f"
                                                  class="apexcharts-text apexcharts-yaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1132">100.00</tspan>
                                                <title>100.00</title></text>
                                            <text id="SvgjsText1134" font-family="Helvetica, Arial, sans-serif" x="20"
                                                  y="120.71142393112183" text-anchor="end" dominant-baseline="auto"
                                                  font-size="11px" font-weight="400" fill="#373d3f"
                                                  class="apexcharts-text apexcharts-yaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1135">80.00</tspan>
                                                <title>80.00</title></text>
                                            <text id="SvgjsText1137" font-family="Helvetica, Arial, sans-serif" x="20"
                                                  y="164.2338025633494" text-anchor="end" dominant-baseline="auto"
                                                  font-size="11px" font-weight="400" fill="#373d3f"
                                                  class="apexcharts-text apexcharts-yaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1138">60.00</tspan>
                                                <title>60.00</title></text>
                                            <text id="SvgjsText1140" font-family="Helvetica, Arial, sans-serif" x="20"
                                                  y="207.75618119557697" text-anchor="end" dominant-baseline="auto"
                                                  font-size="11px" font-weight="400" fill="#373d3f"
                                                  class="apexcharts-text apexcharts-yaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1141">40.00</tspan>
                                                <title>40.00</title></text>
                                            <text id="SvgjsText1143" font-family="Helvetica, Arial, sans-serif" x="20"
                                                  y="251.27855982780454" text-anchor="end" dominant-baseline="auto"
                                                  font-size="11px" font-weight="400" fill="#373d3f"
                                                  class="apexcharts-text apexcharts-yaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1144">20.00</tspan>
                                                <title>20.00</title></text>
                                            <text id="SvgjsText1146" font-family="Helvetica, Arial, sans-serif" x="20"
                                                  y="294.8009384600321" text-anchor="end" dominant-baseline="auto"
                                                  font-size="11px" font-weight="400" fill="#373d3f"
                                                  class="apexcharts-text apexcharts-yaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan id="SvgjsTspan1147">0.00</tspan>
                                                <title>0.00</title></text>
                                        </g>
                                    </g>
                                    <g id="SvgjsG1003" class="apexcharts-inner apexcharts-graphical"
                                       transform="translate(66.43136537725275, 30)">
                                        <defs id="SvgjsDefs1002"></defs>
                                        <line id="SvgjsLine1021" x1="0" y1="0" x2="0" y2="261.1342717933655"
                                              stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt"
                                              class="apexcharts-xcrosshairs" x="0" y="0" width="1"
                                              height="261.1342717933655" fill="#b1b9c4" filter="none" fill-opacity="0.9"
                                              stroke-width="1"></line>
                                        <g id="SvgjsG1067" class="apexcharts-grid">
                                            <g id="SvgjsG1068" class="apexcharts-gridlines-horizontal"></g>
                                            <g id="SvgjsG1069" class="apexcharts-gridlines-vertical">
                                                <line id="SvgjsLine1071" x1="0" y1="0" x2="0" y2="261.1342717933655"
                                                      stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1072" x1="41.28788941438533" y1="0"
                                                      x2="41.28788941438533" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1073" x1="82.57577882877067" y1="0"
                                                      x2="82.57577882877067" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1074" x1="123.863668243156" y1="0"
                                                      x2="123.863668243156" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1075" x1="165.15155765754133" y1="0"
                                                      x2="165.15155765754133" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1076" x1="206.43944707192668" y1="0"
                                                      x2="206.43944707192668" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1077" x1="247.72733648631203" y1="0"
                                                      x2="247.72733648631203" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1078" x1="289.0152259006974" y1="0"
                                                      x2="289.0152259006974" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1079" x1="330.3031153150827" y1="0"
                                                      x2="330.3031153150827" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1080" x1="371.59100472946807" y1="0"
                                                      x2="371.59100472946807" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1081" x1="412.8788941438534" y1="0"
                                                      x2="412.8788941438534" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine1082" x1="454.16678355823876" y1="0"
                                                      x2="454.16678355823876" y2="261.1342717933655" stroke="#e0e0e0"
                                                      stroke-dasharray="0" stroke-linecap="butt"
                                                      class="apexcharts-gridline"></line>
                                            </g>
                                            <line id="SvgjsLine1084" x1="0" y1="261.1342717933655"
                                                  x2="454.16678355823865" y2="261.1342717933655" stroke="transparent"
                                                  stroke-dasharray="0" stroke-linecap="butt"></line>
                                            <line id="SvgjsLine1083" x1="0" y1="1" x2="0" y2="261.1342717933655"
                                                  stroke="transparent" stroke-dasharray="0"
                                                  stroke-linecap="butt"></line>
                                        </g>
                                        <g id="SvgjsG1070" class="apexcharts-grid-borders"></g>
                                        <g id="SvgjsG1027" class="apexcharts-area-series apexcharts-plot-series">
                                            <g id="SvgjsG1028" class="apexcharts-series" zIndex="0" seriesName="Orders"
                                               data:longestSeries="true" rel="1" data:realIndex="0">
                                                <path id="SvgjsPath1031"
                                                      d="M0 187.14622811857862L41.28788941438533 119.68654123862586L82.57577882877067 161.03280093924207L123.86366824315598 113.15818444379173L165.15155765754133 154.5044441444079L206.43944707192665 128.39101696507137L247.72733648631197 169.73727666568757L289.0152259006973 165.3850388024648L330.30311531508266 91.39699512767794L371.59100472946795 147.9760873495738L412.8788941438533 124.0387791018486L454.16678355823865 115.3343033754031L454.16678355823865 261.1342717933655L0 261.1342717933655C0 261.1342717933655 0 187.14622811857862 0 187.14622811857862 "
                                                      fill="rgba(64,81,137,0.1)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-area" index="0"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 0 187.14622811857862 L 41.28788941438533 119.68654123862586 L 82.57577882877067 161.03280093924207 L 123.86366824315598 113.15818444379173 L 165.15155765754133 154.5044441444079 L 206.43944707192665 128.39101696507137 L 247.72733648631197 169.73727666568757 L 289.0152259006973 165.3850388024648 L 330.30311531508266 91.39699512767794 L 371.59100472946795 147.9760873495738 L 412.8788941438533 124.0387791018486 L 454.16678355823865 115.3343033754031 L 454.16678355823865 261.1342717933655 L 0 261.1342717933655z"
                                                      pathFrom="M 0 261.1342717933655 L 0 261.1342717933655 L 41.28788941438533 261.1342717933655 L 82.57577882877067 261.1342717933655 L 123.86366824315598 261.1342717933655 L 165.15155765754133 261.1342717933655 L 206.43944707192665 261.1342717933655 L 247.72733648631197 261.1342717933655 L 289.0152259006973 261.1342717933655 L 330.30311531508266 261.1342717933655 L 371.59100472946795 261.1342717933655 L 412.8788941438533 261.1342717933655 L 454.16678355823865 261.1342717933655z"></path>
                                                <path id="SvgjsPath1032"
                                                      d="M0 187.14622811857862L41.28788941438533 119.68654123862586L82.57577882877067 161.03280093924207L123.86366824315598 113.15818444379173L165.15155765754133 154.5044441444079L206.43944707192665 128.39101696507137L247.72733648631197 169.73727666568757L289.0152259006973 165.3850388024648L330.30311531508266 91.39699512767794L371.59100472946795 147.9760873495738L412.8788941438533 124.0387791018486L454.16678355823865 115.3343033754031C454.16678355823865 115.3343033754031 454.16678355823865 115.3343033754031 454.16678355823865 115.3343033754031 "
                                                      fill="none" fill-opacity="1" stroke="#405189" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="2" stroke-dasharray="0"
                                                      class="apexcharts-area" index="0"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 0 187.14622811857862 L 41.28788941438533 119.68654123862586 L 82.57577882877067 161.03280093924207 L 123.86366824315598 113.15818444379173 L 165.15155765754133 154.5044441444079 L 206.43944707192665 128.39101696507137 L 247.72733648631197 169.73727666568757 L 289.0152259006973 165.3850388024648 L 330.30311531508266 91.39699512767794 L 371.59100472946795 147.9760873495738 L 412.8788941438533 124.0387791018486 L 454.16678355823865 115.3343033754031"
                                                      pathFrom="M 0 261.1342717933655 L 0 261.1342717933655 L 41.28788941438533 261.1342717933655 L 82.57577882877067 261.1342717933655 L 123.86366824315598 261.1342717933655 L 165.15155765754133 261.1342717933655 L 206.43944707192665 261.1342717933655 L 247.72733648631197 261.1342717933655 L 289.0152259006973 261.1342717933655 L 330.30311531508266 261.1342717933655 L 371.59100472946795 261.1342717933655 L 412.8788941438533 261.1342717933655 L 454.16678355823865 261.1342717933655"
                                                      fill-rule="evenodd"></path>
                                                <g id="SvgjsG1029"
                                                   class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                   data:realIndex="0">
                                                    <g class="apexcharts-series-markers">
                                                        <path id="SvgjsPath1151" d="M 0, 0
           m -0, 0
           a 0,0 0 1,0 0,0
           a 0,0 0 1,0 -0,0" fill="#405189" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                              stroke-width="2" stroke-dasharray="0" cx="0" cy="0"
                                                              shape="circle" class="apexcharts-marker wwztf9dlv"
                                                              default-marker-size="0"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                        <g id="SvgjsG1033" class="apexcharts-bar-series apexcharts-plot-series">
                                            <g id="SvgjsG1034" class="apexcharts-series" rel="1" seriesName="Earnings"
                                               data:realIndex="1">
                                                <path id="SvgjsPath1039"
                                                      d="M-6.193183412157801 261.13527179336546L-6.193183412157801 66.91665714704993L6.193183412157801 66.91665714704993L6.193183412157801 261.13527179336546L-6.193183412157801 261.13527179336546C-6.193183412157801 261.13527179336546 -6.193183412157801 261.13527179336546 -6.193183412157801 261.13527179336546C-6.193183412157801 261.13527179336546 -6.193183412157801 261.13527179336546 -6.193183412157801 261.13527179336546C-6.193183412157801 261.13527179336546 -6.193183412157801 261.13527179336546 -6.193183412157801 261.13527179336546C-6.193183412157801 261.13527179336546 -6.193183412157801 261.13527179336546 -6.193183412157801 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M -6.193183412157801 261.13527179336546 L -6.193183412157801 66.91665714704993 L 6.193183412157801 66.91665714704993 L 6.193183412157801 261.13527179336546 Z"
                                                      pathFrom="M -6.193183412157801 261.13527179336546 L -6.193183412157801 261.13527179336546 L 6.193183412157801 261.13527179336546 L 6.193183412157801 261.13527179336546 L 6.193183412157801 261.13527179336546 L 6.193183412157801 261.13527179336546 L 6.193183412157801 261.13527179336546 L -6.193183412157801 261.13527179336546 Z"
                                                      cy="66.91565714704993" cx="6.193183412157801" j="0" val="34"
                                                      barHeight="194.21861464631556"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1041"
                                                      d="M35.09470600222753 261.13527179336546L35.09470600222753 46.61346751511576L47.481072826543134 46.61346751511576L47.481072826543134 261.13527179336546L35.09470600222753 261.13527179336546C35.09470600222753 261.13527179336546 35.09470600222753 261.13527179336546 35.09470600222753 261.13527179336546C35.09470600222753 261.13527179336546 35.09470600222753 261.13527179336546 35.09470600222753 261.13527179336546C35.09470600222753 261.13527179336546 35.09470600222753 261.13527179336546 35.09470600222753 261.13527179336546C35.09470600222753 261.13527179336546 35.09470600222753 261.13527179336546 35.09470600222753 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 35.09470600222753 261.13527179336546 L 35.09470600222753 46.61346751511575 L 47.481072826543134 46.61346751511575 L 47.481072826543134 261.13527179336546 Z"
                                                      pathFrom="M 35.09470600222753 261.13527179336546 L 35.09470600222753 261.13527179336546 L 47.481072826543134 261.13527179336546 L 47.481072826543134 261.13527179336546 L 47.481072826543134 261.13527179336546 L 47.481072826543134 261.13527179336546 L 47.481072826543134 261.13527179336546 L 35.09470600222753 261.13527179336546 Z"
                                                      cy="46.61246751511575" cx="47.481072826543134" j="1" val="65"
                                                      barHeight="214.52180427824973"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1043"
                                                      d="M76.38259541661287 261.13527179336546L76.38259541661287 111.54885643439931L88.76896224092847 111.54885643439931L88.76896224092847 261.13527179336546L76.38259541661287 261.13527179336546C76.38259541661287 261.13527179336546 76.38259541661287 261.13527179336546 76.38259541661287 261.13527179336546C76.38259541661287 261.13527179336546 76.38259541661287 261.13527179336546 76.38259541661287 261.13527179336546C76.38259541661287 261.13527179336546 76.38259541661287 261.13527179336546 76.38259541661287 261.13527179336546C76.38259541661287 261.13527179336546 76.38259541661287 261.13527179336546 76.38259541661287 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 76.38259541661287 261.13527179336546 L 76.38259541661287 111.54885643439931 L 88.76896224092847 111.54885643439931 L 88.76896224092847 261.13527179336546 Z"
                                                      pathFrom="M 76.38259541661287 261.13527179336546 L 76.38259541661287 261.13527179336546 L 88.76896224092847 261.13527179336546 L 88.76896224092847 261.13527179336546 L 88.76896224092847 261.13527179336546 L 88.76896224092847 261.13527179336546 L 88.76896224092847 261.13527179336546 L 76.38259541661287 261.13527179336546 Z"
                                                      cy="111.5478564343993" cx="88.76896224092847" j="2" val="46"
                                                      barHeight="149.58641535896618"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1045"
                                                      d="M117.67048483099819 261.13527179336546L117.67048483099819 24.22120370883465L130.05685165531378 24.22120370883465L130.05685165531378 261.13527179336546L117.67048483099819 261.13527179336546C117.67048483099819 261.13527179336546 117.67048483099819 261.13527179336546 117.67048483099819 261.13527179336546C117.67048483099819 261.13527179336546 117.67048483099819 261.13527179336546 117.67048483099819 261.13527179336546C117.67048483099819 261.13527179336546 117.67048483099819 261.13527179336546 117.67048483099819 261.13527179336546C117.67048483099819 261.13527179336546 117.67048483099819 261.13527179336546 117.67048483099819 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 117.67048483099819 261.13527179336546 L 117.67048483099819 24.221203708834647 L 130.05685165531378 24.221203708834647 L 130.05685165531378 261.13527179336546 Z"
                                                      pathFrom="M 117.67048483099819 261.13527179336546 L 117.67048483099819 261.13527179336546 L 130.05685165531378 261.13527179336546 L 130.05685165531378 261.13527179336546 L 130.05685165531378 261.13527179336546 L 130.05685165531378 261.13527179336546 L 130.05685165531378 261.13527179336546 L 117.67048483099819 261.13527179336546 Z"
                                                      cy="24.220203708834646" cx="130.05685165531378" j="3" val="68"
                                                      barHeight="236.91406808453084"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1047"
                                                      d="M158.95837424538354 261.13527179336546L158.95837424538354 92.39900983621916L171.34474106969913 92.39900983621916L171.34474106969913 261.13527179336546L158.95837424538354 261.13527179336546C158.95837424538354 261.13527179336546 158.95837424538354 261.13527179336546 158.95837424538354 261.13527179336546C158.95837424538354 261.13527179336546 158.95837424538354 261.13527179336546 158.95837424538354 261.13527179336546C158.95837424538354 261.13527179336546 158.95837424538354 261.13527179336546 158.95837424538354 261.13527179336546C158.95837424538354 261.13527179336546 158.95837424538354 261.13527179336546 158.95837424538354 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 158.95837424538354 261.13527179336546 L 158.95837424538354 92.39900983621916 L 171.34474106969913 92.39900983621916 L 171.34474106969913 261.13527179336546 Z"
                                                      pathFrom="M 158.95837424538354 261.13527179336546 L 158.95837424538354 261.13527179336546 L 171.34474106969913 261.13527179336546 L 171.34474106969913 261.13527179336546 L 171.34474106969913 261.13527179336546 L 171.34474106969913 261.13527179336546 L 171.34474106969913 261.13527179336546 L 158.95837424538354 261.13527179336546 Z"
                                                      cy="92.39800983621916" cx="171.34474106969913" j="4" val="49"
                                                      barHeight="168.73626195714633"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1049"
                                                      d="M200.24626365976886 261.13527179336546L200.24626365976886 78.27599797006133L212.63263048408447 78.27599797006133L212.63263048408447 261.13527179336546L200.24626365976886 261.13527179336546C200.24626365976886 261.13527179336546 200.24626365976886 261.13527179336546 200.24626365976886 261.13527179336546C200.24626365976886 261.13527179336546 200.24626365976886 261.13527179336546 200.24626365976886 261.13527179336546C200.24626365976886 261.13527179336546 200.24626365976886 261.13527179336546 200.24626365976886 261.13527179336546C200.24626365976886 261.13527179336546 200.24626365976886 261.13527179336546 200.24626365976886 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 200.24626365976886 261.13527179336546 L 200.24626365976886 78.27599797006133 L 212.63263048408447 78.27599797006133 L 212.63263048408447 261.13527179336546 Z"
                                                      pathFrom="M 200.24626365976886 261.13527179336546 L 200.24626365976886 261.13527179336546 L 212.63263048408447 261.13527179336546 L 212.63263048408447 261.13527179336546 L 212.63263048408447 261.13527179336546 L 212.63263048408447 261.13527179336546 L 212.63263048408447 261.13527179336546 L 200.24626365976886 261.13527179336546 Z"
                                                      cy="78.27499797006132" cx="212.63263048408447" j="5" val="61"
                                                      barHeight="182.85927382330416"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1051"
                                                      d="M241.53415307415418 261.13527179336546L241.53415307415418 149.63093773759843L253.92051989846976 149.63093773759843L253.92051989846976 261.13527179336546L241.53415307415418 261.13527179336546C241.53415307415418 261.13527179336546 241.53415307415418 261.13527179336546 241.53415307415418 261.13527179336546C241.53415307415418 261.13527179336546 241.53415307415418 261.13527179336546 241.53415307415418 261.13527179336546C241.53415307415418 261.13527179336546 241.53415307415418 261.13527179336546 241.53415307415418 261.13527179336546C241.53415307415418 261.13527179336546 241.53415307415418 261.13527179336546 241.53415307415418 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 241.53415307415418 261.13527179336546 L 241.53415307415418 149.63093773759843 L 253.92051989846976 149.63093773759843 L 253.92051989846976 261.13527179336546 Z"
                                                      pathFrom="M 241.53415307415418 261.13527179336546 L 241.53415307415418 261.13527179336546 L 253.92051989846976 261.13527179336546 L 253.92051989846976 261.13527179336546 L 253.92051989846976 261.13527179336546 L 253.92051989846976 261.13527179336546 L 253.92051989846976 261.13527179336546 L 241.53415307415418 261.13527179336546 Z"
                                                      cy="149.62993773759842" cx="253.92051989846976" j="6" val="42"
                                                      barHeight="111.50433405576706"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1053"
                                                      d="M282.8220424885395 261.13527179336546L282.8220424885395 198.96355391722838L295.2084093128551 198.96355391722838L295.2084093128551 261.13527179336546L282.8220424885395 261.13527179336546C282.8220424885395 261.13527179336546 282.8220424885395 261.13527179336546 282.8220424885395 261.13527179336546C282.8220424885395 261.13527179336546 282.8220424885395 261.13527179336546 282.8220424885395 261.13527179336546C282.8220424885395 261.13527179336546 282.8220424885395 261.13527179336546 282.8220424885395 261.13527179336546C282.8220424885395 261.13527179336546 282.8220424885395 261.13527179336546 282.8220424885395 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 282.8220424885395 261.13527179336546 L 282.8220424885395 198.96355391722838 L 295.2084093128551 198.96355391722838 L 295.2084093128551 261.13527179336546 Z"
                                                      pathFrom="M 282.8220424885395 261.13527179336546 L 282.8220424885395 261.13527179336546 L 295.2084093128551 261.13527179336546 L 295.2084093128551 261.13527179336546 L 295.2084093128551 261.13527179336546 L 295.2084093128551 261.13527179336546 L 295.2084093128551 261.13527179336546 L 282.8220424885395 261.13527179336546 Z"
                                                      cy="198.96255391722838" cx="295.2084093128551" j="7" val="44"
                                                      barHeight="62.171717876137095"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1055"
                                                      d="M324.10993190292487 261.13527179336546L324.10993190292487 59.69194229410016L336.49629872724046 59.69194229410016L336.49629872724046 261.13527179336546L324.10993190292487 261.13527179336546C324.10993190292487 261.13527179336546 324.10993190292487 261.13527179336546 324.10993190292487 261.13527179336546C324.10993190292487 261.13527179336546 324.10993190292487 261.13527179336546 324.10993190292487 261.13527179336546C324.10993190292487 261.13527179336546 324.10993190292487 261.13527179336546 324.10993190292487 261.13527179336546C324.10993190292487 261.13527179336546 324.10993190292487 261.13527179336546 324.10993190292487 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 324.10993190292487 261.13527179336546 L 324.10993190292487 59.69194229410015 L 336.49629872724046 59.69194229410015 L 336.49629872724046 261.13527179336546 Z"
                                                      pathFrom="M 324.10993190292487 261.13527179336546 L 324.10993190292487 261.13527179336546 L 336.49629872724046 261.13527179336546 L 336.49629872724046 261.13527179336546 L 336.49629872724046 261.13527179336546 L 336.49629872724046 261.13527179336546 L 336.49629872724046 261.13527179336546 L 324.10993190292487 261.13527179336546 Z"
                                                      cy="59.690942294100154" cx="336.49629872724046" j="8" val="78"
                                                      barHeight="201.44332949926533"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1057"
                                                      d="M365.39782131731016 261.13527179336546L365.39782131731016 168.95487385030748L377.78418814162575 168.95487385030748L377.78418814162575 261.13527179336546L365.39782131731016 261.13527179336546C365.39782131731016 261.13527179336546 365.39782131731016 261.13527179336546 365.39782131731016 261.13527179336546C365.39782131731016 261.13527179336546 365.39782131731016 261.13527179336546 365.39782131731016 261.13527179336546C365.39782131731016 261.13527179336546 365.39782131731016 261.13527179336546 365.39782131731016 261.13527179336546C365.39782131731016 261.13527179336546 365.39782131731016 261.13527179336546 365.39782131731016 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 365.39782131731016 261.13527179336546 L 365.39782131731016 168.95487385030748 L 377.78418814162575 168.95487385030748 L 377.78418814162575 261.13527179336546 Z"
                                                      pathFrom="M 365.39782131731016 261.13527179336546 L 365.39782131731016 261.13527179336546 L 377.78418814162575 261.13527179336546 L 377.78418814162575 261.13527179336546 L 377.78418814162575 261.13527179336546 L 377.78418814162575 261.13527179336546 L 377.78418814162575 261.13527179336546 L 365.39782131731016 261.13527179336546 Z"
                                                      cy="168.95387385030747" cx="377.78418814162575" j="9" val="52"
                                                      barHeight="92.18039794305801"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1059"
                                                      d="M406.6857107316955 261.13527179336546L406.6857107316955 68.52698515644232L419.0720775560111 68.52698515644232L419.0720775560111 261.13527179336546L406.6857107316955 261.13527179336546C406.6857107316955 261.13527179336546 406.6857107316955 261.13527179336546 406.6857107316955 261.13527179336546C406.6857107316955 261.13527179336546 406.6857107316955 261.13527179336546 406.6857107316955 261.13527179336546C406.6857107316955 261.13527179336546 406.6857107316955 261.13527179336546 406.6857107316955 261.13527179336546C406.6857107316955 261.13527179336546 406.6857107316955 261.13527179336546 406.6857107316955 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 406.6857107316955 261.13527179336546 L 406.6857107316955 68.52698515644232 L 419.0720775560111 68.52698515644232 L 419.0720775560111 261.13527179336546 Z"
                                                      pathFrom="M 406.6857107316955 261.13527179336546 L 406.6857107316955 261.13527179336546 L 419.0720775560111 261.13527179336546 L 419.0720775560111 261.13527179336546 L 419.0720775560111 261.13527179336546 L 419.0720775560111 261.13527179336546 L 419.0720775560111 261.13527179336546 L 406.6857107316955 261.13527179336546 Z"
                                                      cy="68.52598515644232" cx="419.0720775560111" j="10" val="63"
                                                      barHeight="192.60828663692317"
                                                      barWidth="12.386366824315601"></path>
                                                <path id="SvgjsPath1061"
                                                      d="M447.97360014608086 261.13527179336546L447.97360014608086 181.55460246433736L460.35996697039644 181.55460246433736L460.35996697039644 261.13527179336546L447.97360014608086 261.13527179336546C447.97360014608086 261.13527179336546 447.97360014608086 261.13527179336546 447.97360014608086 261.13527179336546C447.97360014608086 261.13527179336546 447.97360014608086 261.13527179336546 447.97360014608086 261.13527179336546C447.97360014608086 261.13527179336546 447.97360014608086 261.13527179336546 447.97360014608086 261.13527179336546C447.97360014608086 261.13527179336546 447.97360014608086 261.13527179336546 447.97360014608086 261.13527179336546 "
                                                      fill="rgba(10,179,156,0.9)" fill-opacity="1" stroke-opacity="1"
                                                      stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                      class="apexcharts-bar-area" index="1"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 447.97360014608086 261.13527179336546 L 447.97360014608086 181.55460246433736 L 460.35996697039644 181.55460246433736 L 460.35996697039644 261.13527179336546 Z"
                                                      pathFrom="M 447.97360014608086 261.13527179336546 L 447.97360014608086 261.13527179336546 L 460.35996697039644 261.13527179336546 L 460.35996697039644 261.13527179336546 L 460.35996697039644 261.13527179336546 L 460.35996697039644 261.13527179336546 L 460.35996697039644 261.13527179336546 L 447.97360014608086 261.13527179336546 Z"
                                                      cy="181.55360246433736" cx="460.35996697039644" j="11" val="67"
                                                      barHeight="79.58066932902813"
                                                      barWidth="12.386366824315601"></path>
                                                <g id="SvgjsG1036" class="apexcharts-bar-goals-markers">
                                                    <g id="SvgjsG1038" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1040" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1042" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1044" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1046" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1048" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1050" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1052" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1054" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1056" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1058" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                    <g id="SvgjsG1060" className="apexcharts-bar-goals-groups"
                                                       class="apexcharts-hidden-element-shown"
                                                       clip-path="url(#gridRectMarkerMask9k0lhhm2)"></g>
                                                </g>
                                                <g id="SvgjsG1037"
                                                   class="apexcharts-bar-shadows apexcharts-hidden-element-shown"></g>
                                            </g>
                                        </g>
                                        <g id="SvgjsG1062" class="apexcharts-line-series apexcharts-plot-series">
                                            <g id="SvgjsG1063" class="apexcharts-series" zIndex="2" seriesName="Refunds"
                                               data:longestSeries="true" rel="1" data:realIndex="2">
                                                <path id="SvgjsPath1066"
                                                      d="M0 243.72532034047447L41.28788941438533 235.02084461402893L82.57577882877067 245.90143927208584L123.86366824315598 224.14024995597205L165.15155765754133 215.43577422952654L206.43944707192665 237.19696354564033L247.72733648631197 250.25367713530858L289.0152259006973 241.54920140886307L330.30311531508266 245.90143927208584L371.59100472946795 198.0268227766355L412.8788941438533 235.02084461402893L454.16678355823865 184.97010918696722C454.16678355823865 184.97010918696722 454.16678355823865 184.97010918696722 454.16678355823865 184.97010918696722 "
                                                      fill="none" fill-opacity="1" stroke="rgba(240,101,72,1)"
                                                      stroke-opacity="1" stroke-linecap="butt" stroke-width="2.2"
                                                      stroke-dasharray="8" class="apexcharts-line" index="2"
                                                      clip-path="url(#gridRectBarMask9k0lhhm2)"
                                                      pathTo="M 0 243.72532034047447 L 41.28788941438533 235.02084461402893 L 82.57577882877067 245.90143927208584 L 123.86366824315598 224.14024995597205 L 165.15155765754133 215.43577422952654 L 206.43944707192665 237.19696354564033 L 247.72733648631197 250.25367713530858 L 289.0152259006973 241.54920140886307 L 330.30311531508266 245.90143927208584 L 371.59100472946795 198.0268227766355 L 412.8788941438533 235.02084461402893 L 454.16678355823865 184.97010918696722"
                                                      pathFrom="M 0 261.1342717933655 L 0 261.1342717933655 L 41.28788941438533 261.1342717933655 L 82.57577882877067 261.1342717933655 L 123.86366824315598 261.1342717933655 L 165.15155765754133 261.1342717933655 L 206.43944707192665 261.1342717933655 L 247.72733648631197 261.1342717933655 L 289.0152259006973 261.1342717933655 L 330.30311531508266 261.1342717933655 L 371.59100472946795 261.1342717933655 L 412.8788941438533 261.1342717933655 L 454.16678355823865 261.1342717933655"
                                                      fill-rule="evenodd"></path>
                                                <g id="SvgjsG1064"
                                                   class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                   data:realIndex="2">
                                                    <g class="apexcharts-series-markers">
                                                        <path id="SvgjsPath1152" d="M 0, 0
           m -0, 0
           a 0,0 0 1,0 0,0
           a 0,0 0 1,0 -0,0" fill="#f06548" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                              stroke-width="2" stroke-dasharray="0" cx="0" cy="0"
                                                              shape="circle" class="apexcharts-marker wiia1ya8u"
                                                              default-marker-size="0"></path>
                                                    </g>
                                                </g>
                                            </g>
                                            <g id="SvgjsG1030" class="apexcharts-datalabels" data:realIndex="0"></g>
                                            <g id="SvgjsG1035"
                                               class="apexcharts-datalabels apexcharts-hidden-element-shown"
                                               data:realIndex="1"></g>
                                            <g id="SvgjsG1065" class="apexcharts-datalabels" data:realIndex="2"></g>
                                        </g>
                                        <line id="SvgjsLine1085" x1="-13.100964910333808" y1="0" x2="467.26774846857245"
                                              y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                              stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                        <line id="SvgjsLine1086" x1="-13.100964910333808" y1="0" x2="467.26774846857245"
                                              y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                              class="apexcharts-ycrosshairs-hidden"></line>
                                        <g id="SvgjsG1087" class="apexcharts-xaxis" transform="translate(0, 0)">
                                            <g id="SvgjsG1088" class="apexcharts-xaxis-texts-g"
                                               transform="translate(0, -4)">
                                                <text id="SvgjsText1090" font-family="Helvetica, Arial, sans-serif"
                                                      x="0" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1091">Jan</tspan>
                                                    <title>Jan</title></text>
                                                <text id="SvgjsText1093" font-family="Helvetica, Arial, sans-serif"
                                                      x="41.28788941438533" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1094">Feb</tspan>
                                                    <title>Feb</title></text>
                                                <text id="SvgjsText1096" font-family="Helvetica, Arial, sans-serif"
                                                      x="82.57577882877067" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1097">Mar</tspan>
                                                    <title>Mar</title></text>
                                                <text id="SvgjsText1099" font-family="Helvetica, Arial, sans-serif"
                                                      x="123.86366824315601" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1100">Apr</tspan>
                                                    <title>Apr</title></text>
                                                <text id="SvgjsText1102" font-family="Helvetica, Arial, sans-serif"
                                                      x="165.15155765754136" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1103">May</tspan>
                                                    <title>May</title></text>
                                                <text id="SvgjsText1105" font-family="Helvetica, Arial, sans-serif"
                                                      x="206.4394470719267" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1106">Jun</tspan>
                                                    <title>Jun</title></text>
                                                <text id="SvgjsText1108" font-family="Helvetica, Arial, sans-serif"
                                                      x="247.72733648631205" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1109">Jul</tspan>
                                                    <title>Jul</title></text>
                                                <text id="SvgjsText1111" font-family="Helvetica, Arial, sans-serif"
                                                      x="289.01522590069743" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1112">Aug</tspan>
                                                    <title>Aug</title></text>
                                                <text id="SvgjsText1114" font-family="Helvetica, Arial, sans-serif"
                                                      x="330.3031153150828" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1115">Sep</tspan>
                                                    <title>Sep</title></text>
                                                <text id="SvgjsText1117" font-family="Helvetica, Arial, sans-serif"
                                                      x="371.5910047294681" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1118">Oct</tspan>
                                                    <title>Oct</title></text>
                                                <text id="SvgjsText1120" font-family="Helvetica, Arial, sans-serif"
                                                      x="412.8788941438535" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1121">Nov</tspan>
                                                    <title>Nov</title></text>
                                                <text id="SvgjsText1123" font-family="Helvetica, Arial, sans-serif"
                                                      x="454.1667835582388" y="289.1342717933655" text-anchor="middle"
                                                      dominant-baseline="auto" font-size="12px" font-weight="400"
                                                      fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan1124">Dec</tspan>
                                                    <title>Dec</title></text>
                                            </g>
                                        </g>
                                        <g id="SvgjsG1148"
                                           class="apexcharts-yaxis-annotations apexcharts-hidden-element-shown"></g>
                                        <g id="SvgjsG1149"
                                           class="apexcharts-xaxis-annotations apexcharts-hidden-element-shown"></g>
                                        <g id="SvgjsG1150"
                                           class="apexcharts-point-annotations apexcharts-hidden-element-shown"></g>
                                        <rect id="SvgjsRect1153" width="0" height="0" x="0" y="0" rx="0" ry="0"
                                              opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                              fill="#fefefe" class="apexcharts-zoom-rect"></rect>
                                        <rect id="SvgjsRect1154" width="0" height="0" x="0" y="0" rx="0" ry="0"
                                              opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                              fill="#fefefe" class="apexcharts-selection-rect"></rect>
                                    </g>
                                </svg>
                                <div class="apexcharts-tooltip apexcharts-theme-light">
                                    <div class="apexcharts-tooltip-title"
                                         style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                    <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                         style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                                 style="background-color: rgb(64, 81, 137);"></span>
                                        <div class="apexcharts-tooltip-text"
                                             style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                            <div class="apexcharts-tooltip-y-group"><span
                                                    class="apexcharts-tooltip-text-y-label"></span><span
                                                    class="apexcharts-tooltip-text-y-value"></span></div>
                                            <div class="apexcharts-tooltip-goals-group"><span
                                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                                            <div class="apexcharts-tooltip-z-group"><span
                                                    class="apexcharts-tooltip-text-z-label"></span><span
                                                    class="apexcharts-tooltip-text-z-value"></span></div>
                                        </div>
                                    </div>
                                    <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1"
                                         style="order: 2;"><span class="apexcharts-tooltip-marker"
                                                                 style="background-color: rgb(10, 179, 156);"></span>
                                        <div class="apexcharts-tooltip-text"
                                             style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                            <div class="apexcharts-tooltip-y-group"><span
                                                    class="apexcharts-tooltip-text-y-label"></span><span
                                                    class="apexcharts-tooltip-text-y-value"></span></div>
                                            <div class="apexcharts-tooltip-goals-group"><span
                                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                                            <div class="apexcharts-tooltip-z-group"><span
                                                    class="apexcharts-tooltip-text-z-label"></span><span
                                                    class="apexcharts-tooltip-text-z-value"></span></div>
                                        </div>
                                    </div>
                                    <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-2"
                                         style="order: 3;"><span class="apexcharts-tooltip-marker"
                                                                 style="background-color: rgb(240, 101, 72);"></span>
                                        <div class="apexcharts-tooltip-text"
                                             style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                            <div class="apexcharts-tooltip-y-group"><span
                                                    class="apexcharts-tooltip-text-y-label"></span><span
                                                    class="apexcharts-tooltip-text-y-value"></span></div>
                                            <div class="apexcharts-tooltip-goals-group"><span
                                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                                            <div class="apexcharts-tooltip-z-group"><span
                                                    class="apexcharts-tooltip-text-z-label"></span><span
                                                    class="apexcharts-tooltip-text-z-value"></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light">
                                    <div class="apexcharts-xaxistooltip-text"
                                         style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                </div>
                                <div
                                    class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                    <div class="apexcharts-yaxistooltip-text"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->



        </div>
        <!--end col-->
    </div>
</div>
