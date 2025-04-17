<header class="frame-1">
    <div class="group-1131 mb-10-sm container-xs">
        <!-- Logo Section -->
        <div class="group-864 ml-10">
            <a href="#">
                <button class="group-1000004148 ui button blue_900 size-5xl fill round hover-scale">
                    <img src="{{ asset('images/img_group_1000004148.svg') }}" alt="Logo" />
                </button>
            </a>
            <h3 class="resu-rank ui heading size-heading3xl">Resu<br />Rank</h3>
        </div>

        <!-- Navigation Links -->
        <div class="frame-2">
            <ul class="group-863">
                <li><a href="#">
                        <h6 class="about-1 ui heading size-headinglg hover-underline">About</h6>
                    </a></li>
                <li><a href="#">
                        <h6 class="about-1 ui heading size-headinglg hover-underline">Features</h6>
                    </a></li>
                <li><a href="#">
                        <h6 class="about-1 ui heading size-headinglg hover-underline">Pricing</h6>
                    </a></li>
                <li><a href="#">
                        <h6 class="about-1 ui heading size-headinglg hover-underline">How It Works</h6>
                    </a></li>
                <li><a href="#">
                        <h6 class="about-1 ui heading size-headinglg hover-underline">Contact</h6>
                    </a></li>
            </ul>
        </div>

        <!-- Buttons -->
        <!-- <div class="frame-5">
            <button class="frame-3 ui button blue_900 size-2xl fill round hover-scale"  aria-haspopup="true" aria-controls="sign-in-modal" aria-label="Open Modal">Sign In</button>
            <div class="line-1"></div>
            <button class="frame-4 ui button blue_gray_900_02 size-2xl outline round hover-scale"  aria-haspopup="true" aria-controls="sign-up-modal" aria-label="Open Modal">Get Started for Free</button>
        </div> -->

        <!-- <div class="search-profile">
            <div class="search-container">
                <input type="text" placeholder="Search for Resumes..." class="search-input">
                <button class="search-btn" aria-label="Search">
                    🔍
                </button>
            </div>
            <div class="profile-icon">
                <img src="{{ asset('images/img_image.png') }}" alt="Profile" class="profile-img">
            </div>
        </div> -->

        <div class="search-container">
            <div class="search-bar">
                <input type="text" placeholder="Search for Resume..." class="search-input">
                <div class="line-2"></div>
                <button class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="profile-section">
                <img src="{{ asset('images/user_default.svg') }}" alt="Profile" class="profile-img">
                <!-- <span class="dropdown-icon">▼</span> -->
                <i class="fas fa-chevron-down dropdown-icon"></i>

                <div class="dropdown-menu">
                    <a href="{{ route('profile') }}"><i class="fas fa-user"></i> Profile</a>
                    <a href="#" id="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>

            
        </div>



    </div>
</header>