<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Inventory Barang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

            /* 🔵 GRADIENT NYAMBUNG (SMOOTH BLUE) */
            background: linear-gradient(135deg, #2f3b5f, #3b82f6);

            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:20px;
        }

        .login-container{
            width:100%;
            max-width:900px;
        }

        .login-card{
            display:flex;
            background:white;
            border-radius:18px;
            overflow:hidden;
            box-shadow:0 10px 35px rgba(0,0,0,.12);
        }

        /* LEFT PANEL */
        .login-image{
            flex:1;
            padding:35px;

            /* 🔵 disesuaikan biar nyambung */
            background: linear-gradient(135deg, #1e2a44, #2a5298);

            color:white;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .image-content{
            text-align:center;
        }

        .main-icon{
            font-size:60px;
            margin-bottom:18px;
            color:white;
        }

        .image-content h2{
            font-size:23px;
            font-weight:600;
            margin-bottom:12px;
            line-height:1.4;
        }

        .image-content p{
            font-size:13px;
            line-height:1.6;
            margin-bottom:25px;
            color:rgba(255,255,255,.9);
        }

        .feature-item{
            text-align:left;
            padding:10px 14px;
            background:rgba(255,255,255,.12);
            border-radius:10px;
            margin-bottom:10px;
            font-size:13px;
        }

        .feature-item i{
            margin-right:8px;
        }

        /* RIGHT PANEL */
        .login-form{
            flex:1;
            padding:38px;
        }

        .form-header{
            text-align:center;
            margin-bottom:28px;
        }

        .logo-img{
            width:70px;
            margin-bottom:12px;
        }

        .form-header h3{
            font-size:24px;
            color:#1e2a44; /* 🔵 disesuaikan */
            margin-bottom:5px;
            font-weight:600;
        }

        .form-header p{
            color:#64748b;
            font-size:13px;
        }

        .form-group{
            margin-bottom:18px;
        }

        .form-group label{
            display:block;
            margin-bottom:7px;
            font-size:13px;
            font-weight:600;
            color:#334155;
        }

        .input-group{
            position:relative;
        }

        .input-icon{
            position:absolute;
            left:14px;
            top:50%;
            transform:translateY(-50%);
            z-index:10;
            color:#94a3b8;
        }

        .toggle-password{
            position:absolute;
            right:14px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            z-index:10;
            color:#94a3b8;
        }

        .form-control{
            padding:12px 14px 12px 42px;
            border-radius:10px;
            border:1.8px solid #e2e8f0;
            background:#f8fafc;
            font-size:14px;
            transition:.2s;
        }

        .form-control:focus{
            border-color:#2a5298;
            background:white;
            box-shadow:0 0 0 3px rgba(42,82,152,0.15);
        }

        .form-options{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }

        .checkbox-label{
            display:flex;
            align-items:center;
            gap:6px;
            cursor:pointer;
            font-size:12px;
            color:#64748b;
        }

        .forgot-link{
            text-decoration:none;
            font-size:12px;
            color:#2a5298;
        }

        .forgot-link:hover{
            text-decoration:underline;
        }

        .btn-login{
            width:100%;
            padding:11px;
            border:none;
            border-radius:10px;

            /* 🔵 tombol disamakan gradient */
            background: linear-gradient(135deg, #1e2a44, #2a5298, #3b82f6);

            color:white;
            font-size:14px;
            font-weight:600;
            transition:.2s;
            margin-bottom:18px;
        }

        .btn-login:hover{
            opacity:.95;
            transform:translateY(-2px);
        }

        .alert{
            border:none;
            border-radius:10px;
            font-size:13px;
            margin-bottom:18px;
        }

        .footer-text{
            text-align:center;
            font-size:11px;
            color:#94a3b8;
            line-height:1.5;
        }

        @media(max-width:768px){

            .login-card{
                flex-direction:column;
            }

            .login-image{
                padding:25px;
            }

            .login-form{
                padding:28px 20px;
            }

        }

    </style>

</head>
<body>

<div class="login-container">

    <div class="login-card">

        <!-- LEFT -->
        <div class="login-image">
            <div class="image-content">

                <i class="fas fa-boxes main-icon"></i>

                <h2>Sistem Inventory Barang</h2>

                <p>
                    Kelola data persediaan barang secara lebih
                    mudah, cepat, dan terorganisir.
                </p>

                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    Monitoring stok barang
                </div>

                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    Barang masuk & keluar
                </div>

                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    Laporan inventory
                </div>

            </div>
        </div>

        <!-- RIGHT -->
        <div class="login-form">

            <div class="form-header">

                <img class="logo-img"
                     src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">

                <h3>Login Sistem</h3>
                <p>Masuk untuk mengakses dashboard inventory</p>

            </div>

            <?php if(session()->getFlashdata('msg')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('msg') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/auth/login') ?>" method="post">

                <?= csrf_field() ?>

                <div class="form-group">

                    <label>Username</label>

                    <div class="input-group">

                        <i class="fas fa-user input-icon"></i>

                        <input type="text"
                               name="username"
                               class="form-control"
                               placeholder="Masukkan username"
                               required>

                    </div>
                </div>

                <div class="form-group">

                    <label>Password</label>

                    <div class="input-group">

                        <i class="fas fa-lock input-icon"></i>

                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control"
                               placeholder="Masukkan password"
                               required>

                        <i class="fas fa-eye toggle-password"
                           onclick="togglePassword()"></i>

                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Login
                </button>

                <div class="footer-text">
                    © 2026 PT. Rancang Mode Indonesia
                </div>

            </form>

        </div>

    </div>

</div>

<script>
function togglePassword(){
    let passwordInput=document.getElementById("password");
    let icon=document.querySelector(".toggle-password");

    if(passwordInput.type==="password"){
        passwordInput.type="text";
        icon.classList.replace("fa-eye","fa-eye-slash");
    }else{
        passwordInput.type="password";
        icon.classList.replace("fa-eye-slash","fa-eye");
    }
}
</script>

</body>
</html>