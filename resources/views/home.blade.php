<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Norlox Solutions - Staffing Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
<style>
        :root {
    --primary-color: #4f46e5;
    --secondary-color: #7c3aed;
    --text-color: #1e293b;
    --light-text-color: #64748b;
    --bg-color: #f8fafc;
    --card-bg: #ffffff;
    --border-color: #e2e8f0;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    background-color: var(--bg-color);
    color: var(--text-color);
    line-height: 1.6;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    background: var(--card-bg);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.logo {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary-color);
}

.main-nav {
    display: flex;
    align-items: center;
    gap: 25px;
}

.nav-link {
    color: var(--text-color);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}

.nav-link:hover {
    color: var(--primary-color);
}

.nav-btn {
    padding: 8px 16px;
    background-color: var(--primary-color);
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.3s;
}

.nav-btn:hover {
    background-color: var(--secondary-color);
}

/* Hero Section */
.landing-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 60px 20px;
    text-align: center;
}

.hero-section {
    padding: 60px 20px;
    background: linear-gradient(135deg, #e0e7ff, #c3d2ff);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.hero-text h1 {
    font-size: 48px;
    font-weight: 700;
    color: var(--text-color);
    margin-bottom: 10px;
}

.hero-text p {
    font-size: 18px;
    color: var(--light-text-color);
    max-width: 600px;
    margin: 0 auto 40px;
}

/* User Roles Grid */
.user-roles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.role-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 30px;
    background: var(--card-bg);
    border-radius: 12px;
    text-decoration: none;
    color: var(--text-color);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.role-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.role-icon {
    font-size: 48px;
    color: var(--primary-color);
    margin-bottom: 20px;
}

.role-card h3 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 10px;
}

.role-card p {
    font-size: 14px;
    color: var(--light-text-color);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        gap: 15px;
    }
    .hero-text h1 {
        font-size: 36px;
    }
}
</style>
</head>
<body>
    <header class="header">
        <div class="logo">Norlox Solutions</div>
        <nav class="main-nav">
            <a class="nav-btn" href="{{ route('login') }}">LogIn</a>
        </nav>
    </header>

    <main class="landing-content">
        <section class="hero-section">
            <div class="hero-text">
                <h1>Welcome to Norlox Solutions</h1>
                <p>Your centralized hub for resume management and recruitment workflow. Select your role to get started.</p>
            </div>
            <div class="user-roles-grid">
                <a href="/dashboard/junior" class="role-card">
                    <i class="fas fa-user-graduate role-icon"></i>
                    <h3>Junior Recruiter</h3>
                    <p>Manage your assigned resumes and track their status.</p>
                </a>
                <a href="/dashboard/senior" class="role-card">
                    <i class="fas fa-user-tie role-icon"></i>
                    <h3>Senior Recruiter</h3>
                    <p>Oversee team progress and forward candidates to clients.</p>
                </a>
                <a href="/dashboard/accountant" class="role-card">
                    <i class="fas fa-calculator role-icon"></i>
                    <h3>Accountant</h3>
                    <p>Handle billing and financial reports for recruitment drives.</p>
                </a>
                <a href="/dashboard/trainer" class="role-card">
                    <i class="fas fa-chalkboard-teacher role-icon"></i>
                    <h3>Trainer</h3>
                    <p>Access training materials and onboarding resources.</p>
                </a>
                <a href="/dashboard/admin" class="role-card">
                    <i class="fas fa-user-shield role-icon"></i>
                    <h3>Admin</h3>
                    <p>Manage users, permissions, and system settings.</p>
                </a>
                <a href="/dashboard/customer" class="role-card">
                    <i class="fas fa-user role-icon"></i>
                    <h3>Customer</h3>
                    <p>View and manage your profile, resumes, and training progress.</p>
                </a>
            </div>
        </section>
    </main>
</body>
</html>