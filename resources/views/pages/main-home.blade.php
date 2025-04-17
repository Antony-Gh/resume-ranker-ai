@extends('layouts.app')

@section('title', 'Resume Ranker AI - Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/HomeOne1.css') }}">
    <link rel="stylesheet" href="{{ mix('css/HomeOne.css') }}">
    <link rel="stylesheet" href="{{ mix('css/auth.css') }}">
    <link rel="stylesheet" href="{{ mix('css/SignUpOne.css') }}">
    <link rel="stylesheet" href="{{ mix('css/ForgetPassword.css') }}">
    <link rel="stylesheet" href="{{ mix('css/CheckEmail.css') }}">
    <link rel="stylesheet" href="{{ mix('css/OTPVerification.css') }}">
@endpush

@section('content')
    <div class="group-1153 container-xs">
        <div class="group-1023">
            @include('partials.main-home-nav')
            <div class="group-1020">
                <div class="frame-13-1">
                    <div class="frame-34869">
                        <div class="group-1151">
                            <div class="flex-col-center-center frame-8-2">
                                <img src="{{ asset('images/img_plus_svgrepo_com.svg') }}" alt="Plus Svgrepo Com"
                                    class="plus-svgrepo-com" />
                                <h4 class="create-new ui heading size-heading2xl">Create New</h4>
                            </div>
                            <div class="group-1150">
                                <h1 class="ai-powered-resume-1 ui heading size-heading8xl">
                                    <span class="ai-powered-resume-span"> AI-Powered&nbsp;</span>
                                    <span class="ai-powered-resume-span-6"> Resume Ranking</span>
                                    <span class="ai-powered-resume-span"> &nbsp;Tool!</span>
                                </h1>
                                <p class="did-you-know-that ui text size-text3xl">
                                    Did you know that if you tailor your resume to the job description, you double your
                                    chances to get
                                    an interview?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="group-1021">
                <div class="frame-14">
                    <div class="group-1378">
                        <div class="frame-8-1">
                            <div class="template-1">
                                <div class="title">
                                    <p class="nina-patel ui heading size-headings">Nina Patel</p>
                                    <p class="ux-ui-designer ui text size-textmd">UX/UI Designer</p>
                                    <div class="group-1148">
                                        <div class="group-1149">
                                            <div class="auto-layout-left">
                                                <p class="skilled-ux-designer ui text size-textmd">
                                                    Skilled UX Designer specializing in user-centered designs. Strong
                                                    foundation in visual
                                                    design, prototyping and user research methods. Committed to delivering
                                                    exceptional user
                                                    experiences
                                                </p>
                                                <div class="group-1152">
                                                    <div class="work-experience">
                                                        <p class="title-1 ui heading size-headingxs">Work Experience</p>
                                                        <div class="experience-list">
                                                            <div class="work-education">
                                                                <div class="header">
                                                                    <div class="place-title">
                                                                        <p class="place-name ui heading size-headingxs">UX
                                                                            Design Intern</p>
                                                                        <p class="place-name-1 ui heading size-headingxs">@
                                                                        </p>
                                                                        <p class="title-1 ui text size-textmd">ABC Company
                                                                        </p>
                                                                    </div>
                                                                    <p class="timeline ui text size-textxs">JAN 2023 -
                                                                        PRESENT</p>
                                                                </div>
                                                                <p class="desc ui text size-texts">
                                                                    Conducted user research and analyzed data to identify
                                                                    design opportunities and
                                                                    inform design decisions<br />Collaborated with
                                                                    cross-functional teams to create
                                                                    wireframes, prototypes, and high-fidelity
                                                                    mockups<br />Facilitated design reviews
                                                                    and user testing sessions to gather feedback and improve
                                                                    design solutions
                                                                </p>
                                                            </div>
                                                            <div class="work-education">
                                                                <div class="header">
                                                                    <div class="place-title">
                                                                        <p class="place-name ui heading size-headingxs">
                                                                            Freelance Designer</p>
                                                                        <p class="place-name-1 ui heading size-headingxs">@
                                                                        </p>
                                                                        <p class="title-1 ui text size-textmd">XYZ Project
                                                                        </p>
                                                                    </div>
                                                                    <p class="timeline ui text size-textxs">JAN 2023 -
                                                                        PRESENT</p>
                                                                </div>
                                                                <p class="desc ui text size-texts">
                                                                    Designed wireframes, prototypes, and visual designs for
                                                                    mobile and web-based
                                                                    products<br />Conducted usability testing and iterated
                                                                    on design solutions based
                                                                    on user feedback<br />Collaborated with developers to
                                                                    ensure that designs were
                                                                    accurately implemented and met accessibility standards
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="work-experience">
                                                        <p class="title-1 ui heading size-headingxs">Education</p>
                                                        <div class="experience-list">
                                                            <div class="work-education">
                                                                <div class="header">
                                                                    <div class="place-title">
                                                                        <p class="place-name ui heading size-headingxs">UX
                                                                            Design Intern</p>
                                                                        <p class="place-name-1 ui heading size-headingxs">@
                                                                        </p>
                                                                        <p class="title-1 ui text size-textmd">ABC Company
                                                                        </p>
                                                                    </div>
                                                                    <p class="timeline ui text size-textxs">JAN 2023 -
                                                                        PRESENT</p>
                                                                </div>
                                                                <p class="desc ui text size-texts">@</p>
                                                            </div>
                                                            <div class="work-education">
                                                                <div class="header">
                                                                    <div class="place-title">
                                                                        <p class="place-name ui heading size-headingxs">
                                                                            Freelance Designer</p>
                                                                        <p class="place-name-1 ui heading size-headingxs">@
                                                                        </p>
                                                                        <p class="title-1 ui text size-textmd">XYZ Project
                                                                        </p>
                                                                    </div>
                                                                    <p class="timeline ui text size-textxs">JAN 2023 -
                                                                        PRESENT</p>
                                                                </div>
                                                                <p class="desc ui text size-texts">
                                                                    Designed wireframes, prototypes, and visual designs for
                                                                    mobile and web-based
                                                                    products<br />Conducted usability testing and iterated
                                                                    on design solutions based
                                                                    on user feedback<br />Collaborated with developers to
                                                                    ensure that designs were
                                                                    accurately implemented and met accessibility standards
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="auto-layout-right">
                                                <div class="contact-2">
                                                    <p class="title-1 ui heading size-headingxs">Contact</p>
                                                    <div class="contact-4">
                                                        <p class="portfolio-link ui text size-texts">ninapatel.design</p>
                                                        <a href="https://www.linkedin.com/in/reyhan-space/" target="_blank"
                                                            rel="noreferrer">
                                                            <p class="mail-to-link ui text size-texts">ninapatel@gmail.com
                                                            </p>
                                                        </a>
                                                        <p class="portfolio-link ui text size-texts">+91 432 2222 322</p>
                                                    </div>
                                                </div>
                                                <div class="contact-3">
                                                    <p class="title-1 ui heading size-headingxs">Skills</p>
                                                    <p class="class-2k1 ui text size-texts">
                                                        User Research<br />Interaction Design<br />Visual
                                                        Design<br />Communication and
                                                        Collaboration<br />User Testing<br />Adaptability and Continuous
                                                        Learning
                                                    </p>
                                                </div>
                                                <div class="contact-3">
                                                    <p class="title-1 ui heading size-headingxs">Tools</p>
                                                    <p class="class-2k1 ui text size-texts">
                                                        Sketch<br />Figma<br />Figjam<br />Adobe Creative
                                                        Suite<br />InVision<br />Axure<br />Marvel
                                                        App<br />Balsamiq<br />Webflow<br />UserTesting
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <img src="{{ asset('images/img_image_20.png') }}" alt="Image 20" class="image-20" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="frame-34869-1">
                            <div class="frame-34927">
                                <p class="new-resume ui heading size-text2xl">New Resume</p>
                                <button class="edit ui button indigo_50 size-xs fill round">
                                    <img src="{{ asset('images/img_edit.svg') }}" />
                                </button>
                            </div>
                            <div class="frame-34873">
                                <div class="frame-9-1">
                                    <img src="{{ asset('images/img_frame_13.svg') }}" alt="Frame 13" class="frame-13-2" />
                                    <p class="new-resume ui heading size-text2xl">Edit</p>
                                </div>
                                <div class="frame-10">
                                    <img src="{{ asset('images/img_download_square.svg') }}" alt="Download Square"
                                        class="home-alt-1" />
                                    <p class="new-resume ui heading size-text2xl">Download</p>
                                </div>
                                <div class="frame-15">
                                    <img src="{{ asset('images/img_delete_1.svg') }}" alt="Delete 1" class="delete-1" />
                                    <p class="delete ui heading size-text2xl">Delete</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.loading')


@endsection


@push('scripts')
    <script src="{{ mix('js/main-home.js') }}" defer></script>
 @endpush