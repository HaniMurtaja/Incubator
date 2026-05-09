@if(auth()->user()->hasRole('Admin'))

    <a class="nav-link text-white d-block {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
       href="{{ route('admin.dashboard') }}">
        {{ app()->getLocale()==='ar' ? 'لوحة التحكم' : 'Dashboard' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
       href="{{ route('admin.users.index') }}">
        {{ app()->getLocale()==='ar' ? 'المستخدمون' : 'Users' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"
       href="{{ route('admin.projects.index') }}">
        {{ app()->getLocale()==='ar' ? 'مراجعة المشاريع' : 'Projects Review' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('admin.assignments.*') ? 'active' : '' }}"
       href="{{ route('admin.assignments.index') }}">
        {{ app()->getLocale()==='ar' ? 'سجل التعيينات' : 'Assignments Log' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('admin.meetings.*') ? 'active' : '' }}"
       href="{{ route('admin.meetings.index') }}">
        {{ app()->getLocale()==='ar' ? 'طلبات الاجتماعات' : 'Meeting Requests' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}"
       href="{{ route('admin.audit.index') }}">
        {{ app()->getLocale()==='ar' ? 'سجل تدقيق النظام' : 'System Audit Trail' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('admin.lifecycle.*') ? 'active' : '' }}"
       href="{{ route('admin.lifecycle.index') }}">
        {{ app()->getLocale()==='ar' ? 'جولات الاحتضان' : 'Incubation Rounds' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('admin.stages.*') ? 'active' : '' }}"
       href="{{ route('admin.stages.index') }}">
        {{ app()->getLocale()==='ar' ? 'مراحل الاحتضان' : 'Stages' }}
    </a>

@elseif(auth()->user()->hasRole('Mentor'))

    <a class="nav-link text-white d-block {{ request()->routeIs('mentor.dashboard') ? 'active' : '' }}"
       href="{{ route('mentor.dashboard') }}">
        {{ app()->getLocale()==='ar' ? 'لوحة الموجه' : 'Mentor Dashboard' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('mentor.command.*') ? 'active' : '' }}"
       href="{{ route('mentor.command.index') }}">
        {{ app()->getLocale()==='ar' ? 'مركز القيادة' : 'Command Center' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('mentor.calendar.*') ? 'active' : '' }}"
       href="{{ route('mentor.calendar.index') }}">
        {{ app()->getLocale()==='ar' ? 'تقويم الإرشاد' : 'Mentorship Calendar' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('mentor.rounds.*') ? 'active' : '' }}"
       href="{{ route('mentor.rounds.index') }}">
        {{ app()->getLocale()==='ar' ? 'جولات الاحتضان' : 'Incubator Rounds' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('mentor.projects.*') ? 'active' : '' }}"
       href="{{ route('mentor.projects.index') }}">
        {{ app()->getLocale()==='ar' ? 'المشاريع المسندة' : 'Assigned Projects' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('mentor.tasks.*') ? 'active' : '' }}"
       href="{{ route('mentor.tasks.index') }}">
        {{ app()->getLocale()==='ar' ? 'المهام' : 'Tasks' }}
    </a>

@else

    <a class="nav-link text-white d-block {{ request()->routeIs('entrepreneur.dashboard') ? 'active' : '' }}"
       href="{{ route('entrepreneur.dashboard') }}">
        {{ app()->getLocale()==='ar' ? 'لوحة رائد الأعمال' : 'Dashboard' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('entrepreneur.projects.*') ? 'active' : '' }}"
       href="{{ route('entrepreneur.projects.index') }}">
        {{ app()->getLocale()==='ar' ? 'مشاريعي' : 'My Projects' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('entrepreneur.meetings.*') ? 'active' : '' }}"
       href="{{ route('entrepreneur.meetings.index') }}">
        {{ app()->getLocale()==='ar' ? 'اجتماعاتي' : 'My Meetings' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('entrepreneur.rounds.*') ? 'active' : '' }}"
       href="{{ route('entrepreneur.rounds.index') }}">
        {{ app()->getLocale()==='ar' ? 'جولات الاحتضان' : 'Incubator Rounds' }}
    </a>
    <a class="nav-link text-white d-block {{ request()->routeIs('entrepreneur.submissions.*') ? 'active' : '' }}"
       href="{{ route('entrepreneur.submissions.index') }}">
        {{ app()->getLocale()==='ar' ? 'تسليماتي' : 'My Submissions' }}
    </a>

@endif
