<x-guest-layout title="Login">
    @auth
        @if (auth()->user()->role === \App\Models\User::ROLES['Admin'])
            <script>
                window.location.href = "{{ route('admin.dashboard') }}";
            </script>
        @endif
        @if (auth()->user()->role === \App\Models\User::ROLES['Owner'])
            <script>
                window.location.href = "{{ route('admin.dashboard') }}";
            </script>
        @endif
    @else
        <!-- Particle Background -->
        <div id="particles-js"></div>

        <!-- Floating Geometric Shapes -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            {{-- <div class="shape shape-4"></div>
            <div class="shape shape-5"></div>
            <div class="shape shape-6"></div> --}}
        </div>

        <div class="ultra-modern-login">
            <div class="container">
                <div class="row justify-content-center align-items-center min-vh-100">
                    <div class="col-12" style="max-width: 1200px;">

                        <!-- Main Login Container -->
                        <div class="login-mega-container">
                            <!-- Top Company Banner -->
                            <div class="company-banner-ultra animate__animated animate__fadeInDown">
                                <div class="logo-ultra-container">
                                    <div class="logo-3d-wrapper">
                                        <div class="logo-3d-bg"></div>
                                        <div class="logo-3d-circle"></div>
                                        <div class="logo-3d-content">
                                            <div class="logo-grid-3d">
                                                <div class="grid-item-3d"
                                                    style="background: linear-gradient(45deg, #10b981, #34d399)"></div>
                                                <div class="grid-item-3d"
                                                    style="background: linear-gradient(45deg, #fbbf24, #fde047)"></div>
                                                <div class="grid-item-3d"
                                                    style="background: linear-gradient(45deg, #3b82f6, #60a5fa)"></div>
                                                <div class="grid-item-3d"
                                                    style="background: linear-gradient(45deg, #ef4444, #f87171)"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="logo-text-3d">INVENTORY</div>
                                </div>
                                <h1 class="company-name-ultra">Inventory Management System</h1>
                                <p class="company-address-ultra">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Jl. Raya Kopo Katapang, No. 243, Pangauban, Katapang, Bandung - 40214
                                    <span class="separator">|</span>
                                    <i class="fas fa-phone"></i>
                                    TLP/FAX: (081) 9080 80889
                                </p>
                            </div>

                            <!-- Main Content Cards -->
                            <div class="row g-0 main-content-row">
                                <!-- Left Panel - Animated Illustration -->
                                <div class="col-lg-6">
                                    <div
                                        class="illustration-panel-ultra animate__animated animate__fadeInLeft animate__delay-1s">
                                        <div class="illustration-content">
                                            <!-- Animated Dashboard Preview -->
                                            <div class="dashboard-preview">
                                                <div class="dashboard-header">
                                                    <div class="dashboard-dots">
                                                        <span class="dot red"></span>
                                                        <span class="dot yellow"></span>
                                                        <span class="dot green"></span>
                                                    </div>
                                                    <div class="dashboard-title">Dashboard</div>
                                                </div>
                                                <div class="dashboard-body">
                                                    {{-- <div class="chart-container">
                                                        <div class="chart-bars">
                                                            <div class="bar" style="height: 60%"></div>
                                                            <div class="bar" style="height: 80%"></div>
                                                            <div class="bar" style="height: 45%"></div>
                                                            <div class="bar" style="height: 90%"></div>
                                                            <div class="bar" style="height: 70%"></div>
                                                        </div>
                                                    </div> --}}
                                                    <div class="stats-grid">
                                                        <div class="stat-card">
                                                            <div class="stat-icon">📦</div>
                                                            <div class="stat-number">2,847</div>
                                                            <div class="stat-label">Products</div>
                                                        </div>
                                                        <div class="stat-card">
                                                            <div class="stat-icon">📊</div>
                                                            <div class="stat-number">98.5%</div>
                                                            <div class="stat-label">Efficiency</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Welcome Text -->
                                            <div class="welcome-text-ultra">
                                                <h2 class="welcome-title-ultra">Welcome Back!</h2>
                                                <p class="welcome-subtitle-ultra">Access your intelligent inventory
                                                    management system</p>

                                                <!-- Feature Highlights -->
                                                <div class="feature-highlights">
                                                    <div class="feature-item">
                                                        <i class="fas fa-chart-line"></i>
                                                        <span>Real-time Analytics</span>
                                                    </div>
                                                    {{-- <div class="feature-item">
                                                        <i class="fas fa-shield-alt"></i>
                                                        <span>Enterprise Security</span>
                                                    </div> --}}
                                                    <div class="feature-item">
                                                        <i class="fas fa-mobile-alt"></i>
                                                        <span>Mobile Ready</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Panel - Login Form -->
                                <div class="col-lg-6">
                                    <div class="login-panel-ultra animate__animated animate__fadeInRight animate__delay-1s">
                                        <div class="login-glass-card">
                                            <div class="card-header-ultra">
                                                <div class="signin-icon">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                </div>
                                                <h3>Sign In</h3>
                                                <p>Enter your credentials to continue</p>
                                            </div>

                                            <form action="{{ route('login') }}" method="POST" class="login-form-ultra">
                                                @csrf
                                                @method('POST')

                                                <!-- Email Field -->
                                                <div class="form-group-ultra mb-4">
                                                    <div class="input-wrapper-ultra">
                                                        <div class="input-icon-ultra">
                                                            <i class="fas fa-envelope"></i>
                                                        </div>
                                                        <div class="form-floating-ultra">
                                                            <input type="email"
                                                                class="form-control-ultra @error('email') is-invalid @enderror"
                                                                name="email" id="email" placeholder="Email"
                                                                value="{{ old('email') }}" required>
                                                            <label for="email"></label>
                                                        </div>
                                                        @error('email')
                                                            <div class="invalid-feedback-ultra">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Password Field -->
                                                <div class="form-group-ultra mb-4">
                                                    <div class="input-wrapper-ultra">
                                                        <div class="input-icon-ultra">
                                                            <i class="fas fa-lock"></i>
                                                        </div>
                                                        <div class="form-floating-ultra">
                                                            <input type="password"
                                                                class="form-control-ultra @error('password') is-invalid @enderror"
                                                                name="password" id="password" placeholder="Password"
                                                                required>
                                                            <label for="password"></label>
                                                        </div>
                                                        <button type="button" class="password-toggle-ultra"
                                                            onclick="togglePasswordUltra()">
                                                            <i class="fas fa-eye" id="toggleIconUltra"></i>
                                                        </button>
                                                        @error('password')
                                                            <div class="invalid-feedback-ultra">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Remember Me -->
                                                <div class="form-options-ultra mb-4">
                                                    <div class="checkbox-ultra">
                                                        <input type="checkbox" name="remember" id="remember"
                                                            class="checkbox-input-ultra">
                                                        <label for="remember" class="checkbox-label-ultra">
                                                            <span class="checkbox-custom-ultra"></span>
                                                            Remember me
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Submit Button -->
                                                <button type="submit" class="btn-submit-ultra">
                                                    <span class="btn-text">Sign In</span>
                                                    <span class="btn-icon">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </span>
                                                    <div class="btn-ripple"></div>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('styles')
            <!-- Styles -->
            <style>


                :root {
                    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                    --glass-bg: rgba(255, 255, 255, 0.25);
                    --glass-border: rgba(255, 255, 255, 0.18);
                    --text-primary: #2d3748;
                    --text-secondary: #718096;
                    --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
                }

                * {
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Poppins', sans-serif;
                    margin: 0;
                    padding: 0;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
                    min-height: 100vh;
                    overflow-x: hidden;
                    position: relative;
                }

                /* Particle Background */
                #particles-js {
                    position: fixed;
                    width: 100%;
                    height: 100%;
                    top: 0;
                    left: 0;
                    z-index: 1;
                    pointer-events: none;
                }

                /* Floating Shapes */
                .floating-shapes {
                    position: fixed;
                    width: 100%;
                    height: 100%;
                    top: 0;
                    left: 0;
                    z-index: 2;
                    pointer-events: none;
                }

                .shape {
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.1);
                    animation: float 6s ease-in-out infinite;
                }

                .shape-1 {
                    width: 80px;
                    height: 80px;
                    top: 20%;
                    left: 10%;
                    animation-delay: 0s;
                }

                .shape-2 {
                    width: 60px;
                    height: 60px;
                    top: 60%;
                    left: 15%;
                    animation-delay: 2s;
                }

                .shape-3 {
                    width: 100px;
                    height: 100px;
                    top: 30%;
                    right: 20%;
                    animation-delay: 4s;
                }

                .shape-4 {
                    width: 40px;
                    height: 40px;
                    top: 80%;
                    right: 10%;
                    animation-delay: 1s;
                }

                .shape-5 {
                    width: 120px;
                    height: 120px;
                    top: 10%;
                    right: 30%;
                    animation-delay: 3s;
                }

                .shape-6 {
                    width: 70px;
                    height: 70px;
                    top: 70%;
                    left: 80%;
                    animation-delay: 5s;
                }

                @keyframes float {

                    0%,
                    100% {
                        transform: translateY(0px) rotate(0deg);
                    }

                    50% {
                        transform: translateY(-20px) rotate(180deg);
                    }
                }

                /* Main Container */
                .ultra-modern-login {
                    position: relative;
                    z-index: 10;
                    padding: 2rem 0;
                }

                .login-mega-container {
                    backdrop-filter: blur(20px);
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 30px;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    box-shadow: var(--shadow-2xl);
                    overflow: hidden;
                    position: relative;
                }

                .login-mega-container::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
                    pointer-events: none;
                }

                /* Company Banner */
                .company-banner-ultra {
                    padding: 2rem;
                    text-align: center;
                    background: rgba(255, 255, 255, 0.1);
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                }

                .logo-ultra-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    margin-bottom: 1rem;
                }

                .logo-3d-wrapper {
                    position: relative;
                    width: 120px;
                    height: 120px;
                    margin-bottom: 1rem;
                }

                .logo-3d-bg {
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    background: var(--primary-gradient);
                    border-radius: 50%;
                    filter: blur(10px);
                    animation: logoGlow 3s ease-in-out infinite;
                }

                .logo-3d-circle {
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 50%;
                    border: 2px solid rgba(255, 255, 255, 0.3);
                    backdrop-filter: blur(10px);
                }

                .logo-3d-content {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 60px;
                    height: 60px;
                }

                .logo-grid-3d {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 4px;
                    width: 100%;
                    height: 100%;
                }

                .grid-item-3d {
                    border-radius: 8px;
                    animation: gridPulse 2s ease-in-out infinite;
                }

                .grid-item-3d:nth-child(1) {
                    animation-delay: 0s;
                }

                .grid-item-3d:nth-child(2) {
                    animation-delay: 0.5s;
                }

                .grid-item-3d:nth-child(3) {
                    animation-delay: 1s;
                }

                .grid-item-3d:nth-child(4) {
                    animation-delay: 1.5s;
                }

                .logo-text-3d {
                    font-size: 0.8rem;
                    font-weight: 700;
                    color: white;
                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
                    letter-spacing: 2px;
                }

                .company-name-ultra {
                    font-size: 2rem;
                    font-weight: 800;
                    color: white;
                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
                    margin-bottom: 0.5rem;
                }

                .company-address-ultra {
                    color: rgba(255, 255, 255, 0.9);
                    font-size: 0.9rem;
                    margin: 0;
                }

                .separator {
                    margin: 0 1rem;
                    opacity: 0.5;
                }

                @keyframes logoGlow {

                    0%,
                    100% {
                        transform: scale(1);
                    }

                    50% {
                        transform: scale(1.05);
                    }
                }

                @keyframes gridPulse {

                    0%,
                    100% {
                        transform: scale(1);
                        opacity: 1;
                    }

                    50% {
                        transform: scale(1.1);
                        opacity: 0.8;
                    }
                }

                /* Main Content Row */
                .main-content-row {
                    min-height: 600px;
                }

                /* Left Panel - Illustration */
                .illustration-panel-ultra {
                    background: rgba(255, 255, 255, 0.1);
                    backdrop-filter: blur(15px);
                    border-right: 1px solid rgba(255, 255, 255, 0.1);
                    padding: 3rem;
                    display: flex;
                    align-items: center;
                    position: relative;
                    overflow: hidden;
                }

                .illustration-panel-ultra::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: var(--primary-gradient);
                    opacity: 0.1;
                    pointer-events: none;
                }

                .illustration-content {
                    position: relative;
                    z-index: 2;
                    width: 100%;
                }

                /* Dashboard Preview */
                .dashboard-preview {
                    background: rgba(255, 255, 255, 0.15);
                    backdrop-filter: blur(10px);
                    border-radius: 20px;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    margin-bottom: 2rem;
                    overflow: hidden;
                    box-shadow: var(--shadow-xl);
                    animation: dashboardFloat 8s ease-in-out infinite;
                }

                .dashboard-header {
                    background: rgba(255, 255, 255, 0.1);
                    padding: 1rem;
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                }

                .dashboard-dots {
                    display: flex;
                    gap: 0.5rem;
                }

                .dot {
                    width: 12px;
                    height: 12px;
                    border-radius: 50%;
                }

                .dot.red {
                    background: #ff5f57;
                }

                .dot.yellow {
                    background: #ffbd2e;
                }

                .dot.green {
                    background: #28ca42;
                }

                .dashboard-title {
                    color: white;
                    font-weight: 600;
                    font-size: 0.9rem;
                }

                .dashboard-body {
                    padding: 1.5rem;
                }

                .chart-container {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 12px;
                    padding: 1rem;
                    margin-bottom: 1rem;
                }

                .chart-bars {
                    display: flex;
                    align-items: end;
                    gap: 0.5rem;
                    height: 80px;
                }

                .bar {
                    background: var(--success-gradient);
                    border-radius: 4px;
                    flex: 1;
                    animation: barGrow 2s ease-out infinite;
                }

                .bar:nth-child(1) {
                    animation-delay: 0s;
                }

                .bar:nth-child(2) {
                    animation-delay: 0.2s;
                }

                .bar:nth-child(3) {
                    animation-delay: 0.4s;
                }

                .bar:nth-child(4) {
                    animation-delay: 0.6s;
                }

                .bar:nth-child(5) {
                    animation-delay: 0.8s;
                }

                .stats-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 1rem;
                }

                .stat-card {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 12px;
                    padding: 1rem;
                    text-align: center;
                    border: 1px solid rgba(255, 255, 255, 0.1);
                }

                .stat-icon {
                    font-size: 1.5rem;
                    margin-bottom: 0.5rem;
                }

                .stat-number {
                    font-size: 1.2rem;
                    font-weight: 700;
                    color: white;
                    margin-bottom: 0.2rem;
                }

                .stat-label {
                    font-size: 0.8rem;
                    color: rgba(255, 255, 255, 0.8);
                }

                @keyframes dashboardFloat {

                    0%,
                    100% {
                        transform: translateY(0px);
                    }

                    50% {
                        transform: translateY(-10px);
                    }
                }

                @keyframes barGrow {
                    0% {
                        height: 0;
                    }

                    100% {
                        height: var(--bar-height);
                    }
                }

                /* Welcome Text */
                .welcome-text-ultra {
                    text-align: center;
                    color: white;
                    margin-bottom: 2rem;
                }

                .welcome-title-ultra {
                    font-size: 2.5rem;
                    font-weight: 800;
                    margin-bottom: 1rem;
                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
                }

                .welcome-subtitle-ultra {
                    font-size: 1.1rem;
                    opacity: 0.9;
                    margin-bottom: 2rem;
                }

                .feature-highlights {
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }

                .feature-item {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    background: rgba(255, 255, 255, 0.1);
                    padding: 1rem;
                    border-radius: 12px;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    backdrop-filter: blur(10px);
                    transition: all 0.3s ease;
                }

                .feature-item:hover {
                    background: rgba(255, 255, 255, 0.2);
                    transform: translateX(10px);
                }

                .feature-item i {
                    font-size: 1.2rem;
                    color: #4facfe;
                }

                .feature-item span {
                    font-weight: 500;
                }

                /* Right Panel - Login Form */
                .login-panel-ultra {
                    background: rgba(255, 255, 255, 0.05);
                    backdrop-filter: blur(15px);
                    padding: 3rem;
                    display: flex;
                    align-items: center;
                    position: relative;
                }

                .login-glass-card {
                    background: rgba(255, 255, 255, 0.15);
                    backdrop-filter: blur(20px);
                    border-radius: 25px;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    padding: 2.5rem;
                    width: 100%;
                    box-shadow: var(--shadow-xl);
                    position: relative;
                    overflow: hidden;
                }

                .login-glass-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 1px;
                    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
                    pointer-events: none;
                }

                .card-header-ultra {
                    text-align: center;
                    margin-bottom: 2rem;
                    position: relative;
                }

                .signin-icon {
                    width: 60px;
                    height: 60px;
                    background: var(--primary-gradient);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 1rem;
                    font-size: 1.5rem;
                    color: white;
                    box-shadow: var(--shadow-xl);
                    animation: iconPulse 2s ease-in-out infinite;
                }

                .card-header-ultra h3 {
                    font-size: 1.8rem;
                    font-weight: 700;
                    color: white;
                    margin-bottom: 0.5rem;
                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
                }

                .card-header-ultra p {
                    color: rgba(255, 255, 255, 0.8);
                    font-size: 0.95rem;
                    margin: 0;
                }

                @keyframes iconPulse {

                    0%,
                    100% {
                        transform: scale(1);
                    }

                    50% {
                        transform: scale(1.1);
                    }
                }

                /* Form Styles */
                .login-form-ultra {
                    position: relative;
                }

                .form-group-ultra {
                    position: relative;
                    margin-bottom: 1.5rem;
                }

                .input-wrapper-ultra {
                    position: relative;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 15px;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    backdrop-filter: blur(10px);
                    transition: all 0.3s ease;
                    overflow: hidden;
                }

                .input-wrapper-ultra:focus-within {
                    border-color: rgba(255, 255, 255, 0.4);
                    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
                    background: rgba(255, 255, 255, 0.15);
                }

                .input-icon-ultra {
                    position: absolute;
                    left: 1rem;
                    top: 50%;
                    transform: translateY(-50%);
                    color: rgba(255, 255, 255, 0.7);
                    font-size: 1.1rem;
                    z-index: 3;
                    transition: all 0.3s ease;
                }

                .input-wrapper-ultra:focus-within .input-icon-ultra {
                    color: white;
                    transform: translateY(-50%) scale(1.1);
                }

                .form-floating-ultra {
                    position: relative;
                    flex: 1;
                }

                .form-control-ultra {
                    width: 100%;
                    padding: 1rem 1rem 1rem 3rem;
                    border: none;
                    background: transparent;
                    font-size: 1rem;
                    font-weight: 500;
                    color: white;
                    outline: none;
                    transition: all 0.3s ease;
                    border-radius: 15px;
                }

                .form-control-ultra::placeholder {
                    color: rgba(255, 255, 255, 0.5);
                    opacity: 0;
                    transition: all 0.3s ease;
                }

                .form-control-ultra:focus::placeholder {
                    opacity: 1;
                }

                .form-floating-ultra label {
                    position: absolute;
                    top: 50%;
                    left: 3rem;
                    transform: translateY(-50%);
                    color: rgba(255, 255, 255, 0.7);
                    font-size: 1rem;
                    font-weight: 500;
                    pointer-events: none;
                    transition: all 0.3s ease;
                    z-index: 2;
                }

                .form-control-ultra:focus+label,
                .form-control-ultra:not(:placeholder-shown)+label {
                    top: 0.5rem;
                    left: 3rem;
                    font-size: 0.8rem;
                    color: white;
                    transform: translateY(0);
                    background: rgba(255, 255, 255, 0.1);
                    padding: 0.2rem 0.5rem;
                    border-radius: 8px;
                    backdrop-filter: blur(10px);
                }

                .password-toggle-ultra {
                    position: absolute;
                    right: 1rem;
                    top: 50%;
                    transform: translateY(-50%);
                    background: none;
                    border: none;
                    color: rgba(255, 255, 255, 0.7);
                    font-size: 1rem;
                    cursor: pointer;
                    z-index: 3;
                    transition: all 0.3s ease;
                    padding: 0.5rem;
                    border-radius: 8px;
                }

                .password-toggle-ultra:hover {
                    color: white;
                    background: rgba(255, 255, 255, 0.1);
                }

                .invalid-feedback-ultra {
                    color: #ff6b6b;
                    font-size: 0.85rem;
                    margin-top: 0.5rem;
                    padding-left: 1rem;
                    font-weight: 500;
                }

                .form-control-ultra.is-invalid {
                    border-color: #ff6b6b;
                }

                .input-wrapper-ultra.is-invalid {
                    border-color: #ff6b6b;
                    box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.2);
                }

                /* Form Options */
                .form-options-ultra {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 2rem;
                }

                .checkbox-ultra {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                }

                .checkbox-input-ultra {
                    display: none;
                }

                .checkbox-label-ultra {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    color: rgba(255, 255, 255, 0.8);
                    font-size: 0.9rem;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                .checkbox-label-ultra:hover {
                    color: white;
                }

                .checkbox-custom-ultra {
                    width: 20px;
                    height: 20px;
                    border: 2px solid rgba(255, 255, 255, 0.3);
                    border-radius: 6px;
                    background: rgba(255, 255, 255, 0.1);
                    backdrop-filter: blur(10px);
                    position: relative;
                    transition: all 0.3s ease;
                }

                .checkbox-input-ultra:checked+.checkbox-label-ultra .checkbox-custom-ultra {
                    background: var(--primary-gradient);
                    border-color: transparent;
                    transform: scale(1.1);
                }

                .checkbox-input-ultra:checked+.checkbox-label-ultra .checkbox-custom-ultra::after {
                    content: '✓';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    color: white;
                    font-size: 0.8rem;
                    font-weight: 700;
                }

                /* Submit Button */
                .btn-submit-ultra {
                    width: 100%;
                    padding: 1rem 2rem;
                    background: var(--primary-gradient);
                    border: none;
                    border-radius: 15px;
                    color: white;
                    font-size: 1.1rem;
                    font-weight: 600;
                    cursor: pointer;
                    position: relative;
                    overflow: hidden;
                    transition: all 0.3s ease;
                    box-shadow: var(--shadow-xl);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 1rem;
                }

                .btn-submit-ultra:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
                }

                .btn-submit-ultra:active {
                    transform: translateY(0);
                }

                .btn-text {
                    position: relative;
                    z-index: 2;
                }

                .btn-icon {
                    position: relative;
                    z-index: 2;
                    transition: all 0.3s ease;
                }

                .btn-submit-ultra:hover .btn-icon {
                    transform: translateX(5px);
                }

                .btn-ripple {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 0;
                    height: 0;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.3);
                    transform: translate(-50%, -50%);
                    transition: all 0.6s ease;
                    z-index: 1;
                }

                .btn-submit-ultra:active .btn-ripple {
                    width: 300px;
                    height: 300px;
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    .company-name-ultra {
                        font-size: 1.5rem;
                    }

                    .company-address-ultra {
                        font-size: 0.8rem;
                    }

                    .separator {
                        display: block;
                        margin: 0.5rem 0;
                    }

                    .login-mega-container {
                        border-radius: 20px;
                        margin: 1rem;
                    }

                    .main-content-row {
                        flex-direction: column;
                    }

                    .illustration-panel-ultra {
                        padding: 2rem;
                        border-right: none;
                        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                    }

                    .login-panel-ultra {
                        padding: 2rem;
                    }

                    .welcome-title-ultra {
                        font-size: 2rem;
                    }

                    .feature-highlights {
                        flex-direction: row;
                        flex-wrap: wrap;
                        gap: 0.5rem;
                    }

                    .feature-item {
                        flex: 1;
                        min-width: 150px;
                        justify-content: center;
                        text-align: center;
                        flex-direction: column;
                        gap: 0.5rem;
                    }

                    .feature-item:hover {
                        transform: translateY(-5px);
                    }

                    .stats-grid {
                        grid-template-columns: 1fr;
                    }

                    .dashboard-preview {
                        margin-bottom: 1rem;
                    }

                    .logo-3d-wrapper {
                        width: 80px;
                        height: 80px;
                    }

                    .logo-3d-content {
                        width: 40px;
                        height: 40px;
                    }
                }

                @media (max-width: 480px) {
                    .ultra-modern-login {
                        padding: 1rem 0;
                    }

                    .company-banner-ultra {
                        padding: 1.5rem;
                    }

                    .illustration-panel-ultra,
                    .login-panel-ultra {
                        padding: 1.5rem;
                    }

                    .login-glass-card {
                        padding: 1.5rem;
                    }

                    .welcome-title-ultra {
                        font-size: 1.5rem;
                    }

                    .form-group-ultra {
                        margin-bottom: 1rem;
                    }

                    .feature-highlights {
                        flex-direction: column;
                    }

                    .feature-item {
                        flex-direction: row;
                        text-align: left;
                    }
                }

                /* Loading States */
                .btn-submit-ultra.loading {
                    pointer-events: none;
                    opacity: 0.8;
                }

                .btn-submit-ultra.loading .btn-text {
                    opacity: 0;
                }

                .btn-submit-ultra.loading::after {
                    content: '';
                    position: absolute;
                    width: 20px;
                    height: 20px;
                    border: 2px solid transparent;
                    border-top: 2px solid white;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }

                /* Additional Animations */
                .animate-fade-in {
                    opacity: 0;
                    animation: fadeIn 0.8s ease-out forwards;
                }

                @keyframes fadeIn {
                    to {
                        opacity: 1;
                    }
                }

                .animate-slide-up {
                    transform: translateY(50px);
                    opacity: 0;
                    animation: slideUp 0.8s ease-out forwards;
                }

                @keyframes slideUp {
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                /* Accessibility */
                .btn-submit-ultra:focus,
                .form-control-ultra:focus,
                .checkbox-input-ultra:focus+.checkbox-label-ultra .checkbox-custom-ultra {
                    outline: 2px solid rgba(255, 255, 255, 0.5);
                    outline-offset: 2px;
                }

                /* Print Styles */
                @media print {

                    .floating-shapes,
                    #particles-js {
                        display: none;
                    }

                    .ultra-modern-login {
                        background: white;
                        color: black;
                    }
                }
            </style>
        @endpush

        @push('scripts')
         <!-- External Scripts -->

            <!-- JavaScript -->
            <script>
                // Password Toggle Function
                function togglePasswordUltra() {
                    const passwordInput = document.getElementById('password');
                    const toggleIcon = document.getElementById('toggleIconUltra');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        toggleIcon.className = 'fas fa-eye-slash';
                    } else {
                        passwordInput.type = 'password';
                        toggleIcon.className = 'fas fa-eye';
                    }
                }

                // Form Submission Loading State
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.querySelector('.login-form-ultra');
                    const submitBtn = document.querySelector('.btn-submit-ultra');

                    if (form && submitBtn) {
                        form.addEventListener('submit', function() {
                            submitBtn.classList.add('loading');
                        });
                    }

                    // Add focus animations
                    const inputs = document.querySelectorAll('.form-control-ultra');
                    inputs.forEach(input => {
                        input.addEventListener('focus', function() {
                            this.closest('.input-wrapper-ultra').style.transform = 'translateY(-2px)';
                        });

                        input.addEventListener('blur', function() {
                            this.closest('.input-wrapper-ultra').style.transform = 'translateY(0)';
                        });
                    });

                    // Add entrance animations
                    const animateElements = document.querySelectorAll('.animate__animated');
                    animateElements.forEach((element, index) => {
                        element.style.animationDelay = `${index * 0.2}s`;
                    });
                });

                // Particle.js initialization (if you want to add particles)
                if (typeof particlesJS !== 'undefined') {
                    particlesJS('particles-js', {
                        particles: {
                            number: {
                                value: 80,
                                density: {
                                    enable: true,
                                    value_area: 800
                                }
                            },
                            color: {
                                value: '#ffffff'
                            },
                            shape: {
                                type: 'circle'
                            },
                            opacity: {
                                value: 0.5,
                                random: false
                            },
                            size: {
                                value: 3,
                                random: true
                            },
                            line_linked: {
                                enable: true,
                                distance: 150,
                                color: '#ffffff',
                                opacity: 0.4,
                                width: 1
                            },
                            move: {
                                enable: true,
                                speed: 6,
                                direction: 'none',
                                random: false,
                                straight: false,
                                out_mode: 'out',
                                bounce: false
                            }
                        },
                        interactivity: {
                            detect_on: 'canvas',
                            events: {
                                onhover: {
                                    enable: true,
                                    mode: 'repulse'
                                },
                                onclick: {
                                    enable: true,
                                    mode: 'push'
                                },
                                resize: true
                            }
                        },
                        retina_detect: true
                    });
                }
            </script>


        @endpush
    @endauth
</x-guest-layout>
