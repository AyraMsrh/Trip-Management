<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        {{ $title ?? 'Sistem Pengurusan Lawatan' }}
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>
        :root {
            --purple-950: #241033;
            --purple-900: #35134f;
            --purple-700: #63247c;
            --purple-100: #f3eafa;

            --teal-600: #069b8c;
            --teal-100: #e2f8f5;

            --text-dark: #29252d;
            --text-muted: #77717d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--text-dark);
            background:
                radial-gradient(
                    circle at 10% 10%,
                    rgba(150, 60, 175, 0.12),
                    transparent 28%
                ),
                #faf9fb;
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 1080px;
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            overflow: hidden;
            background: white;
            border: 1px solid #ece8ef;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(53, 19, 79, 0.12);
            animation: authEnter 0.55s ease both;
        }

        .auth-intro {
            position: relative;
            min-height: 650px;
            overflow: hidden;
            padding: 48px;
            color: white;
            background:
                linear-gradient(
                    145deg,
                    rgba(53, 19, 79, 0.97),
                    rgba(99, 36, 124, 0.93)
                );
        }

        .auth-intro::after {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            right: -180px;
            bottom: -180px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 50%;
            box-shadow:
                0 0 0 30px rgba(255, 255, 255, 0.04),
                0 0 0 60px rgba(255, 255, 255, 0.03),
                0 0 0 90px rgba(255, 255, 255, 0.02);
        }

        .auth-intro-logo {
            position: relative;
            z-index: 1;
            width: 200px;
            height: 115px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px;
            background: white;
            border-radius: 14px;
        }

        .auth-intro-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .auth-intro-content {
            position: relative;
            z-index: 1;
            margin-top: 90px;
        }

        .auth-intro h1 {
            max-width: 390px;
            margin: 0 0 18px;
            font-size: 2.3rem;
            font-weight: 800;
            line-height: 1.12;
        }

        .auth-intro p {
            max-width: 390px;
            color: rgba(255, 255, 255, 0.77);
            line-height: 1.7;
        }

        .auth-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 38px;
            padding: 11px 15px;
            color: white;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 999px;
            font-size: 0.8rem;
        }

        .auth-form-panel {
            display: flex;
            align-items: center;
            padding: 55px 70px;
        }

        .auth-form-container {
            width: 100%;
            max-width: 420px;
            margin: auto;
        }

        .auth-form-container h2 {
            margin-bottom: 8px;
            color: var(--purple-900);
            font-size: 1.8rem;
            font-weight: 800;
        }

        .auth-subtitle {
            margin-bottom: 30px;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .auth-form-label {
            display: block;
            margin-bottom: 7px;
            color: var(--purple-900);
            font-size: 0.83rem;
            font-weight: 700;
        }

        .auth-form-control {
            width: 100%;
            min-height: 46px;
            padding: 10px 13px;
            color: var(--text-dark);
            background: white;
            border: 1px solid #ded9e2;
            border-radius: 9px;
            outline: none;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .auth-form-control:focus {
            border-color: var(--purple-700);
            box-shadow: 0 0 0 4px rgba(99, 36, 124, 0.1);
        }

        .auth-field {
            margin-bottom: 19px;
        }

        .auth-submit {
            width: 100%;
            min-height: 46px;
            margin-top: 7px;
            color: white;
            background: var(--purple-700);
            border: 0;
            border-radius: 9px;
            font-weight: 700;
            transition:
                background 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .auth-submit:hover {
            background: var(--purple-900);
            box-shadow: 0 8px 18px rgba(99, 36, 124, 0.2);
            transform: translateY(-1px);
        }

        .auth-footer {
            margin-top: 30px;
            color: var(--text-muted);
            font-size: 0.85rem;
            text-align: center;
        }

        .auth-footer a {
            color: var(--teal-600);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-error {
            margin-bottom: 20px;
            padding: 12px 14px;
            color: #9f2533;
            background: #fde8ea;
            border-radius: 9px;
            font-size: 0.84rem;
        }

        .auth-success {
            margin-bottom: 20px;
            padding: 12px 14px;
            color: #126b5f;
            background: var(--teal-100);
            border-radius: 9px;
            font-size: 0.84rem;
        }

        @keyframes authEnter {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 800px) {
            .auth-wrapper {
                grid-template-columns: 1fr;
            }

            .auth-intro {
                min-height: auto;
                padding: 32px;
            }

            .auth-intro-content {
                margin-top: 38px;
            }

            .auth-intro h1 {
                font-size: 1.8rem;
            }

            .auth-form-panel {
                padding: 40px 28px;
            }
        }

        @media (max-width: 480px) {
            .auth-shell {
                padding: 12px;
            }

            .auth-intro {
                padding: 24px;
            }

            .auth-intro-logo {
                width: 160px;
                height: 90px;
            }
        }
    </style>
</head>

<body>
    <main class="auth-shell">
        @yield('content')
    </main>
</body>
</html>