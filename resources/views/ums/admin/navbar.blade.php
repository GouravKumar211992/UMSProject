<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav d-block container-xxl erpnewheader">
    <div class="header-navbar navbar-light navbar-shadow new-navbarfloating">
                <div class="navbar-container d-flex content">
                    <div class="bookmark-wrapper d-flex align-items-center"> 
                        <ul class="nav navbar-nav headerlogo">
<<<<<<< HEAD
                            <li><img src="{{asset('img/logo.jpg')}}" /></li>
=======
                            <li><img src="{{asset('img/logo.png')}}" /></li>
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
                        </ul>
                        <ul class="nav navbar-nav left-baricontop"> 
                            <li class="nav-item">
                                <a class="nav-link menu-toggle" href="#">
                                    <i></i>
                                </a>
                            </li>
                        </ul>
<!--
                        <ul class="nav navbar-nav bookmark-icons">
                            <li class="nav-item nav-search"> 									
                                <a class="nav-link nav-link-search"><i class="ficon" data-feather="search"></i></a>
                                <div class="search-input">
                                    <div class="search-input-icon"><i data-feather="search"></i></div>
                                    <input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="-1" data-search="search">
                                    <div class="search-input-close"><i data-feather="x"></i></div>
                                    <ul class="search-list search-list-main"></ul>
                                </div>
                            </li>
                        </ul> 
-->
                    </div>
                    {{-- <span class="avatar me-1"><span class="avatar"><a href="/legal">ERP</a></span></span> --}}
                    <ul class="nav navbar-nav align-items-center ms-auto"> 
                        
                        <li class="nav-item d-none d-lg-block select-organization-menu">
                            <select class="form-select">
                                <option>Select Organization</option>
                                <option>Sheelaform</option>
                                <option>Staqo</option>
                            </select>
                        </li>
                        <span class="avatar"><span class="avatar"><a href="/legal">ERP</a></span></span>
                        
                        <li class="nav-item d-none d-lg-block">
                            <div class="theme-switchbox">
                                
                                <div class="themeswitchstyle">
                                    <span class="dark-lightmode"><i data-feather="moon"></i></span>
                                    <span class="day-lightmode"><i data-feather="sun"></i></span>
                                </div>
                                
                            </div> 
                        </li>
                        

                        <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span class="badge rounded-pill bg-danger badge-up">5</span></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header d-flex">
                                        <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                                        <div class="badge rounded-pill badge-light-primary">6 New</div>
                                    </div>
                                </li>
                                <li class="scrollable-container media-list"><a class="d-flex" href="#">
                                        <div class="list-item d-flex align-items-start">
                                            <div class="me-1">
                                                <div class="avatar"><img src="../../../app-assets/images/portrait/small/avatar-s-15.jpg" alt="avatar" width="32" height="32"></div>
                                            </div>
                                            <div class="list-item-body flex-grow-1">
                                                <p class="media-heading"><span class="fw-bolder">Congratulation Sam 🎉</span>winner!</p><small class="notification-text"> Won the monthly best seller badge.</small>
                                            </div>
                                        </div>
                                    </a><a class="d-flex" href="#">
                                        <div class="list-item d-flex align-items-start">
                                            <div class="me-1">
                                                <div class="avatar"><img src="../../../app-assets/images/portrait/small/avatar-s-3.jpg" alt="avatar" width="32" height="32"></div>
                                            </div>
                                            <div class="list-item-body flex-grow-1">
                                                <p class="media-heading"><span class="fw-bolder">New message</span>&nbsp;received</p><small class="notification-text"> You have 10 unread messages</small>
                                            </div>
                                        </div>
                                    </a><a class="d-flex" href="#">
                                        <div class="list-item d-flex align-items-start">
                                            <div class="me-1">
                                                <div class="avatar bg-light-danger">
                                                    <div class="avatar-content">MD</div>
                                                </div>
                                            </div>

                                            <div class="list-item-body flex-grow-1">
                                                <p class="media-heading"><span class="fw-bolder">Revised Order 👋</span>&nbsp;checkout</p><small class="notification-text"> MD Inc. order updated</small>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="list-item d-flex align-items-center">
                                        <h6 class="fw-bolder me-auto mb-0">System Notifications</h6>
                                        <div class="form-check form-check-primary form-switch">
                                            <input class="form-check-input" id="systemNotification" type="checkbox" checked="">
                                            <label class="form-check-label" for="systemNotification"></label>
                                        </div>
                                    </div><a class="d-flex" href="#">
                                        <div class="list-item d-flex align-items-start">
                                            <div class="me-1">
                                                <div class="avatar bg-light-danger">
                                                    <div class="avatar-content"><i class="avatar-icon" data-feather="x"></i></div>
                                                </div>
                                            </div>
                                            <div class="list-item-body flex-grow-1">
                                                <p class="media-heading"><span class="fw-bolder">Server down</span>&nbsp;registered</p><small class="notification-text"> USA Server is down due to high CPU usage</small>
                                            </div>
                                        </div>
                                    </a><a class="d-flex" href="#">
                                        <div class="list-item d-flex align-items-start">
                                            <div class="me-1">
                                                <div class="avatar bg-light-success">
                                                    <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i></div>
                                                </div>
                                            </div>
                                            <div class="list-item-body flex-grow-1">
                                                <p class="media-heading"><span class="fw-bolder">Sales report</span>&nbsp;generated</p><small class="notification-text"> Last month sales report generated</small>
                                            </div>
                                        </div>
                                    </a><a class="d-flex" href="#">
                                        <div class="list-item d-flex align-items-start">
                                            <div class="me-1">
                                                <div class="avatar bg-light-warning">
                                                    <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                                </div>
                                            </div>
                                            <div class="list-item-body flex-grow-1">
                                                <p class="media-heading"><span class="fw-bolder">High memory</span>&nbsp;usage</p><small class="notification-text"> BLR Server using high memory</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="#">Read all notifications</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="avatar">
<!--                                        <img class="round" src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="32" width="32">-->
                                    NG
                                </span>
                            </a>
                            
                            <div class="dropdown-menu drop-newmenu dropdown-menu-end" aria-labelledby="dropdown-user">
                                <a class="dropdown-item" href="profile"><i class="me-50" data-feather="user"></i> Profile</a>
                                <a class="dropdown-item" href="#"><i class="me-50" data-feather="credit-card"></i> Visiting Card</a>
                                <a class="dropdown-item" href="#"><i class="me-50" data-feather="log-in"></i> Request</a>
                                <a class="dropdown-item" href="#"><i class="me-50" data-feather="check-circle"></i> Approval</a> 
                                <a class="dropdown-item" href="#"><i class="me-50" data-feather="tool"></i> Setting</a> 
                                <a class="dropdown-item" href="#"><i class="me-50" data-feather="power"></i> Logout</a>
                            </div>
                        </li>
                        
                        <li class="nav-item dropdown dropdown-notification">
                            <a class="nav-link d-inline-block drivebtnsect" href="#" data-bs-toggle="dropdown">
                                <img src="{{ asset('img/menuiconlist.png') }}" alt="Menu Icon">
                            </a>
                                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end worksdrivebox">
                                    <li class="dropdown-menu-header">
                                        <div class="dropdown-header text-center">
                                            <h4 class="notification-title mb-0 me-auto">My Favourites</h4> 
                                        </div>
                                    </li>
                                    <li class="scrollable-container media-list">
                                        <div class="row">
                                            <div class="col-md-4 col-6">
                                                <a href="#">
                                                    <div class="drivework">
                                                        <img src="img/d4.png" />
                                                        <p>Gmail</p>
                                                    </div>                                            
                                                </a>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <a href="#">
                                                    <div class="drivework">
                                                        <img src="img/d3.png" />
                                                        <p>Outlook</p>
                                                    </div>                                            
                                                </a>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <a href="#">
                                                    <div class="drivework">
                                                        <img src="img/d2.png" />
                                                        <p>Google Drive</p>
                                                    </div>                                            
                                                </a>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <a href="#">
                                                    <div class="drivework">
                                                        <img src="img/d1.png" />
                                                        <p>Whatsapp</p>
                                                    </div>                                            
                                                </a>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <a href="#">
                                                    <div class="drivework">
                                                        <img src="img/d4.png" />
                                                        <p>Gmail</p>
                                                    </div>                                            
                                                </a>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <a href="#">
                                                    <div class="drivework">
                                                        <img src="img/d3.png" />
                                                        <p>Outlook</p>
                                                    </div>                                            
                                                </a>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <a href="#">
                                                    <div class="drivework">
                                                        <img src="img/d2.png" />
                                                        <p>Google Drive</p>
                                                    </div>                                            
                                                </a>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <a href="#">
                                                    <div class="drivework">
                                                        <img src="img/d1.png" />
                                                        <p>Whatsapp</p>
                                                    </div>                                            
                                                </a>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <a href="#">
                                                    <div class="drivework">
                                                        <img src="img/d4.png" />
                                                        <p>Gmail</p>
                                                    </div>                                            
                                                </a>
                                            </div> 

                                        </div>
                                    </li>
                                </ul>
                            </li>
                    </ul>
                </div>
            </div>
</nav>