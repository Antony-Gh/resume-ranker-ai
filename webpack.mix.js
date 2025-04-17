const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs');
mix.options({
    // Prevent duplicate asset copying
    copyUnmodified: false
});

mix.setPublicPath('');

// Define CSS files to compile
const cssFiles = [
    'resources/css/AdminDashboard.css',
    'resources/css/SubscriptionManagement.css',
    'resources/css/Analytics.css',
    'resources/css/OTPVerification.css',
    'resources/css/SuccessfullCard.css',
    'resources/css/CandidateInsights.css',
    'resources/css/PaymentMethod.css',
    'resources/css/SummaryAndRecomendation.css',
    // 'resources/css/Popup.css',
    'resources/css/CheckEmail.css',
    'resources/css/UploadJobDescription.css',
    'resources/css/Cover.css',
    'resources/css/Pricing.css',
    'resources/css/UserManagement.css',
    'resources/css/Font.css',
    'resources/css/ResumeRankings.css',
    'resources/css/app.css',
    'resources/css/ForgetPassword.css',
    // 'resources/css/SignIn.css',
    // 'resources/css/SignUp.css',
    'resources/css/HomeOne.css',
    'resources/css/HomeOne1.css',
    'resources/css/SignUpOne.css',
    'resources/css/Header.css',
    'resources/css/Footer.css',
    'resources/css/auth.css',
];

// Define JS files to compile (if any)
const jsFiles = [
    'resources/js/app.js',
    'resources/js/bootstrap.js',
    // 'resources/js/sign-in-modal.js',
    'resources/js/auth.js',
    'resources/js/otp-verification.js',
    'resources/js/sign-in-script.js',
    'resources/js/sign-up-script.js',
    'resources/js/check-action.js',
    'resources/js/main-home.js',
    'resources/js/forgot-password-script.js',
    'resources/js/reset-password-script.js',
    'resources/js/check-email-script.js',
];

// Compile CSS files
cssFiles.forEach((file) => {
    mix.css(file, 'css');
});

// Compile JS files
jsFiles.forEach((file) => {
    mix.js(file, 'js');
});

mix.copy('resources/fonts', 'fonts', false) // false prevents recursive copying
mix.copy('resources/images', 'images', false) // false prevents recursive copying

// Configure Webpack to handle images and fonts
// mix.webpackConfig({
//     module: {
//         rules: [
//             {
//                 test: /\.(png|jpe?g|gif|svg)$/i,
//                 type: 'asset/resource',
//                 generator: {
//                     filename: 'images/[name][ext]', // Output images to public/images
//                 },
//             },
//             {
//                 test: /\.(woff|woff2|eot|ttf|otf)$/i,
//                 type: 'asset/resource',
//                 generator: {
//                     filename: 'fonts/[name][ext]', // Output fonts to public/fonts
//                 },
//             },
//         ],
//     },
// });

// Enable versioning in production
if (mix.inProduction()) {
    mix.version();

    // Minify all CSS files in the public/css directory
    const cssOutputDir = path.resolve(__dirname, 'css');
    if (fs.existsSync(cssOutputDir)) {
        fs.readdirSync(cssOutputDir).forEach((file) => {
            if (file.endsWith('.css')) {
                mix.minify(path.join(cssOutputDir, file));
            }
        });
    }

    // Minify all JS files in the public/js directory
    const jsOutputDir = path.resolve(__dirname, 'js');
    if (fs.existsSync(jsOutputDir)) {
        fs.readdirSync(jsOutputDir).forEach((file) => {
            if (file.endsWith('.js')) {
                mix.minify(path.join(jsOutputDir, file));
            }
        });
    }
} else {
    mix.sourceMaps();
}
