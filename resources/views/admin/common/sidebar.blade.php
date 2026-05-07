<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        @if(Auth::check() && Auth::user()->roles->contains('name', 'admin'))
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#user" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-title">User</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="user">
                <ul class="nav flex-column sub-menu mb-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.teacher.applications.index') }}">Teacher Applications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.students.index') }}">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.teachers.index') }}">Teachers</a>
                    </li>
                    @if(Auth::user()->roles->contains('name', 'super_admin'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.admins.index') }}">Admins</a>
                    </li>
                    @endif
                </ul>
            </div>
        </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.courses.index') }}">
                <span class="menu-title">Course</span>
                <i class="mdi mdi-school menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.chapters.index') }}">
                <span class="menu-title">Chapter</span>
                <i class="mdi mdi-view-module menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.lessons.index') }}">
                <span class="menu-title">Lesson</span>
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </a>
        </li>


        @if (Auth::check() && Auth::user()->roles->contains('name', 'admin'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <span class="menu-title">Category</span>
                    <i class="mdi mdi-shape-outline menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.levels.index') }}">
                    <span class="menu-title">Level</span>
                    <i class="mdi mdi-chart-bar menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.majors.index') }}">
                    <span class="menu-title">Major</span>
                    <i class="mdi mdi-school menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.events.index') }}">
                    <span class="menu-title">Event</span>
                    <i class="mdi mdi-calendar menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.news.index') }}">
                    <span class="menu-title">News</span>
                    <i class="mdi mdi-newspaper menu-icon"></i>
                </a>
            </li>
        @endif

        <hr>

        @if (Auth::check() && Auth::user()->roles->contains('name', 'admin'))
            <h5 style="margin-left: 13%;" class="text-muted">Content Manage</h5>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#home-page" aria-expanded="false"
                    aria-controls="ui-basic">
                    <span class="menu-title">Home page</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="home-page">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cms.home-page.slider.index') }}">Sliders</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#footer" aria-expanded="false"
                    aria-controls="ui-basic">
                    <span class="menu-title">Footer</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="footer">
                    <ul class="nav flex-column sub-menu mb-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cms.footers.index') }}">Dashborad</a>
                        </li>
                    </ul>
                    <ul class="nav flex-column sub-menu mb-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cms.footers.main-create') }}">Main</a>
                        </li>
                    </ul>
                    <ul class="nav flex-column sub-menu mb-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cms.footers.copyright-create') }}">Copyright</a>
                        </li>
                    </ul>
                    <ul class="nav flex-column sub-menu mb-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cms.footers.logo-create') }}">Logo</a>
                        </li>
                    </ul>
                    <ul class="nav flex-column sub-menu mb-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cms.footers.social-create') }}">Social</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
    </ul>
</nav>
