<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Accounts & Stock Management System</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <style>
         body, html {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
         }
         .login-container {
            height: 100vh;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
         }
         .login-left {
            background: linear-gradient(120deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
         }
         .login-right {
            background: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
         }
         .logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
         }
         .logo i {
            margin-right: 10px;
            font-size: 28px;
         }
         .feature-list {
            margin-top: 30px;
         }
         .feature-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
         }
         .feature-list i {
            margin-right: 10px;
            background: rgba(255,255,255,0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
         }
         .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
         }
         .form-control:focus {
            box-shadow: 0 0 0 3px rgba(41, 128, 185, 0.2);
            border-color: #2980b9;
         }
         .btn-login {
            background: #1e3c72;
            color: white;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
         }
         .btn-login:hover {
            background: #2a5298;
            transform: translateY(-2px);
         }
         .login-header {
            text-align: center;
            margin-bottom: 30px;
         }
         .login-header h3 {
            font-weight: 700;
            color: #2c3e50;
            position: relative;
            padding-bottom: 15px;
         }
         .login-header h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #1e3c72;
         }
         .copyright {
            margin-top: 30px;
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
         }
         @media (max-width: 768px) {
            .login-left {
               display: none;
            }
         }
      </style>
   </head>
   <body>

      <div class="container-fluid">
         <div class="row login-container">
            <!-- Left Side - Login Form -->
            <div class="col-md-5 login-right">
               <div class="login-header">
                  <h3>ACCOUNT LOGIN</h3>
                  <p class="text-muted">Sign in to your account to continue</p>
               </div>
               
               <form action="{{url('/login')}}" method="post">
                  @csrf
                  <div class="mb-3">
                     <label class="form-label">Username</label>
                     <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter your username">
                     </div>
                     @error('user_name')
                     <div class="text-danger small mt-1">{{ $message }}</div>
                     @enderror
                  </div>
                  
                  <div class="mb-4">
                     <label class="form-label">Password</label>
                     <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Enter your password">
                     </div>
                     @error('password')
                     <div class="text-danger small mt-1">{{ $message }}</div>
                     @enderror
                  </div>
                  
                  <div class="d-grid">
                     <button class="btn btn-login btn-lg" type="submit">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                     </button>
                  </div>
                  
                  <div class="text-center mt-3">
                     <a href="#" class="text-decoration-none">Forgot your password?</a>
                  </div>
               </form>
               
               <div class="copyright">
                  &copy; 2023 Accounts & Stock Management System. All rights reserved.
               </div>
            </div>
            
            <!-- Right Side - Information Panel -->
            <div class="col-md-7 login-left d-none d-md-block">
               <div class="logo">
                  <i class="fas fa-chart-line"></i>
                  Accounts & Stock System
               </div>
               
               <h2>Streamline Your Financial Operations</h2>
               <p class="mt-3">Manage your accounts, track inventory, and generate comprehensive financial reports all in one place.</p>
               
               <ul class="feature-list list-unstyled">
                  <li>
                     <i class="fas fa-check"></i>
                     <div>
                        <strong>Real-time Inventory Tracking</strong>
                        <p class="small mb-0">Monitor stock levels and receive alerts for low inventory</p>
                     </div>
                  </li>
                  <li>
                     <i class="fas fa-check"></i>
                     <div>
                        <strong>Comprehensive Financial Reports</strong>
                        <p class="small mb-0">Generate balance sheets, income statements, and cash flow reports</p>
                     </div>
                  </li>
                  <li>
                     <i class="fas fa-check"></i>
                     <div>
                        <strong>Multi-user Access Control</strong>
                        <p class="small mb-0">Assign different permission levels to team members</p>
                     </div>
                  </li>
                  <li>
                     <i class="fas fa-check"></i>
                     <div>
                        <strong>Secure Data Encryption</strong>
                        <p class="small mb-0">Your financial data is protected with bank-level security</p>
                     </div>
                  </li>
               </ul>
               
               <div class="mt-5">
                  <div class="d-flex align-items-center">
                     <div class="border rounded-circle p-2 me-3">
                        <i class="fas fa-quote-left"></i>
                     </div>
                     <div>
                        <p class="fst-italic mb-0">"This system has transformed how we manage our finances and inventory."</p>
                        <p class="small mb-0">— Finance Director, ABC Company</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
   
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.getElementById('user_name').focus();
    });
  </script>

   </body>
</html>