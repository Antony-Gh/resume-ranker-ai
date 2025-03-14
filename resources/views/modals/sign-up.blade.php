
<!-- Modal Dialog -->
<dialog id="modal-dialog-sign-up" aria-label="Modal" aria-modal="true" class="modal-dialog">
    <button class="close-button" aria-controls="modal-dialog-sign-up" aria-label="Close modal">
        <img src="{{ asset('images/img_close_icon.svg') }}" alt="Close" class="close-icon" />
    </button>
    <div class="frame-34842">
        <div class="group-1135 container-xs">
            <a href="#">
                <h1 class="sign-in ui heading size-heading4xl">Sign Up</h1>
            </a>
            <h2 class="welcome-enter-email ui heading size-text2xl">Welcome! Enter Email to Signup</h2>
            <div class="vector-3-2"></div>
            <div class="frame-34845">
                <div class="frame-34846">
                    <div class="group-1000003921">
                        <h3 class="ui heading size-textxl">Name</h3>
                        <label class="group-1000003918-1 ui input white_a700_01 size-lg fill round">
                            <input name="Group 1000003918" placeholder="Enter your name" type="text" />
                            <img src="{{ asset('images/img_basic_mail.svg') }}" alt="Basic / Mail"
                                class="basic--mail" />
                        </label>
                    </div>
                    <div class="group-1000003921">
                        <h4 class="ui heading size-textxl">Email address</h4>
                        <label class="group-1000003918-1 ui input white_a700_01 size-lg fill round">
                            <input name="Group 1000003918" placeholder="Enter your email" type="text" />
                            <img src="{{ asset('images/img_basic_mail.svg') }}" alt="Basic / Mail"
                                class="basic--mail" />
                        </label>
                    </div>
                    <div class="group-1000003921">
                        <h5 class="ui heading size-textxl">Password</h5>
                        <label class="group-1000003918-1 ui input white_a700_01 size-lg fill round">
                            <input name="Group 1000003918" placeholder="Enter your password" type="password" />
                            <img src="{{ asset('images/img_eye_24_outline.svg') }}" alt="Eye / 24 / Outline"
                                class="basic--mail" />
                        </label>
                    </div>
                </div>
                <a href="/#" class="hover-scale" target="_blank">
                    <button class="group-1000003915 ui button blue_900 size-3xl fill">Sign Up</button>
                </a>
                <div class="group-881">
                    <div class="frame-34837">
                        <div class="vector-5"></div>
                        <h6 class="already-have-an ui heading size-text2xl">OR</h6>
                        <div class="vector-5"></div>
                    </div>
                    <div class="group-1000003946">
                        <a href="/#" class="mb-10-sm hover-scale" target="_blank">
                            <button class="group-1000003945 ui button white_a700_01 size-3xl fill round">
                                <img src="{{ asset('images/img_image_here.png') }}" alt="Google"
                                    class="image-here" />
                                <span> Google</span>
                            </button>
                        </a>
                        <a href="/#" class="hover-scale" target="_blank">
                            <button class="group-1000003935 ui button white_a700_01 size-3xl fill round">
                                <div class="left-icon-wrapper">
                                    <img src="{{ asset('images/img_facebook.svg') }}" alt="Facebook" class="facebook" />
                                </div>
                                <span> Facebook</span>
                            </button>
                        </a>
                    </div>
                </div>
                <p class="already-have-an ui heading size-textxl">
                    <span class="don-t-have-an-account--span"> Already Have an account?&nbsp;</span>
                    <span class="don-t-have-an-account--span-1 hover-scale" id="open-signin-modal"> Sign In</span>
                </p>
            </div>
        </div>
    </div>
</dialog>