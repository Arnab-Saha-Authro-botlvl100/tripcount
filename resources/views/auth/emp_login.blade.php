<x-guest-layout>
    @include('layouts.head')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <style media="screen">
        *,
    *:before,
    *:after{
      padding: 0;
      margin: 0;
      box-sizing: border-box;
    }
    body{
      background-color: #1F2937;
    }
    .background{
      width: 430px;
      height: 520px;
      position: absolute;
      transform: translate(-50%,-50%);
      left: 50%;
      top: 50%;
    }
    .background .shape{
      height: 120px;
      width: 120px;
      position: absolute;
      border-radius: 50%;
    }
    .shape:first-child{
      background: linear-gradient(
          #1845ad,
          #23a2f6
      );
      left: -50px;
      top: -50px;
    }
    .shape:last-child{
      background: linear-gradient(
          to right,
          #ff512f,
          #f09819
      );
      right: -30px;
      bottom: -80px;
    }
    form{
      margin-top:30px;
      height: 520px;
      width: 400px;
      background-color: rgba(255,255,255,0.13);
      position: absolute;
      transform: translate(-50%,-50%);
      top: 50%;
      left: 50%;
      border-radius: 10px;
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255,255,255,0.1);
      box-shadow: 0 0 40px rgba(8,7,16,0.6);
      padding: 50px 35px;
    }
    form *{
      font-family: 'Poppins',sans-serif;
      color: #ffffff;
      letter-spacing: 0.5px;
      outline: none;
      border: none;
    }
    form h3{
      font-size: 32px;
      font-weight: 500;
      line-height: 42px;
      text-align: center;
    }
    
    label{
      display: block;
      margin-top: 15px;
      font-size: 16px;
      font-weight: 500;
    }
    input{
      display: block;
      height: 50px;
      width: 100%;
      background-color: rgba(255,255,255,0.07);
      border-radius: 3px;
      padding: 0 10px;
      margin-top: 4px;
      font-size: 14px;
      font-weight: 300;
    }
    ::placeholder{
      color: #e5e5e5;
    }
    button{
      margin-top: 15px;
      margin-bottom:5px;
      width: 100%;
      background-color: #ffffff;
      color: #080710;
      padding: 15px 0;
      font-size: 18px;
      font-weight: 600;
      border-radius: 5px;
      cursor: pointer;
    }
    .social{
    margin-top: 30px;
    display: flex;
    }
    .social a{
    background: red;
    width: 150px;
    border-radius: 3px;
    padding: 5px 10px 10px 5px;
    background-color: rgba(255,255,255,0.27);
    color: #eaf0fb;
    text-align: center;
    }
    .social a:hover{
    background-color: rgba(255,255,255,0.47);
    }
    .social .fb{
    margin-left: 25px;
    }
    @media (max-width: 1000px) {
            .background {
                width: 330px;
                height: 420px;
            }
            .shape:first-child {
                left: -80px;
                top: -80px;
            }
            .shape:last-child {
                right: -80px;
                bottom: -120px;
            }
            form {
                width: 400px;
                height: 520px;
            }
            .background .shape{
            height: 120px;
            width: 120px;
            position: absolute;
            border-radius: 50%;
            }
        }

        @media (max-width: 767px) {
            .background {
                flex-direction: column;
                height: 100%;
                padding: 20px;
            }
            .shape:first-child,
            .shape:last-child {
                display: none;
            }
            form {
                padding: 30px 20px;
            }
        }
    
    </style>
   
    <main class="">
        <div class="background">
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        <form method="POST" class="px-4 py-4 !h-fit " action="{{ route('emp_login') }}">
            @csrf
            <h3 class="">Employee Login Here</h3>
    
            <label for="company">Company</label>
            <input type="text" placeholder="Company" id="company" name="company" >

            <label for="email">Email</label>
            <input type="text" placeholder="Email" id="email" name="email" >
    
            <label for="password">Password</label>
            <input type="password" placeholder="Password" id="password" name="password">
    
            <button type="submit">Log In</button>
            @if (Route::has('password.request'))
            <a class="font-medium text-md pt-3 text-white" href="{{ route('password.request') }}">forgot password?</a>
            @endif
            <div class="social">
                <a class="go" href="{{ route('login') }}">Login</a>
                <a class="fb" href="{{ route('emp_login') }}">  Employer Login</a>
            </div>
        </form>
    </main>

    <script>
        $(document).ready(function(){
            $.ajax({
                url: 'search-user', // Specify your endpoint URL here
                method: 'GET', // Specify the HTTP method (GET, POST, etc.)
                data: { name: name }, // Pass any data you need to send to the server
                dataType: 'json', // Specify the expected data type of the response
                success: function(response) {
                    
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error(error);
                }
            });
        });
    </script>
    
</x-guest-layout>
