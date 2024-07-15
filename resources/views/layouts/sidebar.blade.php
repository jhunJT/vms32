<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                @if(Auth::user()->role == 'admin' && Auth::user()->status == 'Active')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL('/dashboard') }}"><i class="ri-dashboard-line me-2"></i> Dashboard</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link  arrow-none" href="#" id="topnav-apps" role="button">
                            <i class="ri-apps-2-line me-2"></i>Management <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-app">
                            <a href="{{ route('allusers.show') }}" class="dropdown-item " >Users</a>
                            <a href="{{ route('allusers/performance') }}" class="dropdown-item " >Encoder Performance</a>

                            {{-- <a href="" class="dropdown-item">Settings</a> --}}

                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>District <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-form"
                                    role="button">District I <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-form">
                                    <a href="javascript:void(0);" class="dropdown-item selectedMuncit" data-mun="ALMAGRO">ALMAGRO</a>
                                    <a href="javascript:void(0);" class="dropdown-item selectedMuncit" data-mun="CITY OF CALBAYOG">CITY OF CALBAYOG</a>
                                    <a href="javascript:void(0);" class="dropdown-item selectedMuncit" data-mun="GANDARA">GANDARA</a>
                                    <a href="javascript:void(0);" class="dropdown-item selectedMuncit" data-mun="MATUGUINAO">MATUGUINAO</a>
                                    <a href="javascript:void(0);" class="dropdown-item selectedMuncit" data-mun="PAGSANGHAN">PAGSANGHAN</a>
                                    <a href="javascript:void(0);" class="dropdown-item selectedMuncit" data-mun="SAN JORGE">SAN JORGE</a>
                                    <a href="javascript:void(0);" class="dropdown-item selectedMuncit" data-mun="SANTA MARGARITA">SANTA MARGARITA</a>
                                    <a href="javascript:void(0);" class="dropdown-item selectedMuncit" data-mun="SANTO NIÑO">SANTO NIÑO</a>
                                    <a href="javascript:void(0);" class="dropdown-item selectedMuncit" data-mun="TAGAPUL-AN">TAGAPUL-AN</a>
                                    <a href="javascript:void(0);" class="dropdown-item selectedMuncit" data-mun="TARANGNAN">TARANGNAN</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-form"
                                    role="button">District II <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-form">
                                    <a href="" class="dropdown-item">Basey</a>
                                    <a href="" class="dropdown-item">Calbiga</a>
                                    <a href="" class="dropdown-item">City of Catbalogan</a>
                                    <a href="javascript:void(0);" class="dropdown-item">Daram</a>
                                    <a href="javascript:void(0);" class="dropdown-item">Hinabangan</a>
                                    <a href="javascript:void(0);" class="dropdown-item">Jiabong</a>
                                    <a href="javascript:void(0);" class="dropdown-item">Marabut</a>
                                    <a href="javascript:void(0);" class="dropdown-item">Motiong</a>
                                    <a href="javascript:void(0);" class="dropdown-item">Paranas</a>
                                    <a href="javascript:void(0);" class="dropdown-item">Pinabacdao</a>
                                    <a href="javascript:void(0);" class="dropdown-item">San Jose de Buan</a>
                                    <a href="javascript:void(0);" class="dropdown-item">San Rita</a>
                                    <a href="javascript:void(0);" class="dropdown-item">San Sebastian</a>
                                    <a href="javascript:void(0);" class="dropdown-item">Talalora</a>
                                    <a href="javascript:void(0);" class="dropdown-item">Villareal</a>
                                    <a href="javascript:void(0);" class="dropdown-item">Zumarraga</a>

                                </div>
                            </div>

                        </div>
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
                                    <a href="javascript:void(0);" class="dropdown-item" key="t-topbar-light" target="_blank">Data</a>
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
                    </li>

                @elseif (Auth::user()->role == 'encoder' && Auth::user()->status == 'Active')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Municipality<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a href="{{ route('dashboard.encoder') }}" class="dropdown-item">Santa Margarita</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Records<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a href="{{ route('cvrecord.index') }}" class="dropdown-item">CV Records</a>
                                <a href="{{ route('district.grants') }}" class="dropdown-item">Grants Records</a>
                            </div>
                        </div>
                    </li>
                @elseif (Auth::user()->role == 'supervisor' && Auth::user()->status == 'Active')
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
                                {{-- <a href="{{ route('allusers/performance') }}" class="dropdown-item " >Encoder Performance</a> --}}
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Municipality<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a href="{{ route('dashboard.supervisor.dataview') }}" class="dropdown-item">Santa Margarita</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Records<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a href="{{ route('cvrecord.index') }}" class="dropdown-item">CV Records</a>
                                <a href="{{ route('district.grants') }}" class="dropdown-item">Grants Records</a>
                            </div>
                        </div>
                    </li>
                @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
