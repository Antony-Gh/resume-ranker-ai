<!-- Modal Backdrop -->
<div class="modal-backdrop" id="modal-backdrop"></div>

<!-- Modal Dialog -->
<dialog id="modal-dialog-sign-in" aria-label="Modal" aria-modal="true" class="modal-dialog">
    <button class="close-button" aria-controls="modal-dialog-sign-in" aria-label="Close modal">
        <img src="{{ asset('images/img_close_icon.svg') }}" alt="Close" class="close-icon" />
    </button>
    <div class="frame-34842">
        <div class="group-1135 container-xs">
            <a href="#">
                <h1 class="sign-in ui heading size-heading4xl">Sign In</h1>
            </a>
            <h2 class="welcome-back-please ui heading size-text2xl">Welcome back! Please enter your details.</h2>
            <div class="vector-3-2"></div>
            <div class="group-897">
                <div class="group-890">
                    <div class="group-1000003921">
                        <h2 class="ui heading size-textxl">Email address</h2>
                        <label class="group-1000003919 ui input white_a700_01 size-lg fill round">
                            <input name="Group 1000003918" placeholder="Enter your email" type="text" />
                            <img src="{{ asset('images/img_basic_mail.svg') }}" alt="Basic / Mail" class="basic--mail" />
                        </label>
                    </div>
                    <div class="group-1000003922">
                        <div class="group-1000003920">
                            <h3 class="ui heading size-textxl">Password</h3>
                            <label class="group-1000003919 ui input white_a700_01 size-lg fill round">
                                <input name="Group 1000003919" placeholder="Enter Your Password" type="password" />
                                <img src="{{ asset('images/img_eye_24_outline.svg') }}" alt="Eye / 24 / Outline" class="basic--mail" />
                            </label>
                        </div>
                        <a href="#" class="forgot-password--link">
                            <h4 class="forgot-password ui heading size-text2xl">Forgot Password?</h4>
                        </a>
                    </div>
                </div>
                <a href="/#" class="hover-scale" target="_blank">
                    <button class="group-1000003915 ui button blue_900 size-3xl fill">Sign In</button>
                </a>
                <div class="group-881">
                    <div class="frame-34837">
                        <div class="vector-5"></div>
                        <h5 class="or ui heading size-text2xl">OR</h5>
                        <div class="vector-5"></div>
                    </div>
                    <div class="group-1000003946">
                        <a class="mb-10-sm hover-scale" href="/#" target="_blank">
                            <button class="group-1000003945 ui button white_a700_01 size-3xl fill round">
                                <img src="{{ asset('images/img_image_here.png') }}" alt="<<image Here>>" class="image-here" />
                                <span> Google</span>
                            </button>
                        </a>
                        <a class="hover-scale" href="/#" target="_blank">
                            <button class="group-1000003935 ui button white_a700_01 size-3xl fill round">
                                <div class="left-icon-wrapper">
                                    <img src="{{ asset('images/img_facebook.svg') }}" alt="Facebook" class="facebook" />
                                </div>
                                <span> Facebook</span>
                            </button>
                        </a>
                    </div>
                </div>
                <h6 class="don-t-have-an-account ui heading size-textxl">
                    <span class="don-t-have-an-account--span"> Don&#39;t Have an account?&nbsp;</span>
                    <span class="don-t-have-an-account--span-1 hover-scale" id="open-signup-modal"> Sign Up</span>
                </h6>
            </div>
        </div>
    </div>
</dialog>