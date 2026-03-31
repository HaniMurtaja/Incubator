<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Incubator Lab') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta21/dist/css/tabler.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background: #f7f8fc; }
        .hero-pattern { background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 18px 18px; }
        .role-box { transition: all .25s ease; }
        .role-box:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(0,0,0,.08); }
    </style>
</head>
<body>
<header class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container-xl">
        <a class="navbar-brand fw-bold" href="{{ route('landing') }}">{{ __('ui.app_name') }}</a>
        <div class="navbar-nav ms-auto d-flex flex-row gap-3 align-items-center">
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('locale.switch', 'ar') }}">{{ __('ui.switch_ar') }}</a>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('locale.switch', 'en') }}">{{ __('ui.switch_en') }}</a>
            <a class="nav-link" href="#services">{{ app()->getLocale() === 'ar' ? 'الخدمات' : 'Services' }}</a>
            <a class="nav-link" href="#about">{{ app()->getLocale() === 'ar' ? 'من نحن' : 'About Us' }}</a>
            <a class="nav-link" href="#contact">{{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact' }}</a>
            <a class="btn btn-primary btn-sm" href="{{ route('login') }}">{{ app()->getLocale() === 'ar' ? 'دخول مباشر' : 'Quick Access' }}</a>
        </div>
    </div>
</header>

<main class="hero-pattern">
    <section class="py-6">
        <div class="container-xl py-5 text-center">
            <div class="d-inline-flex align-items-center bg-white rounded-pill shadow-sm px-3 py-2 border mb-4">
                <span class="badge bg-dark text-white me-2">V</span>
                <strong>{{ __('ui.app_name') }}</strong>
            </div>
            <h1 class="display-5 fw-bold mb-2">{{ app()->getLocale() === 'ar' ? 'منصة الحاضنة الافتراضية' : 'Virtual Incubator Platform' }}</h1>
            <p class="lead text-muted mb-5">{{ app()->getLocale() === 'ar' ? 'حل متكامل لإدارة دورة حياة المشاريع من الفكرة إلى الاحتضان والتقييم' : 'A complete platform to manage startup lifecycle from idea to incubation and evaluation.' }}</p>

            <div class="row justify-content-center g-3 mb-5">
                <div class="col-md-8 col-lg-6"><div class="role-box card card-body"><div class="d-flex justify-content-between"><div><strong>{{ __('ui.role_admin') }}</strong><div class="text-muted small">{{ app()->getLocale() === 'ar' ? 'إدارة المستخدمين والمشاريع' : 'Manage users and projects' }}</div></div><span>🔒</span></div></div></div>
                <div class="col-md-8 col-lg-6"><div class="role-box card card-body"><div class="d-flex justify-content-between"><div><strong>{{ __('ui.role_mentor') }}</strong><div class="text-muted small">{{ app()->getLocale() === 'ar' ? 'إدارة المراحل والمهام والتقييم' : 'Manage stages, tasks and evaluations' }}</div></div><span>👥</span></div></div></div>
                <div class="col-md-8 col-lg-6"><div class="role-box card card-body"><div class="d-flex justify-content-between"><div><strong>{{ __('ui.role_entrepreneur') }}</strong><div class="text-muted small">{{ app()->getLocale() === 'ar' ? 'تقديم المشاريع وتنفيذ المهام' : 'Submit projects and execute tasks' }}</div></div><span>⚡</span></div></div></div>
            </div>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">{{ app()->getLocale() === 'ar' ? 'الدخول حسب الدور' : 'Enter by Role' }}</a>
        </div>
    </section>

    <section id="services" class="py-5 bg-white border-top border-bottom">
        <div class="container-xl">
            <h2 class="fw-bold text-center mb-4">{{ app()->getLocale() === 'ar' ? 'خدمات النظام' : 'System Services' }}</h2>
            <div class="row row-cards">
                <div class="col-md-4"><div class="card h-100"><div class="card-body"><h3 class="h4">{{ app()->getLocale() === 'ar' ? 'استقبال المشاريع' : 'Project Intake' }}</h3><p class="text-muted">{{ app()->getLocale() === 'ar' ? 'تقديم فكرة المشروع مع الوصف والتصنيف والمرفقات.' : 'Submit project ideas with descriptions, categories, and attachments.' }}</p></div></div></div>
                <div class="col-md-4"><div class="card h-100"><div class="card-body"><h3 class="h4">{{ app()->getLocale() === 'ar' ? 'إدارة مسار الاحتضان' : 'Incubation Workflow' }}</h3><p class="text-muted">{{ app()->getLocale() === 'ar' ? 'تنظيم المراحل والمهام ومتابعة التقدم في كل مرحلة.' : 'Manage stages, tasks, and progress across incubation steps.' }}</p></div></div></div>
                <div class="col-md-4"><div class="card h-100"><div class="card-body"><h3 class="h4">{{ app()->getLocale() === 'ar' ? 'التقييم والإشعارات' : 'Evaluation & Notifications' }}</h3><p class="text-muted">{{ app()->getLocale() === 'ar' ? 'مراجعة التسليمات وإرسال ملاحظات وإشعارات فورية.' : 'Review submissions and provide instant feedback notifications.' }}</p></div></div></div>
            </div>
        </div>
    </section>

    <section id="about" class="py-5">
        <div class="container-xl">
            <h2 class="fw-bold mb-3">{{ app()->getLocale() === 'ar' ? 'من نحن' : 'About Us' }}</h2>
            <p class="text-muted mb-0">{{ app()->getLocale() === 'ar' ? 'Incubator Lab منصة رقمية مخصصة لدعم بيئات ريادة الأعمال، تساعد الجهات الحاضنة في إدارة المشاريع بوضوح وشفافية، وتمكّن الموجهين ورواد الأعمال من التعاون ضمن مسار احتضان منظم وقابل للقياس.' : 'Incubator Lab is a digital platform that empowers incubation ecosystems with transparent project governance and measurable collaboration between admins, mentors, and entrepreneurs.' }}</p>
        </div>
    </section>

    <section id="contact" class="py-5">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="h3 mb-2">{{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}</h2>
                            <p class="text-muted">{{ app()->getLocale() === 'ar' ? 'أرسل استفسارك وسيتم التواصل معك في أقرب وقت.' : 'Send your inquiry and our team will reach out soon.' }}</p>

                            @if (session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('contact.submit') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الموضوع' : 'Subject' }}</label>
                                        <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الرسالة' : 'Message' }}</label>
                                        <textarea class="form-control" name="message" rows="5" required>{{ old('message') }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary">{{ app()->getLocale() === 'ar' ? 'إرسال' : 'Send' }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="py-4 bg-dark text-white">
    <div class="container-xl d-flex justify-content-between">
        <span>{{ __('ui.app_name') }}</span>
        <a href="{{ route('login') }}" class="text-white">{{ app()->getLocale() === 'ar' ? 'الدخول حسب الدور' : 'Enter by Role' }}</a>
    </div>
</footer>
</body>
</html>

