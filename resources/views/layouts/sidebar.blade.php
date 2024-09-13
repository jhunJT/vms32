<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    {{-- admin --}}
                @if(Auth::user()->role == 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL('/dashboard') }}"><i class="ri-dashboard-line me-2"></i> Dashboard</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link  arrow-none" href="#" id="topnav-apps" role="button">
                            <i class="ri-apps-2-line me-2"></i>Management <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-app">
                            <a href="{{ route('allusers.show') }}" class="dropdown-item " >Users</a>
                            <a href="{{ route('allusers.userlogs') }}" class="dropdown-item " >Users Logs</a>
                            {{-- <a href="{{ route('allusers/performance') }}" class="dropdown-item " >Encoder Performance</a> --}}
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Data <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <a class="dropdown-item " href="{{ route('admin.recordsadmin') }}" role="button">District Data</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Records<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <a href="{{ route('cvrecord.index') }}" class="dropdown-item" target="_blank">CV Records</a>
                            <a href="{{ route('district.grants') }}" class="dropdown-item" target="_blank">Grants Records</a>
                            <a href="{{ route('hlrecords.index') }}" class="dropdown-item" target="_blank">HL Records</a>
                            <a href="{{ route('pbpcrecords.index') }}" class="dropdown-item" target="_blank">PB/PC Records</a>
                        </div>
                    </li>

                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link  arrow-none" href="#" id="topnav-apps" role="button">
                            <i class="ri-apps-2-line me-2"></i>Archive <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-app">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="javascript:void(0);" id="topnav-layout-hori"
                                    role="button">
                                    <span key="t-horizontal">2023 BSKE</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                    <a href="{{ URL('/archives/bske2023') }}" class="dropdown-item" key="t-topbar-light" target="_blank">Result</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="javascript:void(0);" id="topnav-layout-hori"
                                    role="button">
                                    <span key="t-horizontal">2022 NLE</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                    <a href="{{ route('nle2022.statistics') }}" class="dropdown-item" key="t-topbar-light" target="_blank">Data</a>
                                    <a href="{{ URL('/archives/nle2022') }}" class="dropdown-item" key="t-topbar-light" target="_blank">Result</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="javascript:void(0);" id="topnav-layout-hori"
                                    role="button">
                                    <span key="t-horizontal">2019 NLE</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                    <a href="javascript:void(0);" class="dropdown-item" key="t-topbar-light" target="_blank">Data</a>
                                    <a href="javascript:void(0);" class="dropdown-item" key="t-topbar-light" target="_blank">Result</a>
                                </div>
                            </div>
                        </div>
                    </li> --}}

                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Records<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a href="{{ route('cvrecord.index') }}" class="dropdown-item">CV Records</a>
                                <a href="{{ route('district.grants') }}" class="dropdown-item">Grants Records</a>
                            </div>
                        </div>
                    </li> --}}

                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="javascript:void(0);" id="topnav-layout" role="button">
                            <i class="ri-layout-3-line me-2"></i><span key="t-layouts">Reports</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-layout">
                            <a href="" class="dropdown-item" target="_blank">Occupation</a>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="javascript:void(0);" id="topnav-layout-hori"
                                    role="button">
                                    <span key="t-horizontal">District Report</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                    <a href="http://192.168.1.112:8081/jasperserver/login.html" class="dropdown-item" key="t-topbar-light" target="_blank">Jasper Report</a>
                                </div>
                            </div>
                        </div>
                    </li> --}}

                {{--  Encoder--}}
                @elseif (Auth::user()->role == 'encoder')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Municipality<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <a href="{{ route('dashboard.encoder') }}" class="dropdown-item">{{ Auth::User()->muncit }}</a>
                            <a href="{{ route('coordinates.index') }}" class="dropdown-item">SET COORDINATES</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Records<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <a href="{{ route('cvrecord.index') }}" class="dropdown-item" target="_blank">CV Records</a>
                            <a href="{{ route('district.grants') }}" class="dropdown-item" target="_blank">Grants Records</a>
                            <a href="{{ route('hlrecords.index') }}" class="dropdown-item" target="_blank">HL Records</a>
                            <a href="{{ route('pbpcrecords.index') }}" class="dropdown-item" target="_blank">PB/PC Records</a>
                        </div>
                    </li>

                 {{--  supervisor--}}
                @elseif (Auth::user()->role == 'supervisor')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL('/dashboard/supervisor') }}"><i class="ri-dashboard-line me-2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>User<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a href="{{ URL('/supervisor/user/show') }}" class="dropdown-item " >Users</a>
                                {{-- <a href="{{ route('supervisor.performance') }}" class="dropdown-item " >Encoder Performance</a> --}}
                                {{-- <a href="{{ route('setdrpdwn.index') }}" class="dropdown-item " >Set Dropdowns</a> --}}
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Municipality<div class="arrow-down"></div>

                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <a href="{{ route('dashboard.supervisor.dataview') }}" class="dropdown-item">{{ Auth::User()->muncit }}</a>
                            <a href="{{ route('coordinates.index') }}" class="dropdown-item">SET COORDINATES</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Records<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <a href="{{ route('cvrecord.index') }}" class="dropdown-item" target="_blank">CV Records</a>
                            <a href="{{ route('district.grants') }}" class="dropdown-item" target="_blank">Grants Records</a>
                            <a href="{{ route('hlrecords.index') }}" class="dropdown-item" target="_blank">HL Records</a>
                            <a href="{{ route('pbpcrecords.index') }}" class="dropdown-item" target="_blank">PB/PC Records</a>
                            <a href="{{ route('unsurveyed.index') }}" class="dropdown-item" target="_blank">Unsurveyed</a>
                        </div>
                    </li>
                @elseif (Auth::user()->role == 'superuser')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL('/dashboard') }}"><i class="ri-dashboard-line me-2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link  arrow-none" href="#" id="topnav-apps" role="button">
                            <i class="ri-apps-2-line me-2"></i>Archive <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-app">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="javascript:void(0);" id="topnav-layout-hori"
                                    role="button">
                                    <span key="t-horizontal">2023 BSKE</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                    {{-- <a href="javascript:void(0);" class="dropdown-item" key="t-topbar-light" target="_blank">Data</a> --}}
                                    <a href="{{ URL('/archives/bske2023') }}" class="dropdown-item" key="t-topbar-light" target="_blank">Result</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="javascript:void(0);" id="topnav-layout-hori"
                                    role="button">
                                    <span key="t-horizontal">2022 NLE</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                    <a href="{{ route('nle2022.statistics') }}" class="dropdown-item" key="t-topbar-light" target="_blank">Data</a>
                                    <a href="{{ URL('/archives/nle2022') }}" class="dropdown-item" key="t-topbar-light" target="_blank">Result</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="javascript:void(0);" id="topnav-layout-hori"
                                    role="button">
                                    <span key="t-horizontal">2019 NLE</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                    <a href="javascript:void(0);" class="dropdown-item" key="t-topbar-light" target="_blank">Data</a>
                                    <a href="javascript:void(0);" class="dropdown-item" key="t-topbar-light" target="_blank">Result</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Fix Records<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            {{-- <a href="{{ route('fixhl-member.index') }}" class="dropdown-item" target="_blank">CV Records Fix</a> --}}
                            <a href="{{ route('fixhl.fixhlindex') }}" class="dropdown-item" target="_blank">HL Records Fix</a>
                        </div>
                    </li>
                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Records<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <a href="{{ route('cvrecord.index') }}" class="dropdown-item" target="_blank">CV Records</a>
                            <a href="{{ route('district.grants') }}" class="dropdown-item" target="_blank">Grants Records</a>
                            <a href="{{ route('hlrecords.index') }}" class="dropdown-item" target="_blank">HL Records</a>
                            <a href="{{ route('pbpcrecords.index') }}" class="dropdown-item" target="_blank">PB/PC Records</a>
                        </div>
                    </li> --}}
                @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
