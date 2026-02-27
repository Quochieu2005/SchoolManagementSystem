<style>
.profile {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px 10px;
}

.profile-image-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 12px;
}

.profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    background: #f5f5f5;
    border: 3px solid #fff;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    position: relative;
}

.profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-status {
    position: absolute;
    bottom: 6px;
    right: 6px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.profile-status.online {
    background: #4CAF50;
}

.profile-status.offline {
    background: #9E9E9E;
}

.admin-badge {
    position: absolute;
    top: 6px;
    right: 6px;
    background: #e34724;
    color: #fff;
    font-size: 10px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 10px;
}

.profile-data {
    text-align: center;
    margin-bottom: 10px;
}

.profile-data-name {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
}

.profile-data-title {
    font-size: 13px;
    color: #cfd8dc;
    margin-top: 3px;
    word-break: break-word;
}

.profile-data-role {
    margin-top: 6px;
    font-size: 12px;
    color: #e34724;
    padding: 4px 12px;
    background: rgba(227,71,36,0.15);
    border-radius: 14px;
    display: inline-block;
}


.profile-controls {
    display: flex;
    justify-content: center;
    gap: 28px;
    padding-top: 10px;
    margin-top: 8px;
    border-top: 1px solid rgba(255,255,255,0.08);
    width: 100%;
}

.profile-control-left,
.profile-control-right {
    font-size: 18px;
    color: #cfd8dc;
    transition: color 0.25s ease, transform 0.25s ease;
    text-decoration: none;
}

.profile-control-left:hover,
.profile-control-right:hover {
    color: #e34724;
    transform: scale(1.1);
}

.profile-mini-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
}

</style>
<div class="page-sidebar">
    <!-- START X-NAVIGATION -->
    <ul class="x-navigation">
        <li style="background: #e34724">
            <a style="font-size: 20px; text-align: center;font-weight: bold;" href="{{ route('cpanel.dashboard') }}">SCHOOL</a>
            <a href="#" class="x-navigation-control"></a>
        </li>
        <li class="xn-profile">
            <a href="#" class="profile-mini">
                <img src="{{ Auth::user()->profile_pic 
                            ? asset(Auth::user()->profile_pic) 
                            : asset('assets/images/users/avatar.jpg') }}" 
                        alt="{{ Auth::user()->name }}" 
                        class="profile-mini-img" />
            </a>
            <div class="profile">
                <div class="profile-image-wrapper">
                    <div class="profile-image">
                        <img src="{{ Auth::user()->profile_pic 
                                ? asset(Auth::user()->profile_pic) 
                                : asset('assets/images/users/avatar.jpg') }}" 
                            alt="{{ Auth::user()->name }}"
                            class="profile-img" 
                            onerror="this.src='{{ asset('assets/images/users/avatar.jpg') }}'" />
                        @if(Auth::user()->is_admin == 1)
                            <span class="admin-badge" title="Super Admin">SA</span>
                        @elseif(Auth::user()->is_admin == 2)
                            <span class="admin-badge" title="Administrator">AD</span>
                        @endif
                    </div>
                </div>

                <div class="profile-data">
                    @if(!empty(Auth::user()->last_name))
                        <div class="profile-data-name">
                            {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                        </div>
                    @else
                        <div class="profile-data-name">
                            {{ Auth::user()->name }}
                        </div>
                    @endif

                    <div class="profile-data-title">{{ Auth::user()->email }}</div>

                    @if(Auth::user()->is_admin == 1)
                        <div class="profile-data-role">Super Admin</div>
                    @elseif(Auth::user()->is_admin == 2)
                        <div class="profile-data-role">Admin</div>
                    @elseif(Auth::user()->is_admin == 3)
                        <div class="profile-data-role">School</div>
                    @elseif(Auth::user()->is_admin == 4)
                        <div class="profile-data-role">School Admin</div>
                    @elseif(Auth::user()->is_admin == 5)
                        <div class="profile-data-role">Teacher</div>
                    @elseif(Auth::user()->is_admin == 6)
                        <div class="profile-data-role">Student</div>
                    @elseif(Auth::user()->is_admin == 7)
                        <div class="profile-data-role">Parent</div>
                    @else
                        <div class="profile-data-role">User</div>
                    @endif
                </div>
                <div class="profile-controls">
                    <a href="" class="profile-control-left" title="Profile Info">
                        <span class="fa fa-user"></span>
                    </a>
                    <a href="" class="profile-control-right" title="Messages">
                        <span class="fa fa-envelope"></span>
                    </a>
                </div>
            </div>
        </li>
        <li class="{{ Request::segment(2) == 'dashboard' ? 'active' : '' }}">
            <a href="{{ route('cpanel.dashboard') }}"><span class="fa fa-desktop"></span><span class="xn-text">Dashboard</span></a>
        </li>

        @if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2)
            <li class="{{ Request::segment(2) == 'admin' ? 'active' : '' }}">    
                <a href="{{ route('cpanel.admin') }}"><span class="fa fa-user-secret"></span> <span class="xn-text">Admin</span></a>
            </li>

            <li class="{{ Request::segment(2) == 'school' ? 'active' : '' }}">    
                <a href="{{ route('cpanel.school') }}"><span class="fa fa-school"></span> <span class="xn-text">School</span></a>
            </li>
        @endif

        @if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2 ||  Auth::user()->is_admin == 3)

            <li class="{{ Request::segment(2) == 'school admin' ? 'active' : '' }}">    
                <a href="{{ route('cpanel.school.admin') }}"><span class="fa fa-user"></span> <span class="xn-text">School Admin</span></a>
            </li>
            
            <li class="{{ Request::segment(2) == 'teacher' ? 'active' : '' }}">    
                <a href="{{ route('cpanel.teacher') }}"><span class="fa fa-user"></span> <span class="xn-text">Teacher</span></a>
            </li>

            <li class="{{ Request::segment(2) == 'student' ? 'active' : '' }}">    
                <a href="{{ route('cpanel.student') }}"><span class="fa fa-user"></span> <span class="xn-text">Student</span></a>
            </li>
        @endif

        @if(Auth::user()->is_admin == 3)
            <li class="xn-openable {{ Request::segment(2) == 'class' || Request::segment(2) == 'subject' ? 'active' : '' }}"> 
                <a href="#"><span class="fa fa-file-text-o"></span> <span class="xn-text">Academics</span></a>
                <ul>
                    <li class="{{ Request::segment(2) == 'class' ? 'active' : '' }}"><a href="{{ route('cpanel.class') }}"><span class="fa fa-random"></span> Class</a></li>

                    <li class="{{ Request::segment(2) == 'subject' ? 'active' : '' }}"><a href="{{ route('cpanel.subject') }}"><span class="fa fa-random"></span> Subject</a></li>

                </ul>
            </li>
        @endif

        <li class="xn-openable"> 
            <a href="#"><span class="fa fa-file-text-o"></span> <span class="xn-text">Layouts</span></a>
            <ul>
                <li><a href="layout-boxed.html">List</a></li>
            </ul>
        </li>
    </ul>
    <!-- END X-NAVIGATION -->
</div>